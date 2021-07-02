<?php
class KatalogSeoBrandsWidget extends CWidget {
	public function run() {
		if (Yii::app()->getModule('katalogSeoBrands')->enabledModule) {
			$settings = KatalogSeoBrandsSettings::model()->find();
			
			if ($settings->show_widget) {
				$items = KatalogSeoBrandsItems::model()->findAll(array('order' => 'id DESC', 'limit' => 3));

				if ($count = count($items)) {
?>
		<div class="b-brands-list">
			<h3 class="btn btn-info"><?php echo Yii::t('katalogSeoBrands', 'SEO brands updates'); ?></h3>
			<ul class="nav ul-all-brands">
<?php
				for ($i = 0; $i < $count; $i ++) {
					if (is_object($items[$i]->cat))
						echo '<li><a href="/'.$settings->url.'/'.$items[$i]->brand.'/'.$items[$i]->cat->url.'/'.$items[$i]->article.'/">'.$items[$i]->article.'</a></li>'."\n";
				}
?>
				<li><button onclick="window.location.href='/<?php echo $settings->url; ?>/';" class="btn btn-all-brands"><?php echo Yii::t('katalogSeoBrands', 'Show all brands'); ?></button></li>
			</ul>
		</div>
<?php
				}
			}
		}
	}
}
?>