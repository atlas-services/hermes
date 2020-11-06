<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('list_length', [$this, 'listLength']),
            new TwigFilter('col_lg', [$this, 'colLg']),
            new TwigFilter('get_class', 'get_class'),
        ];
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

    public function colLg($prct)
    {
        $collg = 12;

        switch ($prct) {
            case '10%':
                $collg = 1;
                break;
            case '20%':
                $collg = 3;
                break;
            case '30%':
                $collg = 4;
                break;
            case '40%':
                $collg = 5;
                break;
            case '50%':
                $collg = 6;
                break;
            case '60%':
                $collg = 7;
                break;
            case '70%':
                $collg = 9;
                break;
            case '80%':
                $collg = 10;
                break;
            case '90%':
                $collg = 11;
            case '100%':
                $collg = 12;
        }

        return $collg;
    }

    public function getName()
    {
        return 'class_twig_extension';
    }

    public function get_class($object)
    {
        return (new \ReflectionClass($object))->getShortName();
    }
}
