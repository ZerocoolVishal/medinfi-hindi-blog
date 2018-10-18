<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->pageDescription='Login into the PVAutomation';
/*$this->breadcrumbs=array(
	'Login',
);*/
?>


<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'login-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

    <div align="center">
        <br><br><br><br><br><br>

        <?php if(Yii::app()->user->hasFlash('error')):?>
        <div class="flash-error">
            <?php echo Yii::app()->user->getFlash('error'); ?>
            <?php
            Yii::app()->clientScript->registerScript(
                'myHideEffect',
                '$(".flash-error").animate({opacity: 1.0}, 3000).fadeOut("slow");',
                CClientScript::POS_READY
            );
            ?>
        </div>
        <?php endif; ?>

        <?php if(isset($_GET['changepass']) && $_GET['changepass']==1):?>
        <div class="flash-success">
            <?php echo "<span>Your password changed successfully. Please 
	          		login with your new credentials</span>"; ?>
            <?php
            Yii::app()->clientScript->registerScript(
                'myHideEffect',
                '$(".flash-success").animate({opacity: 1.0}, 10000).fadeOut("slow");',
                CClientScript::POS_READY
            );
            ?>
        </div>
        <?php endif; ?>

        <!-- <p class="note">Fields with <span class="required">*</span> are required.</p> -->
        <div class="row">
            <?php echo $form->textField($model,'email', array('class'=>' logintxtfld', 'placeholder'=>'Username')); ?>
            <?php echo $form->error($model,'email'); ?>
        </div>

        <div class="row">
            <?php echo $form->passwordField($model,'password', array('class'=>' logintxtfld', 'placeholder'=>'Password')); ?>
            <?php //echo $form->error($model,'password'); ?>
        </div>

        <a href="<?php echo Yii::app()->createUrl('User/ForgotPassword'); ?>"> 
            Forgot Password
        </a><br><br>
        <div class="row buttons">
            <?php echo CHtml::submitButton('Login', array('id'=>'loginbtn')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->