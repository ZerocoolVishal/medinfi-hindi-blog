<?php
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

<script type="text/javascript">document.getElementById("change-password-form").reset();</script>
<?php
}
else
{
?>

  <?php if(Yii::app()->user->hasFlash('error'))
  {
  ?>
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
<script type="text/javascript">document.getElementById("change-password-form").reset();</script>

  <?php
  }

  ?>

<?php } ?>

<h1>Change Password</h1>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	 'id' => 'change-password-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
            'validateOnSubmit' => true,
            'action'		=>array('url' => '/user/changepassword'),

	),
)); ?>
 
  <div class="row"> <?php echo $form->labelEx($model,'old_password'); ?> <?php echo $form->passwordField($model,'old_password',array("style"=>"margin-left:44px;width:50%")); ?> <?php echo $form->error($model,'old_password'); ?> </div><br>
 
  <div class="row"> <?php echo $form->labelEx($model,'new_password'); ?> <?php echo $form->passwordField($model,'new_password',array("style"=>"margin-left:40px;;width:50%")); ?> <?php echo $form->error($model,'new_password'); ?> </div><br>
 
  <div class="row"> <?php echo $form->labelEx($model,'repeat_password'); ?> <?php echo $form->passwordField($model,'repeat_password',array("style"=>"margin-left:27px;;width:50%")); ?> <?php echo $form->error($model,'repeat_password'); ?> </div><br>
 
  <div class="row submit">
    <?php echo CHtml::submitButton('Change'); ?>
  </div>
  <?php $this->endWidget(); ?>
</div>

