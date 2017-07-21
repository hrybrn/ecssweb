<?php
function setLang() {
    global $lang;
    global $relPath;
    if (!isset($relPath)) {
        $relPath = '../';
    }
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
                    redirectToLang($lang);
                    return;
                }
            }
        }
    }
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
$lc = str_replace('-', '_', $lang);
$count = preg_match('/^([a-z]{2})_([a-z]{2})$/i', $lc, $match);
if ($count === 1) {
    $lc = strtolower($match[1]) . '_' . strtoupper($match[2]);
}

putenv("LANG=$lc");
setlocale(LC_ALL, $lc);

$domain = 'messages';
bindtextdomain($domain, $relPath . '../data/locale');
bind_textdomain_codeset($domain, 'UTF-8');
textdomain($domain);

// example
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= _("hi") ?></title>
</head>
<body>
<?= _("hi") ?>
</body>
</html>
