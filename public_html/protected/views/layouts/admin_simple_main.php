<?php
/* @var $this Controller */
if ($this->metaTitle != null && !empty($this->metaTitle))
    $this->pageTitle = $this->metaTitle;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <?php if ($this->metaKeywords != null && !empty($this->metaKeywords)) { ?>
            <meta name="description" content="<?= htmlspecialchars($this->metaKeywords) ?>" />
        <?php } ?>
        <?php if ($this->metaDescription != null && !empty($this->metaDescription)) { ?>
            <meta name="keywords" content="<?= htmlspecialchars($this->metaDescription) ?>" /> 
        <?php } ?>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/styles.css" />

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <?php Yii::app()->bootstrap->register(); ?>

        <?php
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.reveal.js');
        $cs->registerScriptFile(Yii::app()->baseUrl . '/js/myYiiJs.js');
        $cs->registerCssFile(Yii::app()->baseUrl . '/css/reveal.css');
        ?>
    </head>
    <body>
        <?php
        echo $content;
        ?>
    </body>
    <div id="myModal" class="reveal-modal">
        <h1 id="modalTitle"></h1>
        <p id="modalText"></p>
        <a class="close-reveal-modal">&#215;</a>
    </div>
    <iframe name="_footer_iframe" style="display: none;"/>
</html>
