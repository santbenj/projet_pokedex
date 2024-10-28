<?php
namespace App\Controller;

use App\Entity\Pokemon;
use App\Entity\Talent;
use App\Entity\Statistique;
use App\Form\PokemonType;
use App\Service\PokeApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Pokemon_Controller extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private PokeApiService $pokeApiService;
    private $client;

    public function __construct(EntityManagerInterface $entityManager, HttpClientInterface $client)
    {
        $this->entityManager = $entityManager;
        $this->client = $client;
    }

    #[Route('/pokemon/import', name: 'pokemon_import', methods: ['GET', 'POST'])]
    public function import(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $importStatus = $this->importPokemons();
            return $this->render('pokemon/import.html.twig', [
                'importStatus' => $importStatus,
            ]);
        }

        return $this->render('pokemon/import.html.twig', [
            'importStatus' => null,
        ]);
    }
    private function importPokemons(): string
    {
        $totalPokemons = 10;
        $batchSize = 3; // Nombre de Pokémon à importer par lot
        $importedCount = 0;

        for ($i = 1; $i <= $totalPokemons; $i += $batchSize) {
            $batch = array_slice(range($i, min($i + $batchSize - 1, $totalPokemons)), 0, $batchSize);
            $pokemonsData = [];

            foreach ($batch as $pokemonId) {
                $response = $this->client->request('GET', "https://pokeapi.co/api/v2/pokemon/{$pokemonId}");
                $data = $response->toArray();

                // Vérifiez si le Pokémon existe déjà
                $existingPokemon = $this->entityManager->getRepository(Pokemon::class)->findOneBy(['numeroPokedex' => $data['id']]);
                if (!$existingPokemon) {
                    $pokemon = new Pokemon();
                    $pokemon->setIsManual(false);
                    $pokemon->setNom($data['name']);
                    $pokemon->setNumeroPokedex($data['id']);
                    //$pokemon->setDescription($data['flavor_text_entries'][0]['flavor_text']);
                    $pokemon->setImage($data['sprites']['front_default']);
                    $pokemon->setPoids($data['weight'] / 10); // Convertir en kg
                    $pokemon->setTaille($data['height'] / 10); // Convertir en m
                    // Récupérer les types
            
            
                    // Récupérer les types
                    foreach ($data['types'] as $typeData) {
                        $pokemon->setType1($typeData['type']['name']);
                        if ($typesData['slot'] == 2) {
                            $pokemon->setType2($typeData['type']['name']);
                        }
                    }

                    // Récupérer les capacités
                    foreach ($data['abilities'] as $listeTalent) {
                        $talent = new Talent();
                        $talent->setNom($listeTalent['ability']['name']);
                        $talent->setIsHidden($listeTalent['is_hidden']);
                        $this->entityManager->persist($talent);
                        $pokemon->addTalent($talent);
                    }

                    // Récupérer les statistiques
                    $statistique = new Statistique();
                    $statistique->setHp($data['stats'][0]['base_stat']);
                    $statistique->setAttack($data['stats'][1]['base_stat']);
                    $statistique->setDefense($data['stats'][2]['base_stat']);
                    $statistique->setSpecialAttack($data['stats'][3]['base_stat']);
                    $statistique->setSpecialDefense($data['stats'][4]['base_stat']);
                    $statistique->setSpeed($data['stats'][5]['base_stat']);
                    $statistique->setPokemon($pokemon); // Lier les statistiques au Pokémon

                    $this->entityManager->persist($statistique);
                    $pokemon->setStatistique($statistique); // Associer les statistiques au Pokémon

                    // Persister le Pokémon
                    $this->entityManager->persist($pokemon);
                    $importedCount++;
                }
            }

            // Flush les changements en base de données par lot
            $this->entityManager->flush();
        }

        return "$importedCount Pokémon importés avec succès.";
    }

    #[Route("/pokemon/create", name: "pokemon_create")]
    public function create(Request $request): Response
    {
        $pokemon = new Pokemon();
        $form = $this->createForm(PokemonType::class, $pokemon);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si le Pokémon existe déjà
            $existingPokemon = $this->entityManager->getRepository(Pokemon::class)->findOneBy(['nom' => $pokemon->getNom()]);
            if ($existingPokemon) {
                $this->addFlash('error', 'Ce Pokémon existe déjà.');
                return $this->redirectToRoute('pokemon_create');
            }

            $pokemon->setIsManual(true);
            $this->entityManager->persist($pokemon);
            $this->entityManager->flush();

            return $this->redirectToRoute('pokemon_list');
        }

        return $this->render('pokemon/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/pokemon/delete/{id}", name: "pokemon_delete_confirm")]
    public function deleteConfirm(Pokemon $pokemon): Response
    {
        return $this->render('pokemon/delete.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }

    #[Route("/pokemon/delete/{id}/execute", name: "pokemon_delete")]
    public function delete(Pokemon $pokemon): Response
    {
        $this->entityManager->remove($pokemon);
        $this->entityManager->flush();
        return $this->redirectToRoute('pokemon_list');
    }

    #[Route("/pokemon/edit/{id}", name: "pokemon_edit")]
    public function edit(Request $request, Pokemon $pokemon): Response
    {
        $form = $this->createForm(PokemonType::class, $pokemon);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si le Pokémon existe déjà
            $existingPokemon = $this->entityManager->getRepository(Pokemon::class)->findOneBy(['nom' => $pokemon->getNom()]);
            if ($existingPokemon && $existingPokemon->getId() !== $pokemon->getId()) {
                $this->addFlash('error', 'Ce Pokémon existe déjà.');
                return $this->redirectToRoute('pokemon_edit', ['id' => $pokemon->getId()]);
            }

            $pokemon->setIsManual(true);
            $this->entityManager->flush();

            return $this->redirectToRoute('pokemon_list');
        }

        return $this->render('pokemon/edit.html.twig', [
            'form' => $form->createView(),
            'pokemon' => $pokemon,
        ]);
    }

    #[Route("/pokemon/list", name: "pokemon_list")]
    public function list(): Response
    {
        $pokemons = $this->entityManager->getRepository(Pokemon::class)->findAll();
        return $this->render('pokemon/list.html.twig', [
            'pokemons' => $pokemons,
        ]);
    }

    #[Route("/pokemon/details/{id}", name: "pokemon_details")]
    public function details(Pokemon $pokemon): Response
    {
        return $this->render('pokemon/details.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }
}
