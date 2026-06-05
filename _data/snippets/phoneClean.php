id: 47
source: 1
name: phoneClean
properties: 'a:0:{}'

-----

$str = str_replace(" ", '', $str);
$str = str_replace("(", '', $str);
$str = str_replace(")", '', $str);
$str = str_replace("-", '', $str);
$str = str_replace("+", '', $str);

return $str;