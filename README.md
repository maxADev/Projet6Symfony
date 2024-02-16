# Projet6Symfony

<h3>Blog communautaire sur les tricks en snowboard</h3>
<p>Blog développé avec symfony 6</p>
<p>PHP 8.2 (prérequis)</p>

<h3>Installation sur serveur</h3>
1) Effectuer un git clone du projet :
<code>git clone https://github.com/maxADev/Projet6Symfony.git</code><br><br>
2) Changer la configuration de la base de données et du mailer dans le .env du projet<br><br>
3) Télécharger et installer les dépendances avec composer :
<br>Exécuter : <code>composer install --no-dev</code>
Puis : <code>composer dump-autoload -o</code><br><br>
4) Si la base de données n'existe pas utiliser cette commande pour la créer :
<code>php bin/console doctrine:database:create</code><br><br>
5) Ajouter les tables avec cette commande :
<code>php bin/console doctrine:migrations:migrate</code><br><br>
6) Ajouter le contenu prédéfinis avec cette commande :
<code>php bin/console doctrine:fixtures:load</code><br><br>

<h3>Installation sur docker</h3>
Docker installé et configuré au préalable.<br><br>
1) Git clone du projet, de préférence sur linux, éviter windows.<br><br>
2) Aller à la racine du projet puis utiliser cette commande :
<code>docker-compose up -d</code>
Cela devrait créer les containers et les lancers.<br><br>
3) Suivre les mêmes étapes que l'installation sur serveur.
