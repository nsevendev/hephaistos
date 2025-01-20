# Fixture  

- Permet de creer de la data factice dans votre BDD (test, dev, prod)  

## System de Fixtures  

- Les fixture doivent etre dans leur dossier respectif de leur entité exemple: `fixtures/EntityName/VotreFixture.php`  
- Les fixture doivent extends de `AbstractFixture` et implementer `FixtureGroupInterface`

`FixtureGroupInterface` permet de regrouper les fixtures par groupe à fin de faire des creations de données par groupement d'entité liées.  
`AbstractFixture` permet de rendre plus simple la creation de données fonctionnement:
        - contient la fonction `load(ObjectManager $manager): void`
        qui execute une boucle sur la fonction `supply` qui elle meme doit etre implementer dans votre fixture.  
        et qui doit retourner une itération de data (utilisation de yield), à chaque itération sur l'objet elle 
        execute `RecursiveEntityPersiste::persist` cette fonction s'occupe de faire un persite et de flush mais egalement  
        de creer les entity liées si besoin, à l'aide la récursivitée.

Pour un exemple voir le fichier `fixtures/PingEntity/PingEntityFixture.php`

## Les groupes

- dans votre class de fixture vous pouvez definir si votre fixture fera partie d'un groupe, voici l'implementation:
cette fonction viens de `FixtureGroupInterface`
```php
public static function getGroups(): array
{
    return ['ping_entity'];
}
```

- une fois creer et une fois votre fixture executer, vous pouvez l'utiliser comme ceci:
```bash
php bin/console doctrine:fixtures:load --group=ping_entity
```
