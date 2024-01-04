# Symfony 6 « avancé »

## Auteur
- Vasseur Lucas

## Installation / Configuration

Lancez :
```shell
composer install
``` 
pour installer [PHP Coding Standards Fixer](https://cs.symfony.com/) et le configurer dans PhpStorm (le fichier `.php-cs-fixer.php` contient les règles personnalisées basées sur la recommandation [PSR-12](https://www.php-fig.org/psr/psr-12/))

Lancez :
```shell
npm install
```
pour installer Webpack Encore et ses dépendances.

- Sass : Utilisation de [Sass](https://sass-lang.com/) pour la gestion des feuilles de style.
- Bootstrap : Utilisation de [Bootstrap](https://getbootstrap.com/) pour la mise en forme des pages.

### Configurer PhpStorm

Configurer l'intégration de PHP Coding Standards Fixer dans PhpStorm en fixant le jeu de règles sur `Custom` et en désignant `.php-cs-fixer.php` comme fichier de configuration de règles de codage.

## Serveur Web local

Lancez le serveur Web local avec cette commande :
```bash
composer start
```

Lance la commande de vérification du code par PHP CS Fixer :
```bash
composer test:cs
```

Lance la commande de correction du code par PHP CS Fixer :
```bash
composer fix:cs
```

Lance la commande de vérification des fichiers YAML contenus dans le répertoire « config » :
```bash
composer test:yaml
```

Lance la commande de vérification des fichiers Twig contenus dans le répertoire « templates » :
```bash
composer test:twig
```

### Génération de la base de données :
```bash
composer db
```

Lance :
- `php bin/console doctrine:database:drop --force --if-exists` : Destruction forcée de la base de données
- `php bin/console doctrine:database:create` : Création de la base de données
- `php bin/console doctrine:migrations:migrate --no-interaction` : Application des migrations successives sans questions interactives
- `php bin/console doctrine:fixtures:load --no-interaction` : Génération des données factices sans questions interactives

## Style de codage 

Le code suit la recommandation [PSR-12](https://www.php-fig.org/psr/psr-12/) : - il peut être contrôlé avec `composer test:cs` - il peut être reformaté automatiquement avec `composer fix:cs`


### user

- admin@example.com   mdp: test role: ROLE_ADMIN
- admin2@example.com   mdp: test role: ROLE_ADMIN
- user@example.com   mdp: test role: ROLE_USER
- user2@example.com   mdp: test role: ROLE_USER
