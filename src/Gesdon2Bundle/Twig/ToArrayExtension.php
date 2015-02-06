<?php

namespace Gesdon2Bundle\Twig;


class ToArrayExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('to_array', array($this, 'to_array')),
        );
    }
    public function to_array($object)
    {
        return (array)$object;
    }
    public function getName()
    {
        return 'to_array_extension';
    }
}
