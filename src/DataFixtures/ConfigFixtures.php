<?php

namespace App\DataFixtures;


use App\Entity\Config\Config;
use App\Entity\Interfaces\ContactInterface;
use App\Entity\Template;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager;

class ConfigFixtures extends Fixture implements FixtureGroupInterface
{

    public function load(ObjectManager $manager)
    {
        /*
         * Configuration formulaires
         */

        /*
         * modèle template
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('form');
        $item->setValue('');
        $item->setSummary("Liste des formulaires(séparés par une virgule");

        $manager->persist($item);
        $manager->flush();

        /*
         * Configuration générale
         */

        /*
         * modèle template
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('directory');
        $item->setValue('hermes');
        $item->setSummary("Choix du template général d'affichage (hermes ou ...)");

        $manager->persist($item);
        $manager->flush();

        /*
         * Contact
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode(ContactInterface::CONTACT);
        $item->setValue('contact@atlas-services.fr');
        $item->setSummary("Email de contact (utilisé dans le formulaire de contact)");

        $manager->persist($item);
        $manager->flush();

        /*
         * Title
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('title');
        $item->setValue('Titre Atlas Services Modèle');
        $item->setSummary("Texte affiché sur l'onglet du navigateur web");

        $manager->persist($item);
        $manager->flush();

        /*
         * Favicon
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('favicon');
        $item->setValue('Favicon');
        $item->setSummary("Favicon");

        $manager->persist($item);
        $manager->flush();

        /*
         * Logo
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('logo');
        $item->setValue('Atlas Services Modèle Logo');
        $item->setSummary("Logo ou Texte affiché sur le lien de navigation de la page");

        $manager->persist($item);
        $manager->flush();

        /*
         * Image Accueil
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('accueil');
        $item->setValue('tiers');
        $item->setSummary("Image affichée sur la page d'accueil du site");

        $manager->persist($item);
        $manager->flush();

        /*
         * Largeur affichage page
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('container_width');
        $item->setValue('100%');
        $item->setSummary("Largeur du contenu affiché (en %)");

        $manager->persist($item);
        $manager->flush();


        /*
         * Body Bgcolor
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('bgcolor');
        $item->setValue('transparent');
        $item->setSummary("Couleur de fond du body");

        $manager->persist($item);
        $manager->flush();

        /*
         * Configuration menu
         */

        /*
         * Largeur affichage menu
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('nav_container_width');
        $item->setValue('100%');
        $item->setSummary("Largeur du menu affiché (en %)");

        $manager->persist($item);
        $manager->flush();

        /*
         * Nav Bgcolor
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('nav_bgcolor');
        $item->setValue('transparent');
        $item->setSummary("Couleur de fond de la barre de menu");

        $manager->persist($item);
        $manager->flush();

        /*
         * Nav Bgcolor
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('nav_menu_bgcolor');
        $item->setValue('transparent');
        $item->setSummary("Couleur de fond du menu");

        $manager->persist($item);
        $manager->flush();

        /*
         * Nav Li Bgcolor
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('nav_li_bgcolor');
        $item->setValue('transparent');
        $item->setSummary("Couleur de fond des éléments du menu");

        $manager->persist($item);
        $manager->flush();

        /*
         * Bgcolor active
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('nav_bgcolor_active');
        $item->setValue('transparent');
        $item->setSummary("Couleur de fond du menu sélectionné");

        $manager->persist($item);
        $manager->flush();

        /*
         * Nav Bgcolor scroll
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('nav_bgcolor_shrink');
        $item->setValue('transparent');
        $item->setSummary("Couleur de fond de la barre de menu après scroll");

        $manager->persist($item);
        $manager->flush();


        /*
         * Nav color menu
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('nav_link_color');
        $item->setValue('#8f2160');
        $item->setSummary("Couleur des éléments du menu");

        $manager->persist($item);
        $manager->flush();

        /*
         * Configuration content
         */

        /*
         * Largeur affichage content
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('content_container_width');
        $item->setValue('100%');
        $item->setSummary("Largeur du contenu affiché (en %)");

        $manager->persist($item);
        $manager->flush();

        /*
         * Content Bgcolor
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('content_bgcolor');
        $item->setValue('#transparent');
        $item->setSummary("Couleur de fond du contenu");

        $manager->persist($item);
        $manager->flush();

        /*
         * Configuration footer
         */

        /*
         * Largeur affichage footer
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('footer_container_width');
        $item->setValue('100%');
        $item->setSummary("Largeur du footer affiché (en %)");

        $manager->persist($item);
        $manager->flush();

        /*
         * Bgcolor footer
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('footer_bgcolor');
        $item->setValue('#000000');
        $item->setSummary("Couleur de fond du footer");

        $manager->persist($item);
        $manager->flush();

        /*
         * color footer
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('footer_color');
        $item->setValue('#000000');
        $item->setSummary("Couleur du footer");

        $manager->persist($item);
        $manager->flush();


        /*
         * Configuration isotope
         */
        /*
         * Bgcolor isotope
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('isotope_bgcolor');
        $item->setValue('transparent');
        $item->setSummary("Couleur de fond modèle isotope");

        $manager->persist($item);
        $manager->flush();

        /*
         * Color isotope
         */
        $item = new Config();
        $item->setActive(true);
        $item->setCode('isotope_color');
        $item->setValue('#000000');
        $item->setSummary("Couleur des éléments du modèle isotope");

        $manager->persist($item);
        $manager->flush();

    }

    public static function getGroups(): array
    {
        return ['templates'];
    }

}
