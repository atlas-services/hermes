<?php

namespace Tests\Controller\Admin;

use Symfony\Component\Translation\TranslatorInterface;
use Tests\Controller\AbstractBaseControllerTest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MenuControllerTest extends AbstractBaseControllerTest
{

    public function testLogin()
    {
        $client = self::$client;

        $client->request('GET', '/fr/admin/');

        $this->assertResponseIsSuccessful();

    }

    public function testAddSheetAndMenu( )
    {
        $client = self::$client;
        $translator = self::$translator;

        $this->prepareMenu();

        $this->assertResponseIsSuccessful();

        $crawler = $this->preparePageMenu();

        $this->assertResponseIsSuccessful();

        $this->assertStringContainsString( 'Je crée une nouvelle page.', $client->getResponse());

        // add Content

        $this->addContent($crawler);

        $this->assertResponseIsSuccessful();


//        //add menu
//        $crawler = $client->request('GET', '/fr/admin/menu/new');
//        $save = $translator->trans('global.update');
//        dd($save);
//
//        $form_menu = $crawler->selectButton($save)->form();
//
//        $form_menu["menu[name]"] = 'Menu1';
//        $form_menu["menu[section[name]"] = 'Post1';
//        $form_menu["menu[post[name]"] = 'Post1';
//
//	      $crawler = $client->submit($form_sheet);
//
//        $this->assertResponseIsSuccessful();
    }

    protected function prepareMenu( )
    {
        $client = self::$client;

        $client->request('GET', '/fr/admin/page/');

        self::$client = $client;

    }

    protected function preparePageMenu( )
    {
        $client = self::$client;
        $translator = self::$translator;

        $new_sheet = $translator->trans('global.new');

        $crawler = $client->clickLink($new_sheet);

        $saveAndAdd = $translator->trans('sheet.update_next');

        $form_sheet = $crawler->selectButton($saveAndAdd)->form();

        $form_sheet["sheet[name]"] = 'Page test menu';

        $crawler = $client->submit($form_sheet);

        self::$client = $client;

        return $crawler;

    }

    protected function addContent($crawler)
    {
        $client = self::$client;
        $translator = self::$translator;

        $imd_dir = realpath(__DIR__.'/../../../public/img/hermes/test/list/');
        $files = scandir($imd_dir);
        unset($files[0]);
        unset($files[1]);
        $files = (array_values($files));

        foreach ($files as $file){

        }

        // add Content
        $saveAndAddPost = $translator->trans('menu.update_next');

        $form_content = $crawler->selectButton($saveAndAddPost)->form();

        $form_content["menu[sections][0][template]"] = 8 ; // 'Folio Classique';
        $form_content["menu[sections][0][posts][0][content]"]= 'test post0 folio classique' ; // 'Folio Classique';

        $uploadedFile = new UploadedFile(
            realpath(__DIR__.'/../../../public/img/hermes/test/list/1.jpg'),
        '1.jpg'
        );
        $form_content["menu[sections][0][posts][0][imageFile][file]"]= $uploadedFile ; // 'Folio Classique';

        $client->submit($form_content);

    }
}