<?php
$this->widget('bootstrap.widgets.TbDetailView', array(
	'data' => $model,
	'attributes' => array(
		/*array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name' => 'payment_method',
			'type' => 'raw',
			'value' => function($data) {
				return CHtml::textField('id', $data->payment_method, array(
						'class' => 'preprint span2' 
				));
			} 
		),*/
		array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name' => 'delivery_method',
			'type' => 'raw',
			'value' => function($data) {
				return CHtml::textField('id', $data->delivery_method, array(
						'class' => 'preprint span2' 
				));
			} 
		),
		array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name' => 'delivery_name',
			'type' => 'raw',
			'value' => '' 
		),
		array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name' => 'zipcode',
			'type' => 'raw',
			'value' => function($data) {
				return CHtml::textField('id', $data->zipcode, array(
						'class' => 'preprint span2' 
				));
			} 
		),
		array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name' => 'country',
			'type' => 'raw',
			'value' => function($data) {
				return CHtml::textField('id', $data->country, array(
						'class' => 'preprint span2' 
				));
			} 
		),
		array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name' => 'city',
			'type' => 'raw',
			'value' => function($data) {
				return CHtml::textField('id', $data->city, array(
						'class' => 'preprint span2' 
				));
			} 
		),
		array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name' => 'street',
			'type' => 'raw',
			'value' => function($data) {
				return CHtml::textField('id', $data->street, array(
						'class' => 'preprint span2' 
				));
			} 
		),
		array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name' => 'house',
			'type' => 'raw',
			'value' => function($data) {
				return CHtml::textField('id', $data->house, array(
						'class' => 'preprint span2' 
				));
			} 
		),
		array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name' => 'description',
			'type' => 'raw',
			'value' => function($data) {
				return CHtml::textField('id', $data->description, array(
						'class' => 'preprint span2' 
				));
			} 
		) 
	) 
));
?>