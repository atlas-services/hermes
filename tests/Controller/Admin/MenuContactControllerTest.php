<?php

namespace Tests\Controller\Admin;

use Symfony\Component\Translation\TranslatorInterface;
use Tests\Controller\AbstractBaseControllerTest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\DataFixtures\LoadTemplate;

class MenuContactControllerTest extends AbstractBaseControllerTest
{

    public function testAddContactSheetAndMenu( )
    {
        $client = self::$client;
        $translator = self::$translator;

        $this->prepareMenu();

        $this->assertResponseIsSuccessful();

        $crawler = $this->preparePageContactMenu();

        $this->assertResponseIsSuccessful();

        $this->assertStringContainsString( 'Je crée une nouvelle page.', $client->getResponse());

        // add Contact

        $this->addContact($crawler);

        $this->assertResponseIsSuccessful();

    }

    protected function prepareMenu( )
    {
        $client = self::$client;

        $client->request('GET', '/fr/admin/page/');

        self::$client = $client;

    }

    protected function preparePageContactMenu( )
    {
        $client = self::$client;
        $translator = self::$translator;

        $new_sheet = $translator->trans('global.new');

        $crawler = $client->clickLink($new_sheet);

        $saveAndAdd = $translator->trans('sheet.update_next');

        $form_sheet = $crawler->selectButton($saveAndAdd)->form();

        $form_sheet["sheet[locale]"]->select('fr'); // 'fr_FR'
        $form_sheet["sheet[name]"] = 'Contact';
        $form_sheet["sheet[referenceName]"] = 'contact';
        
        $crawler = $client->submit($form_sheet);

        self::$client = $client;

        return $crawler;

    }

    protected function addContact($crawler)
    {
        $client = self::$client;
        $translator = self::$translator;

        // add Contact
        $save = $translator->trans('global.update');

        $form_contact = $crawler->selectButton($save)->form();

        $form_contact["menu[locale]"]->select('fr'); // 'fr_FR'
        $form_contact["menu[name]"] = 'contact';
        $form_contact["menu[referenceName]"] = 'contact';
        $form_contact["menu[sections][0][template]"]->select('39') ; // 37 = Libre, 39 = contact, 45 = Folio Classique  ;
        
        $client->submit($form_contact);

    }


    protected function addContent($crawler)
    {
        $client = self::$client;
        $translator = self::$translator;

        $imd_dir = realpath(__DIR__.'/../../../public/img/hermes/crms/Templates/Images');
        $files = scandir($imd_dir);
        unset($files[0]);
        unset($files[1]);
        $files = (array_values($files));

        foreach ($files as $file){

        }

        // add Content
        $saveAndAddPost = $translator->trans('menu.update_next');

        $form_content = $crawler->selectButton($saveAndAddPost)->form();

        $form_content["menu[sections][0][template]"] = 39 ; // 'Folio Classique';
        $form_content["menu[sections][0][posts][0][content]"]= 'test post0 folio classique' ; // 'Folio Classique';

        $uploadedFile = new UploadedFile(
            realpath(__DIR__.'/../../../public/img/hermes/crms/Templates/Images/image1.webp'),
        'image1.webp'
        );
        $form_content["menu[sections][0][posts][0][imageFile][file]"]= $uploadedFile ; // 'Folio Classique';

        $client->submit($form_content);

    }
}