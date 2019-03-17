# Applicatif Supply

Bêta App\Supply:

Cette applicatif fournit les éléments suivants :
 - Une interface graphique Sonata pour manipuler les différentes facettes du projet
 - Un jeu de données permettant de tester toutes les fonctionnalités disponibles (data.sql)
 - La gestion CRUD des produits
 - La gestion CRUD des fournisseurs
 - La gestion CRUD des commandes fournisseurs
 - La gestion CRUD des commandes clients
 - Une fonction d'import des commandes clients via un fichier csv
 - Une fonction de recalcule des stock à date . Les éléments mis à jour sont :
	 - Le stock courant par produit
	 - La rotation des stocks par produit
	 - Le champ permettant de voir si un produit est bientôt en rupture
 - Une mise à jour dynamique de 3 derniers champs lorsque une nouvelle commande fournisseur est passé ou alors qu'un nouveau batch de commande client est importé

Cette applicatif ne fournit pas :
- De générateur de mail par fournisseur pour le réapprovisionnement
- De tests unitaires
- Une authentification

# Pré-requis
- Docker
- Docker-compose
# Installation
- Récuperer le projet depuis github : git clone https://github.com/pchauvelin/supply.git
- Compléter le fichier ".env" du projet avec pour modèle le fichier ".env.dist"
- Build le projet avec docker-compose
~~~bash
docker-compose build
docker-compose up
docker-compose exec php bash
cd /var/www/supply
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console assets:install
mkdir -p public/uploads/sales
~~~

-	Un sample de data (data.sql) est disponible dans le dossier "sample" du projet
-	Penser à ajouter dans votre vhost l'ip du container associé au domaine supply.local
# Screens 
- Accès : http://supply.local/admin

![Dashboard](https://imgur.com/JrPf0yL)
![Produits / stocks](https://imgur.com/u0s6bjJ)
![Commandes clients](https://imgur.com/lorIfdq)
