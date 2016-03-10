<?php
$xpdo_meta_map['modVkMarketCategory']= array (
  'package' => 'modvkmarket',
  'version' => '1.1',
  'table' => 'modvkmarket_categories',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'id' => NULL,
    'vkgroup_id' => NULL,
    'cat_ids' => NULL,
    'resource_ids' => NULL,
  ),
  'fieldMeta' => 
  array (
    'id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
      'generated' => 'native',
    ),
    'vkgroup_id' => 
    array (
      'dbtype' => 'int',
      'phptype' => '10',
      'null' => false,
    ),
    'cat_ids' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'resource_ids' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
);
