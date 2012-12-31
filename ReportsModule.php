<?php

class ReportsModule extends BaseEventTypeModule
{
	public function init() {
		$this->setImport(array(
			'Reports.models.*',
			'Reports.views.*',
			'Reports.components.*',
			'Reports.controllers.*',
		));
		parent::init();
	}

	public function beforeControllerAction($controller, $action) {
		if (parent::beforeControllerAction($controller, $action)) {
			return true;
		}
		else
			return false;
	}
}
