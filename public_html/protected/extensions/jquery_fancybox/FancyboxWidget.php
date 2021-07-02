<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FancyboxWibjet
 *
 * @author Sergij
 */
class FancyboxWidget extends CWidget {
    public static $_assest = '';
    public $items = array();

    public function init() {
        parent::init();
    }

    public function run() {
        parent::run();
        $this->registerAssets();
        foreach ($this->items as $data) {
            $this->render('_image', array(
                'data' => $data,
                'id' => $this->id,
            ));
        }
    }

    public function registerAssets() {
        if (self::$_assest == '') {
            $assetsDir = dirname(__FILE__) . "/assets";
            self::$_assest = Yii::app()->assetManager->publish($assetsDir);
        }

        $script = <<<SCRIPT
                $(document).ready(function() {
               $("a[rel=image_group_$this->id]").fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'titlePosition' 	: 'over',
				'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
					return '<span id="fancybox-title-over">Изображение ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
				}
			});
                });
SCRIPT;
        Yii::app()->clientScript->registerScript(__CLASS__ . "#fancy" . $this->id, $script, CClientScript::POS_END);


//        Yii::app()->clientScript->registerCssFile(self::$_assest . "/style.css");
        Yii::app()->clientScript->registerCoreScript('jquery');
        //Yii::app()->clientScript->registerScriptFile(self::$_assest . "/fancybox/jquery.mousewheel-3.0.4.pack.js");
        Yii::app()->clientScript->registerScriptFile(self::$_assest . "/fancybox/jquery.fancybox.pack.js");
        Yii::app()->clientScript->registerCssFile(self::$_assest . "/fancybox/jquery.fancybox.css");
    }
}