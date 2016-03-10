<?php
$xpdo_meta_map['modVkMarketSync']= array (
  'package' => 'modvkmarket',
  'version' => '1.1',
  'table' => 'modvkmarket_sync',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'object_id' => 0,
    'group_ids' => '',
    'processed' => 1,
  ),
  'fieldMeta' => 
  array (
    'object_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'string',
      'null' => false,
      'default' => 0,
    ),
    'group_ids' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => true,
      'default' => '',
    ),
    'processed' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 1,
    ),
  ),
  'indexes' => 
  array (
    'object_id' => 
    array (
      'alias' => 'object_id',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'object_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'processed' => 
    array (
      'alias' => 'processed',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'processed' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
