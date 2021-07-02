<?php
	$this->breadcrumbs = array(
	    Yii::t('requests', 'VIN Request'),
	);
	
	$this->pageTitle = Yii::t('requests', 'VIN Request');
	
	if (!Yii::app()->user->isGuest)
	    $this->renderPartial('userControl.views.userProfile._cabinet_top', array('title' => 'VIN Запрос'));
	else {
	    echo '<h1>' . Yii::t('requests', 'VIN Request') . '</h1>';
	}
	
	echo $this->renderPartial('_form', array('model' => $model, 'recaptchakey' => $recaptchakey));
?>