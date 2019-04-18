<?php

namespace JosKoomen\Detect;


class DetectionFactory
{
    public static function userAgent()
    {
        return (string) UserAgentDetector::make()->userAgent();
    }
}
