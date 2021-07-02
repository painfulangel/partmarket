<?php
class KatalogVavtoWidget extends CWidget {
	public function run() {
		if (Yii::app()->getModule('katalogVavto')->enabledModule) {
			$menu = Yii::app()->getModule('katalogVavto')->getLeftMenu();
			
			$h3 = Yii::t('site_layout', 'Model');
			
			switch ($menu['type']) {
				case 'brand':
					$h3 = Yii::t('site_layout', 'Car brands');
				break;
				case 'models':
					$id = Yii::app()->request->getParam('id', 0);
					
					if (is_object($item = KatalogVavtoItems::model()->findByPk($id))) {
						if (is_object($item->cath)) $h3 = $item->cath->title;
					}
				break;
			}
?>
		<div class="b-brands-list">
			<h3 class="btn btn-info"><?php echo $h3; ?></h3>
<?php
			$this->widget('bootstrap.widgets.TbMenu', array(
            	'items' => $menu['items'],
            	'htmlOptions' => array('class' => 'nav nav-vavto',),
			));
?>
		</div>
<?php
		}
	}
}
?>