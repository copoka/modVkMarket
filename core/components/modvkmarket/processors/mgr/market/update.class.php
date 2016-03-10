<?php

class modVkMarketUpdateProcessor extends modObjectProcessor {

    public function process(){

        $this->modx->getService('modvkmarket','modvkmarket.modVkMarket', MODX_CORE_PATH.'components/modvkmarket/model/');

        $product_id = $this->getProperty('product_id');
        $item_id = $this->modx->getObject('modVkMarketSync', array('object_id' => $product_id))->market_id;

        if(!$product_id || $product_id <= 0 || $item_id <= 0){
            return false;
        }

        $vk = $this->modx->modvkmarket->config['vk'];
        $group_id = $this->modx->modvkmarket->config['group_id'];

        $miniShop2 = $this->modx->getService('minishop2','miniShop2',
            MODX_CORE_PATH . 'components/minishop2/model/minishop2/');
        if (!($miniShop2 instanceof miniShop2)) return '';

        $srv = $vk->api('photos.getMarketUploadServer', array(
            'group_id' => $group_id,
            'main_photo' => 1
        ));

        $upload_server = $srv['response']['upload_url'];

        $product = $this->modx->getObject('msProduct', $product_id);

        $object_id = $product->get('id');
        $name = $product->get('pagetitle');
        $price = $product->get('price');
        $description = $product->get('introtext');

        $description .= "\r\n".$this->modx->getOption('site_url').$product->get('uri');

        if(mb_strlen($description) < 10 || mb_strlen($name) < 2 || !$price){

        }

        $image = $product->get('image');

        if($image){
            $thumb_width = 500;
            $thumb_height = 500;
            $options = "w=$thumb_width&height=$thumb_height&zc=1&f=jpg";
            $this->modx->resource = $this->modx->getObject('modResource', $product->id);
            $thumbnail = $this->modx->runSnippet("phpthumbof", array('input' => $image, 'options' => $options));
        }else{
            $thumbnail = '/assets/components/modvkmarket/img/no_image.jpg';
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
        }
        // 	print_r($photo);
        // 	return;

        $good = $vk->api('market.edit', array(
            'owner_id' => '-'.$group_id,
            'item_id' => $item_id,
            'name'=> $name,
            'price' => $price,
            'description' => $description,
            'category_id' => 704,
            'main_photo_id' => $photo["response"][0]["pid"]
        ));

        if(isset($good['error'])){
            $this->modx->log(1, json_encode($good));
        }

        $msg = "Товар успешно обновлен";

        $level = xPDO::LOG_LEVEL_INFO;
        return $this->prepareResponse(true, $msg, $level, false);
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

return 'modVkMarketUpdateProcessor';