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
class Role extends CActiveRecord
{
    /**
	 * @return string the associated database table name
	 */
    public function tableName()
    {
        return 'med_role';
    }

    /**
	 * @return array validation rules for model attributes.
	 */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(

            array('role_name', 'required'),
            array('role_name', 'length', 'max'=>50),
            array('role_description', 'length', 'max'=>250),
            array('created_on,created_by,updated_on,updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, created_on,created_by,updated_on,updated_by,role_name,role_description', 'safe', 'on'=>'search'),
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
            'doctorTypes' => array(self::HAS_MANY, 'UserRole', 'role_id'),
        );
    }

    /**
	 * @return array customized attribute labels (name=>label)
	 */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'role_name' => 'User Type',
            'role_description' => 'Role Description',
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
        $criteria->compare('role_name',$this->role_name,true);
        $criteria->compare('role_description',$this->role_description,true);
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
