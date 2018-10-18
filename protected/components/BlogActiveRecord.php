<?php

/*
Created By :Jiji
Created on : 06-10-2016 12:15PM
This class is for returning the connection object of blog database
*/

class BlogActiveRecord extends CActiveRecord {

	 private static $blogDb = null;
	  private static $analyticsDb = null;
 
    protected static function getBlogDbConnection()
    {
        if (self::$blogDb !== null)
            return self::$blogDb;
        else
        {
            self::$blogDb = Yii::app()->wpDb;
            if (self::$blogDb instanceof CDbConnection)
            {
                self::$blogDb->setActive(true);
                return self::$blogDb;
            }
            else
                throw new CDbException(Yii::t('yii','Active Record requires a "db" CDbConnection application component.'));
        }
    }
    protected static function getAnalyticsDbConnection()
        {
            if (self::$analyticsDb !== null)
                return self::$analyticsDb;
            else
            {
                self::$analyticsDb = Yii::app()->analyticsDb;
                if (self::$analyticsDb instanceof CDbConnection)
                {
                    self::$analyticsDb->setActive(true);
                    return self::$analyticsDb;
                }
                else
                    throw new CDbException(Yii::t('yii','Active Record requires a "db" CDbConnection application component.'));
            }
        }

}

?>