<?php

if (!function_exists('joskoomen_print_detect_classes')) {
    function joskoomen_print_detect_classes()
    {
        $detector = \JosKoomen\Detect\UserAgentDetector::make();
        
        $classes = $detector->getDetectionClasses();
        return implode(" ", $classes);

    }
}

if (!function_exists('joskoomen_useragent_detector')) {
    function joskoomen_useragent_detector()
    {
        return \JosKoomen\Detect\UserAgentDetector::make();
    }
}