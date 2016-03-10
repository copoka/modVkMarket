<?php

class mvmCategoryRemoveProcessor extends modProcessor {
	public $classKey = 'modVkMarketCategory';

	/** {inheritDoc} */
	public function process() {
		if (!$ids = explode(',', $this->getProperty('ids'))) {
			return $this->failure($this->modx->lexicon('modvkmarket_err_remove'));
		}
		$items = $this->modx->getIterator($this->classKey, array('id:IN' => $ids));
		foreach ($items as $item) {
			$item->remove();
		}
		return $this->success();
	}

}

return 'mvmCategoryRemoveProcessor';