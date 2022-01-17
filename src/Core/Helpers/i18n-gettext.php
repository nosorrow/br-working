<?php
/*
include_once 'lib/gettext.inc';

define('PROJECT_DIR', realpath('./'));
define('LOCALE_DIR', PROJECT_DIR .DIRECTORY_SEPARATOR . 'locale');
define('DEFAULT_LOCALE', 'en_US');

$supported_locales = array('en_US', 'sr_CS', 'de_CH');
$encoding = 'UTF-8';

$locale = (isset($_GET['lang']))? $_GET['lang'] : DEFAULT_LOCALE;

// gettext setup
T_setlocale(LC_MESSAGES, $locale);
// Set the text domain as 'messages'
$domain = 'message';
T_bindtextdomain($domain, LOCALE_DIR);
T_bind_textdomain_codeset($domain, $encoding);
T_textdomain($domain);
*/

include_once SYSTEM_DIR . "Libs/i18n/php-gettext/gettext.php";

include_once SYSTEM_DIR . "Libs/i18n/php-gettext/streams.php";

function get_locale_lang()
{
    if (!empty(request_get('lang'))) {

        $locale_lang = request_get('lang');

    } elseif (!empty(sessionData("lang"))) {

        $locale_lang = sessionData("lang");

    } elseif (!empty(get_cookie('lang'))) {

        $locale_lang = get_cookie('lang');

    } elseif (\Core\Libs\Config::getConfigFromFile('lang')) {

        $locale_lang = \Core\Libs\Config::getConfigFromFile('lang');

    } else {
        $locale_lang = "bg_BG";
    }

    return $locale_lang;
}


/**
 * @return gettext_reader
 */
function init_i18n()
{
    $locale_lang = get_locale_lang();

    $domain = APPLICATION_DIR . "locale/$locale_lang/LC_MESSAGES/$locale_lang.mo";

    $locale_file = new FileReader($domain);

    $locale_fetch = new gettext_reader($locale_file);

    return $locale_fetch;
}

/**
 * @param $text
 * @return string
 */
function tr_($text)
{
    $locale_fetch = init_i18n();

    return $locale_fetch->translate($text);
}

/**
 * @param $singular
 * @param $plural
 * @param $number
 * @return translated
 */
function tn_($singular, $plural, $number)
{
    $locale_fetch = init_i18n();

    return $locale_fetch->ngettext($singular, $plural, $number);
}
