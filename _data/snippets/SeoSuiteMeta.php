id: 45
name: SeoSuiteMeta
description: 'Snippet for adding the title and meta description in your website.'
category: 'SEO Suite'
properties: 'a:0:{}'

-----

use Sterc\SeoSuite\Snippets\Meta;

$meta = new Meta($modx);

return $meta->process($scriptProperties);