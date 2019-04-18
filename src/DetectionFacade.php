<?php

namespace JosKoomen\Detect;


use Illuminate\Support\Facades\Facade;

class DetectionFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'joskoomen_detection';
    }

}

