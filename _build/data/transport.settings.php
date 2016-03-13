<?php

$settings = array();

$tmp = array(
	'access_token' => array(
		'xtype' => 'textfield',
		'value' => '6761fd0597613005779984e77e442a5c5498d82b5f42c7e5efcf3c413234fe5a11bbafa15b17126e16d6c',
		'area'  => 'modvkmarket_vksetting'
	),
	'app_id' => array(
		'xtype' => 'textfield',
		'value' => '5342566',
		'area'  => 'modvkmarket_vksetting'
	),
	'app_secret' => array(
		'xtype' => 'textfield',
		'value' => 'vRHLuNqIYm8FVgdNJ07T',
		'area'  => 'modvkmarket_vksetting'
	),
	'desc_field' => array(
		'xtype' => 'textfield',
		'value' => 'introtext',
		'area'  => 'modvkmarket_main'
	),
	'goods_clear_limit' => array(
		'xtype' => 'textfield',
		'value' => '20',
		'area'  => 'modvkmarket_main'
	),
	'goods_sync_limit' => array(
		'xtype' => 'textfield',
		'value' => '5',
		'area'  => 'modvkmarket_main'
	),
	'shop_type' => array(
		'xtype' => 'textfield',
		'value' => 'default',
		'area'  => 'modvkmarket_main'
	)
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => PKG_NAME_LOWER . '_' . $k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	), '', true, true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
