<div align="center">
    <br><br><br><br><br><br>
    <?php
    $this->pageTitle=Yii::app()->name . ' - BEForgot Password';
    $this->pageDescription='Change your Medinfi account passsword ';

    if(Yii::app()->user->hasFlash('success'))
    {
    ?>
    <div class="flash-success" style="font-size:14px;">
        <a id="close_btn" style="float:right;cursor:pointer;font-size:14px;">X</a>
        <?php echo Yii::app()->user->getFlash('success'); ?>
        <?php
        Yii::app()->clientScript->registerScript(
            '',
            '$("#close_btn").click(function(){ $(".flash-success").fadeOut("slow"); })',
            CClientScript::POS_READY
        );

        ?>
    </div>
    <script type="text/javascript">document.getElementById("forgot-form").reset();</script>

    <?php
    }
    else
    {
    ?>

    <?php if(Yii::app()->user->hasFlash('error'))
    {
    ?>
    <div class="flash-error" style="font-size:14px;">
        <a id="close_btn" style="float:right;cursor:pointer;font-size:14px;">X</a>
        <?php echo Yii::app()->user->getFlash('error'); ?>
        <?php
        Yii::app()->clientScript->registerScript(
            '',
            '$("#close_btn").click(function(){ $(".flash-error").fadeOut("slow"); })',
            CClientScript::POS_READY
        );
        ?>
    </div>

    <script type="text/javascript">document.getElementById("forgot-form").reset();</script>
    <?php
    }

    ?>

    <?php } ?>

    <div class="form">
        <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'forgot-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>




        <!-- <p class="note">Fields with <span class="required">*</span> are required.</p> -->
        <div class="row">
            <?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->textField($model,'email', array('class'=>' logintxtfld', 'placeholder'=>'Email')); ?>
            <?php echo $form->error($model,'email'); ?>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Get Password'); ?>
        </div>


    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
