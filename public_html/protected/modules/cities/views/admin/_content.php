<?php
    echo $form->textFieldRow($model, 'name', array('class' => 'span5'));
    echo $form->textFieldRow($model, 'coef', array('class' => 'span5'));
    echo $form->textFieldRow($model, 'address', array('class' => 'span5'));
    echo $form->textFieldRow($model, 'phone', array('class' => 'span5'));
    echo $form->textFieldRow($model, 'email', array('class' => 'span5'));

    //!!! Виджет, выводящий редактор нужного типа
    $this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'contacts'));
    //!!! Виджет, выводящий редактор нужного типа

    echo $form->checkBoxRow($model, 'by_default', array());
?>