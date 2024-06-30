# Application légére pour une base de e-commerce

### Description :
 L’application permet d'avoir un ecommerce avec gestion des utilisateur, gestion de produits et gestion de commande simplifier (pas de systéme de paiement en place)
 Vous trouverer une [Demonstration ici](https://abkff.fr/light_e-commerce/public/), vous pouvez vous créer un compte pour l essaie ou utiliser le compte test@test.fr avec le mdp : 123456\
 La validation de l'inscription par mail est implanté mais par active au niveau de la gestion de l'utilisateur.

### Contenue :
  * Le site est contruit sur une base symfony 7

### Installation :
  1. faire un `composer install` à la recine du dossier pour l'installation des dépendances
  2. éditer le fichier .env pour les connections à la base de donée ainsi que l envoie de mail
  3. faire un `php bin/console make:migration` puis php `bin/console doctrine:migrations:migrate`
  4. Enjoy

## To-Do
- [x] gestion utilisateur
- [x] gestion admin
- [x] gestion produit
- [x] gestion commentaire
- [x] gestion panier
- [x] gestion commande
- [x] envoie mail validation panier (mailtrap)
- [x] envoie pdf commande avec mail (mailtrap)
- [x] ux page home
- [x] ux page register
- [x] ux page login
- [x] ux page produit
- [x] sécurité accées page
- [ ] systeme de réglement
