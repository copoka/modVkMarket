<?php

/**
 * Get a list of Items
 */
class modVkMarketSyncProcessor extends modObjectProcessor {

    public function process(){

        $this->modx->getService('modvkmarket','modvkmarket.modVkMarket', MODX_CORE_PATH.'components/modvkmarket/model/');

        $vk = $this->modx->modvkmarket->config['vk'];
        $limit = $this->modx->getOption('modvkmarket_goods_sync_limit');

        $miniShop2 = $this->modx->getService('minishop2','miniShop2',
            MODX_CORE_PATH . 'components/minishop2/model/minishop2/');
        if (!($miniShop2 instanceof miniShop2)) return '';

		$cats = $this->modx->getCollection('modVkMarketCategory');
		$catscount = count($cats);
		$i = 0;
		foreach ( $cats as $cat ) {

			$i++;
			$group_id = $cat->get('vkgroup_id');
			$parents = $cat->get('cat_ids');
			$resources = $cat->get('resource_ids');
			$srv = $vk->api('photos.getMarketUploadServer', array(
				'group_id' => $group_id,
				'main_photo' => 1
			));

			$upload_server = $srv['response']['upload_url'];

			$q = $this->modx->newQuery('msProduct');
			$q->leftJoin('modVkMarketSync', 'Sync', 'msProduct.id = Sync.object_id');
			$q->where(array('Sync.group_ids:IS' => null, 'OR:Sync.group_ids:NOT LIKE' => '%"'.$group_id.'"%'));
			$q->where(array('msProduct.published' => 1));
			if ( !empty($parents) ) {
				$parents = explode(',', $parents);
				$pids = array_unique(array_merge($parents, $this->modx->modvkmarket->getChildIds('msCategory', $parents)));
				$q->where(array('msProduct.parent:IN' => $pids));
			}
			if ( !empty($resources) ) {
				$rids = explode(',', $resources);
				$q->where(array('msProduct.id:IN' => $rids));
			}
			$q->limit($limit);

			$products = $this->modx->getCollection('msProduct', $q);
			$prodCount = count($products);

			if ($prodCount > 0) {
				$added = 0;
				foreach($products as $product){
					$object_id = $product->get('id');
					$name = $product->get('pagetitle');
					$price = $product->get('price');
					$description = $product->get('introtext');

					$description .= "\r\n".$this->modx->getOption('site_url').$product->get('uri');

					if(mb_strlen($description) < 10 || mb_strlen($name) < 2 || !$price){
						continue;
					}

					$image = $product->get('image');

					if($image){
						$size = getimagesize($_SERVER['DOCUMENT_ROOT'].$image);
						$thumb_width = 500;
						$thumb_height = 500;
						if($size[0] < 500 || $size[1] < 500){
							$options = "w=$thumb_width&height=$thumb_height&f=jpg";
						}else{
							$options = "w=$thumb_width&height=$thumb_height&zc=1&f=jpg";
						}
						$this->modx->resource = $this->modx->getObject('modResource', $product->id);
						$thumbnail = ( $this->modx->getCount('modSnippet', array('name' => 'phpthumbof')) ) ?
							$this->modx->runSnippet("phpthumbof", array('input' => $image, 'options' => $options))
							: $image;
					}else{
						$thumbnail = MODX_BASE_URL.'assets/components/modvkmarket/img/no_image.jpg';
					}

					$photopath =  $_SERVER['DOCUMENT_ROOT'].$thumbnail;

					$vkphotos = $this->modx->modvkmarket->uploadImage($upload_server, $photopath);

					$photo = $vk->api('photos.saveMarketPhoto',
						array(
							'group_id'=>$group_id,
							'photo'=>$vkphotos['photo'],
							'server'=>$vkphotos['server'],
							'hash'=>$vkphotos['hash'],
							'crop_data' => $vkphotos['crop_data'],
							'crop_hash' => $vkphotos['crop_hash']
						));

					if(isset($photo['error'])){
						$this->modx->log(1, json_encode($photo));
						continue;
					}
					// 	print_r($photo);
					// 	return;

					$good = $vk->api('market.add', array(
						'owner_id' => '-'.$group_id,
						'name'=> $name,
						'price' => $price,
						'description' => $description,
						'category_id' => 704,
						'main_photo_id' => $photo["response"][0]["pid"]
					));
					if(isset($good['error'])){
						$this->modx->log(1, json_encode($good));
						continue;
					}

					$market_id = $good['response']['market_item_id'];
					$sync = $this->modx->getObject('modVkMarketSync', array('object_id' => $object_id));
					if ( count($sync) > 0 ) {
						$grids = $sync->get('group_ids');
						$grids[$group_ids] = $market_id;
						$sync->set('group_ids');
						$sync->save();
					} else {
						$sync = $this->modx->newObject('modVkMarketSync');
						$sync->fromArray(
							array(
								'object_id' => $object_id,
								'group_ids' => array($group_id => $market_id),
								'processed' => 1
							)
						);
						$sync->save();
					}

					$added++;
					usleep(500000);

				}

				$params = array(
					'owner_id' => '-'.$group_id,
					'count' => 1,
					'offset' => 0,
					'extended' => 1
				);

				$results = $vk->api('market.get', $params);
				$response = $results['response'];
				$total = $response[0];


				if($prodCount > 0){
					$msg = "Добавлено $added товаров в группу $group_id, всего в маркете $total товаров.";
					$continue = true;
				}else{
					$msg = "Все товары добавлены в группу $group_id. Всего в маркете $total товаров.";
					$continue = ( $i < $catscount ) ? true : false;
				}

				$level = xPDO::LOG_LEVEL_INFO;
				return $this->prepareResponse(true, $msg, $level, $continue);

			}

			$msg = "Сихронизация завершена";
			$continue = false;
			$level = xPDO::LOG_LEVEL_INFO;
			return $this->prepareResponse(true, $msg, $level, $continue);
		}
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

return 'modVkMarketSyncProcessor';