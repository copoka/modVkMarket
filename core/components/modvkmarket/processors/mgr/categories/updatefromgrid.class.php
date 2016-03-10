<?php

class mvmCategoriesUpdateFromGridProcessor extends modObjectUpdateProcessor {
	public $objectType = 'modVkMarketCategory';
	public $classKey = 'modVkMarketCategory';
	public $languageTopics = array('modvkmarket:default');

	/** {@inheritDoc} */
	public function initialize() {
		$data = $this->modx->fromJSON($this->getProperty('data'));
		if (empty($data) || $data['vkgroup_id'] === '') {
			return $this->modx->lexicon('modvkmarket_err_save');
		}
		$this->setProperties($data);
		$this->unsetProperty('data');
		return parent::initialize();
	}

}
return 'mvmCategoriesUpdateFromGridProcessor';