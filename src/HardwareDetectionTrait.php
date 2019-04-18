<?php

namespace JosKoomen\Detect;

trait HardwareDetectionTrait
{
    public function getHardwareType()
    {
        $useragent = UserAgentDetector::make()->userAgent();
        if (!$useragent['success']) return [];
        $data = [
            'type' => $useragent['device']['name'],
            'sub_type' => $useragent['device']['sub_type'],
            'sub_sub_type' => $useragent['device']['sub_sub_type'],
            'platform' => $useragent['device']['platform'],
            'vendor' => $useragent['device']['vendor'],
        ];
        return $data;
    }

    public function hardwareIsAppleDevice()
    {
        return $this->_validateHardwareVendor('apple');
    }

    public function hardwareIsSamsungDevice()
    {
        return $this->_validateHardwareVendor('samsung');
    }

    public function hardwareIsNokiaDevice()
    {
        return $this->_validateHardwareVendor('nokia');
    }

    public function hardwareIsMicrosoftDevice()
    {
        return $this->_validateHardwareVendor('microsoft');
    }

    public function hardwareIsLGDevice()
    {
        return $this->_validateHardwareVendor('lg');
    }

    public function hardwareIsLenovoDevice()
    {
        return $this->_validateHardwareVendor('lenovo');
    }

    public function hardwareIsHuaweiDevice()
    {
        return $this->_validateHardwareVendor('huawei');
    }

    public function hardwareIsDellDevice()
    {
        return $this->_validateHardwareVendor('dell');
    }

    public function hardwareIsHPDevice()
    {
        return $this->_validateHardwareVendor('hewlett packard');
    }

    public function hardwareIsBlackberryDevice()
    {
        return $this->_validateHardwareVendor('blackberry');
    }

    public function hardwareIsMotorolaDevice()
    {
        return $this->_validateHardwareVendor('motorola');
    }

    public function hardwareIsComputer()
    {
        return $this->_validateHardwareType('computer');
    }

    public function hardwareIsMobileDevice()
    {
        return $this->_validateHardwareType('mobile');
    }

    public function hardwareIsPhone()
    {
        return $this->hardwareIsMobileDevice()
            && $this->_validateHardwareSubType('phone');
    }

    public function hardwareIsTablet()
    {
        return $this->hardwareIsMobileDevice()
            && $this->_validateHardwareSubType('tablet');
    }

    public function hardwareIsGlasses()
    {
        return $this->hardwareIsMobileDevice()
            && $this->_validateHardwareSubType('wearable')
            && $this->_validateHardwareSubSubType('glasses');
    }

    public function hardwareIsHandheldGameConsole()
    {
        return $this->hardwareIsMobileDevice()
            && $this->_validateHardwareSubType('handheld-game');
    }

    public function hardwareIsMusicPlayer()
    {
        return $this->hardwareIsMobileDevice()
            && $this->_validateHardwareSubType('music-player');
    }

    public function hardwareIsEbookReader()
    {
        return $this->hardwareIsMobileDevice()
            && $this->_validateHardwareSubType('ebook-reader');
    }

    public function hardwareIsLargeScreen()
    {
        return $this->_validateHardwareType('large-screen');
    }

    public function hardwareIsMediaPlayer()
    {
        return $this->hardwareIsLargeScreen()
            && $this->_validateHardwareSubType('media-player');
    }

    public function hardwareIsTelevision()
    {
        return $this->hardwareIsLargeScreen()
            && $this->_validateHardwareSubType('tv');
    }

    public function hardwareIsGoogleTV()
    {
        $useragent = UserAgentDetector::make()->userAgent();
        if (is_null($useragent->operating_system->flavour_code)) return false;
        return strtolower($useragent->operating_system->flavour_code) === 'google-tv';
    }

    public function hardwareIsGameConsole()
    {
        return $this->hardwareIsLargeScreen()
            && $this->_validateHardwareSubType('game-console');
    }

    public function hardwareIsNintendoConsole()
    {
        return $this->_validateHardwareVendor('nintendo')
            && ($this->hardwareIsGameConsole() || $this->hardwareIsHandheldGameConsole());
    }

    public function hardwareIsPlayStation()
    {
        return $this->hardwareIsGameConsole()
            && $this->_validateHardwareVendor('sony');
    }

    public function hardwareIsXBox()
    {
        return $this->hardwareIsGameConsole()
            && $this->_validateHardwareVendor('microsoft');
    }

    public function hardwareIsBillboard()
    {
        return $this->hardwareIsLargeScreen()
            && $this->_validateHardwareSubType('billboard');
    }

    public function hardwareIsServer()
    {
        return $this->_validateHardwareType('server');
    }

    public function hardwareIsCar()
    {
        return $this->_validateHardwareType('vehicle')
            && $this->_validateHardwareSubType('car');
    }

    protected function getHardwareClasses()
    {
        $classes = [];

        foreach ($this->getHardwareType() as $hardware => $value) {

            if (!is_null($value)) {
                $classes[] = strtolower(str_replace(' ', '-', $value));
            }
        }

        return $classes;

    }

    private function _validateHardwareValue($p_value, $p_key)
    {
        $useragent = UserAgentDetector::make()->userAgent();
        if (!$useragent['success'] || is_null($useragent['device'][$p_key])) return false;
        return strtolower($useragent['device'][$p_key]) === strtolower($p_value);
    }

    private function _validateHardwareType($p_type)
    {
        return $this->_validateHardwareValue($p_type, 'name');
    }

    private function _validateHardwareSubType($p_type)
    {
        return $this->_validateHardwareValue($p_type, 'sub_type');
    }

    private function _validateHardwareSubSubType($p_type)
    {
        return $this->_validateHardwareValue($p_type, 'sub_sub_type');
    }

    private function _validateHardwareVendor($p_vendor)
    {
        return $this->_validateHardwareValue($p_vendor, 'vendor');
    }

}
