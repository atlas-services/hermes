<?php

// src/EventListener/UserChangedNotifier.php
namespace App\EventListener;

use App\Entity\Hermes\Post;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostUpdateEventArgs;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Post::class)]

class PostListener
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function postUpdate(Post $post, PostUpdateEventArgs $event): void
    {
        if($post instanceof Post){
            if(str_contains($post->getContent(), 'sandbox=""')){
                $content = str_replace( 'sandbox=""', 'sandbox="allow-same-origin allow-scripts"', $post->getContent());
                $post->setContent($content);
                $this->em->persist($post);
                $this->em->flush();
            }
        }
    }

}