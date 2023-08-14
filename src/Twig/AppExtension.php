<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('list_length', [$this, 'listLength']),
            new TwigFilter('max_post_length', [$this, 'maxPostLength']),
            new TwigFilter('space_length', [$this, 'spaceLength']),
            new TwigFilter('col_lg', [$this, 'colLg']),
            new TwigFilter('col_imgs', [$this, 'colImgs']),
            new TwigFilter('nb_col', [$this, 'nbCol']),
            new TwigFilter('change_px', [$this, 'changePx']),
            new TwigFilter('col_nb_char', [$this, 'colNbChar']),
            new TwigFilter('get_class',  [$this, 'getClass']),
            new TwigFilter('newsletter',  [$this, 'Newsletter']),
        ];
    }

    public function nbCol($nbCol)
    {
        $col= 12;
        switch ($nbCol) {
            case 1 :
                $col = 12;
                break;
            case 2 :
                $col = 6;
                break;
            case 3 :
                $col = 4;
                break;
            case 4 :
                $col = 3;
                break;
            case 5:
            case 6:
            case 7:
            case 8:
                $col = 2;
                break;
            case 9:
            case 10:
            case 11:
            case 12:
                $col = 1;
                break;

           }

        return $col;

    }

    public function listLength($posts)
    {
        $list_length = 0;
        $list = [5, 7, 8, 9, 10, 11];

        if (0 != count($posts)) {
            $list_length = (12 / (count($posts)));
            if (in_array(count($posts), $list)) {
                $list_length = (12 / (count($posts))) + 1;
            }
            if (count($posts) > 12) {
                $list_length = 3 ;
            }
        }
        $list_length = round($list_length);

        return $list_length;
    }

    public function maxPostLength($posts)
    {
        $length = 0;
        foreach ($posts as $post){
            if(strlen($post->getName()) > $length){
                $length = strlen($post->getName());
            }
        }
        return $length;

    }

    public function spaceLength($post, $max)
    {
        $space = '';
        $nbWhite = $max - strlen($post->getName()) ;
        for ($i=0; $i <= $nbWhite ; $i++){
            $space .= '&nbsp; ' ;
        }
        return $space;

    }

    public function colLg($prct,$section = null)
    {
        if(!is_null($section)){
            if(null == $section->getTemplateNbCol()){
                return intval($prct);
            }else{
                try {
                    return intval(12/$section->getTemplateNbCol());
                }catch(\Exception $e)
                {
                    return intval(12);
                }
            }
        }
        return intval($prct);

        $collg = 12;

        return $collg;
    }

    public function colNbChar($prct)
    {
        $nbchar = 35;

        switch ($prct) {
            case '10%':
                $nbchar = 10;
                break;
            case '20%':
                $nbchar = 20;
                break;
            case '30%':
            case '40%':
            case '50%':
                $nbchar = 25;
                break;
            case '60%':
                $nbchar = 30;
                break;
            case '70%':
                $nbchar = 35;
                break;
            case '80%':
                $nbchar = 40;
                break;
            case '90%':
                $nbchar = 60;
            case '100%':
                $nbchar = 48;
        }

        return $nbchar;
    }

    public function getName()
    {
        return 'class_twig_extension';
    }

    public function getClass($object)
    {
        return (new \ReflectionClass($object))->getShortName();
    }

    public function changePx($value, $diff)
    {
        if(strpos($value, 'px') > 0){
            $value = str_replace('px', '', $value);
            if(strpos($diff, 'px') > 0){
                $diff = str_replace('px', '', $diff);
            }
            $value = $value - $diff;
            $value = $value.'px';
        }

        return $value;
    }

    public function colImgs($section = null)
    {
        if(!is_null($section)){
            $posts = $section->getPosts();
            if(empty($posts->toArray())){
                return $posts->toArray();
            }
            $nb_col = $section->getTemplateNbCol() ? $section->getTemplateNbCol() : 3   ;
            $total = count($posts);
            $nb_imgs_col = $total/$nb_col;
            $nb_imgs_reste = $total%$nb_col;
            $round = round($nb_imgs_col);
 

            $pictures = array_chunk($posts->toArray(), $round);

            if(isset($pictures[$nb_col])){
                $lasts = array_pop($pictures);
                foreach($lasts as $key => $picture){
                    $pictures[$key][] = $picture;
                }
            }

            return $pictures;

        }
        return $section;

        $collg = 12;

        return $collg;
    }


    public function Newsletter($content, $host)
    {

        $src_img = 'src="=/';

        if(str_contains($content, $src_img)){

            $content = str_replace( $src_img, 'src="'.$host.'/' , $content);

            return $content;
        }

        return $content;
    }

}
