<?php
namespace Tests\Controller;

use Tests\DataFixtures\LoadUser;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractBaseControllerTest extends WebTestCase
{
    const EMAIL = LoadUser::EMAIL;
    const PASSWORD = LoadUser::PASSWORD;

	public static $client;
    public static $fixtures;
    public static $translator;

    /** @var AbstractDatabaseTool */
    protected $databaseTool;

    protected function setUp(): void
    {
        static::$translator = static::getContainer()->get('translator');
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        if (null == self::$fixtures){
            static::$fixtures = $this->databaseTool
                ->loadFixtures([
                    LoadUser::class,
                ],);
        }
        $this->login();
    }

    public static function createClient(array $options = [], array $server = [])
    {
        self::ensureKernelShutdown();
        $client =  parent::createClient($options, $server); // $client

        return $client;
    }

    protected function login($username = self::EMAIL, $password = self::PASSWORD, $bFollow = true, $url = "/fr/login", $submit = "Se connecter")
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', $url);

        $form = $crawler->selectButton($submit)->form();
        $form['email']->setValue($username);
        $form['password']->setValue($password);

        $client->submit($form);
        $client->followRedirects($bFollow);

        static::$client = $client;
    }
}