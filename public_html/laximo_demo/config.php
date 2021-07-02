<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

class Config {
    public static $ui_localization = 'ru'; // ru or en
    public static $catalog_data = 'ru_RU'; // en_GB or ru_RU

    public static $useLoginAuthorizationMethod = true;

    // login/key from laximo.ru
    public static $userLogin = 'ru894056';
    public static $userKey = 'TguTTi39m0gHo6fwR6FhRN2U7qjQkK2CoiUVWYtJCH0';

    public static $redirectUrl = 'http://beta.partexpert.ru/art/$oem$';
}
