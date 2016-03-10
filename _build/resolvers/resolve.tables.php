<?php

if ($object->xpdo) {
	/* @var modX $modx */
	$modx =& $object->xpdo;

	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
		case xPDOTransport::ACTION_UPGRADE:

			$modelPath = $modx->getOption('modvkmarket_core_path',null,$modx->getOption('core_path').'components/modvkmarket/').'model/';
			$modx->addPackage('modvkmarket',$modelPath);
			$m = $modx->getManager();
			$m->createObjectContainer('modVkMarketSync');
			$m->createObjectContainer('modVkMarketCategory');

			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;