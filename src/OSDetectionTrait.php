<?php

namespace JosKoomen\Detect;

trait OSDetectionTrait
{
    public function getOperatingSystem()
    {
        $useragent = UserAgentDetector::make()->userAgent();
        if (!$useragent['success']) return [];
        $data = [
            'name' => $useragent['os']['name'],
            'full_name' => $useragent['os']['full_name'],
            'version' => $useragent['os']['version'],
            'flavour_code' => $useragent['os']['flavour_code']
        ];
        return $data;
    }

    public function osIsIos($p_version = null, $p_orLower = false, $p_withSubVersion = false)
    {
        $os = $this->_validateOsName('ios');

        if ($os && !is_null($p_version)) {

            if ($p_withSubVersion !== false) {
                if ($p_orLower !== false) {
                    return $this->_validateOsVersion($p_version, UserAgentDetector::FLOAT, true);
                }
                return $this->_validateOsVersion($p_version, UserAgentDetector::FLOAT);
            }

            if ($p_orLower !== false) {
                return $this->_validateOsVersion($p_version, UserAgentDetector::INTEGER, true);
            }
            return $this->_validateOsVersion($p_version, UserAgentDetector::INTEGER);
        }

        return $os;
    }

    public function osIsAndroid($p_version = null)
    {
        $android = $this->_validateOsName('android');

        if ($android && !is_null($p_version)) {
            return $this->_validateOsVersion($p_version, UserAgentDetector::STRING);
        }

        return $android;
    }

    public function osRunsOnCitrix()
    {
        $useragent = UserAgentDetector::make()->userAgent();
        return strpos(strtolower($useragent['string']), 'citrix') !== false;
    }

    public function osIsMacOs()
    {
        $os1 = $this->_validateOsName('mac os x');
        $os2 = $this->_validateOsName('macos');
        return $os1 || $os2;
    }

    public function osIsWindows($p_version = null, $p_alsoMobile = false)
    {
        $version = true;
        $os = $this->_validateOsName('windows');
        if ($p_alsoMobile !== false) {
            $os = ($os || $this->osIsWindowsMobile());
        }

        if (!is_null($p_version)) {
            $version = $this->_validateOsVersion($p_version, UserAgentDetector::STRING);
        }

        return $os && $version;
    }

    public function osIsWindowsMobile()
    {
        $os1 = $this->_validateOsName('windows mobile');
        $os2 = $this->_validateOsName('windows phone');
        return $os1 || $os2;
    }

    public function osIsLinux()
    {
        return $this->_validateOsName('linux');
    }

    public function osIsChromeOs()
    {
        return $this->_validateOsName('chromeos');
    }

    public function osIsBlackberryOs()
    {
        return $this->_validateOsName('blackberry os');
    }

    public function osIsSymbian()
    {
        return $this->_validateOsName('symbian');
    }

    protected function getOSClasses()
    {
        $classes = [];

        foreach ($this->getOperatingSystem() as $os => $value) {

            if (!is_null($value)) {
                switch ($os) {
                    case 'name':
                        $classes[] = strtolower(str_replace(' ', '-', $value));
                        break;
                    case 'flavour_code':
                        $classes[] = $value;
                        break;
                }
            }
        }

        return $classes;

    }

    private function _validateOsName($string)
    {
        $useragent = UserAgentDetector::make()->userAgent();
        if (!$useragent['success']) return false;
        return strtolower($useragent['os']['name']) === strtolower($string);
    }

    private function _validateOsVersion($p_version, $p_type = UserAgentDetector::FLOAT, $p_currentVersionOrLower = false)
    {
        $useragent = UserAgentDetector::make()->userAgent();
        if (!$useragent['success']) return false;
        switch ($p_type) {
            default:
            case UserAgentDetector::FLOAT:
                if ($p_currentVersionOrLower) {
                    return floatval($useragent['os']['version']) <= floatval($p_version);
                } else {
                    return floatval($useragent['os']['version']) === floatval($p_version);
                }
                break;
            case UserAgentDetector::INTEGER:
                if ($p_currentVersionOrLower) {
                    return intval($useragent['os']['version']) <= intval($p_version);
                } else {
                    return intval($useragent['os']['version']) === intval($p_version);
                }
                break;
            case UserAgentDetector::STRING:
                return strval(strtolower($useragent['os']['version'])) === strval($p_version);
                break;
        }

    }


}
