> [!WARNING]
Ce projet  ne fonctionne pas, l'importation de pokemon echoue à cause d'un problème mémoire

# Installation 

Pour lancer le projet il suffit de lancer les containers avec : 

```
SYMFONY_VERSION=6.4.* docker compose up -d --wait
```

Cela configurera le projet avec les entités et la base de données 
il y a 3 entités: 

Pokemon
Talent
Statistique 

La base de donnée est en PostgreSQL avec api platferom/symfony6 

