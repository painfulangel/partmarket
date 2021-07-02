<?php
$this->breadcrumbs[Yii::t('katalogSeoBrands', 'Producers')] = '/'.$settings->url.'/';
$this->breadcrumbs[] = $brand;
?>
<h1><?php echo $settings->brand_h1; ?></h1>
<?php
	$this->metaDescription = $settings->brand_description;
	$this->metaKeywords = $settings->brand_keywords;
	$this->metaTitle = $settings->brand_title;

	/*$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'cart-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'value' => '\'<a href="/'.$settings->url.'/'.str_replace('/', '__', $brand).'/\'.str_replace(\'/\', \'__\', $data->url).\'/">\'.$data->name.\'</a>\'',
            'type' => 'raw',
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("name")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
    ),
    'enableSorting'=>array(),
    ));*/
    /*$data = $dataProvider->getData();

    $count = count($data);
    for ($i = 0; $i < $count; $i ++) {
        $data[$i]
    }*/
    $this->widget('bootstrap.widgets.TbListView', array(
        'dataProvider' => $dataProvider,
        'itemView' => '_view2',
        'template' => '{items} {pager}',
        'id' => 'katalogseobrands',
        'htmlOptions' => array('class' => 'row-fluid katalogseobrands'),
        'viewData' => array('url' => $settings->url, 'brand' => $brand),
    ));
?>
<?php echo $settings->brand_text; ?>
<style>
	@media (min-width: 1200px) {
	  .row-fluid.katalogseobrands [class*="span"]:first-child {
	    margin-left: 2.564102564102564% !important;
	  }
	}

    #cart-grid .summary {
        display: none;
    }
</style>