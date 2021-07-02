<?php
$this->breadcrumbs[Yii::t('katalogSeoBrands', 'Producers')] = '/'.$settings->url.'/';
$this->breadcrumbs[$brand] = '/'.$settings->url.'/'.str_replace('/', '__', $brand).'/';
$this->breadcrumbs[] = $category->name;
?>
<h1><?php echo $settings->brand_h1; ?></h1>
<?php
	$this->metaDescription = $settings->brand_description;
	$this->metaKeywords = $settings->brand_keywords;
	$this->metaTitle = $settings->brand_title;

	/*$this->widget('bootstrap.widgets.TbListView', array(
		'dataProvider' => $dataProvider,
		'itemView' => '_brand_view',
		'template' => '{items} {pager}',
		'id' => 'katalogseobrands',
		'htmlOptions' => array('class' => 'row-fluid katalogseobrands'),
		'viewData' => array('url' => $settings->url),
	));*/
	$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'cart-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'article',
            'value' => '\'<a href="/'.$settings->url.'/'.str_replace('/', '__', $brand).'/'.$category->url.'/\'.$data->article.\'/">\'.$data->article.\'</a>\'',
            'type' => 'raw',
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("brand")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("article")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price',
            'value' => 'Yii::app()->getModule(\'prices\')->getPriceFormatFunction($data->price)',
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("name")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            //'name' => 'buy_button',
            'value' => '$data->buyButton("'.$settings->brand_buy.'")',
            'type' => 'raw',
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                //'aria-label'=>$model->getAttributeLabel("name")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
    ),
    'enableSorting'=>array(),
));
?>
<?php echo $settings->brand_text; ?>
<style>
	@media (min-width: 1200px) {
	  .row-fluid.katalogseobrands [class*="span"]:first-child {
	    margin-left: 2.564102564102564% !important;
	  }
	}
</style>