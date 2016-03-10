<?php

/**
 * The home manager controller for modVkMarket.
 *
 */
class modVkMarketHomeManagerController extends modVkMarketMainController {
	/* @var modVkMarket $modVkMarket */
	public $modVkMarket;


	/**
	 * @param array $scriptProperties
	 */
	public function process(array $scriptProperties = array()) {
	}


	/**
	 * @return null|string
	 */
	public function getPageTitle() {
		return $this->modx->lexicon('modvkmarket');
	}


	/**
	 * @return void
	 */
	public function loadCustomCssJs() {
		$this->addCss($this->modVkMarket->config['cssUrl'] . 'mgr/main.css');
		$this->addCss($this->modVkMarket->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
		$this->addJavascript($this->modVkMarket->config['jsUrl'] . 'mgr/misc/utils.js');
		$this->addJavascript($this->modVkMarket->config['jsUrl'] . 'mgr/misc/console.js');
		$this->addJavascript($this->modVkMarket->config['jsUrl'] . 'mgr/goods/goods.grid.js');
		$this->addJavascript($this->modVkMarket->config['jsUrl'] . 'mgr/goods/goods.windows.js');
		$this->addJavascript($this->modVkMarket->config['jsUrl'] . 'mgr/goods/categories.grid.js');
		$this->addJavascript($this->modVkMarket->config['jsUrl'] . 'mgr/home.panel.js');
		$this->addJavascript($this->modVkMarket->config['jsUrl'] . 'mgr/home.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			MODx.load({ xtype: "modvkmarket-page-home"});
		});
		</script>');
	}
}