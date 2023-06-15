<?php
namespace Tests\Controller\Admin;

use Tests\DataFixtures\LoadUser;
use Tests\Controller\AbstractBaseControllerTest;

class SheetControllerTest extends AbstractBaseControllerTest
{

    public function testLogin( )
    {
       $client = static::$client;
       $client->request('GET','/fr/admin');

       $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testAddSheet( )
    {
    	$client = static::$client;
    	$translator = static::$translator;

        $crawler = $client->request('GET', '/fr/admin/nouvelle-page');

        $save = $translator->trans('global.update');

        $form = $crawler->selectButton($save)->form();
        $form["sheet[name]"] = 'Page test';
        $form["sheet[referenceName]"] = 'pagetest';

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddSheetContact( )
    {
    	$client = static::$client;
    	$translator = static::$translator;

        $crawler = $client->request('GET', '/fr/admin/nouvelle-page');

        $save = $translator->trans('global.update');

        $form = $crawler->selectButton($save)->form();
        $form["sheet[name]"] = 'Contact';
        $form["sheet[referenceName]"] = 'contact';

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}