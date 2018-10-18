<?php

/**
 * This is the model class for table "med_user".
 *
 * The followings are the available columns in table 'med_user':
 * @property string $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property string $pic
 * @property string $rank
 * @property string $created_at
 * @property string $user_status
 *
 * The followings are the available model relations:
 * @property MedDeliveryreport[] $medDeliveryreports
 * @property MedDocreview[] $medDocreviews
 * @property MedHospreview[] $medHospreviews
 */
class User extends CActiveRecord
{
    public $doctor_search;
    public $feedback_search;
    public $hospital_search;
    public $activeuser_search;
    public $old_password;
    public $new_password;
    public $repeat_password;
    public $userrole_search;


    /**
	 * @return string the associated database table name
	 */

    public $password_repeat;

    public $otp;
    public $isEmail;
    public $fbId;

    public function tableName()
    {
        return 'med_user';
    }

    /**
	 * @return array validation rules for model attributes.
	 */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email', 'required', 'on' => 'registration'),
            array('email','email'),
            array('email', 'findUserExistance', 'on' => 'registration'),
            array('email', 'findUserExistanceWeb', 'on' => 'frontregistration'),
            array('new_password, repeat_password', 'required', 'on' => 'changePwdApp'),
            array('password', 'required', 'on' => 'frontregistration'),
            //array('email', 'unique', 'on' => 'adminUserRegistration'),
            array('user_status','validUser','on' => 'adminUserRegistration'),
            array('phone', 'required','on' => 'phoneregistration'),
            array('phone','numerical','integerOnly'=>true),
            array('phone', 'findUserExistancePhone', 'on' => 'phoneregistration'),
            array('phone', 'required','on' => 'phoneregistrationWeb'),
            array('phone', 'findUserExistancePhoneWeb', 'on' => 'phoneregistrationWeb'),
            array('name', 'length', 'max'=>50),
            //array('name', 'InputValidators', 'on'=>'registration'),
            array('username', 'length', 'max'=>20),
            array('password, password_repeat, email', 'length', 'max'=>50),
            array('password', 'compare', 'compareAttribute' => 'password_repeat', 'on'=>'register'),
            array('phone', 'length', 'max'=>15),
            array('pic', 'length', 'max'=>70),
            array('rank', 'length', 'max'=>10),
            array('address, city, created_at, DeviceIMEI,ManufactureName,Model,APILevel,ISO,DeviceIMEI', 'safe'),
            array('old_password, new_password, repeat_password', 'required', 'on' => 'changePwd'),
            array('old_password', 'findPasswords', 'on' => 'changePwd'),
            //array('old_password', 'findPasswords', 'on' => 'frontregistration'),
            array('new_password', 'comparepassword', 'on' => 'changePwd'),
            array('repeat_password', 'compare', 'compareAttribute'=>'new_password', 'on'=>'changePwd'),
            array('repeat_password', 'compare', 'compareAttribute'=>'new_password', 'on'=>'changePwdApp'),
            array('password_repeat', 'compare', 'compareAttribute'=>'password', 'on'=>'frontregistration'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, username, password, phone, email, address, pic, rank, created_at,doctor_search,doctor_search,hospital_search,feedback_search,userrole_search', 'safe', 'on'=>'search'),
        );
    }

    //matching the old password with your existing password.
    public function findPasswords($attribute, $params)
    {
        $user = User::model()->findByPk(Yii::app()->session['userId']);
        if ($user->password != md5($this->old_password))
            $this->addError($attribute, 'Old password is incorrect.');
    }

    //matching the old password with your existing password.
    public function comparepassword($attribute, $params)
    {
        $user = User::model()->findByPk(Yii::app()->session['userId']);
        if ($user->password == md5($this->new_password))
            $this->addError($attribute, 'New password cannot be same as old password.');
    }

    public function validUser($attribute, $params)
    {
        $users = User::model()->findAllByAttributes(array('email'=>$this->email));
        $liveUsers =0;
        if (!empty($users))
        {
            foreach ($users as $user) {
                if($user->user_status=='live') {
                    //Yii::log('user is already there '.$user->user_status);
                    $liveUsers++;
                }
            }

            if($liveUsers>0) {
                //Yii::log('user registration is doing ');
                $this->addError($attribute, 'Email id is already registered');
            }
        }

    }

    //matching the old password with your existing password.
    public function findUserExistance($attribute, $params)
    {
        $user = User::model()->findByAttributes(array('email'=>$this->email,'user_status'=>'live'));
        if (!empty($user))
        {
            $this->addError($attribute,ERROR_EMAIL_DUPLICATION_APP);	
        }
    }

    public function findUserExistancePhone($attribute, $params)
    {
        $user = User::model()->findByAttributes(array('phone'=>$this->phone,'user_status'=>'live'));
        if (!empty($user))
        {
            $this->addError($attribute,ERROR_PHONE_DUPLICATION_APP);	
        }
    }

    //matching the old password with your existing password.
    public function findUserExistanceWeb($attribute, $params)
    {
        $user = User::model()->findByAttributes(array('email'=>$this->email,'user_status'=>'live'));
        if (!empty($user))
        {
            $this->addError($attribute,"Email ID already registered");	
        }
    }

    public function findUserExistancePhoneWeb($attribute, $params)
    {
        $user = User::model()->findByAttributes(array('phone'=>$this->phone,'user_status'=>'live'));
        if (!empty($user))
        {
            $this->addError("email","Mobile already registered");	
        }
    }

    /**
	 * @return array relational rules.
	 */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'medDeliveryreports' => array(self::HAS_MANY, 'MedDeliveryreport', 'userId'),
            'medDocreviews' => array(self::HAS_MANY, 'Docreview', 'userId'),
            'medHospreviews' => array(self::HAS_MANY, 'Hospreview', 'userId'),
            'medUserfeedback' => array(self::HAS_MANY, 'Userfeedback', 'userId'),
            'Userrole' => array(self::HAS_MANY, 'UserRole', 'user_id'),
            'meddoctor'=>array(
                self::HAS_ONE,'Doctor',array('doctorId'=>'id'),
                'through'=>'medDocreviews'),
            'medhospital'=>array(
                self::HAS_ONE,'Hospital',array('hospitalId'=>'id'),
                'through'=>'medHospreviews'),
            'role'=>array(
                self::HAS_ONE,'Role',array('role_id'=>'id'),
                'through'=>'Userrole'), //role mapping with user 
        );
    }

    /**
	 * @return array customized attribute labels (name=>label)
	 */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'username' => 'Username',
            'password' => 'Password',
            'password_repeat' => 'Confirm Password',
            'phone' => 'Phone',
            'email' => 'Email',
            'address' => 'Address',
            'city' => 'City',
            'pic' => 'Pic',
            'rank' => 'Rank',
            'created_at' => 'Created At',
        );
    }

    /**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;
        $criteria->alias = $this->tableName();

        $criteria->with = array('medHospreviews', 'medDocreviews','medUserfeedback','meddoctor','medhospital','role');
        $criteria->group = $criteria->alias.'.id';
        $criteria->together = true;

        $criteria->compare($criteria->alias.'.id',$this->id,true);
        $criteria->compare($criteria->alias.'.name',$this->name,true);
        $criteria->compare($criteria->alias.'.password',$this->password,true);
        $criteria->compare($criteria->alias.'.phone',$this->phone,true);
        $criteria->compare($criteria->alias.'.email',$this->email,true);
        $criteria->compare($criteria->alias.'.address',$this->address,true);
        $criteria->compare($criteria->alias.'.city',$this->city,true);
        $criteria->compare($criteria->alias.'.pic',$this->pic,true);
        $criteria->compare($criteria->alias.'.rank',$this->rank,true);
        $criteria->compare($criteria->alias.'.created_at',$this->created_at,true);
        $criteria->compare('medhospital.name', $this->hospital_search, true);
        $criteria->compare('meddoctor.firstname', $this->doctor_search, true);
        $criteria->compare('medUserfeedback.feedback', $this->feedback_search, true);
        $criteria->compare('role.role_name', $this->userrole_search, true);
        $criteria->compare($criteria->alias.'.email',$this->email,true);


        if(isset(Yii::app()->user->role) && Yii::app()->user->role==2)
        {
            $criteria->addCondition(" role.id=4 ");
        }

        $criteria->addCondition($criteria->alias.".user_status='live' "); // code change for implementing soft deletion bu jiji jan 1st 2016

        $data= new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'attributes'=>array(
                    'hospital_search'=>array(
                        'asc'=>'medhospital.name',
                        'desc'=>'medhospital.name DESC',
                    ),
                    'doctor_search'=>array(
                        'asc'=>'meddoctor.firstname',
                        'desc'=>'meddoctor.firstname DESC',
                    ),
                    'feedback_search'=>array(
                        'asc'=>'medUserfeedback.feedback',
                        'desc'=>'medUserfeedback.feedback DESC',
                    ),
                    'userrole_search'=>array(
                        'asc'=>'role.role_name',
                        'desc'=>'role.role_name DESC',
                    ),
                    'user_email'=>array(
                        'asc'=>$criteria->alias.'.email',
                        'desc'=>$criteria->alias.'.email DESC',
                    ),
                    '*',
                ),
            ),
        ));

        $_SESSION['search_excel_data']=$data;

        return $data;



    }

    //to get reviewa of doctor from the user
    public function getDoctorReviews($plain=0) 
    {
        $res = array();
        $modelDocReviewAppend=array(); 
        $modelDocReview = Yii::app()->db->createCommand("SELECT
													md.firstname,md.id
													FROM
													med_docreview dcw
													INNER JOIN 
													med_doctor md 
													ON 
													dcw.doctorId = md.id 
													where dcw.userId=".$this->id."")->queryAll();    	  

        $modelDocReviewAppend="";
        $i=1;
        foreach($modelDocReview as $key=>$value)
        {

            if($plain==1)
            {
                $modelDocReviewAppend.=$i.". ".$value['firstname']."\n";

            }
            else
            {
                $modelDocReviewAppend.=$i.". ".CHtml::link($value['firstname'],array('Doctor/Detail/'.$value['id']))."<br>";

            }
            $i++;
        }	

        return $modelDocReviewAppend;
    }

    public function getHospitalReviews($plain=0) 
    {
        $res = array();
        $modelHospitalReviews=array(); 
        $modelHospReview = Yii::app()->db->createCommand("SELECT
													mh.name,mh.id
													FROM
													med_hospreview mhr
													INNER JOIN 
													med_hospital mh
													ON 
													mhr.hospitalId = mh.id 
													where mhr.userId=".$this->id."")->queryAll();    	  

        $modelHospitalReviews="";
        $i=1;
        foreach($modelHospReview as $key=>$value)
        {
            if($plain==1)
            {
                $modelHospitalReviews.=$i.". ".$value['name']."\n";

            }
            else
            {
                $modelHospitalReviews.=$i.". ".CHtml::link($value['name'],array('Hospital/Detail/'.$value['id']))."<br>";
            }
            $i++;
        }	

        return $modelHospitalReviews;
    }
    //to get user feedback
    public function getUserFeedback($plain=0) 
    {
        $res = array();
        $userFeedbackAppend=array(); 
        $userFeedback = Yii::app()->db->createCommand("SELECT feedback from med_userfeedback fd
													 where fd.userId=".$this->id."")->queryAll();    	  

        $userFeedbackAppend="";
        $i=1;
        foreach($userFeedback as $key=>$value)
        {
            if($plain==1)
            {
                $userFeedbackAppend.=$i.". ".$value['feedback']."\n";
            }
            else
            {
                $userFeedbackAppend.=$i.". ".$value['feedback']."<br>";
            }
            $i++;

        }	

        return $userFeedbackAppend;
    }

    //to get user feedback
    public function getActiveUserCount() 
    {
        $res = array();

        $activeuser = Yii::app()->db->createCommand("SELECT CONCAT(YEAR(start_time), '/', 
													   month(start_time)) AS month_name, 
       												   YEAR(start_time) yearname, WEEK(start_time) as weeknumber, COUNT(*) activeusercount
														FROM med_usersessionlog
														where  
														userId=".$this->id." and   
														start_time>= NOW()-INTERVAL 1 MONTH
														")->queryAll();    	  
        $activeuserAppend="";
        $i=1;
        foreach($activeuser as $key=>$value)
        {
            $activeuserAppend.=$value['activeusercount'];
            $i++;
        }	

        return $activeuserAppend;
    }

    public function getUserRole() 
    {
        $userRolesArr = array();

        $user_role = UserRole::model()->findAllByAttributes(array("user_id"=>$this->id));    	  
        $user_role_append="";
        $i=0;
        foreach($user_role as $key=>$value)
        {
            $role_name=Role::model()->findByPk($value['role_id']);
            $userRolesArr[]=$role_name['role_name'];

            $i++;
        }	
        if(!empty($userRolesArr))
        {
            $user_role_append=implode(" , ",$userRolesArr);
        }

        return $user_role_append;
    }

    //to get user feedback
    public function getIsAdminUser() 
    {	
        global $EXCLUDE_USER_ROLE_CHANGE_PASSWORD;
        $res = FALSE;
        $criteria = new CDbCriteria;
        // $criteria->addCondition('user_id'=>$this->id);
        $criteria->addCondition("user_id='$this->id'");
        $criteria->addNotInCondition('role_id',$EXCLUDE_USER_ROLE_CHANGE_PASSWORD);
        $adminUser = UserRole::model()->findAll($criteria);  

        //check user permission
        $sessionUserId=Yii::app()->session['userId'];
        $isPermitted = UserRole::model()->findByAttributes(array('user_id'=>$sessionUserId)); 

        if($adminUser && $isPermitted['role_id']==1)
        {
            $res=TRUE;
        } 	  
        return $res;
    }



    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
