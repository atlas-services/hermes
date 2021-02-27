<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 02/08/19
 * Time: 22:07
 */

namespace App\EventListener;

use App\Entity\Hermes\User;
//use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserListener
{

    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();


        // only act on some "User" entity
        if (!$entity instanceof User) {
            return;
        }
        $user = $entity;
        $this->handleEvent($user);

    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // only act on some "User" entity
        if (!$entity instanceof User) {
            return;
        }
        $user = $entity;
//        die('Something is being inserted!');
        $this->handleEvent($user);


    }

    private function handleEvent(User $user)
    {
        $plainPassword = $user->getPassword();

        $encoder = $this->encoderFactory->getEncoder($user);

        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password);
    }

}

