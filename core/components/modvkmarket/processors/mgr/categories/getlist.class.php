<?php

class mvmCategoriesGetlistProcessor extends modObjectGetListProcessor {
	public $objectType = 'modVkMarketCategory';
	public $classKey = 'modVkMarketCategory';
	public $languageTopics = array('modvkmarket:default','modvkmarket:manager');
	public $defaultSortField = 'modVkMarketCategory.id';
	public $defaultSortDirection  = 'ASC';

	/** {@inheritDoc} */
	public function initialize() {
		if (!$this->getProperty('limit')) {$this->setProperty('limit', 20);}

		return parent::initialize();
	}

}
return 'mvmCategoriesGetlistProcessor';