<?php
namespace Tests\Controller;

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

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}