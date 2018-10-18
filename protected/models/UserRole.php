<?php

/**
 * This is the model class for table "med_doctype".
 *
 * The followings are the available columns in table 'med_doctype':
 * @property string $id
 * @property string $speciality
 * @property string $detail
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property DoctorType[] $doctorTypes
 */
class UserRole extends CActiveRecord
{
    /**
	 * @return string the associated database table name
	 */
    public function tableName()
    {
        return 'user_role';
    }

    /**
	 * @return array validation rules for model attributes.
	 */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id', 'length', 'max'=>50),
            array('role_id', 'length', 'max'=>250),
            array('created_on,created_by,updated_on,updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, created_on,created_by,updated_on,updated_by,role_id,user_id', 'safe', 'on'=>'search'),
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
            'meduser' => array(self::BELONGS_TO, 'User', 'user_id'),
            'medrole' => array(self::BELONGS_TO, 'Role', 'role_id'),
        );
    }

    /**
	 * @return array customized attribute labels (name=>label)
	 */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'role_id' => 'Role ID',
            'user_id' => 'User ID',
            'created_on' => 'Created On',
            'created_by' => 'Created By',
            'updated_on' => 'Updated On',
            'updated_by' => 'Updated By'
        );
    }


    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('role_id',$this->role_id,true);
        $criteria->compare('user_id',$this->user_id,true);
        $criteria->compare('created_on',$this->created_on,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Doctype the static model class
	 */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
