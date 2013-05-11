php-user_info
=============

PHP class to get information about the website visitor (IP, reverse DNS, referer, OS, etc...)

Usage
-----

1) Include file with UserInfo class in your project:<br/>
```
require_once('<path_to_folder_which_contain_this_file>/UserInfo.php')
```

2) Create UserInfo object:<br/>
```
$UserInfo = new UserInfo();
```

3) Call methods to get info about the website visitor:

* $UserInfo->getIP() - get IP of visitor
* $UserInfo->getReverseDNS() - get Reverse DNS of visitor
* $UserInfo->getCurrentURL() - get current URL
* $UserInfo->getRefererURL() - get Referer URL
* $UserInfo->getDevice() - get Device type (PC/iPad/iPhone/etc...) of visitor
* $UserInfo->getOS() - get OS of visitor
* $UserInfo->getBrowser() - get Browser type of visitor
* $UserInfo->getLanguage() - get Browser Language of visitor
* $UserInfo->getCountryCode() - get Country Code of visitor
* $UserInfo->getCountryName() - get Country Name of visitor
* $UserInfo->getRegionCode() - get Region Code of visitor
* $UserInfo->getRegionName() - get Region Name of visitor
* $UserInfo->getCity() - get City of visitor
* $UserInfo->getZipcode() - get Zipcode of visitor
* $UserInfo->getLatitude() - get Latitude of visitor
* $UserInfo->getLongitude() - get Logitude of visitor

Requirements
------------

1) Browscap<br/>
[http://www.php.net/manual/en/misc.configuration.php#ini.browscap](http://www.php.net/manual/en/misc.configuration.php#ini.browscap)

2) cURL<br/>
[http://php.net/manual/en/book.curl.php](http://php.net/manual/en/book.curl.php)

Creator
------------
[Oleg Koval](http://github.com/olegkoval)<br/>
[@olegkoval](http://twitter.com/olegkoval)