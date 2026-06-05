id: 17
name: FormItIsSelected
description: 'A custom output filter used with dropdowns for checking selected status.'
category: FormIt
properties: 'a:0:{}'

-----

/**
 * FormItIsChecked
 *
 * Custom output filter that returns checked="checked" if the value is set
 *
 * @var string $input
 * @var string $options
 * @var modX $modx
 *
 * @package formit
 */
$output = ' ';
if ($input == $options) {
    $output = ' selected="selected"';
}

$input = $modx->fromJSON($input);
if (!empty($input) && is_array($input) && in_array($options,$input)) {
  $output = ' selected="selected"';
}

return $output;