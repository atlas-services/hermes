<?php

namespace App\MessageHandler;

use App\Message\MailNotification;
use App\Mailer\Mailer;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MailNotificationHandler
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    public function __invoke(MailNotification $notification)
    {
        $this->mailer->send($notification->getEntity(), $notification->getContact(), 'Contact', $notification->getTemplate(), $notification->getContext());
        // ... do some work - like sending an SMS message!
    }
}
