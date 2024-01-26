<?php

namespace App\Service;

class CheckInstance
{
    function checkInstance($object, Array $classnames): bool
    {
        dump($object);
        foreach($classnames as $classname) {
            if (!$object instanceof $classname)
            {
                return false;
            }
        }
        return true;
    }
}
