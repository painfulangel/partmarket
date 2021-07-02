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
    <?php echo $form->errorSummary($model_profile); ?>
    <div class="formDiv">
        <div class="emailFieldsDiv">              
            <?php echo $form->textFieldRow($model, 'email', array('class' => 'span4')); ?>
        </div>
        <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span4')); ?>

        <?php echo $form->passwordFieldRow($model, 'passwordRepeat', array('class' => 'span4')); ?>

        <?php echo $form->textFieldRow($model_profile, 'first_name', array('class' => 'span5', 'maxlength' => 255)); ?>

        <?php echo $form->textFieldRow($model_profile, 'second_name', array('class' => 'span5', 'maxlength' => 255)); ?>

        <?php echo $form->textFieldRow($model_profile, 'father_name', array('class' => 'span5', 'maxlength' => 255)); ?>




        <div class="control-group ">
            <?php echo $form->labelEx($model_profile, 'phone', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                $this->widget('CMaskedTextField', array(
                    'model' => $model_profile,
                    'attribute' => 'phone',
                    'mask' => '+7 (999) 999-9999',
                    //'charMap' => array('.'=>'[\.]' , ','=>'[,]'),
                    'htmlOptions' => array('class' => 'span5', 'maxlength' => 11)
                ));
                ?>
            </div>
            <?php echo $form->error($model_profile, 'phone'); ?>
        </div>

        <div class="control-group ">
            <?php echo $form->labelEx($model_profile, 'extra_phone', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                $this->widget('CMaskedTextField', array(
                    'model' => $model_profile,
                    'attribute' => 'extra_phone',
                    'mask' => '+7 (999) 999-9999',
                    //'charMap' => array('.'=>'[\.]' , ','=>'[,]'),
                    'htmlOptions' => array('class' => 'span5', 'maxlength' => 11)
                ));
                ?>
            </div>
            <?php echo $form->error($model_profile, 'extra_phone'); ?>
        </div>

        <?php echo $form->textFieldRow($model_profile, 'skype', array('class' => 'span5', 'maxlength' => 255)); ?>

        <div class="control-group ">
            <div class="controls">
                <?php echo $form->labelEx($model_profile, 'delivery'); ?>
            </div>
        </div>
        <?php echo $form->textFieldRow($model_profile, 'delivery_zipcode', array('class' => 'span5', 'maxlength' => 255)); ?>

        <?php echo $form->textFieldRow($model_profile, 'delivery_country', array('class' => 'span5', 'maxlength' => 255)); ?>

        <?php echo $form->textFieldRow($model_profile, 'delivery_city', array('class' => 'span5', 'maxlength' => 255)); ?>

        <?php echo $form->textFieldRow($model_profile, 'delivery_street', array('class' => 'span5', 'maxlength' => 255)); ?>

        <?php echo $form->textFieldRow($model_profile, 'delivery_house', array('class' => 'span5', 'maxlength' => 255)); ?>

        <?php echo $form->textAreaRow($model_profile, 'comment', array('class' => 'span5', 'row' => 5)); ?>

        <div class="legal-entity-toggle ">
            <?php
            echo $form->radioButtonListInlineRow($model_profile, 'legal_entity', array('0' => $model_profile->getAttributeLabel('legal_entity1'), '1' => $model_profile->getAttributeLabel('legal_entity2'), '2' => $model_profile->getAttributeLabel('legal_entity3')), array('class' => 'legal-entity-fire-toggle', 'id' => 'legal_entity_id'));
            ?>
        </div>
        <script>
            $(function() {
<?php if ($model_profile->legal_entity == 1) { ?>
                    $('#legal-block').show();
<?php } else if ($model_profile->legal_entity == 2) {
    ?>
                    $('#ip-block').show();
<?php } ?>
                $('.legal-entity-fire-toggle').change(function() {
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



            <?php echo $form->textFieldRow($model_profile, 'organization_type', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'organization_name', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'organization_inn', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'organization_director', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'organization_ogrn', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'okpo', array('class' => 'span5', 'maxlength' => 255)); ?>

            <div class="control-group ">
                <div class="controls">
                    <?php echo $form->labelEx($model_profile, 'bank_name'); ?>
                </div>
            </div>

            <?php echo $form->textFieldRow($model_profile, 'bank', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'bank_kpp', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'bank_bik', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'bank_rc', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'bank_ks', array('class' => 'span5', 'maxlength' => 255)); ?>

            <div class="control-group ">
                <div class="controls">
                    <?php echo $form->labelEx($model_profile, 'legal'); ?>
                </div>
            </div>
            <?php echo $form->textFieldRow($model_profile, 'legal_zipcode', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'legal_country', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'legal_city', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'legal_street', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'legal_house', array('class' => 'span5', 'maxlength' => 255)); ?>


        </div>

        <div id="ip-block" style="display: none;">

            <?php echo $form->textFieldRow($model_profile, 'organization_name', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'organization_inn', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'ogrnip', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'okpo', array('class' => 'span5', 'maxlength' => 255)); ?>


            <div class="control-group ">
                <div class="controls">
                    <?php echo $form->labelEx($model_profile, 'bank_name'); ?>
                </div>
            </div>

            <?php echo $form->textFieldRow($model_profile, 'bank', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'bank_kpp', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'bank_bik', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'bank_rc', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'bank_ks', array('class' => 'span5', 'maxlength' => 255)); ?>

            <div class="control-group ">
                <div class="controls">
                    <?php echo $form->labelEx($model_profile, 'legal'); ?>
                </div>
            </div>
            <?php echo $form->textFieldRow($model_profile, 'legal_zipcode', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'legal_country', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'legal_city', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'legal_street', array('class' => 'span5', 'maxlength' => 255)); ?>

            <?php echo $form->textFieldRow($model_profile, 'legal_house', array('class' => 'span5', 'maxlength' => 255)); ?>


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
