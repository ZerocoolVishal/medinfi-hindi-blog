<?php
class BlogTaskController extends CController
{

    public $pageTitle='Blog Backend Default Title';
    public $pageDescription='Blog Backend Default';

    /* Created by Abhi on Dec 2017
	    This action is basically identifying the user role and redirecting them in to their own dashboard view*/
    public function actionCheckUserMode() {
        $this->redirect(array('blogTaskInboxView'));
    }

    //not found page if there is no proper permission
    public function actionnotfound()
    {
        $this->render('notfound');
    }

    public function actionBlogTaskInboxView() {
        try {
            $userId = "0";
            $userRole = "0";
            if(isset(Yii::app()->session['userId'])){
                $userId =  Yii::app()->session['userId'];
                $userRole =  Yii::app()->session['med_role'];
            }
            else{
                $this->redirect(array('User/Login'));
            }

            $userRoleDetails = Role::model()->findByPk($userRole);

            if(!empty($userRoleDetails) && ($userRoleDetails->role_name == BLOG_WRITER || $userRoleDetails->role_name == BLOG_EDITOR
                                            || $userRoleDetails->role_name == BLOG_AUDITOR || $userRoleDetails->role_name == ADMIN)){

                $model = new BlogTask();
                $model->unsetAttributes();
                if(isset($_GET['BlogTask']))
                    $model->attributes = $_GET['BlogTask'];

                if($userRoleDetails->role_name == BLOG_EDITOR){
                    $model->editor_id = $userId;}

                if($userRoleDetails->role_name == BLOG_WRITER){
                    $model->writer_id = $userId;}

                if($userRoleDetails->role_name == BLOG_AUDITOR){
                    $model->auditor_id = $userId;}

                if($userRoleDetails->role_name == BLOG_WRITER || $userRoleDetails->role_name == BLOG_EDITOR){
                    $this->render('tasksView',array('model'=>$model,'userRoleDetails'=>$userRoleDetails));
                }else if ($userRoleDetails->role_name == BLOG_AUDITOR || $userRoleDetails->role_name == ADMIN){
                    $this->render('auditorTasksView',array('model'=>$model,'userRoleDetails'=>$userRoleDetails));
                }

            }
            else{
                Yii::app()->getController()->redirect(array('/BlogTask/notfound'));
            }


        }catch(Exception $ex) {
            Yii::log("exception  ".$ex->getMessage());
            MedinfiExceptionNotifier::notifyException($ex);
        }

    }

    public function actionCreateBlogTask() {
        try {

            if(isset(Yii::app()->session['userId'])){
                $userId =  Yii::app()->session['userId'];
                $userRole =  Yii::app()->session['med_role'];
            }
            else{
                $this->redirect(array('User/Login'));
            }

            $userRoleDetails = Role::model()->findByPk($userRole);

            if(!empty($userRoleDetails) && ($userRoleDetails->role_name == BLOG_EDITOR)){

                $model = new BlogTask();
                $model->unsetAttributes();
                if(isset($_GET['BlogTask']))
                    $model->attributes = $_GET['BlogTask'];

                $getBlogWriterQuery = "SELECT med_user.id,med_user.username,med_user.email FROM med_user,user_role,med_role WHERE med_user.id = user_role.user_id and med_role.role_name  LIKE 'Blog Writer' and user_role.role_id = med_role.id";
                $getBlogWriterResult = Yii::app()->db->createCommand($getBlogWriterQuery)->queryAll();

                $this->render('createTask',array('model'=>$model,'getBlogWriterResult'=>$getBlogWriterResult));
            }
            else{
                Yii::app()->getController()->redirect(array('/BlogTask/notfound'));
            }


        }catch(Exception $ex) {
            Yii::log("exception ".$ex->getMessage());
            MedinfiExceptionNotifier::notifyException($ex);
        }

    }

    public function actionEditBlogTask($id) {

        $userId = "0";
        $userRole = "0";
        if(isset(Yii::app()->session['userId'])){
            $userId =  Yii::app()->session['userId'];
            $userRole =  Yii::app()->session['med_role'];
        }
        else{
            $this->redirect(array('User/Login'));
        }

        $userRoleDetails = Role::model()->findByPk($userRole);

        if($userRoleDetails->role_name == BLOG_WRITER || $userRoleDetails->role_name == BLOG_EDITOR ||
           $userRoleDetails->role_name == BLOG_AUDITOR || $userRoleDetails->role_name == ADMIN){

            $taskDetails = BlogTask::model()->findByAttributes(array('id'=>$id));

            if(empty($taskDetails)){
                Yii::app()->getController()->redirect(array('/BlogTask/notfound'));
                return;
            }

            if($userRoleDetails->role_name == BLOG_AUDITOR && $taskDetails->auditor_id != $userId){
               Yii::app()->getController()->redirect(array('/BlogTask/notfound'));
               return;
            }
            if($userRoleDetails->role_name == BLOG_WRITER && $taskDetails->writer_id != $userId){
               Yii::app()->getController()->redirect(array('/BlogTask/notfound'));
               return;
            }
            if($userRoleDetails->role_name == BLOG_EDITOR && $taskDetails->editor_id != $userId){
               Yii::app()->getController()->redirect(array('/BlogTask/notfound'));
               return;
            }


            $getTaskQuery = "SELECT * FROM blog_task_details WHERE task_id = ".$id." ORDER BY id DESC LIMIT 1";
            $getTaskResult = Yii::app()->db->createCommand($getTaskQuery)->queryAll();

            $detailActionQuery = "SELECT blog_task_action.*,med_user.name FROM blog_task_action,med_user WHERE blog_task_action.action_by = med_user.id AND blog_task_action.task_id = ".$id." ORDER BY id";
            $detailActionResult = Yii::app()->db->createCommand($detailActionQuery)->queryAll();

            $docVersion = BlogTaskUtility::getDocVersion($id);

            $this->render('editTask',array('taskDetails'=>$taskDetails,'additionalInfo'=>$getTaskResult,'userRoleDetails'=>$userRoleDetails,'docVersion'=>$docVersion,'detailActionResult'=>$detailActionResult));

        }
        else{
            Yii::app()->getController()->redirect(array('/BlogTask/notfound'));
        }

    }

    public function actionuserCreation()
    {
        try{

            if(isset(Yii::app()->session['userId'])){
                $userId =  Yii::app()->session['userId'];
                $userRole =  Yii::app()->session['med_role'];
            }
            else{
                $this->redirect(array('User/Login'));
            }

            $userRoleDetails = Role::model()->findByPk($userRole);

            if(!empty($userRoleDetails) && ($userRoleDetails->role_name == BLOG_AUDITOR || $userRoleDetails->role_name == ADMIN)){

                $this->render('userCreation');
            }
            else{
                Yii::app()->getController()->redirect(array('/BlogTask/notfound'));
            }


        }
        catch(Exception $ex) {
            MedinfiExceptionNotifier::notifyException($ex);
        }
    }

    public function actionAddBlogTask() {

        try{
            global $connection1;
            $connection1 = Yii::app()->db;

            $blogWriter = isset($_REQUEST['blogWriter']) ? $_REQUEST['blogWriter']: '';
            $submissionDate = isset($_REQUEST['submissionDate']) ? $_REQUEST['submissionDate']: '';
            $blogTopic = isset($_REQUEST['blogTopic']) ? $_REQUEST['blogTopic']: '';
            $focusKeyword = isset($_REQUEST['focusKeyword']) ? $_REQUEST['focusKeyword']: '';

            $dateTimeObj = DateTime::createFromFormat('m/d/Y', $submissionDate);
            $formattedAssignedDate = $dateTimeObj->format('Y-m-d');

            $editorId = "0";
            if(isset(Yii::app()->session['userId'])){
                $editorId =  Yii::app()->session['userId'];
            }

            if($blogWriter!="" && $submissionDate!="" && $blogTopic!="" && $focusKeyword !="" && $editorId !="0")
            {

                $transaction = $connection1->beginTransaction();
                try{
                    $blogTask = new BlogTask();
                    $blogAction = new BlogTaskAction();

                    $blogTask->blog_topic = $blogTopic;
                    $blogTask->focus_keyword = $focusKeyword;
                    $blogTask->created_on = date('Y-m-d H:i:s');
                    $blogTask->target_submission_date = $formattedAssignedDate;
                    $blogTask->editor_id = $editorId;
                    $blogTask->writer_id = $blogWriter;
                    $blogTask->auditor_id = BlogTaskUtility::getBlogAuditor();
                    $result=$blogTask->insert();

                    $blogAction->task_id=$blogTask->id;
                    $blogAction->action='assigned_by_editor';
                    $blogAction->status='ASSIGNED';
                    $blogAction->action_by=$editorId;
                    $blogAction->reason="";
                    $blogAction->comments="";
                    $blogAction->action_on=date('Y-m-d H:i:s');
                    $result2=$blogAction->insert();

                    $blogTask->action_id = $blogAction->id;
                    $blogTask->update();

                    $transaction->commit();

                    $res['status'] = 'success';
                    $res['desc'] = 'Blog task created successfully';
                    $reason='';
                    BlogTaskUtility::getSendTaskMail($blogTask,$reason,"assigned_by_editor");

                }
                catch(Exception $ex) {
                    $transaction->rollBack();
                    MedinfiExceptionNotifier::notifyException($ex);
                    $res['status'] = 'fail';
                    $res['desc'] = 'Something went wrong';
                }

            }else{
                $res['status'] = 'fail';
                $res['desc'] = 'Required data are missing';
            }

            BlogUtility::jencode($res);
        }
        catch(Exception $ex) {
            MedinfiExceptionNotifier::notifyException($ex);
        }
    }

    public function actionAddUser()
    {
        try
        {
            global $connection1;
            $connection1 = Yii::app()->db;

            $userType = isset($_REQUEST['userType']) ? $_REQUEST['userType']: '';
            $userName = isset($_REQUEST['userName']) ? $_REQUEST['userName']: '';
            $userMobileNo = isset($_REQUEST['userMobileNo']) ? $_REQUEST['userMobileNo']: '';
            $userEmail = isset($_REQUEST['userEmail']) ? $_REQUEST['userEmail']: '';
            $userAddress = isset($_REQUEST['userAddress']) ? $_REQUEST['userAddress']: '';

            $getuserdetails= "SELECT id FROM med_user where email like '".$userEmail."' and user_status like 'live'";
            $userdetails= Yii::app()->db->createCommand($getuserdetails)->queryAll();
            $addrole=true;
            $user_id;
            $user_status;
            $new_user;
            if(isset(Yii::app()->session['userId'])){
                $creator_userId =  Yii::app()->session['userId'];
            }
            if (sizeof($userdetails) > 0)
            {
                $user_id=$userdetails[0]['id'];
                $new_user=false;

                $getuserrole= "SELECT role_id FROM user_role where user_id='".$user_id."' ORDER BY role_id DESC";
                $userrole= Yii::app()->db->createCommand($getuserrole)->queryAll();

                if (sizeof($userrole) > 0)
                {
                    $user_role=$userrole[0]['role_id'];
                    global $BLOG_BACKEND_ACCESS;
                    if((in_array($user_role,$BLOG_BACKEND_ACCESS)))
                    {
                        $res['status'] = 'fail';
                        $res['desc'] = "User already exists";
                        $addrole=false;
                    }
                }
            }
            else
            {
                $new_user=true;
                $transaction = $connection1->beginTransaction();
                try{
                    $user= new User();
                    $user->name=$userName;
                    $user->username=$userName;
                    $user->phone=$userMobileNo;
                    $user->address=$userAddress;
                    $user->email=$userEmail;
                    $user->user_status="live";

                    $user->insert();

                    $res['status'] = 'success';
                    $res['desc'] = 'User created successfully';

                    $newpassword=$this->randomString(6);
                    $user->password = md5($newpassword);
                    if($user->save())
                    {
                        //PhpBBUtil::phpbb_synchronize_user($user->id, $user->email,$newpassword, '1');
                        $subject='Credentials for Medinfi blog backend access';
                        $message='Dear User,<br><br>';
                        $message.='Your Credentials to access Medinfi blog backend is:<br>Email: '.$user->email.'<br>Password:  <b>'.$newpassword .' </b><br>';
                        $message.='URL: https://www.medinfi.com/blog/backend-login <br><br>';
                        $message.='Regards<br> Medinfi Team<br>';

                        SendEmail::sendYiiMail($user->email,"admin@medinfi.com", 'Medinfi', $replyto='', $subject, $message,$cc='',$bcc='');

                        $res['status'] = 'success';
                        $res['desc'] = ' User created successfully.';
                    }
                    else
                    {
                        $res['status'] = 'fail';
                        $res['desc'] = 'Failed to create password.';
                    }
                    $transaction->commit();
                    $user_id=$user->id;

                }
                catch(Exception $ex) {
                    $transaction->rollBack();
                    MedinfiExceptionNotifier::notifyException($ex);
                }
            }
            if($addrole)
            {
                $transaction = $connection1->beginTransaction();
                try{
                    $userrole= new UserRole();
                    $userrole->role_id=$userType;
                    $userrole->user_id=$user_id;
                    $userrole->created_on=date('Y-m-d H:i:s');
                    $userrole->created_by=$creator_userId;

                    $userrole->insert();

                    $res['status'] = 'success';
                    $res['desc'] = 'User role created successfully';
                    $transaction->commit();

                    if(!$new_user)
                    {
                        $subject='Medinfi blog backend access';
                        $message='Dear User,<br><br>';
                        $message.='You have access to Medinfi blog backend using:<br>Email: '.$userEmail.' </b><br>';
                        $message.='URL: https://www.medinfi.com/blog/backend-login <br><br>';
                        $message.='Regards<br> Medinfi Team<br>';

                        SendEmail::sendYiiMail($userEmail,"admin@medinfi.com", 'Medinfi', $replyto='', $subject, $message,$cc='',$bcc='');

                        $res['status'] = 'success';
                        $res['desc'] = ' User role created successfully.';
                    }
                }
                catch(Exception $ex) {
                    $transaction->rollBack();
                    MedinfiExceptionNotifier::notifyException($ex);
                }
            }
            BlogUtility::jencode($res);
        }
        catch(Exception $ex)
        {
            MedinfiExceptionNotifier::notifyException($ex);
        }
    }

    public function actionUploadBlogTask() {

        try {
            $blogTaskId =  isset($_POST['blogId']) ? $_POST['blogId'] : "";
            $fileName =  isset($_POST['fileName']) ? $_POST['fileName'] : "";
            $action =  isset($_POST['action']) ? $_POST['action'] : "";
            $comments =  isset($_POST['comments']) ? $_POST['comments'] : "";
            $bloglink =  isset($_POST['bloglink']) ? $_POST['bloglink'] : "";
            $reason =  isset($_POST['reason']) ? $_POST['reason'] : "";
            $UploadDocStatus = "false";

            $userId = "0";
            $userRole = "0";
            if(isset(Yii::app()->session['userId'])){
                $userId =  Yii::app()->session['userId'];
                $userRole =  Yii::app()->session['med_role'];
            }

            //$userRoleDetails = Role::model()->findByPk($userRole);

            global $connection1;
            $connection1 = Yii::app()->db;

            $filename ="";
            if($fileName!='') {
                $imgFile = $_FILES['file']['tmp_name'];
                $filename = $fileName.'.docx';
                $destinationPath = S3_FIRST_FOLDER_PREFIX.S3_FOLDER_NAME.$filename;
                if(!(S3Uploads::uploadfile($imgFile,S3_FIRST_FOLDER_PREFIX.S3_FOLDER_NAME.$filename .'')))
                {
                    $UploadDocStatus = "false";
                    Yii::log('Fail to upload blog document', 'error','application.actionSuggestDoctorHospital');
                }
                else{
                    $UploadDocStatus = "true";
                    Yii::log('successfully uploaded...');
                }
            }

            if($UploadDocStatus == 'true'){
                if($action == 'approved_by_editor' || $action == 'reassigned_by_editor' || $action == 'submitted_by_writer'
                   || $action == 'published_by_auditor' || $action == 'rejected_by_auditor' || $action == 'reassigned_by_auditor'){

                    $transaction = $connection1->beginTransaction();
                    try{
                        $blogTask = BlogTask::model()->findByPk($blogTaskId);
                        $blogAction = new BlogTaskAction();
                        $blogTaskDetail = new BlogTaskDetails();

                        $blogAction->task_id=$blogTaskId;

                        if($action == 'approved_by_editor'){
                            $blogAction->action='approved_by_editor';
                            $blogAction->status='APPROVED';
                        }
                        else if($action == 'reassigned_by_editor'){
                            $blogAction->action='reassigned_by_editor';
                            $blogAction->status='RE-ASSIGNED';
                        }
                        else if($action == 'submitted_by_writer'){
                            $blogAction->action='submitted_by_writer';
                            $blogAction->status='SUBMITTED';
                        }
                        else if($action == 'published_by_auditor'){
                            $blogAction->action='published_by_auditor';
                            $blogAction->status='PUBLISHED';
                        }
                        else if($action == 'rejected_by_auditor'){
                            $blogAction->action='rejected_by_auditor';
                            $blogAction->status='REJECTED';
                        }
                        else if($action == 'reassigned_by_auditor'){
                            $blogAction->action='reassigned_by_auditor';
                            $blogAction->status='RE-ASSIGNED';
                        }

                        $blogAction->action_by=$userId;
                        $blogAction->comments=$comments;
                        $blogAction->action_on=date('Y-m-d H:i:s');
                        $blogAction->reason=$reason;
                        $result2=$blogAction->insert();

                        $blogTask->action_id = $blogAction->id;
                        $blogTask->link = $bloglink;
                        $blogTask->update();

                        $blogTaskDetail->task_id=$blogTaskId;
                        $blogTaskDetail->document_version= BlogTaskUtility::getNextDocVersion($blogTaskId);
                        $blogTaskDetail->document_name=$filename;
                        $blogTaskDetail->document_link=$destinationPath;
                        $blogTaskDetail->updated_by=$userId;
                        $blogTaskDetail->updated_on=date('Y-m-d H:i:s');
                        $blogTaskDetail->insert();

                        $transaction->commit();

                        $res['status'] = 'success';
                        $res['desc'] = 'Blog task uploaded successfully';

		                if($action == 'published_by_auditor'){
		                	BlogTaskUtility::getSendTaskMail($blogTask,$bloglink,"published_by_auditor");
                        }else if($action == 'rejected_by_auditor'){
                            BlogTaskUtility::getSendTaskMail($blogTask,$reason,"rejected_by_auditor");
                        }else if($action == 'reassigned_by_auditor'){
                            BlogTaskUtility::getSendTaskMail($blogTask,$comments,"reassigned_by_auditor");
                        }else if($action == 'reassigned_by_editor'){
                                BlogTaskUtility::getSendTaskMail($blogTask,$comments,"reassigned_by_editor");
                        }else if($action == 'approved_by_editor'){
                            $reason='';
                            BlogTaskUtility::getSendTaskMail($blogTask,$reason,"approved_by_editor");
                        }else if($action == 'submitted_by_writer'){
                            $reason='';
                            BlogTaskUtility::getSendTaskMail($blogTask,$reason,"submitted_by_writer");
                        }
                    }
                    catch(Exception $ex) {
                        $transaction->rollBack();
                        MedinfiExceptionNotifier::notifyException($ex);
                        $res['status'] = 'fail';
                        $res['desc'] = 'Something went wrong';
                    }
                }
            }else{
                $res['status'] = 'fail';
                $res['desc'] = 'Fail to upload blog document';
            }

            BlogUtility::jencode($res);
        }
        catch(Exception $ex) {
            MedinfiExceptionNotifier::notifyException($ex);
        }

    }

    //to create random characters
    public function randomString($length = 10)
    {
        $str = "";
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++)
        {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    public function actionExport()
    {
        Yii::Import('application.extensions.ExportXLS.ExportXLS');
        $headercolums =array('Blog Task Id','Blog Topic','Focus Keyword','Created on','Submission date','Writer name','Editor name','Auditor name',
                             'Comments','Reason','Action','Status','Latest Submission Date','Latest Re-Assigned Date','Latest Approved Date','Rejected Date','Published Date');

        $dataProvider = BlogTask::model()->search();
        $dataProvider->pagination = false;

        $datalist=array();
        $i=0;
        $row="";

        foreach( $dataProvider->data as $data )
        {
            $submissionDate="";
            $reassignedDate="";
            $approvedDate="";
            $rejectedDate="";
            $publishedDate="";

            $submissionDateQuery = "SELECT action_on FROM `blog_task_action` WHERE `task_id` = ".$data->id." AND `status` LIKE 'SUBMITTED' ORDER BY `id` DESC LIMIT 1";
            $submissionDateResult = Yii::app()->db->createCommand($submissionDateQuery)->queryAll();
            if(!empty($submissionDateResult)){
                $submissionDate = $submissionDateResult[0]['action_on'];
            }

            $reassignedDateQuery = "SELECT action_on FROM `blog_task_action` WHERE `task_id` = ".$data->id." AND `status` LIKE 'RE-ASSIGNED' ORDER BY `id` DESC LIMIT 1";
            $reassignedDateResult = Yii::app()->db->createCommand($reassignedDateQuery)->queryAll();
            if(!empty($reassignedDateResult)){
                $reassignedDate = $reassignedDateResult[0]['action_on'];
            }

            $approvedDateQuery = "SELECT action_on FROM `blog_task_action` WHERE `task_id` = ".$data->id." AND `status` LIKE 'APPROVED' ORDER BY `id` DESC LIMIT 1";
            $approvedDateResult = Yii::app()->db->createCommand($approvedDateQuery)->queryAll();
            if(!empty($approvedDateResult)){
                $approvedDate = $approvedDateResult[0]['action_on'];
            }

            $rejectedDateQuery = "SELECT action_on FROM `blog_task_action` WHERE `task_id` = ".$data->id." AND `status` LIKE 'REJECTED' ORDER BY `id` DESC LIMIT 1";
            $rejectedDateResult = Yii::app()->db->createCommand($rejectedDateQuery)->queryAll();
            if(!empty($rejectedDateResult)){
                $rejectedDate = $rejectedDateResult[0]['action_on'];
            }

            $publishedDateQuery = "SELECT action_on FROM `blog_task_action` WHERE `task_id` = ".$data->id." AND `status` LIKE 'PUBLISHED' ORDER BY `id` DESC LIMIT 1";
            $publishedDateResult = Yii::app()->db->createCommand($publishedDateQuery)->queryAll();
            if(!empty($publishedDateResult)){
                $publishedDate = $publishedDateResult[0]['action_on'];
            }

            $datalist[$i]['id'] = isset($data->id)?$data->id:"";
            $datalist[$i]['blog_topic'] = isset($data->blog_topic)?$data->blog_topic:"";
            $datalist[$i]['focus_keyword'] = isset($data->focus_keyword)?$data->focus_keyword:"";
            $datalist[$i]['created_on'] = isset($data->created_on)?$data->created_on:"";
            $datalist[$i]['target_submission_date'] = isset($data->target_submission_date)?$data->target_submission_date:"";
            $datalist[$i]['writer_name'] = isset($data->writerdetails->name)?$data->writerdetails->name:"";
            $datalist[$i]['editor_name'] = isset($data->editordetails->name)?$data->editordetails->name:"";
            $datalist[$i]['auditor_name'] = isset($data->auditordetails->name)?$data->auditordetails->name:"";
            $datalist[$i]['comments'] = isset($data->actiondetails->comments)?$data->actiondetails->comments:"";
            $datalist[$i]['reason'] = isset($data->actiondetails->reason)?$data->actiondetails->reason:"";
            $datalist[$i]['action'] = isset($data->actiondetails->action)?$data->actiondetails->action:"";
            $datalist[$i]['status'] = isset($data->actiondetails->action)?$data->actiondetails->status:"";

            $datalist[$i]['submissionDate'] = $submissionDate;
            $datalist[$i]['reassignedDate'] = $reassignedDate;
            $datalist[$i]['approvedDate'] = $approvedDate;
            $datalist[$i]['rejectedDate'] = $rejectedDate;
            $datalist[$i]['publishedDate'] = $publishedDate;

            $i++;
        }
        $row=(count($datalist)>0)?$datalist:"";
        // Xls File Name
        $filename = 'blog_tasks_'.date("d-m-y-h:i:s").'.xls';
        $xls      = new ExportXLS($filename);
        $header = null;
        $xls->addHeader($headercolums);
        $xls->addRow($row);
        $xls->sendFile();

    }

}

?>