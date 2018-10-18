<?php
class BlogTaskUtility {

    //select auditor with less number of task pending to publish
    public static function getBlogAuditor() {
        $getAuditorQuery = "SELECT med_user.id,med_user.username,med_user.email,count(blog_task.id) as count FROM med_user
                                JOIN user_role ON med_user.id = user_role.user_id
                                JOIN med_role ON user_role.role_id = med_role.id
                                LEFT JOIN blog_task ON med_user.id = blog_task.auditor_id
                                LEFT JOIN blog_task_action ON blog_task.action_id = blog_task_action.id
                                WHERE med_role.role_name  LIKE 'Blog Auditor' AND (
                                blog_task_action.status not like 'PUBLISHED' OR blog_task_action.status is null)
                                GROUP BY med_user.id ORDER BY count ASC LIMIT 1";
        $getAuditorResult = Yii::app()->db->createCommand($getAuditorQuery)->queryAll();

        if(!empty($getAuditorResult)){
            return $getAuditorResult[0]['id'];
        }
        else{
            return 0;
        }

    }

    //get current document version based on blog task id
    public static function getDocVersion($taskId) {
        $getTaskQuery = "SELECT * FROM blog_task_details WHERE task_id = ".$taskId." ORDER BY id DESC LIMIT 1";
        $getTaskResult = Yii::app()->db->createCommand($getTaskQuery)->queryAll();

        if(!empty($getTaskResult)){
            return $getTaskResult[0]['document_version'];
        }
        else{
            return 0;
        }
    }

    //get next document version to upload based on blog task id
    public static function getNextDocVersion($taskId) {
        $getTaskQuery = "SELECT * FROM blog_task_details WHERE task_id = ".$taskId." ORDER BY id DESC LIMIT 1";
        $getTaskResult = Yii::app()->db->createCommand($getTaskQuery)->queryAll();

        if(!empty($getTaskResult)){
            return $getTaskResult[0]['document_version']+1;
        }
        else{
            return 1;
        }
    }

public static function getSendTaskMail($blogTask,$value,$action) {

        try {
            $auditorQuery = "SELECT * FROM med_user WHERE id = ".$blogTask->auditor_id;
            $auditorResult = Yii::app()->db->createCommand($auditorQuery)->queryAll();

            $writerQuery = "SELECT * FROM med_user WHERE id = ".$blogTask->writer_id;
            $writerResult = Yii::app()->db->createCommand($writerQuery)->queryAll();

            $editorQuery = "SELECT * FROM med_user WHERE id = ".$blogTask->editor_id;
            $editorResult = Yii::app()->db->createCommand($editorQuery)->queryAll();


            $adminQuery = "SELECT med_user.* FROM med_user, user_role WHERE med_user.id = user_role.user_id and role_id = 1";
            $adminResult = Yii::app()->db->createCommand($adminQuery)->queryAll();

            //mail to team after
            $subject='';
            $message='Hi Team,<br><br>';
            if($action == 'published_by_auditor'){
                $message.='Blog Task : '.$blogTask->blog_topic.' is published<br>';
                $message.='Link : <a href="'.$value.'">'.$value.'</a><br><br>';
                $subject='Blog Task Published';
				$email = array($adminResult[0]['email'], $auditorResult[0]['email'], $editorResult[0]['email'], $writerResult[0]['email'], BLOG_PUBLISH_NOTIFY_EMAIL);
            }else if($action == 'rejected_by_auditor'){
                $message.='Blog Task : '.$blogTask->blog_topic.' is rejected<br>';
                $message.='Reason : '.$value.'<br><br>';
                $subject='Blog Task Rejected';
				$email = array($adminResult[0]['email'], $auditorResult[0]['email'], $editorResult[0]['email'], $writerResult[0]['email']);
            }else if($action == 'reassigned_by_auditor'){
				$message='Hi '.$editorResult[0]['name'].'<br><br>';
				$message.='Blog Task : '.$blogTask->blog_topic.' is Reassigned<br>';
                $message.='Comments : '.$value.'<br><br>';
                $subject='Blog Task Reassigned';
				$email = array($editorResult[0]['email']);
            }else if($action == 'assigned_by_editor'){
				$message='Hi '.$writerResult[0]['name'].',<br><br>';
				$message.='Blog Task : '.$blogTask->blog_topic.' is assigned<br><br>';
                $subject='Blog Task Assigned';
				$email = array($writerResult[0]['email']);
			}else if($action == 'reassigned_by_editor'){
				$message='Hi '.$writerResult[0]['name'].',<br><br>';
				$message.='Blog Task : '.$blogTask->blog_topic.' is Reassigned<br>';
                $message.='Comments : '.$value.'<br><br>';
                $subject='Blog Task Reassigned';
				$email = array($writerResult[0]['email']);
			}else if($action == 'approved_by_editor'){
				$message='Hi '.$auditorResult[0]['name'].',<br><br>';
				$message.='Blog Task : '.$blogTask->blog_topic.' is Approved<br><br>';
                $subject='Blog Task Approved';
				$email = array($auditorResult[0]['email']);
			}else if($action == 'submitted_by_writer'){
				$message='Hi '.$editorResult[0]['name'].',<br><br>';
				$message.='Blog Task : '.$blogTask->blog_topic.' is submitted<br><br>';
                $subject='Blog Task Submitted';
				$email = array($editorResult[0]['email']);
			}
            $message.='Thanks<br>';
            $message.='Team Medinfi';
            Yii::log("email result  ".json_encode($email));
            SendEmail::sendYiiMail($email,'no-reply@medinfi.com', 'Medinfi', $replyto='', $subject, $message,$cc='',$bcc='');
        }
        catch(Exception $ex) {
        }
    }
}
?>