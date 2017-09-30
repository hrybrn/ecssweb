<?php
/**
 * get language setting from url if valid $_GET['lang'] is set,
 * or get language setting from http accept-language header if possible and redirect,
 * otherwise use en as default and redirect.
 */
function setLang() {
    global $lang;
    global $relPath;
    if (!isset($relPath)) {
        $relPath = '../';
    }

    // get languages config
    global $languages;
    $raw = file_get_contents($relPath . '../data/languages.json');
    $languages = json_decode($raw, true);

    // get language setting from url
    if (isset($_GET['lang'])) {
        if (array_key_exists($_GET['lang'], $languages) && $languages[$_GET['lang']]['enabled'] === 'true') {
            $lang = $_GET['lang'];
            return;
        }
    }

    // get language setting from http header
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        // get accept-language
        $rawAcceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $parsingAcceptLanguages = explode(',', str_replace(';', ',', $rawAcceptLanguage)); // split
        $acceptLanguages = array();
        foreach ($parsingAcceptLanguages as $parsingAcceptLanguage) {
            $count = preg_match('/^([a-z]{2})(-[a-z]{2})?$/i', $parsingAcceptLanguage, $match); // match language code
            if ($count === 1) {
                array_push($acceptLanguages, $match[0]);
            }
        }
        // check valid language
        foreach ($acceptLanguages as $acceptLanguage) {
            $acceptLanguage = strtolower($acceptLanguage);
            if (array_key_exists($acceptLanguage, $languages) && $languages[$acceptLanguage]['enabled'] === 'true') {
                $lang =  $acceptLanguage;
                redirectToLang($lang);
                return;
            }
        }
    }

    // default en
    $lang = 'en';
    redirectToLang($lang);
}

function redirectToLang($lang) {
    // scheme
    $scheme = isset($_SERVER['HTTPS']) ? 'https' : 'http';
    // query
    $_GET['lang'] = $lang;
    $query = http_build_query($_GET);
    // path
    $path = parse_url($_SERVER['REQUEST_URI'])['path'];

    // assemble url
    $url = $scheme . '://' . $_SERVER['HTTP_HOST'] . $path . '?' . $query;

    // redirect
    header('Location: ' . $url);
    exit;
}

setLang();

// set locale
$lc = $languages[$lang]['locale'];

putenv("LANG=$lc");
setlocale(LC_ALL, $lc);

function setTextDomain($domain) {
    global $relPath;
    bindtextdomain($domain, $relPath . '../data/locale');
    bind_textdomain_codeset($domain, 'UTF-8');
    textdomain($domain);
}

