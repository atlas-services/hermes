<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 14/04/20
 * Time: 15:17
 */

namespace App\Service;

use App\Entity\Config\Config;
use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Post;
use App\Entity\Hermes\Section;
use App\Entity\Hermes\Sheet;
use App\Entity\Hermes\Template;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;



class Copy
{

    private $em ;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function copySection($section, $fromSection, $copy = false){

        try {
            $this->em->persist($section);

            if($copy){
                foreach($section->getPosts() as $post){
                    $oldPost = clone $post;
                    $oldPost->setSection($fromSection);
                    $this->em->persist($fromSection);
                    $this->em->persist($oldPost);
                }
            }

            $this->em->flush();
            return ['info' => 'Section copiée'];

        }catch (\Exception $e){
            return ['warning' => $e->getMessage()];
        }

    }
    public function copyPost($post, $to){

        try {
            $newPost = clone $post;
            $newPost->setSection($to);
            $this->em->persist($post);

            $this->em->flush();
            return ['info' => 'Page créée'];

        }catch (\Exception $e){
            return ['warning' => $e->getMessage()];
        }

    }

}
