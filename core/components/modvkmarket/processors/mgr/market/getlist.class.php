<?php

/**
 * Get a list of Items
 */
class modVkMarketGetlistProcessor extends modObjectProcessor {

    public function process(){


        $this->modx->getService('modvkmarket','modvkmarket.modVkMarket', MODX_CORE_PATH.'components/modvkmarket/model/');

        $vk = $this->modx->modvkmarket->config['vk'];

        $limit = $this->getProperty('limit');
        $start = $this->getProperty('start');

		$res = array();
		$total = 0;
		$cats = $this->modx->getCollection('modVkMarketCategory');
		foreach ( $cats as $cat ) {

			$params = array(
				'owner_id' => '-'.$cat->get('vkgroup_id'),
				'count' => $limit,
				'offset' => $start,
				'extended' => 1
			);

			$goods = $vk->api('market.get', $params);

			$results = $goods['response'];
			$total += $results[0];
			unset($results[0]);
			foreach($results as $key=>$result){
				$result['actions'] = array();
				$result['group_id'] = $cat->get('vkgroup_id');
				$res[] = $result;
			}

		}

        $resp = array(
            "success" => true,
            "total"	  => $total,
            "results" => $res,
        );
        return json_encode($resp);
    }

}

return 'modVkMarketGetlistProcessor';