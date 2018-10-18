<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    /**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
    public function authenticate()
    {
        /*$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);*/
        $model = User::model()->findByAttributes(array('email'=>$this->username, 'password'=>md5($this->password),'user_status'=>'live'));
        if($model === null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        elseif($model->password!==md5($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else {
            global $BLOG_BACKEND_ACCESS;
            $user_role=UserRole::model()->findByAttributes(array('user_id'=>$model->id,'role_id'=>$BLOG_BACKEND_ACCESS));

            if(!empty($user_role) && (in_array($user_role->role_id,$BLOG_BACKEND_ACCESS)))
            {
                $this->errorCode=self::ERROR_NONE;
                Yii::app()->session['userId'] = $model->id;
                Yii::app()->session['med_email'] = $model->email;
                Yii::app()->session['med_password'] = $this->password;
                Yii::app()->session['med_role'] = $user_role->role_id;
                $this->setState('role',$user_role->role_id);
                $this->setState('rank',$model->rank);
                $this->setState('username',$model->name);

            }
            else
            {
                return false;
            }
        }
        return !$this->errorCode;
    }

    public function checkUserExistence($email,$password) {
        $userCheck = TRUE;
        $model = User::model()->findByAttributes(array('email'=>$email, 'password'=>md5($password),'user_status'=>'live'));
        if(empty($model)){
        $userCheck =FALSE;
        }

        return $userCheck;
    }
}