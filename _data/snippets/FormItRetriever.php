id: 15
name: FormItRetriever
description: 'Fetches a form submission for a user for displaying on a thank you page.'
category: FormIt
properties: 'a:4:{s:17:"placeholderPrefix";a:7:{s:4:"name";s:17:"placeholderPrefix";s:4:"desc";s:31:"prop_fir.placeholderprefix_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:3:"fi.";s:7:"lexicon";s:17:"formit:properties";s:4:"area";s:0:"";}s:20:"redirectToOnNotFound";a:7:{s:4:"name";s:20:"redirectToOnNotFound";s:4:"desc";s:34:"prop_fir.redirecttoonnotfound_desc";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:17:"formit:properties";s:4:"area";s:0:"";}s:11:"eraseOnLoad";a:7:{s:4:"name";s:11:"eraseOnLoad";s:4:"desc";s:25:"prop_fir.eraseonload_desc";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";s:17:"formit:properties";s:4:"area";s:0:"";}s:13:"storeLocation";a:7:{s:4:"name";s:13:"storeLocation";s:4:"desc";s:27:"prop_fir.storelocation_desc";s:4:"type";s:4:"list";s:7:"options";a:2:{i:0;a:2:{s:5:"value";s:5:"cache";s:4:"text";s:16:"formit.opt_cache";}i:1;a:2:{s:5:"value";s:7:"session";s:4:"text";s:18:"formit.opt_session";}}s:5:"value";s:5:"cache";s:7:"lexicon";s:17:"formit:properties";s:4:"area";s:0:"";}}'

-----

/**
 * FormItRetriever
 *
 * Retrieves a prior form submission that was stored with the &store property
 * in a FormIt call.
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package formit
 */

use Sterc\FormIt;

$fi = new FormIt($modx, $scriptProperties);

/* setup properties */
$placeholderPrefix    = $modx->getOption('placeholderPrefix', $scriptProperties, 'fi.');
$eraseOnLoad          = $modx->getOption('eraseOnLoad', $scriptProperties, false);
$redirectToOnNotFound = $modx->getOption('redirectToOnNotFound', $scriptProperties, false);

/* fetch data from cache and set to placeholders */
$fi->loadRequest();
$fi->request->loadDictionary();

$data = $fi->request->dictionary->retrieve();
if (!empty($data)) {
    /* set data to placeholders */
    foreach ($data as $k => $v) {
        /*checkboxes & other multi-values are stored as arrays, must be imploded*/
        if (is_array($v)) {
            $data[$k] = implode(',', $v);
        }
    }

    $modx->toPlaceholders($data, $placeholderPrefix, '');

    /* if set, erase the data on load, otherwise depend on cache expiry time */
    if ($eraseOnLoad) {
        $fi->request->dictionary->erase();
    }
/* if the data's not found, and we want to redirect somewhere if so, do here */
} elseif (!empty($redirectToOnNotFound)) {
    $modx->sendRedirect($modx->makeUrl($redirectToOnNotFound));
}

return '';