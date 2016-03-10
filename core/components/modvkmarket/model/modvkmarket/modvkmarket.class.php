<?php

/**
 * The base class for modVkMarket.
 */
class modVkMarket {
	/* @var modX $modx */
	public $modx;


	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;


		$corePath = $this->modx->getOption('modvkmarket_core_path', $config, $this->modx->getOption('core_path') . 'components/modvkmarket/');
		$assetsUrl = $this->modx->getOption('modvkmarket_assets_url', $config, $this->modx->getOption('assets_url') . 'components/modvkmarket/');
		$connectorUrl = $assetsUrl . 'connector.php';

		include_once($corePath. 'libs/VK.php');
		include_once($corePath. 'libs/VKException.php');

		$appid = $this->modx->getOption('modvkmarket_app_id');
		$appsecret = $this->modx->getOption('modvkmarket_app_secret');
		$token = $this->modx->getOption('modvkmarket_access_token');

		try {
			$vk = new VK\VK($appid, $appsecret, $token);
		} catch (VK\VKException $error) {
			$vk = '';
			$this->modx->log(1, $error->getMessage());
		}

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'connectorUrl' => $connectorUrl,
			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'chunkSuffix' => '.chunk.tpl',
			'snippetsPath' => $corePath . 'elements/snippets/',
			'processorsPath' => $corePath . 'processors/',
			'group_id' => $group_id,
			'vk' => $vk

		), $config);

		$this->modx->addPackage('modvkmarket', $this->config['modelPath']);
		$this->modx->lexicon->load('modvkmarket:default');
	}

	public function uploadImage($server, $file){

		$this->modx->log(1, $file);
		if (version_compare(phpversion(), '5.5.0', '<')) {
			$image = '@'.$file;
		}else{
			$image = new CURLFile($file);
		}

		$params = array(
			'file' => $image
		);



		/*$client = $this->modx->getService('rest.modRestCurlClient');
        $vkphotos = $client->request($upload_server, '/', 'POST', $params);*/

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $server);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		$vkphotos = curl_exec($ch);
		curl_close($ch);

		$vkphotos = json_decode($vkphotos,1);

		return $vkphotos;
	}
	
	public function getChildIds($class, $parents){
		if ( empty($class) ) $class = 'modResource';
		if ( !is_array($parents) ) $parents = explode(',', $parents);
		$childs = array();
		foreach ( $parents as $parent ) {
			$res = $this->modx->getCollection($class, array('parent' => $parent, 'published' => 1, 'deleted' => 0));
			if ( count($res) > 0 )
				foreach ( $res as $tmp ) {
					$childs[] = $tmp->get('id');
					$tmpids = $this->getChildIds($class, $tmp->get('id'));
					if ( is_array($tmpids) ) $childs = array_merge($childs, $tmpids);
				}
		}
		return ( is_array($childs) ) ? array_unique($childs) : '';
	}

}