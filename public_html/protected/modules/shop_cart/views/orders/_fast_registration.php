<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => "LRegisterForm-form",
        'htmlOptions' => array('class' => 'regForm'),
        'action' => array('order', 'step' => 'registration'),
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),)
    );
    ?>
    <?php echo $form->errorSummary($model); ?>
    <?php if($model->getError('email')): ?>
        <div class="forgot-password">
            <a href="/login" class="btn btn-primary">Войти</a>
            <a href="/lily/account/restore/" class="btn btn-danger">Восстановить пароль</a>
        </div>
    <?php endif; ?>
    <div class="formDiv">
        <div class="emailFieldsDiv">              
            <?php echo $form->textFieldRow($model, 'email', array('class' => 'span4')); ?>
        </div>
        <?php echo $form->passwordFieldRow($model, 'reg_password', array('class' => 'span4', 'autocomplete' => 'off')); ?>



        <?php echo $form->textFieldRow($model, 'first_name', array('class' => 'span5', 'maxlength' => 255)); ?>

        <?php echo $form->textFieldRow($model, 'second_name', array('class' => 'span5', 'maxlength' => 255)); ?>

        <?php echo $form->textFieldRow($model, 'father_name', array('class' => 'span5', 'maxlength' => 255)); ?>




        <div class="control-group ">
            <?php echo $form->labelEx($model, 'phone', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                /*$this->widget('CMaskedTextField', array(
                    'model' => $model,
                    'attribute' => 'phone',
                    'mask' => '+9 (999) 999-9999',
                    //'charMap' => array('.'=>'[\.]' , ','=>'[,]'),
                    'htmlOptions' => array('class' => 'span5', 'maxlength' => 11)
                ));*/
                ?>
                <?php $this->widget("ext.maskedInput.MaskedInput", array(
                    "model" => $model,
                    "attribute" => "phone",
                    "mask" => '+9 (999) 999-9999',
                    "clientOptions" => array("autoUnmask"=> true),
                    'htmlOptions' => array('class' => 'span5', 'maxlength' => 11)
                ));?>
            </div>
            <?php echo $form->error($model, 'phone'); ?>
        </div>

        <div class="control-group ">
            <?php echo $form->labelEx($model, 'extra_phone', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
/*                $this->widget('CMaskedTextField', array(
                    'model' => $model,
                    'attribute' => 'extra_phone',
                    'mask' => '+9 (999) 999-9999',
                    //'charMap' => array('.'=>'[\.]' , ','=>'[,]'),
                    'htmlOptions' => array('class' => 'span5', 'maxlength' => 11)
                ));
                */?>
                <?php $this->widget("ext.maskedInput.MaskedInput", array(
                    "model" => $model,
                    "attribute" => "extra_phone",
                    "mask" => '+9 (999) 999-9999',
                    "clientOptions" => array("autoUnmask"=> true),
                    'htmlOptions' => array('class' => 'span5', 'maxlength' => 11)
                ));?>
            </div>
            <?php echo $form->error($model, 'extra_phone'); ?>
        </div>

        <?php echo $form->textFieldRow($model, 'skype', array('class' => 'span5', 'maxlength' => 255)); ?>

        <div class="control-group ">
            <div class="controls">
                <?php echo $form->labelEx($model, 'delivery'); ?>
            </div>
        </div>
        <?php echo $form->textFieldRow($model, 'delivery_zipcode', array('class' => 'span5', 'maxlength' => 255)); ?>

        <?php echo $form->textFieldRow($model, 'delivery_country', array('class' => 'span5', 'maxlength' => 255)); ?>

        <?php echo $form->textFieldRow($model, 'delivery_city', array('class' => 'span5', 'maxlength' => 255)); ?>

        <?php echo $form->textFieldRow($model, 'delivery_street', array('class' => 'span5', 'maxlength' => 255)); ?>

        <?php echo $form->textFieldRow($model, 'delivery_house', array('class' => 'span5', 'maxlength' => 255)); ?>

        <?php echo $form->textAreaRow($model, 'comment', array('class' => 'span5', 'row' => 5)); ?>

        <div class="legal-entity-toggle ">
            <?php
            echo $form->radioButtonListInlineRow($model, 'legal_entity', array('0' => $model->getAttributeLabel('legal_entity1'), '1' => $model->getAttributeLabel('legal_entity2'), '2' => $model->getAttributeLabel('legal_entity3')), array('class' => 'legal-entity-fire-toggle', 'id' => 'legal_entity_id'));
            ?>
        </div>
        <script>
            $(function () {
<?php if ($model->legal_entity == 1) { ?>
                    $('#legal-block').show();
<?php } else if ($model->legal_entity == 2) {
    ?>
                    $('#ip-block').show();
<?php } ?>
                $('.legal-entity-fire-toggle').change(function () {
                    var current_value = $('.legal-entity-toggle input:checked').val();
                    if (current_value == "1") {
                        $('#legal-block').show();
                        $('#ip-block').hide();
                    } else if (current_value == "2") {
                        $('#ip-block').show();
                        $('#legal-block').hide();
                    } else {
                        $('#legal-block').hide();
                        $('#ip-block').hide();
                    }
                });
            });
        </script>
        <div id="legal-block" style="display: none;">



            <?php echo $form->textFieldRow($model, 'organization_type', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'organization_name', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'organization_inn', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'organization_director', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'organization_ogrn', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'okpo', array('class' => 'span5', 'maxlength' => 255)); ?>

            <div class="control-group ">
                <div class="controls">
                    <?php echo $form->labelEx($model, 'bank_name'); ?>
                </div>
            </div>

            <?php echo $form->textFieldRow($model, 'bank', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'bank_kpp', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'bank_bik', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'bank_rc', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'bank_ks', array('class' => 'span5', 'maxlength' => 255)); ?>

            <div class="control-group ">
                <div class="controls">
                    <?php echo $form->labelEx($model, 'legal'); ?>
                </div>
            </div>
            <?php echo $form->textFieldRow($model, 'legal_zipcode', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'legal_country', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'legal_city', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'legal_street', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'legal_house', array('class' => 'span5', 'maxlength' => 255)); ?>


        </div>

        <div id="ip-block" style="display: none;">

            <?php echo $form->textFieldRow($model, 'organization_name', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'organization_inn', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'ogrnip', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'okpo', array('class' => 'span5', 'maxlength' => 255)); ?>


            <div class="control-group ">
                <div class="controls">
                    <?php echo $form->labelEx($model, 'bank_name'); ?>
                </div>
            </div>

            <?php echo $form->textFieldRow($model, 'bank', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'bank_kpp', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'bank_bik', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'bank_rc', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'bank_ks', array('class' => 'span5', 'maxlength' => 255)); ?>

            <div class="control-group ">
                <div class="controls">
                    <?php echo $form->labelEx($model, 'legal'); ?>
                </div>
            </div>
            <?php echo $form->textFieldRow($model, 'legal_zipcode', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'legal_country', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'legal_city', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'legal_street', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model, 'legal_house', array('class' => 'span5', 'maxlength' => 255)); ?>


        </div>


        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => Yii::t('shop_cart', 'Express registration'),
            ));
            ?>
        </div>

    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
