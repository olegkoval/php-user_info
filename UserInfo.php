<?php
/**
 * Get information about user which visited page of your website
 * @package UserInfo
 * @uses browscap http://www.php.net/manual/en/misc.configuration.php#ini.browscap
 * @uses cURL http://php.net/manual/en/book.curl.php
 * @uses freegeoip http://freegeoip.net
 * @author Oleg Koval <oleh.koval@gmail.com>
 * @copyright Copyright (c) 2013 Oleg Koval -- http://olegkoval.com
 * @version 1.1
 * @link https://github.com/olegkoval/php-user_info
 */

class UserInfo {
    private $browserInfo;
    private $geoInfo;

    /**
     * Autoload information from external services and set values of internal proprties
     */
    public function __construct() {
        //use try-catch to prevent error when server is not configured to use browscap (get_browser() function)
        try {
            $this->browserInfo = get_browser($_SERVER['HTTP_USER_AGENT'], true);
        }
        catch(Exception $e) {
            $this->browserInfo = array();
        }

        //or we got some cURL exception, etc.
        try {
            $this->geoInfo = $this->getGeoInfo();

            if (!is_array($this->geoInfo)) {
                throw new Exception('We do not got a valid JSON answer from Freegeoip service.', 1);
            }
        }
        catch(Exception $e) {
            $this->geoInfo = array();
        }

    }

    /**
     * Get user IP
     * @return string
     */
    public function getIP() {
        $result = null;

        //for proxy servers
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $result = end(array_filter(array_map('trim', explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']))));
        }
        else {
            $result = $_SERVER['REMOTE_ADDR'];
        }

        return $result;
    }

    /**
     * Get user reverse DNS
     * @return string
     */
    public function getReverseDNS() {
        return gethostbyaddr($this->getIP());
    }

    /**
     * Get current page URL
     * @return string
     */
    public function getCurrentURL() {
        return 'http'. (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 's': '') 
                . '://' . $_SERVER["SERVER_NAME"] 
                . ($_SERVER['SERVER_PORT'] != '80' ? $_SERVER['SERVER_PORT'] : '')
                . $_SERVER["REQUEST_URI"];
    }

    /**
     * Get referer URL
     * @return string
     */
    public function getRefererURL() {
        return (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
    }

    /**
     * Get user browser language
     * @return string
     */
    public function getLanguage() {
        return strtoupper(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
    }

    /**
     * Get user Device info (PC/Mac/Mobile/iPhone/iPad/etc...)
     * @return string
     */
    public function getDevice() {
        $result = '';

        if (is_array($this->browserInfo) && isset($this->browserInfo['device_name'])) {
            $result = $this->browserInfo['device_name'];
        }

        return $result;
    }

    /**
     * Get user OS info
     * @return string
     */
    public function getOS() {
        $result = '';

        if (is_array($this->browserInfo) && isset($this->browserInfo['platform'])) {
            $result = $this->browserInfo['platform'];
        }

        return $result;
    }

    /**
     * Get user Browser info
     * @return string
     */
    public function getBrowser() {
        $result = '';

        if (is_array($this->browserInfo) && isset($this->browserInfo['browser'])) {
            $result = $this->browserInfo['browser'] . (isset($this->browserInfo['version']) ? ' v.' . $this->browserInfo['version'] : '');
        }

        return $result;
    }

    /**
     * Get user Country Code
     * @return string
     */
    public function getCountryCode() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['country_code'])) {
            $result = $this->geoInfo['country_code'];
        }

        return $result;
    }

    /**
     * Get user Country Name
     * @return string
     */
    public function getCountryName() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['country_name'])) {
            $result = $this->geoInfo['country_name'];
        }

        return $result;
    }

    /**
     * Get user Region Code
     * @return string
     */
    public function getRegionCode() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['region_code'])) {
            $result = $this->geoInfo['region_code'];
        }

        return $result;
    }

    /**
     * Get user Region Name
     * @return string
     */
    public function getRegionName() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['region_name'])) {
            $result = $this->geoInfo['region_name'];
        }

        return $result;
    }

    /**
     * Get user City
     * @return string
     */
    public function getCity() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['city'])) {
            $result = $this->geoInfo['city'];
        }

        return $result;
    }

    /**
     * Get user Zipcode
     * @return string
     */
    public function getZipcode() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['zipcode'])) {
            $result = $this->geoInfo['zipcode'];
        }

        return $result;
    }

    /**
     * Get user Latitude
     * @return string
     */
    public function getLatitude() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['latitude'])) {
            $result = $this->geoInfo['latitude'];
        }

        return $result;
    }

    /**
     * Get user Longitude
     * @return string
     */
    public function getLongitude() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['longitude'])) {
            $result = $this->geoInfo['longitude'];
        }

        return $result;
    }

    /**
     * Check if connection was through proxy
     * @return boolean
     */
    public function isProxy() {
        $result = false;

        //for proxy servers
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $addresses = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            if (count($addresses) > 0) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Get geo information about user. For this we use user IP and external service
     * Freegeoip (http://freegeoip.net)
     */
    private function getGeoInfo() {
        $url = 'http://freegeoip.net/json/' . self::getIP();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        return $result;
    }
}