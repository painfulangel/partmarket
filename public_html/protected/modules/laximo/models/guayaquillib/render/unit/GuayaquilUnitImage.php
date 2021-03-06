<?php
class GuayaquilUnitImage extends GuayaquilTemplate
{
	var $catalog = NULL;
	var $unit = NULL;
	var $imagemap = NULL;

	var $containerwidth = 960;
	var $containerheight = 600;

	var $spacer;
	var $zoom_image = NULL;

	function __construct(IGuayaquilExtender $extender)
	{
		parent::__construct($extender);
	}

	function Draw($catalog, $unit, $imagemap)
	{
		$assetsDir = dirname(__FILE__).'/../../../../assets';
		$dir = Yii::app()->assetManager->publish($assetsDir);

		Yii::app()->getClientScript()->registerScriptFile($dir.'/js/dragscrollable.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile($dir.'/js/jquery.mousewheel.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile($dir.'/js/jquery.colorbox.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile($dir.'/js/unit.js', CClientScript::POS_HEAD);
		
		Yii::app()->getClientScript()->registerCssFile($dir.'/css/unit.css');
		Yii::app()->getClientScript()->registerCssFile($dir.'/css/colorbox.css');
		
		/*$this->AppendJavaScript(dirname(dirname(__FILE__)).'/dragscrollable.js');
		$this->AppendJavaScript(dirname(dirname(__FILE__)).'/jquery.mousewheel.js');
		$this->AppendJavaScript(dirname(dirname(__FILE__)).'/jquery.colorbox.js');
		$this->AppendJavaScript(dirname(__FILE__).'/unit.js');
		$this->AppendCSS(dirname(__FILE__).'/unit.css');
		$this->AppendCSS(dirname(__FILE__).'/../colorbox.css');*/

		$this->catalog = $catalog;
		$this->unit = $unit;
		$this->zoom_image = '/images/laximo/zoom.png';

		$html = $this->DrawUnitImage($unit, $imagemap);

		return $html;
	}

	function DrawUnitImage($unit, $imagemap)
	{
		$html = $this->DrawMagnifier($unit);
		$html .= '<div id="viewport" class="inline_block" style="position:absolute; border: 1px solid #777; background: white; width:'.($this->containerwidth/2).'px; height:'.$this->containerheight.'px; overflow: auto;">';

		$html .= $this->DrawImageMap($imagemap);
		$html .= $this->DrawImage($unit);

		$html .= '</div>';

		return $html;
	}

	function DrawImageMap($imagemap)
	{
		$this->spacer = '/images/laximo/spacer.gif';

		$html = '';

		foreach($imagemap->row as $area)
			$html .= $this->DrawImageMapElement($area);

			return $html;
	}

	function DrawImageMapElement($area)
	{
		$html = '<div name="'.$area['code'].'" class="dragger g_highlight" style="position:absolute; width:'.($area['x2']-$area['x1']).'px; height:'.($area['y2']-$area['y1']).'px; margin-top:'.$area['y1'].'px; margin-left:'.$area['x1'].'px; overflow:hidden;">';

		$html .= $this->DrawImageMapElementClickableArea();

		$html .= '</div>';

		return $html;
	}

	function DrawImageMapElementClickableArea()
	{
		return '<img src="'.$this->spacer.'" width="200" height="200"/>';
	}

	function DrawImage($unit)
	{
		$img = $unit['largeimageurl'];
		if (!strlen($img))
			$img = '/images/laximo/noimage.png';
		
		$sizes = @getimagesize(str_replace('%size%', 'source', $img));
			
		$html = '<img class="dragger" onLoad="rescaleImage(-100);" src="'.str_replace('%size%', 'source', $img).'" owidth="'.(is_array($sizes) && array_key_exists(0, $sizes) ? $sizes[0] : '').'"  oheight="'.(is_array($sizes) && array_key_exists(1, $sizes) ? $sizes[1] : '').'"/>';
		return $html;
	}

	private function DrawMagnifier($unit)
	{
		return '<div class="guayaquil_unit_icons"><div class="guayaquil_zoom" full="'.str_replace('%size%', 'source', $unit['largeimageurl']).'" title="'.$unit['code'].': '.$unit['name'].'"><img src="'.$this->zoom_image.'"></div></div>';
	}
}