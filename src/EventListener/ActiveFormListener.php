<?php
namespace App\EventListener;


use App\Entity\Config;
use App\Entity\Sheet;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ActiveFormListener
{

    // the listener methods receive an argument which gives you access to
    // both the entity object of the event and the entity manager itself
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof Config) {
            return;
        }else{
            $config = $entity;
        }

        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible

            if ('form' == $config->getCode()) {
                if('contact' == $config->getValue()){
                    $form = $config->getValue();
                    $entityManager = $args->getObjectManager();
                    $sheet_form = $entityManager->getRepository(Sheet::class)->findOneBy(['active' => true, 'name' => $form]);
                    // Création sheet si le formulaire n'existe pas
                    if(is_null($sheet_form)){
                        $newSheet = new Sheet();
                        $newSheet->setCode($form);
                        $newSheet->setName($form);
                        $newSheet->setSlug($form);
                        $entityManager->persist($newSheet);
                        $entityManager->flush();
                    }
                }
            }
        return;
    }
}