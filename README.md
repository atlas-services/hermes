Hermes - CMS
==================

Introduction -FR
----------------

Hermes est un CMS basé sur Symfony5 et les standards du Web.
Il fournit une interface d'administration afin de créer des contenus riche pour votre site Web.
Il fournit une interface d'administration pour configurer les couleurs, largeur...des différentes partie de votre site Web.
Il fournit quelques templates de type folios, carousels, cards ainsi qu'une saisie "libre" qui utilise [FOSCKEditorBundle](https://symfony.com/doc/master/bundles/FOSCKEditorBundle/index.html) afin de créer de belles pages responsive.

Introduction -EN
----------------
Hermes is a CMS  based on Symfony5 and the standards of Web.
It provides an admin to create a complete web site.
It provides configuration to select the color, background-color, width...for the different parts of your Web site (Menu, Content, Footer)
It provides some templates like folios, carousels, cards or "free presentation" using the [FOSCKEditorBundle](https://symfony.com/doc/master/bundles/FOSCKEditorBundle/index.html) to create nice and responsive pages.


Documentation
-------------

In progress.
    - require PHP7.4
    - require yarn

Show Room et modeles - FR
------------------------

   - Vous pouvez acceder au show-room de nos principaux templates : [modeles](http://modeles.atlas-services.fr)
   - Vous pouvez aussi voir quelques modèles de sites : 
       - [modele1](http://modele1.atlas-services.fr)
       - [modele2](http://modele2.atlas-services.fr)
       - [modele3](http://modele3.atlas-services.fr)
       - [modele4](http://modele4.atlas-services.fr)
  
 Show Room and modeles -EN
 -------------------------      
   - You can see a show-room of our templates : [modeles](http://modeles.atlas-services.fr)
   - You can see some modeles : 
       - [modele1](http://modele1.atlas-services.fr)
       - [modele2](http://modele2.atlas-services.fr)
       - [modele3](http://modele3.atlas-services.fr)
       - [modele4](http://modele4.atlas-services.fr)

License
-------

This CMS is released under the MIT license. See the included
[LICENSE](LICENSE) file for more information.

Contribuer - FR
---------------

Contributeurs bienvenus! Hermes est un projet open source. Si vous souhaitez contribuer, n'hésitez pas à proposer une PR! Vous pouvez lire le fichier [CONTRIBUTING](/CONTRIBUTING.md) qui vous indiquera quelques directions de contributions .

Contribute - EN
---------------
We love contributors! Hermes is an open source project. If you'd like to contribute, feel free to propose a PR! You
can follow the [CONTRIBUTING](/CONTRIBUTING.md) file which will explain you some needs about contributing.


Install 
====================================

Get the Repository 

    cd /var/www/html
    
    git clone git@github.com:atlas-services/hermes.git
    or 
    git clone https://github.com/atlas-services/hermes.git    

    cd hermes
    git checkout master

    git pull
    
Get the vendors and post-install the project

    composer install
     
Install node modules 

    yarn install  
      
Start Server

    symfony:server:start  
       
Start webpack

    yarn encore dev (--watch)