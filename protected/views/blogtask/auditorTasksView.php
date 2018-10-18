<?php
/* @var $this BlogTaskController */
/* @var $model BlogTask */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Blog Task View';
$this->pageDescription='Blog Task View Page';

Yii::import('zii.widgets.grid.CGridView');

class SpecialGridView extends CGridView {
    public $extraparam;
}

?>

<!--<h1>Blog Task View</h1>-->
<div class="search-form" style="display:none">
    <br>
</div><!-- search-form -->

<?php

$role = $userRoleDetails->role_name;
$this->widget('SpecialGridView', array(
    'id'=>'askfriend-grid',
    'dataProvider'=>$model->search(),
    'filter' =>$model,
    'extraparam'   => $role,
    'columns'=>array(

        array(
            'type'=>'raw',
            'name'=>'id',
            'header'=>'Blog Task Id',
            //'value'=>'(isset($data->request_id)?$data->request_id:"")',
            'value'=>'
               (($this->grid->extraparam == BLOG_AUDITOR ) ?
               ((isset($data->actiondetails->action) && $data->actiondetails->action == "approved_by_editor") ? (CHtml::link($data->id, array("blogtask/editBlogTask/".$data->id))) : $data->id)
               :
               ((isset($data->actiondetails->action) && $data->actiondetails->action == "approved_by_editor") ? (CHtml::link($data->id, array("blogtask/editBlogTask/".$data->id))) : $data->id)
               )
               ',
        ),

        array(
            'type'=>'raw',
            'name'=>'blog_topic',
            'header'=>'Blog Topic',
            //'value'=>'(isset($data->blog_topic)?$data->blog_topic:"")',
            'value'=>'
                   (($this->grid->extraparam == BLOG_AUDITOR ) ?
                   ((isset($data->actiondetails->action) && $data->actiondetails->action == "approved_by_editor") ? (CHtml::link($data->blog_topic, array("blogtask/editBlogTask/".$data->id))) : $data->blog_topic)
                   :
                   ((isset($data->actiondetails->action) && $data->actiondetails->action == "approved_by_editor") ? (CHtml::link($data->blog_topic, array("blogtask/editBlogTask/".$data->id))) : $data->blog_topic)
                   )
                   ',
        ),
        array(
            'type'=>'raw',
            'name'=>'focus_keyword',
            'header'=>'Focus Keyword',
            'value'=>'(isset($data->focus_keyword)?$data->focus_keyword:"")',
        ),
        array(
            'type'=>'raw',
            'name'=>'created_on',
            'header'=>'Created on',
            'value'=>'(isset($data->created_on)?$data->created_on : "")',
        ),

        array(
            'type'=>'raw',
            'name'=>'target_submission_date',
            'header'=>'Submission date',
            'value'=>'(isset($data->target_submission_date)?$data->target_submission_date : "")',
        ),
        array(
            'type'=>'raw',
            'name'=>'task_writer',
            'header'=>'Writer name',
            'value'=>'(isset($data->writerdetails->name)?$data->writerdetails->name : "")',
        ),
        array(
            'type'=>'raw',
            'name'=>'task_editor',
            'header'=>'Editor name',
            'value'=>'(isset($data->editordetails->name)?$data->editordetails->name : "")',
        ),
        array(
            'type'=>'raw',
            'name'=>'task_auditor',
            'header'=>'Auditor name',
            'value'=>'(isset($data->auditordetails->name)?$data->auditordetails->name : "")',
        ),

        array(
            'type'=>'raw',
            'name'=>'task_comments',
            'header'=>'Comments',
            'value'=>'(isset($data->actiondetails->comments)?$data->actiondetails->comments : "")',
        ),
        array(
            'type'=>'raw',
            'name'=>'task_reason',
            'header'=>'Reason',
            'value'=>'(isset($data->actiondetails->reason)?$data->actiondetails->reason : "")',
        ),

        array(
            'type'=>'raw',
            'name'=>'task_action',
            'header'=>'Action',
            'value'=>'(isset($data->actiondetails->action)?$data->actiondetails->action : "")',
        ),
        array(
            'type'=>'raw',
            'name'=>'task_status',
            'header'=>'Status',
            'value'=>'(isset($data->actiondetails->status)?$data->actiondetails->status : "")',
        ),

    )

));

?>

<div style="float:right">

<?php
echo CHtml::button('Export All', array('onclick' => 'js:document.location.href="export"','style'=>'cursor:pointer;background:#2e6ab1;'));?>&nbsp;
</div>

<script>
$('.summary').hide();
</script>

