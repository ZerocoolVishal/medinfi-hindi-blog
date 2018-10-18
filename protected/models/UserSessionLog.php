<?php

/**
 * This is the model class for table "med_token".
 *
 * The followings are the available columns in table 'med_token':
 * @property string $id
 * @property string $userId
 * @property string $tokenId
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property MedUser $user
 */
class UserSessionLog extends BlogActiveRecord
{

    public function getDbConnection()
    {
        return self::getAnalyticsDbConnection();
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_session';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId', 'required'),
			array('userId', 'length', 'max'=>20),
			array('userId,session_id,lat,lon,gps_status,identified_location,identified_city,start_time,updated_on', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id,userId,session_id,lat,lon,gps_status,identified_location,identified_city,start_time,updated_on', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'Meduser' => array(self::BELONGS_TO, 'User', 'userId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' 			=> 'ID',
			'userId' 		=> 'User ID',
			'session_id' 	=> 'Session ID',
			'lat' 			=> 'Lattitude',
			'lon' 			=> 'Longitude',
			'gps_status' 	=> 'GPS Status',
			'identified_location' 	=> 'Identified Location',
			'identified_city' => 'Identified City',
			'start_time' => 'Created On'

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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('session_id',$this->session_id,true);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('lon',$this->lon,true);
		$criteria->compare('gps_status',$this->gps_status,true);
		$criteria->compare('identified_location',$this->identified_location,true);
		$criteria->compare('identified_city',$this->identified_city,true);
		$criteria->compare('start_time',$this->start_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Token the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
