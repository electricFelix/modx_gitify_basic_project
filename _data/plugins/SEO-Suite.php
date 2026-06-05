id: 12
name: 'SEO Suite'
description: 'Plugin to handle the SEO Suite redirects.'
category: 'SEO Suite'
properties: 'a:0:{}'

-----

use Sterc\SeoSuite\SeoSuite;

$seosuite = $modx->services->get('seosuite');
$seosuite->firePlugins($modx->event, $scriptProperties);