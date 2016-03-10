<?php

/**
 * Class modVkMarketMainController
 */
abstract class modVkMarketMainController extends modExtraManagerController {
	/** @var modVkMarket $modVkMarket */
	public $modVkMarket;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('modvkmarket_core_path', null, $this->modx->getOption('core_path') . 'components/modvkmarket/');
		require_once $corePath . 'model/modvkmarket/modvkmarket.class.php';

		$this->modVkMarket = new modVkMarket($this->modx);
		//$this->addCss($this->modVkMarket->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->modVkMarket->config['jsUrl'] . 'mgr/modvkmarket.js');
		$this->addHtml('
		<script type="text/javascript">
			modVkMarket.config = ' . $this->modx->toJSON($this->modVkMarket->config) . ';
			modVkMarket.config.connector_url = "' . $this->modVkMarket->config['connectorUrl'] . '";
		</script>
		');

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('modvkmarket:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends modVkMarketMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}