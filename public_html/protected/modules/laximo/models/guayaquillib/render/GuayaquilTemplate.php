<?php
class GuayaquilTemplate
{
	var $extender;

	public function __construct(IGuayaquilExtender $extender)
	{
		$this->extender = $extender;

		static $guayaquil_templateinitialized;

		if (!isset($guayaquil_templateinitialized))
		{
			$assetsDir = dirname(__FILE__).'/../../../assets';
			Yii::app()->clientScript->registerCssFile(Yii::app()->assetManager->publish($assetsDir).'/css/guayaquil.css');
			
			$guayaquil_templateinitialized = 1;
		}
	}

	public function GetLocalizedString($name, $params = false)
	{
		if ($this->extender == NULL)
			return $name;

			return $this->extender->GetLocalizedString($name, $params, $this);
	}

	public function FormatLink($type, $dataItem, $catalog)
	{
		if ($this->extender == NULL)
			die('Add IGuayaquilExtender object to template or redefine method FormatLink');

			return $this->extender->FormatLink($type, $dataItem, $catalog, $this);
	}

	public function AppendJavaScript($filename)
	{
		if ($this->extender == NULL)
			die('Add IGuayaquilExtender object to template or redefine method AppendJavaScript');

			return $this->extender->AppendJavaScript($filename, $this);
	}

	public function AppendCSS($filename)
	{
		if ($this->extender == NULL)
			die('Add IGuayaquilExtender object to template or redefine method AppendCSS');

			return $this->extender->AppendCSS($filename, $this);
	}

	public function Convert2uri($filename)
	{
		if ($this->extender == NULL)
			die('Add IGuayaquilExtender object to template or redefine method Convert2uri');

			return $this->extender->Convert2uri($filename, $this);
	}
}