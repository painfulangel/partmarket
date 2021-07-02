<?php
	echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255));
	
	echo $form->textFieldRow($model, 'alias', array('class' => 'span5', 'maxlength' => 255));
	
	echo $form->checkBoxRow($model, 'active_state', array('class' => 'span1', 'maxlength' => 255))
?>