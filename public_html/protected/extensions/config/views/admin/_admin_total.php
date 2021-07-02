<?php
foreach ($data as $g_key => $group) {
    if ($g_key != $type)
        continue;
    echo '<div class="config-group"><h4>'.(isset($groups[$g_key]) ? $groups[$g_key] : '').'</h4>';
    $flag = false;
    foreach ($group as $id => $row) {
        echo '<div class="config-group-element '.($flag ? 'bg' : '').'">';
        $flag = !$flag;
        echo "<div class='element-label'>".Yii::t('config', $row['label'])."</div>";
        echo '<div class="element-input">';
        
        switch ($row['type']){
        	case 'string':
        		echo CHtml::textField('param_'.($lang == NULL ? '' : $lang['link_name']).$id, $row['value'], array('class' => 'span10', 'maxlength' => 128));
        	break;
        	case 'password':
        		echo CHtml::passwordField('param_'.($lang == NULL ? '' : $lang['link_name']).$id, $row['value'], array('class' => 'span10', 'maxlength' => 128));
        	break;
        	case 'text':
        		echo CHtml::textArea('param_'.($lang == NULL ? '' : $lang['link_name']).$id, $row['value'], array('rows' => 6, 'cols' => 50, 'class' => 'span10'));
        	break;
        	case 'checkBox':
        		echo CHtml::checkBox('param_'.($lang == NULL ? '' : $lang['link_name']).$id, $row['value'], array('class' => ''));
        	break;
        	case 'list[PriceGroups]':
        		echo CHtml::dropDownList('param_'.($lang == NULL ? '' : $lang['link_name']).$id, $row['value'], CHtml::listData(PricesRulesGroups::model()->findAll(), 'id', 'name'), array('class' => 'span10'));
        	break;
        	case 'list[Currencies]':
        		echo CHtml::dropDownList('param_'.($lang == NULL ? '' : $lang['link_name']).$id, $row['value'], CHtml::listData(Currencies::model()->findAll(), 'id', 'name'), array('class' => 'span10'));
        	break;
        	case 'list[PriceUserGroup]':
        		echo CHtml::dropDownList('param_'.($lang == NULL ? '' : $lang['link_name']).$id, $row['value'], PricesRulesGroups:: getPriceCathegoryList(), array('class' => 'span10'));
        	break;
        	case 'list[Redactor]':
        		echo CHtml::dropDownList('param_'.($lang == NULL ? '' : $lang['link_name']).$id, $row['value'], array(1 => 'Imperavi', 2 => 'CKEditor', 3 => 'TinyMCE'), array('class' => 'span10'));
        	break;
        	case 'list[SearchType]':
        		echo CHtml::dropDownList('param_'.($lang == NULL ? '' : $lang['link_name']).$id, $row['value'], array(1 => Yii::t('config', 'Search splitted by brands'), 2 => Yii::t('config', 'General search')), array('class' => 'span10'));
        	break;
        }
        
        echo '</div>';
        echo "<div class='element-description'>".Yii::t('config', $row['description'])."</div><div class='clear'></div>";
        echo '</div>';
    }
    echo '<div class="config-group-element '.($flag ? 'bg' : '').'">';
    $flag = !$flag;
    echo "<div class='element-label'></div>";
    echo '<div class="element-input">';
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('config', 'Save'),
    ));
    echo '</div>';
    echo "<div class='element-description'></div><div class='clear'></div>";
    echo '</div>';

    echo '</div>';
}
?>