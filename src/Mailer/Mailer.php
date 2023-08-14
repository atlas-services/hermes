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
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


class Mailer
{
    protected $mailer;
    protected $emailLogger;
    protected $filesystem;
    protected $params;

    public function __construct(MailerInterface $mailer, LoggerInterface $emailLogger, Filesystem $filesystem ,ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->emailLogger = $emailLogger;
        $this->filesystem = $filesystem;
        $this->params = $params;
    }

    public function addLogo()
    {
        //$dir = getcwd(). "/".$this->params->get('hermes_path_content_image')."/Config/";
        $project_dir = $this->params->get('kernel.project_dir');
        $dir = $project_dir. "/public/".$this->params->get('hermes_path_content_image')."/Config/";
      
        $files = array_values(array_diff(scandir($dir), array('..', '.')));     
        foreach ($files as $key => $link) {
            if(is_dir($dir.$link)){
                unset($files[$key]);
            }
        }         
        if(isset($files[0]) && $this->filesystem->exists($dir.$files[0])){
            $this->filesystem->copy($dir.$files[0], $dir.'logo_email.png', true);
        }else{
            $logo_hermes = $project_dir. '/public/img/hermes.png';
            $this->filesystem->copy($logo_hermes, $dir.'logo_email.png', true);
        }
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
        //ajout Logo
        $this->addLogo();
        //envoi mail
        $toAddresses = explode(';', $to);
        try {
            $from = $this->params->get('hermes_admin_email');
            $email = (new TemplatedEmail())
                ->from(new Address($from))
                // ->from(new Address($contact->getEmail()))
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

    public function sendNewsletter($subject , $to, $template="", $context =[])
    {

        $toAddresses = explode(';', $to);
        $nb= 0;
        try {
            $from = $this->params->get('hermes_admin_email');
            foreach ($toAddresses as $addTo){
            $email = (new TemplatedEmail())
                ->from(new Address($from))
                ->subject($subject)
                ->htmlTemplate($template)
                ->context($context);

                $email->addTo(new Address($addTo));
                $this->mailer->send($email);
                $nb++;
            }
            $notification = "Votre Newsletter a bien été envoyée à $nb personnes";
            $return = [
                'type' => 'notice',
                'message' => $notification
            ];
            $logContext = [
                'statut' => 'ok',
                'from' => $from,
                'to' => $addTo,
                'subject' => $subject,
                'message' => $template,
            ];
            $this->emailLogger->info($notification, $logContext);
        } catch (\Exception $e) {
            $notification = "Votre message n'a pu être envoyé.";
            $logContext = [
                'exception' => $e->getMessage(),
                'statut' => 'ko',
                'from' => $from,
                'to' => $addTo,
                'subject' => $subject,
                'message' => $template,
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
