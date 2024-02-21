.PHONY:

hermes-install:
	mkdir data 2> /dev/null || true
	mkdir public/build 2> /dev/null || true
	yarn install --ignore-engines
	export NODE_OPTIONS=--openssl-legacy-provider  &&  yarn encore dev 
	composer install
	php bin/console ckeditor:install --tag=4.22.1
	php bin/console elfinder:install
	php bin/console  assets:install
	php bin/console d:s:u --force
	php bin/console d:s:u --force --em=config
	php bin/console hermes:db-update
	php bin/console cache:clear

hermes-re-init:
	rm -r data/db/* 2> /dev/null || true
	chmod -Rf 775 data/db/* 2> /dev/null || true
	php bin/console hermes:prepare-directories

doctrine-init:
	php bin/console doctrine:cache:clear-metadata
	php bin/console doctrine:cache:clear-query
	php bin/console doctrine:cache:clear-result
	php bin/console d:s:u --force
	php bin/console d:s:u --force --em=config
	php bin/console hermes:db-update
	rm -r var/cache/* var/log/* 2> /dev/null || true

phpunit-test-admin:
	rm -r data/db/test.sqlite data/db/config/test.sqlite 2> /dev/null || true
	php bin/console doctrine:cache:clear-metadata
	php bin/console doctrine:cache:clear-query
	php bin/console doctrine:cache:clear-result
	php bin/console doctrine:database:drop --force --connection=default --env=test
	php bin/console doctrine:database:drop --force --connection=config --env=test
	php bin/console doctrine:database:create --env=test
	php bin/console d:s:u --force --em=default --env=test
	php bin/console d:s:u --force --em=config --env=test
	SYMFONY_ENV=test composer install
	php bin/console hermes:db-update --env=test
	rm -r var/cache/* var/log/* 2> /dev/null || true
	vendor/bin/phpunit -c phpunit.xml tests/Controller/Admin/MenuContactControllerTest.php


phpunit-test-front:
	rm -r data/db/test.sqlite data/db/config/test.sqlite 2> /dev/null || true
	php bin/console doctrine:cache:clear-metadata
	php bin/console doctrine:cache:clear-query
	php bin/console doctrine:cache:clear-result
	php bin/console doctrine:database:drop --force --connection=default --env=test
	php bin/console doctrine:database:drop --force --connection=config --env=test
	php bin/console doctrine:database:create --env=test
	php bin/console d:s:u --force --em=default --env=test
	php bin/console d:s:u --force --em=config --env=test
	SYMFONY_ENV=test composer install
	php bin/console hermes:db-update --env=test
	rm -r var/cache/* var/log/* 2> /dev/null || true
	vendor/bin/phpunit -c phpunit.xml tests/Controller/Admin/MenuContactControllerTest.php
	vendor/bin/phpunit -c phpunit.xml tests/Controller/Front



phpunit-test-page:
	rm -r data/db/test.sqlite data/db/config/test.sqlite 2> /dev/null || true
	php bin/console doctrine:cache:clear-metadata
	php bin/console doctrine:cache:clear-query
	php bin/console doctrine:cache:clear-result
	php bin/console doctrine:database:drop --force --connection=default --env=test
	php bin/console doctrine:database:drop --force --connection=config --env=test
	php bin/console doctrine:database:create --env=test
	php bin/console d:s:u --force --em=default --env=test
	php bin/console d:s:u --force --em=config --env=test
	SYMFONY_ENV=test composer install
	php bin/console hermes:db-update --env=test
	rm -r var/cache/* var/log/* 2> /dev/null || true
	php bin/console doctrine:fixtures:load --env=test -n
	vendor/bin/phpunit -c phpunit.xml tests/Menu/PageTest.php

phpunit-test-sendNewsletter:
	rm -r var/cache/* var/log/* 2> /dev/null || true
	php bin/console hermes:prepare-directories --env=test
	vendor/bin/phpunit -c phpunit.xml tests/Mailer/MailerTest.php

init-test:
	cp .env.test .env
	composer install
	yarn install
	yarn encore dev
	php bin/console hermes:prepare-directories
	php bin/console doctrine:cache:clear-metadata
	php bin/console doctrine:cache:clear-query
	php bin/console doctrine:cache:clear-result
	php bin/console doctrine:cache:clear-result
	php bin/console doctrine:database:drop --force
	php bin/console d:s:u --force
	php bin/console d:s:u --force --em=config
	php bin/console hermes:db-update
	php bin/console assets:install --symlink
	rm -r var/cache/* var/log/* 2> /dev/null || true

init-prod:
	composer install --no-dev --optimize-autoloader
	php bin/console doctrine:cache:clear-metadata
	php bin/console doctrine:cache:clear-query
	php bin/console doctrine:cache:clear-result
	php bin/console d:s:u --force
	php bin/console d:s:u --force --em=config
	php bin/console hermes:db-update
	php bin/console assets:install --symlink
	rm -r var/cache/* var/log/* 2> /dev/null || true

doctrine-re-init:
	php bin/console doctrine:cache:clear-metadata
	php bin/console doctrine:cache:clear-query
	php bin/console doctrine:cache:clear-result
	php bin/console doctrine:database:drop --force --connection=default
	php bin/console doctrine:database:drop --force --connection=config
	php bin/console doctrine:database:create
	php bin/console d:s:u --force --em=default
	php bin/console d:s:u --force --em=config
	php bin/console hermes:db-update
# 	rm -r var/cache/* var/log/* 2> /dev/null || true


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

behat-total:
	clear && vendor/bin/behat

make-fixtures:
	php bin/console make:fixtures

fixtures-load:
	php bin/console doctrine:fixtures:load

fixtures-load-users:
	php bin/console doctrine:fixtures:load --group=users

fixtures-load-sheets:
	php bin/console doctrine:fixtures:load --group=sheets

section-template:
	sed 's/id=\"section\"/id=\"$(SECTION2)\"/g' templates/front/base/template/$(SECTION1).html.twig > templates/front/base/template/$(SECTION2).html.twig

ckeditor-install:
	php bin/console ckeditor:install --tag=4.22.1

elfinder-install:
	php bin/console elfinder:install
