<?php

class UserController extends Controller
{

    public $pageTitle='PVAutomation Default Titile';
    public $pageDescription='PVAutomation Default';

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete',// we only allow deletion via POST request
        );
    }

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionLogin()
    {
        if(Yii::app()->session['userId'])
        {
            $this->redirect(array('blogTask/checkUserMode'));
        }
        $model = new User();

        if(isset($_REQUEST['User'])) {
            $email = isset($_REQUEST['User']['email']) ? $_REQUEST['User']['email'] : '';
            $password = isset($_REQUEST['User']['password']) ? $_REQUEST['User']['password'] : '';
            Yii::log($email);
            Yii::log($password);
            $identity = new UserIdentity($email, $password);
            if($identity->checkUserExistence($email,$password)) {
                if($identity->authenticate()) {
                    Yii::app()->user->login($identity);
                    $this->redirect(array('blogTask/checkUserMode'));
                } else {
                    Yii::app()->user->setFlash('error', "<span>Invalid Username or Password!</span>");
                }
            } else {
                Yii::app()->user->setFlash('error', "<span>User doesn't exists!</span>");
            }
        }
        $this->render('login', array("model" => $model));
    }

    public function actionForgotPassword()
    {

        $model = new User();

        if(isset($_REQUEST['User'])) 
        {
            $email = isset($_REQUEST['User']['email']) ? $_REQUEST['User']['email'] : '';
            $userdetails=User::model()->findByAttributes(array("email"=>$email,'user_status'=>'live'));
            if($userdetails)
            {
                //$user_role=UserRole::model()->findByAttributes(array("user_id"=>$userdetails->id));

                global $BLOG_BACKEND_ACCESS;
                $user_role=UserRole::model()->findByAttributes(array('user_id'=>$userdetails->id,'role_id'=>$BLOG_BACKEND_ACCESS));

                if(!empty($user_role) && ($user_role->role_id==1 || $user_role->role_id==7 || $user_role->role_id==8 || $user_role->role_id==9))
                {
                    $newpassword=$this->randomString(6);
                    $userdetails->password = md5($newpassword);
                    if($userdetails->save())
                    {
                        //PhpBBUtil::phpbb_synchronize_user($userdetails->id, $userdetails->email,$newpassword, '1');
                        $subject='Password for medinfi blog backend access : Forgot Password';
                        $message='Dear User,<br><br>';
                        $message.='Your new password to access medinfi blog backend is:  <b>'.$newpassword .' </b><br><br>';
                        $message.='Regards<br> Medinfi Team<br>';

                        SendEmail::sendYiiMail($userdetails->email,"admin@medinfi.com", 'Medinfi', $replyto='', $subject, $message,$cc='',$bcc='');

                        Yii::app()->user->setFlash('success', "<span>Please check your mail to get new password.</span>");
                        $this->refresh();
                    }
                    else
                    {
                        Yii::app()->user->setFlash('error', "<span>Fail to send password</span>");
                        $this->refresh();
                    }
                }
                else
                {
                    Yii::app()->user->setFlash('error', "<span>No user found for provided email</span>");
                    $this->refresh();
                }

            }
            else
            {
                Yii::app()->user->setFlash('error', "<span>No user found for provided email</span>");
                $this->refresh();
            }

        }

        $this->render('forgotpassword', array("model" => $model));


    }

    //Logout user and destroy session
    public function actionLogout()
    {
        Yii::log("inside actionLogout");
        Yii::app()->user->logout(false);
        Yii::app()->session->clear();
        Yii::app()->session->destroy();
        //Yii::app()->user->clearState();
        Yii::log("before login after logout");
        $this->redirect('login');
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

    //User Login else display invalid message
    	public function actionChangePassword()
    	{
    		$model = new User;
     		$id=Yii::app()->session['userId'];
        	$model = User::model()->findByPk($id);
        	$model->setScenario('changePwd');


         	if(isset($_POST['User']))
         	{
            	$model->attributes = $_POST['User'];
            	$model->validate();
    			$errors = $model->getErrors();
    			if(empty($errors))
    			{
    	          $model->password = md5($model->new_password);
    	          if($model->save())
    	          {
    	          	//PhpBBUtil::phpbb_synchronize_user($model->id, $model->email,$model->new_password, '1');
    	          	Yii::app()->user->setFlash('successpasschange', "<span>Your password changed successfully. Please
    	          		login with your new credentials</span>");
    	          	Yii::app()->user->logout(false);
    				Yii::app()->session->clear();
    				Yii::app()->session->destroy();
    	          	$this->redirect(array('User/Login',"changepass"=>"1"));
    	        	$this->refresh();
    	          }
    	          else
    	          {
    	          	Yii::app()->user->setFlash('error', "<span>Fail to change password. Try again!</span>");
    	        	$this->refresh();
    	          }

    	        }
            }

        $this->render('changepassword',array('model'=>$model));

    	}

}
?>