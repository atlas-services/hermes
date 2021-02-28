<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 14/04/20
 * Time: 15:17
 */

namespace App\Mailer;

use App\Entity\Hermes\Contact;
use App\Entity\Interfaces\ContactInterface;
use App\Entity\Hermes\Order;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Contracts\Translation\TranslatorInterface;


class Mailer
{
    protected $mailer;
    protected $emailLogger;
    protected $translator;

    public function __construct(MailerInterface $mailer, LoggerInterface $emailLogger, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->emailLogger = $emailLogger;
        $this->translator = $translator;
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
            $notification = $this->translator->trans('email.send');
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
            $notification = $this->translator->trans('email.error_send');
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

    public function sendOrder($from, $to, $subject, $template, $context, $pdf = null)
    {
        //envoi mail
        $toAddresses = explode(';', $to);
        $message = $subject;
        try {
            $email = (new TemplatedEmail())
                ->from(new Address($from))
                ->cc(new Address('hermes@atlas-services.fr'))
                ->subject($subject)
                ->htmlTemplate($template)
                ->context($context);
            foreach ($toAddresses as $addTo){
                $email->addTo(new Address($addTo));
            }
            if(!is_null($pdf)){
                $email->attach($pdf, sprintf('commande-du-%s.pdf', date('Y-m-d')));
            }
            $this->mailer->send($email);
            $notification = $this->translator->trans('email.order.send');
            $return = [
                'type' => 'success',
                'message' => $notification
            ];
            $logContext = [
                'statut' => 'ok',
                'email' => $from,
                'message' => $message,
            ];
            $this->emailLogger->info($notification, $logContext);
        } catch (\Exception $e) {
            $notification = $this->translator->trans('email.error_send');
            $logContext = [
                'exception' => $e->getMessage(),
                'statut' => 'ko',
                'email' => $from,
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
