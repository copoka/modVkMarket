<?php

class mvmCategoryCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'modVkMarketCategory';
	public $classKey = 'modVkMarketCategory';
	public $languageTopics = array('modvkmarket:default');

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
		if ($this->hasErrors()) {
			return false;
		}
		return !$this->hasErrors();
	}

}

return 'mvmCategoryCreateProcessor';