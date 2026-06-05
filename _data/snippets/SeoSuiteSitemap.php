id: 46
name: SeoSuiteSitemap
category: 'SEO Suite'
properties: 'a:0:{}'

-----

use Sterc\SeoSuite\Snippets\Sitemap;

$sitemap = new Sitemap($modx);

return $sitemap->process($scriptProperties);