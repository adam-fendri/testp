<?php

namespace App\TwigExtensions;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CustomTwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('defaultImage',[$this,'defaultImage'])
        ];
    }

    public function defaultImage(string $path){
        if(strlen((trim($path)))==0){
            return 'image1.png';
        }
        return $path;
    }
}