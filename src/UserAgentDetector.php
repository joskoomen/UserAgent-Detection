<?php

namespace JosKoomen\Detect;

use JosKoomen\Api\AbstractApiValidationTrait;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Log;

class UserAgentDetector
{
    use AbstractApiValidationTrait,
        HardwareDetectionTrait,
        SoftwareDetectionTrait,
        OSDetectionTrait;

    private static $_instance = null;
    private static $_useragent = null;

    const STRING = 'string';
    const INTEGER = 'integer';
    const FLOAT = 'float';

    const CLASSES_NONE = 0;
    const CLASSES_LAYOUT_ONLY = 1;
    const CLASSES_LAYOUT_BROWSERS = 2;
    const CLASSES_ALL_BUT_HARDWARE = 3;
    const CLASSES_ALL = 4;

    public static function make()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new UserAgentDetector();
        }
        return self::$_instance;
    }

    public function userAgent()
    {
        if (!isset(self::$_useragent) || !session()->has('useragent')) {

            try {
                $params = $this->addTimeAndSignature(['user_agent' => $_SERVER['HTTP_USER_AGENT']]);
                $response = guzzle_http_client()->request('POST', config('joskoomen-detection.api_url') . '/api/v' . config('joskoomen-detection.api_version') . '/user_agent', [
                    'form_params' => $params,
                    'content_type' => 'application/json'
                ]);
            } catch (BadResponseException $e) {
                $data = [];
                $data['string'] = null;
                $data['success'] = false;
                $data['error'] = $e->getResponse()->getBody()->getContents();
                return $data;
            }

            if ($response->getStatusCode() === 200) {
                $response_body = json_decode($response->getBody()->getContents(), true);
                $response_body['success'] = true;
                self::$_useragent = $response_body;
                session('user_agent', serialize($response_body));
            }

        } else {
            self::$_useragent = unserialize(session('user_agent'));
        }
        return self::$_useragent;
    }

    public function getDetectionClasses()
    {
        $configVal = intval(config('joskoomen-detection.class_level'));
        if ($configVal === self::CLASSES_NONE) return [];

        $classes = [];

        if ($configVal >= self::CLASSES_LAYOUT_BROWSERS) {
            $softwareClasses = $this->getSoftwareClasses();
            $classes = array_merge($classes, $softwareClasses);
        }
        if ($configVal >= self::CLASSES_ALL_BUT_HARDWARE) {
            $osClasses = $this->getOSClasses();
            $classes = array_merge($classes, $osClasses);
        }
        if ($configVal >= self::CLASSES_ALL) {
            $hardwareClasses = $this->getHardwareClasses();
            $classes = array_merge($classes, $hardwareClasses);
        }

        return array_unique($classes);
    }

    protected function getHashSecret()
    {
        if ($this->_hasDebugMode()) {
            Log::debug('UserAgentDetector::getHashSecret ( ' . config('joskoomen-detection.hashsecret') . ' )');
        }
        return config('joskoomen-detection.hashsecret');
    }
}
