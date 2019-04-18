<?php

namespace JosKoomen\Detect;

trait SoftwareDetectionTrait
{
    public function getSoftwareType()
    {
        $useragent = UserAgentDetector::make()->userAgent();
        if (!$useragent['success']) return [];
        $data = [
            'type' => $useragent['browser']['type'],
            'sub_type' => $useragent['browser']['sub_type'],
            'name_code' => $useragent['browser']['name_code'],
            'version' => $useragent['browser']['version'],
        ];
        return $data;
    }

    public function softwareIsBot()
    {
        return $this->_validateSoftwareType('bot');
    }

    public function softwareIsWebBrowser()
    {
        return $this->_validateSoftwareType('browser')
            && $this->_validateSoftwareSubType('web-browser');
    }

    public function softwareIsInAppBrowser()
    {

        return $this->_validateSoftwareType('browser')
            && $this->_validateSoftwareSubType('in-app-browser');
    }

    public function softwareIsCrawler()
    {
        return $this->softwareIsBot()
            && $this->_validateSoftwareSubType('crawler');
    }

    public function softwareIsSiteMonitorBot()
    {
        return $this->softwareIsBot()
            && $this->_validateSoftwareSubType('site-monitor');
    }

    public function softwareIsAnalyzerBot()
    {
        return $this->softwareIsBot()
            && ($this->_validateSoftwareSubType('analyzer') || $this->_validateSoftwareSubType('security-analyzer'));
    }

    public function softwareIsGoogle()
    {
        return $this->_validateUserAgentString('lighthouse') || $this->_validateSoftwareNameCode('google', false);
    }

    public function softwareIsGoogleLighthouse()
    {
        return $this->_validateUserAgentString('lighthouse');
    }

    public function softwareIsGoogleSiteVerifier()
    {
        return $this->softwareIsAnalyzerBot() && $this->_validateSoftwareNameCode('google-site-verifier-bot');
    }

    public function softwareIsGoogleBot($p_checkMobileToo = false)
    {

        $bot1 = $this->_validateSoftwareNameCode('googlebot');

        if ($p_checkMobileToo !== false) {
            $bot2 = $this->_validateSoftwareNameCode('googlebot-mobile');
            return $this->softwareIsCrawler() && ($bot1 || $bot2);
        }
        return $this->softwareIsCrawler() && $bot1;
    }

    public function softwareIsGoogleBotMobile()
    {
        return $this->softwareIsCrawler()
            && $this->_validateSoftwareNameCode('googlebot-mobile');
    }

    public function softwareIsGoogleFaviconCrawler()
    {
        return $this->softwareIsCrawler()
            && $this->_validateSoftwareNameCode('googlebot-favicon-crawler');
    }

    public function softwareIsFacebookBot()
    {
        return $this->softwareIsCrawler()
            && $this->_validateSoftwareNameCode('facebook-bot');
    }

    public function softwareIsPinterestBot()
    {
        return $this->softwareIsCrawler()
            && $this->_validateSoftwareNameCode('pinterest-bot');
    }

    public function softwareIsSlackBot()
    {
        return $this->softwareIsCrawler()
            && $this->_validateSoftwareNameCode('slackbot-link-checker');
    }

    public function softwareIsBingBot()
    {
        return $this->softwareIsCrawler()
            && $this->_validateSoftwareNameCode('bingbot');
    }

    public function softwareIsTwitterBot()
    {
        return $this->softwareIsCrawler()
            && $this->_validateSoftwareNameCode('twitterbot');
    }

    public function softwareIsW3CValidatorBot()
    {
        return $this->softwareIsCrawler()
            && $this->_validateSoftwareNameCode('w3c', false);
    }

    public function softwareIsFacebookAppBrowser()
    {
        return $this->softwareIsInAppBrowser()
            && $this->_validateSoftwareNameCode('facebook-app');
    }

    public function softwareIsTwitterAppBrowser()
    {
        return $this->softwareIsInAppBrowser()
            && $this->_validateSoftwareNameCode('twitter-app');
    }

    public function softwareIsChrome()
    {
        return $this->softwareIsWebBrowser()
            && $this->_validateSoftwareNameCode('chrome');
    }

    public function softwareIsSafari()
    {
        return $this->softwareIsWebBrowser()
            && $this->_validateSoftwareNameCode('safari');
    }

    public function softwareIsIE()
    {
        return $this->softwareIsWebBrowser()
            && $this->_validateSoftwareNameCode('internet-explorer');
    }

    public function softwareIsIEMobile()
    {
        return $this->softwareIsWebBrowser()
            && $this->_validateSoftwareNameCode('internet-explorer-mobile');
    }

    public function softwareIsEdge()
    {
        return $this->softwareIsWebBrowser()
            && $this->_validateSoftwareNameCode('edge');
    }

    public function softwareIsNetscape()
    {
        return $this->softwareIsWebBrowser()
            && $this->_validateSoftwareNameCode('netscape-navigator');
    }

    public function softwareIsFirefox()
    {
        return $this->softwareIsWebBrowser()
            && $this->_validateSoftwareNameCode('firefox');
    }

    public function softwareIsOpera()
    {
        return $this->softwareIsWebBrowser()
            && $this->_validateSoftwareNameCode('opera');
    }

    public function softwareIsOperaMini()
    {
        return $this->softwareIsWebBrowser()
            && $this->_validateSoftwareNameCode('opera-mini');
    }

    public function softwareIsSamsungBrowser()
    {
        return $this->softwareIsWebBrowser()
            && $this->_validateSoftwareNameCode('samsung-browser');
    }

    public function softwareIsAndroidBrowser()
    {
        return $this->softwareIsWebBrowser()
            && $this->_validateSoftwareNameCode('android-browser');
    }

    public function softwareIsBlackberryBrowser()
    {
        return $this->softwareIsWebBrowser()
            && $this->_validateSoftwareNameCode('blackberry-browser');
    }

    public function softwareIsGoogleSearchApp()
    {
        return $this->softwareIsWebBrowser()
            && $this->_validateSoftwareNameCode('google-search-app');
    }

    protected function getSoftwareClasses()
    {
        $classes = [];

        if ($this->_validateSoftwareType('browser')) {
            if ($this->softwareIsWebBrowser()) {
                $classes[] = 'web-browser';
            }
            if ($this->softwareIsInAppBrowser()) {
                $classes[] = 'in-app-browser';
            }
            $software = $this->getSoftwareType();

            if (isset($software['name_code']) && !is_null($software['name_code'])) {
                $classes[] = $software['name_code'];

                if (!is_null($software['version'])) {
                    $classes[] = $software['name_code'] . str_replace('.', '_', $software['version']);
                }

            }
        }

        return $classes;
    }

    private function _validateSoftwareValue($p_value, $p_key)
    {
        $useragent = UserAgentDetector::make()->userAgent();
        if (!$useragent['success'] || is_null($useragent['browser'][$p_key])) return false;
        return strtolower($useragent['browser'][$p_key]) === strtolower($p_value);
    }


    private function _validateSoftwareType($p_type)
    {
        return $this->_validateSoftwareValue($p_type, 'type');
    }

    private function _validateUserAgentString($p_string)
    {
        $useragent = UserAgentDetector::make()->userAgent();
        if (!$useragent['success'] || is_null($useragent['string'])) return false;
        return strpos(strtolower($useragent['string']), $p_string) !== false;
    }

    private function _validateSoftwareSubType($p_type)
    {
        return $this->_validateSoftwareValue($p_type, 'sub_type');
    }

    private function _validateSoftwareNameCode($p_type, $p_strict = true)
    {
        $useragent = UserAgentDetector::make()->userAgent();
        if (is_null($useragent['browser']) || is_null($useragent['browser']['name_code'])) return false;

        if ($p_strict === true) {
            return strtolower($useragent['browser']['name_code']) === $p_type;
        }
        // else
        return strpos(strtolower($useragent['browser']['name_code']), $p_type) !== false;
    }

}
