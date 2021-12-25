<?php
namespace Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Translation\TranslatorInterface;

abstract class AbstractBaseControllerTest extends WebTestCase
{
    const EMAIL = "firstname.lastname@yopmail.com";
    const PASSWORD = 'firstname';

	public static $client;
	public static $translator;

    protected function setUp(): void
    {
        $this->login();
        static::$translator = $this->getTranslator();
    }

    protected function login()
    {
        $client = static::createClient();

        // get or create the user somehow (e.g. creating some users only
        // for tests while loading the test fixtures)
//        $userRepository = static::$container->get(UserRepository::class);
//        $testUser = $userRepository->findOneByEmail(self::EMAIL);
//        $client->loginUser($testUser);
//        $client->followRedirects();
//        static::$client = $client;
//        return $client;

        $crawler = $client->request('GET', '/fr/login');

        $form_login = $crawler->selectButton('Se connecter')->form();
        $form_login['email']->setValue(self::EMAIL);
        $form_login['password']->setValue( self::PASSWORD);

        $client->submit($form_login);

        $client->followRedirects();

        static::$client = $client;

    }

    protected function getTranslator()
    {
        self::bootKernel();

        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();

        return  self::$container->get('translator');

	}
}