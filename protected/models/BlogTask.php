<?php

/**
 * This is the model class for table "blog_task".
 *
 * The followings are the available columns in table 'blog_task':
 * @property int $id
 * @property string $blog_topic
 * @property string $focus_keyword
 * @property string $created_on
 * @property string $target_submission_date
 * @property string $payout_type
 * @property string $payout_amount
 * @property int $editor_id
 * @property int $writer_id
 * @property int $auditor_id
 * @property string $link
  */
class BlogTask extends CActiveRecord
{
    /**
	 * @return string the associated database table name
	 */

    public $task_action;
    public $task_status;
    public $task_writer;
    public $task_editor;
    public $task_auditor;
    public $task_comments;
    public $task_reason;

    public function tableName()
    {
        return 'blog_task';
    }

    /**
	 * @return array validation rules for model attributes.
	 */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, blog_topic, focus_keyword,created_on,target_submission_date,task_action,task_status,editor_id,writer_id,auditor_id,task_writer,task_editor,task_auditor', 'safe'),
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
            'actiondetails' => array(self::BELONGS_TO, 'BlogTaskAction', 'action_id'),
            'editordetails' => array(self::BELONGS_TO, 'User', 'editor_id'),
            'writerdetails' => array(self::BELONGS_TO, 'User', 'writer_id'),
            'auditordetails' => array(self::BELONGS_TO, 'User', 'auditor_id'),
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
        $criteria=new CDbCriteria;
        $criteria->compare('t.id',$this->id,true);
        $criteria->with = array('actiondetails','writerdetails','editordetails','auditordetails');
        $criteria->compare('blog_topic',$this->blog_topic,true);
        $criteria->compare('focus_keyword',$this->focus_keyword,true);
        $criteria->compare('created_on',$this->created_on,true);
        $criteria->compare('target_submission_date',$this->target_submission_date,true);
        $criteria->compare('actiondetails.action',$this->task_action,true);
        $criteria->compare('actiondetails.status',$this->task_status,true);
        $criteria->compare('editor_id',$this->editor_id,true);
        $criteria->compare('writer_id',$this->writer_id,true);
        $criteria->compare('auditor_id',$this->auditor_id,true);
        $criteria->compare('writerdetails.name',$this->task_writer,true);
        $criteria->compare('editordetails.name',$this->task_editor,true);
        $criteria->compare('auditordetails.name',$this->task_auditor,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,

            'sort'=>array(
                'attributes'=>array(
                    'task_action'=>array(
                        'asc'=>'actiondetails.action',
                        'desc'=>'actiondetails.action DESC',
                    ),
                    'task_status'=>array(
                        'asc'=>'actiondetails.status',
                        'desc'=>'actiondetails.status DESC',
                    ),
                    'task_auditor'=>array(
                        'asc'=>'auditordetails.name',
                        'desc'=>'auditordetails.name DESC',
                    ),
                    'task_editor'=>array(
                        'asc'=>'editordetails.name',
                        'desc'=>'editordetails.name DESC',
                    ),
                    'task_writer'=>array(
                        'asc'=>'writerdetails.name',
                        'desc'=>'writerdetails.name DESC',
                    ),
                    '*',
                ),
            ),

        ));

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
