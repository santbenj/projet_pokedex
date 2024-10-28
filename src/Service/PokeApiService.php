<?php

namespace App\Service;

use App\Entity\Pokemon;
use App\Entity\Talent;
use App\Entity\Statistique;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokeApiService
{
    private $client;
    private $entityManager;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function fetchPokemons()
    {
        for ($i = 1; $i <= 151; $i++) {
            $response = $this->client->request('GET', "https://pokeapi.co/api/v2/pokemon/{$i}");
            $data = $response->toArray();

            $pokemon = new Pokemon();
            $pokemon->setIsManual(false);
            $pokemon->setNom($data['name']);
            $pokemon->setNumeroPokedex($data['id']);
            $pokemon->setImage("https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/home/{$data['id']}.png");
            $pokemon->setPoids($data['weight'] / 10); // Convertir en kg
            $pokemon->setTaille($data['height'] / 10); // Convertir en m

            // Récupérer les types
            $types = $data['types'];
            $pokemon->setType1($types[0]['type']['name']);
            if (count($types) > 1) {
                $pokemon->setType2($types[1]['type']['name']);
            }

            // Récupérer les talents
            $abilities = $data['abilities'];
            foreach ($abilities as $ability) {
                $talent = new Talent();
                $talent->setNom($ability['ability']['name']);
                $talent->setIsHidden($ability['is_hidden']);
                $talent->setPokemon($pokemon);
                $this->entityManager->persist($talent);
            }

            // Récupérer les statistiques
            $statsResponse = $this->client->request('GET', "https://pokeapi.co/api/v2/pokemon/{$i}/");
            $statsData = $statsResponse->toArray();
            $statistique = new Statistique();
            foreach ($statsData['stats'] as $stat) {
                switch ($stat['stat']['name']) {
                    case 'hp':
                        $statistique->setHp($stat['base_stat']);
                        break;
                    case 'attack':
                        $statistique->setAttack($stat['base_stat']);
                        break;
                    case 'defense':
                        $statistique->setDefense($stat['base_stat']);
                        break;
                    case 'special-attack':
                        $statistique->setSpecialAttack($stat['base_stat']);
                        break;
                    case 'special-defense':
                        $statistique->setSpecialDefense($stat['base_stat']);
                        break;
                    case 'speed':
                        $statistique->setSpeed($stat['base_stat']);
                        break;
                }
            }

            // Lier les statistiques au Pokémon
            $pokemon->setStatistique($statistique);

            // Persister le Pokémon et ses talents
            $this->entityManager->persist($statistique);
            $this->entityManager->persist($pokemon);
        }

        // Enregistrer tous les changements en une seule opération
        $this->entityManager->flush();
    }
}
