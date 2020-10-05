Hermes - CMS
==================

Introduction
------------

The CMS is based on Symfony Project and the standards of Web.
It provides an admin to create a complete web site.
It provides configuration to select the color, backgroud-color, width...for the different parts of your Web site (Menu, Content, Footer)
It provides some templates like folios, carousels, cards or "free presentation" using the [FOSCKEditorBundle](https://symfony.com/doc/master/bundles/FOSCKEditorBundle/index.html) to create nice and responsive pages.


Documentation
-------------

In progress.
    - require PHP7.2
    - require yarn

Show Room and modeles
-------------

   - You can see a show-room of our templates : [modeles](http://modeles.atlas-services.fr)
   - You can see some modeles : 
       - [modele1](http://modele1.atlas-services.fr)
       - [modele2](http://modele2.atlas-services.fr)
       - [modele3](http://modele3.atlas-services.fr)
       - [modele4](http://modele4.atlas-services.fr)

License
-------

This bundle is released under the MIT license. See the included
[LICENSE](LICENSE) file for more information.

## Contribute
-------------

We love contributors! Hermes is an open source project. If you'd like to contribute, feel free to propose a PR! You
can follow the [CONTRIBUTING](/CONTRIBUTING.md) file which will explain you how to set up the project.


Install 
====================================

1) Get the Repository
-----------------------------

Se positionner dans son répertoire de travail /var/www/html

    cd /var/www/html
    
    git clone git@github.com:atlas-services/hermes.git
    or 
    git clone https://github.com/atlas-services/hermes.git    

Se positionner sur la branche dédiée.

    git checkout master

Mettre à jour son repo local

    git pull
    
Get the vendor and post-install the project

    composer install
     
Install node modules 

    yarn install  
      
Start Server

    symfony:server:start  
       
Start webpack

    yarn encore dev (--watch)