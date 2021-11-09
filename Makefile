.PHONY:

hermes-install:
	mkdir data
	mkdir public/build
	mkdir public/data
	mkdir public/data/uploads
	mkdir public/data/uploads/entity
	mkdir public/data/uploads/content
	yarn install
	composer install
	bin/console d:s:u --force
	bin/console d:s:u --force --em=config
	bin/console hermes:db-update
	bin/console cache:clear

doctrine-init:
	bin/console doctrine:cache:clear-metadata
	bin/console doctrine:cache:clear-query
	bin/console doctrine:cache:clear-result
	bin/console d:s:u --force
	bin/console d:s:u --force --em=config
	bin/console hermes:db-update
	rm -r var/cache/* var/log/* 2> /dev/null || true

init-test:
	cp .env.test .env
	composer install
	yarn install
	yarn encore dev
	bin/console hermes:prepare-directories
	bin/console doctrine:cache:clear-metadata
	bin/console doctrine:cache:clear-query
	bin/console doctrine:cache:clear-result
	bin/console doctrine:cache:clear-result
	bin/console doctrine:database:drop --force
	bin/console d:s:u --force
	bin/console d:s:u --force --em=config
	bin/console hermes:db-update
	bin/console ckeditor:install --clear=skip
	bin/console elfinder:install
	bin/console assets:install --symlink
	rm -r var/cache/* var/log/* 2> /dev/null || true

init-prod:
	composer install --no-dev --optimize-autoloader
	bin/console doctrine:cache:clear-metadata
	bin/console doctrine:cache:clear-query
	bin/console doctrine:cache:clear-result
	bin/console d:s:u --force
	bin/console d:s:u --force --em=config
	bin/console hermes:db-update
	bin/console ckeditor:install --clear=skip
	bin/console elfinder:install
	bin/console assets:install --symlink
	rm -r var/cache/* var/log/* 2> /dev/null || true

doctrine-re-init:
	bin/console doctrine:cache:clear-metadata
	bin/console doctrine:cache:clear-query
	bin/console doctrine:cache:clear-result
	bin/console doctrine:database:drop --force --connection=default
	bin/console doctrine:database:drop --force --connection=config
	bin/console doctrine:database:create
	bin/console d:s:u --force --em=default
	bin/console d:s:u --force --em=config
	bin/console hermes:db-update
# 	rm -r var/cache/* var/log/* 2> /dev/null || true


behat-re-init:
	rm ../data/db/behatstripe.sqlite  2> /dev/null || true && cp ../data/db/behatstripe_init.sqlite ../data/db/behatstripe.sqlite

behat-login:
	clear && vendor/bin/behat --suite=login

behat-sheet: doctrine-re-init
	clear && vendor/bin/behat --suite=sheet

behat-menu: doctrine-re-init
	clear && vendor/bin/behat --suite=menu

behat-section: doctrine-re-init
	clear && vendor/bin/behat --suite=section

behat-post: doctrine-re-init
	clear && vendor/bin/behat --suite=post

behat-contact:
	clear && vendor/bin/behat --suite=contact

behat-atlas: doctrine-re-init
	clear && vendor/bin/behat --suite=atlas

behat-modeles: doctrine-re-init
	clear && vendor/bin/behat --suite=modeles

behat-error:
	clear && vendor/bin/behat --suite=error

behat-error-sheet:
	clear && vendor/bin/behat --suite=sheet-error

behat-error-menu:
	clear && vendor/bin/behat --suite=menu-error

behat-error-post:
	clear && vendor/bin/behat --suite=post-error

behat-ecommerce-cart:
	clear && vendor/bin/behat --suite=ecommerce-cart

behat-ecommerce-tunnel: behat-re-init
	clear && vendor/bin/behat --suite=ecommerce-tunnel

behat-total:
	clear && vendor/bin/behat

make-fixtures:
	bin/console make:fixtures

fixtures-load:
	bin/console doctrine:fixtures:load

fixtures-load-users:
	bin/console doctrine:fixtures:load --group=users

fixtures-load-sheets:
	bin/console doctrine:fixtures:load --group=sheets

ckeditor-install:
	bin/console ckeditor:install
	bin/console assets:install --symlink

elfinder-install:
	bin/console elfinder:install

section-template:
	sed 's/id=\"section\"/id=\"$(SECTION2)\"/g' templates/front/base/template/$(SECTION1).html.twig > templates/front/base/template/$(SECTION2).html.twig
