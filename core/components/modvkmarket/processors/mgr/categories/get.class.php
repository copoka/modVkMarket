<?php

class mvmCategoriesGetProcessor extends modObjectGetProcessor {
	public $objectType = 'modVkMarketCategory';
	public $classKey = 'modVkMarketCategory';
	public $languageTopics = array('modvkmarket:default','modvkmarket:manager');

	/** {inheritDoc} */
	public function cleanup() {
		$array = $this->object->toArray('', true);
		return $this->success('', $array);
	}

}

return 'mvmCategoriesGetProcessor';
