#!/bin/bash

php bin/console d:d:d --env=test --force 2> /dev/null || true
php bin/console d:s:c --env=test --em=default
php bin/console d:d:d --env=test --em=config --force 2> /dev/null || true
php bin/console d:s:c --env=test --em=config


#php bin/console hermes:db-update --env=test


php bin/console hermes:prepare-directories
php bin/console ckeditor:install --tag=4.22.1 --clear=skip
php bin/console elfinder:install
php bin/console assets:install --symlink

php vendor/bin/simple-phpunit -c phpunit.xml.dist tests/Controller/Admin/SheetControllerTest.php
php vendor/bin/simple-phpunit -c phpunit.xml.dist tests/Controller/Admin/MenuControllerTest.php
