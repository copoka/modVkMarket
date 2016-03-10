<?php

/**
 * Get a list of Items
 */
class modVkMarketClearProcessor extends modObjectProcessor {

    public function process(){

        $this->modx->getService('modvkmarket','modvkmarket.modVkMarket', MODX_CORE_PATH.'components/modvkmarket/model/');

        $vk = $this->modx->modvkmarket->config['vk'];
        $countSync = $this->modx->getCount('modVkMarketSync');

        if($countSync > 0){
            $this->modx->removeCollection('modVkMarketSync', array('object_id:>' => 0));
        }

        $limit = $this->modx->getOption('modvkmarket_goods_clear_limit');

		$cats = $this->modx->getCollection('modVkMarketCategory');
		$catscount = count($cats);
		$i = 0;
		foreach ( $cats as $cat ) {

			$i++;
			$group_id = $cat->get('vkgroup_id');
			$params = array(
				'owner_id' => '-'.$group_id,
				'count' => $limit,
				'offset' => 0,
				'extended' => 1
			);

			$results = $vk->api('market.get', $params);
			$response = $results['response'];
			$total = $response[0];
			unset($response[0]);
			$goods = $response;
			if($total > 0) {
				$removed = 0;
				foreach ($goods as $good) {
					$remove = $vk->api('market.delete',
						array(
							'owner_id' => '-' . $group_id,
							'item_id' => $good['id']
						)
					);
					if (!$remove) {
						$this->modx->log(1, json_decode($remove));
					}
					$removed++;
					usleep(400000);
				}
				
				$remain = $total - $removed;
				if($remain <= 0){
					$remain = 0;
					$msg = "Все товары в маркете $group_id успешно удалены.";
					$continue = ( $i < $catscount ) ? true : false;
				}else{
					$msg = "Удалено $removed товаров в маркете $group_id, осталось $remain.";
					$continue = true;
				}
			} else continue;

			$level = xPDO::LOG_LEVEL_INFO;
			return $this->prepareResponse(true, $msg, $level, $continue);

		}

		$msg = "Все товары в маркетах успешно удалены.";
		$continue = false;

		$level = xPDO::LOG_LEVEL_INFO;
		return $this->prepareResponse(true, $msg, $level, $continue);
    }

    protected function prepareResponse($success, $msg = '', $level = xPDO::LOG_LEVEL_INFO, $continue = false){
        $result = array(
            "success"   => $success,
            "message"   => $msg,
            "level"     => $level,
            "continue"  => $continue,
            "data"      => [],          // Надо, чтобы MODX-Ajax не разваливался
        );

        if($this->getProperty("output_format") == "json"){
            $result = json_encode($result);
        }

        return $result;
    }
}

return 'modVkMarketClearProcessor';