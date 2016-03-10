<?php
return;
$modx->getService('modvkmarket','modvkmarket.modVkMarket', MODX_CORE_PATH.'components/modvkmarket/model/');

switch ($modx->event->name) {

    // Documents
    case 'OnDocFormSave':

        $modx->error->reset();
        if(!$object){
            $object = $resource;
        }
        $object = $object->toArray();
        if ($object['class_key'] == 'msProduct') {

            $product_id = $object['id'];
            $published = $object['published'];

            $item_id = $modx->getObject('modVkMarketSync', array('object_id' => $product_id))->market_id;

            if($published == 1){
                if(isset($item_id) && $item_id > 0){
                    $processor = 'mgr/market/update';
                }else{
                    $processor = 'mgr/market/create';
                }

                $params = array('product_id' => $product_id);

                $response = $modx->runProcessor($processor, $params
                    ,array(
                        'processors_path' => $modx->getOption('modvkmarket_core_path').'processors/',
                    )
                );
            }
        }
        break;
    case 'OnBeforeEmptyTrash':
        if(is_array($ids)){
            $params = array('ids' => $ids);
            $processor = 'mgr/market/delete';
            $response = $modx->runProcessor($processor, $params
                ,array(
                    'processors_path' => $modx->getOption('modvkmarket_core_path').'processors/',
                )
            );
        }
        break;
}