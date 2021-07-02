    <?php echo $form->textFieldRow($model,'title'); ?>
	
	<?php echo $form->textFieldRow($model,'keywords'); ?>
	
	<?php echo $form->textFieldRow($model,'description'); ?>
    
    <?php
				//!!! Виджет, выводящий редактор нужного типа
				$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'comment'));
				//!!! Виджет, выводящий редактор нужного типа
	?>