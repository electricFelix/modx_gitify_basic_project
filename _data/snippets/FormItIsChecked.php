id: 16
name: FormItIsChecked
description: 'A custom output filter used with checkboxes/radios for checking checked status.'
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
    $output = ' checked="checked"';
}
$input = $modx->fromJSON($input);
if (!empty($input) && is_array($input) && in_array($options,$input)) {
  $output = ' checked="checked"';
}
return $output;