CHANGELOG
==================

Installation de Vue.js 3.4
--------------------------

npm install vue@^3.4 vue-loader@^17.4.0 @vue/compiler-sfc --save-dev && npm install

webpack.config.js :
    .addEntry('hermes_admin_vue', './assets/admin/js/vue/app.js')
    .enableVueLoader()

base.html.twig : {{ encore_entry_script_tags('hermes_admin_vue') }}

Migration SF6.4 - Sf7.2 -FR
---------------------------

Annotation to attributes :

Doctrine && Symfony : rector : https://www.doctrine-project.org/2022/11/04/annotations-to-attributes.html.

Vich Uploader : https://github.com/dustin10/VichUploaderBundle/blob/master/docs/usage.md
