<?php

/**
 * This is the model class for table "blog_task_action".
 *
 * The followings are the available columns in table 'blog_task_action':
 * @property int $id
 * @property int $task_id
 * @property string $action
 * @property int $action_by
 * @property string $reason
 * @property string $action_on
  */
class BlogTaskAction extends CActiveRecord
{
    /**
	 * @return string the associated database table name
	 */
    public function tableName()
    {
        return 'blog_task_action';
    }

    /**
	 * @return array validation rules for model attributes.
	 */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(

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
        );
    }

    /**
	 * @return array customized attribute labels (name=>label)
	 */
    public function attributeLabels()
    {

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

    }

    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return City the static model class
	 */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
