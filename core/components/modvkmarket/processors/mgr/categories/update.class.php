<?php

class mvmCategoriesUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'modVkMarketCategory';
	public $classKey = 'modVkMarketCategory';
	public $languageTopics = array('modvkmarket:default','modvkmarket:manager');

	/**
	 * @return bool
	 */
	public function beforeSet() {
		$required = array('vkgroup_id');
		foreach ($required as $tmp) {
			if (!$this->getProperty($tmp)) {
				$this->addFieldError($tmp, $this->modx->lexicon('field_required'));
			}
		}
		return !$this->hasErrors();
	}

}

return 'mvmCategoriesUpdateProcessor';