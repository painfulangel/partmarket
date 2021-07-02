<?php
/* @var $this Controller */
if ($this->metaTitle != null && !empty($this->metaTitle))
    $this->pageTitle = $this->metaTitle;
?>
<!DOCTYPE html>
<html>
<head lang="ru">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <?php if ($this->metaKeywords != null && !empty($this->metaKeywords)) { ?>
        <meta name="description" content="<?php echo htmlspecialchars($this->metaKeywords) ?>"/>
    <?php } ?>
    <?php if ($this->metaDescription != null && !empty($this->metaDescription)) { ?>
        <meta name="keywords" content="<?php echo htmlspecialchars($this->metaDescription) ?>"/>
    <?php } ?>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <?php Yii::app()->bootstrap->register(); ?>
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="/fonts/fonts.css">
    <link rel="stylesheet" href="/css/admin_layout.css">
    <?php
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile(Yii::app()->baseUrl.'/css/reveal.css');
    $cs->registerCssFile(Yii::app()->baseUrl.'/css/main.css');
    $cs->registerCssFile(Yii::app()->baseUrl.'/css/styles.css');
    $cs->registerCssFile(Yii::app()->baseUrl.'/css/custom.responsive.css');
    $cs->registerCssFile(Yii::app()->baseUrl.'/css/font-awesome.min.css');
    ?>
</head>

<body class="empty">
    <div class="empty">
        <?php echo $content ?>
    </div>
</html>