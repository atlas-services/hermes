<?php
// tests/Service/NewsletterGeneratorTest.php
namespace App\Tests\Mailer;

use App\Mailer\Mailer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MailerTest extends KernelTestCase
{
    public function testSendNewletter(): void
    {

        $subject = 'test send Newsletter';
        $template = 'newsletter/newsletter.html.twig'; # '<p> Newsletter test envoyée</p>';
        $to = ['tayebc@yahoo.fr', 'contact@atlas-services.fr', 'contact@hermes-cms.org'];
        
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $newsletterGenerator = $container->get(Mailer::class);
        $newsletter = $newsletterGenerator->sendNewsletter($subject, $to, $template, ['ctx' => 'bon context']);

        $nb = count($to);

        $notification = "Votre Newsletter a bien été envoyée à $nb personnes";
        $return = [
            'type' => 'notice',
            'message' => $notification
        ];

        $this->assertEquals($return, $newsletter);
    }
}