<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 14/04/20
 * Time: 15:17
 */

namespace App\Mailer;

use App\Entity\Contact;
use App\Entity\Interfaces\ContactInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


class Mailer
{
    protected $mailer;
    protected $emailLogger;

    public function __construct(MailerInterface $mailer, LoggerInterface $emailLogger)
    {
        $this->mailer = $mailer;
        $this->emailLogger = $emailLogger;
    }

    public function send(ContactInterface $contact, $to, $subject, $template, $context)
    {
        $telephone = 'NC';
        if (method_exists($contact,'getTelephone')){
            $telephone = $contact->getTelephone();
        }
        $message = 'NC';
        if (method_exists($contact, 'getMessage')){
            $message = $contact->getMessage();
        }
        if (method_exists($contact,'getContent')){
            $message = $contact->getContent();
        }
        //envoi mail
        $toAddresses = explode(';', $to);
        try {
            $email = (new TemplatedEmail())
                ->from(new Address($contact->getEmail()))
                ->cc(new Address('contact@atlas-services.fr'))
                ->subject($subject)
                ->htmlTemplate($template)
                ->context($context);
            foreach ($toAddresses as $addTo){
                $email->addTo(new Address($addTo));
            }
            $this->mailer->send($email);
            $notification = "Votre message a bien été envoyé.";
            $return = [
                'type' => 'notice',
                'message' => $notification
            ];
            $logContext = [
                'statut' => 'ok',
                'contact' => $contact->getName(),
                'email' => $contact->getEmail(),
                'telephone' => $telephone,
                'message' => $message,
            ];
            $this->emailLogger->info($notification, $logContext);
        } catch (\Exception $e) {
            $notification = "Votre message n'a pu être envoyé.";
            $logContext = [
                'exception' => $e->getMessage(),
                'statut' => 'ko',
                'contact' => $contact->getName(),
                'email' => $contact->getEmail(),
                'telephone' => $telephone,
                'message' => $message,
            ];
            $return = [
                'type' => 'info',
                'message' => $notification
            ];
            echo 'Exception reçue : ', $e->getMessage(), "\n";
            $this->emailLogger->alert($notification, $logContext);
        }
        return $return;

    }


}
