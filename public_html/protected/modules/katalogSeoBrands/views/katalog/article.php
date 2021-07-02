<?php
    $this->breadcrumbs[Yii::t('katalogSeoBrands', 'Producers')] = '/'.$settings->url.'/';
    $this->breadcrumbs[$brand] = '/'.$settings->url.'/'.str_replace('/', '__', $brand).'/';
    $this->breadcrumbs[$category->name] = '/'.$settings->url.'/'.str_replace('/', '__', $brand).'/'.$category->url.'/';
    $this->breadcrumbs[] = $article;
?>
<h1><?php echo $settings->article_h1; ?></h1>
<?php
	$this->metaDescription = $settings->article_description;
	$this->metaKeywords = $settings->article_keywords;
	$this->metaTitle = $settings->article_title;

    echo $settings->article_content;
    /*echo '<p>'.Yii::t('katalogSeoBrands', 'Article').': '.$model->article.'</p>';
    echo '<p>'.Yii::t('katalogSeoBrands', 'Name').': '.$model->name.'</p>';
    echo '<p>'.Yii::t('katalogSeoBrands', 'Brand').': '.$model->brand.'</p>';
    echo '<p>'.Yii::t('katalogSeoBrands', 'Price').': '.Yii::app()->getModule('prices')->getPriceFormatFunction($model->price).'</p>';*/

    echo $model->buyButton($settings->brand_buy);

    echo $settings->article_text;
?>
