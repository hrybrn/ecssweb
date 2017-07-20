<?php
function setLang() {
    global $lang;
    global $relPath;
    $availableLocales = scandir($relPath . '../data/locale');
    if (isset($_GET['lang'])) { // get language setting from url
        foreach ($availableLocales as $availableLocale) { // check if valid lang
            $availableLocale = strtolower(str_replace('_', '-', $availableLocale));
            if ($_GET['lang'] === $availableLocale) {
                $lang =  $availableLocale;
                return;
            }
        }
    }
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) { // get language setting from http header
        $acceptLang = $_SERVER['HTTP_ACCEPT_LANGUAGE']; // raw string
        $parsingAcceptLanguages = explode(',', str_replace(';', ',', $acceptLang)); // split string
        $acceptLanguages = array();
        foreach ($parsingAcceptLanguages as $parsingAcceptLanguage) {
            $count = preg_match('/^([a-z]{2})(-[a-z]{2})?$/i', $parsingAcceptLanguage, $match); // match if language code
            if ($count === 1) { // if match
                array_push($acceptLanguages, $match[0]);
            }
        }
        foreach ($acceptLanguages as $acceptLanguage) { // check if valid lang
            foreach ($availableLocales as $availableLocale) {
                $availableLocale = strtolower(str_replace('_', '-', $availableLocale));
                $acceptLanguage = strtolower($acceptLanguage);
                if (strcmp($acceptLanguage, $availableLocale) === 0) {
                    $lang =  $availableLocale;
                    // TODO: perform a redirect
                    return;
                }
            }
        }
    }
    $lang = 'en';
    // TODO: perform a redirect
}

setLang();