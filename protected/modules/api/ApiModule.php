<?php

class ApiModule extends CWebModule
{
	public function init()
	{
		$this->setImport(array(
			'api.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			return true;
		}
		else
			return false;
	}
}
