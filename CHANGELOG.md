CHANGELOG
==================

Installation de Vue.js 3.4
--------------------------


### Pré-requis 
## Installation de webpack-encore
composer require symfony/webpack-encore-bundle

### installation de vue
npm install vue@^3.4 vue-loader@^17.4.0 @vue/compiler-sfc --save-dev && npm install

### ajout js pour vue (/assets/admin/js/vue/app.js)
import { createApp } from 'vue'

### Configuration webpack (hermes_admin_vue)
webpack.config.js :
    .addEntry('hermes_admin_vue', './assets/admin/js/vue/app.js')
    .enableVueLoader()

### intégration dans twig (hermes_admin_vue) :
base.html.twig : {{ encore_entry_script_tags('hermes_admin_vue') }}

Migration SF6.4 - Sf7.2 -FR
---------------------------

Annotation to attributes :

Doctrine && Symfony : rector : https://www.doctrine-project.org/2022/11/04/annotations-to-attributes.html.

Annotations => attributes pour Vich Uploader : https://github.com/dustin10/VichUploaderBundle/blob/master/docs/usage.md
