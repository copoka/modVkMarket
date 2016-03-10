<?php

$properties = array();

$tmp = array(
	/*'id' => array(
		'type' => 'textfield',
		'value' => 0,
	)*/
);

foreach ($tmp as $k => $v) {
	$properties[] = array_merge(
		array(
			'name' => $k,
			'desc' => PKG_NAME_LOWER . '_prop_' . $k,
			'lexicon' => PKG_NAME_LOWER . ':properties',
		), $v
	);
}

return $properties;