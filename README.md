# Cours Symfony 4

Etapes à suivre pour installer.

1) Cloner le repo Git
```sh
git clone https://github.com/AymDev/sf4.git
```

2) Installer les dépendances PHP
```sh
composer install
```

3) Installer les dépendances JS
```sh
yarn install
```

4) Compiler les ressources front (CSS, JS)
```sh
yarn encore dev
```

5) Créer si nécessaire la base de données
```sh
bin/console doctrine:database:create
```

6) Executer les migrations de BDD
```sh
bin/console doctrine:migrations:migrate
```