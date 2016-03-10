<?php

/**
 * Get a list of Items
 */
class modVkMarketDeleteProcessor extends modObjectProcessor {

    public function process(){

        $this->modx->getService('modvkmarket','modvkmarket.modVkMarket', MODX_CORE_PATH.'components/modvkmarket/model/');

        $vk = $this->modx->modvkmarket->config['vk'];

        $ids = $this->getProperty('ids');

        foreach($ids as $id){
            $obj = $this->modx->getObject('modVkMarketSync', array('object_id' => $id));
            if($obj){
				$grids = $obj->get('group_ids');
				foreach ( $grids as $group_id => $market_id ) {
					$remove = $vk->api('market.delete',
						array(
							'owner_id' => '-' . $group_id,
							'item_id' => $market_id
						)
					);
					if (!$remove) {
						$this->modx->log(1, json_decode($remove));
					}
					usleep(250000);
				}
				if ( $remove ) $obj->remove();
            }
        }
    }
}

return 'modVkMarketDeleteProcessor';