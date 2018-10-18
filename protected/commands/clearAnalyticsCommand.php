<?php

class clearAnalyticsCommand extends CConsoleCommand {

/* 1. copy logs from user_session to user_session_archive
  
*/

	public function actionClearAnalytics() {

    	$connection=Yii::app()->analyticsDb;

        try {
            $count = 0;

            do{
               echo 'Clear session logs start';

               //Copy logs from user_session table to user_session_archive
               $insertQuery = $connection->createCommand("INSERT INTO user_session_archive SELECT * FROM  user_session WHERE start_time < (SELECT CURRENT_DATE - INTERVAL 30 DAY) ORDER BY id ASC LIMIT 1000")->execute();

               //Delete all archive logs from user_session table
               $deleteNotification = $connection->createCommand("Delete user_session from user_session inner join user_session_archive on user_session.id = user_session_archive.id")->execute();

               $result = $connection->createCommand("SELECT count(*) as count FROM `user_session` where start_time < (SELECT CURRENT_DATE - INTERVAL 30 DAY)")->queryAll();
               $count = $result[0]['count'];

                echo 'Count     '.$count;

            }while($count > 0);

	    }
	    catch(Exception $e)
        {
        echo 'error while copying and deleting analytics logs';
	    }


	    try {
                $count = 0;

            do{
               echo 'Clear user_session_page logs start';

               //Copy logs from user_session_page table to user_session_page_archive
               $insertQuery = $connection->createCommand("INSERT INTO user_session_page_archive SELECT * FROM  user_session_page WHERE start_time < (SELECT CURRENT_DATE - INTERVAL 30 DAY) ORDER BY id ASC LIMIT 1000")->execute();

               //Delete all archive logs from user_session_page table
               $deleteNotification = $connection->createCommand("Delete user_session_page from user_session_page inner join user_session_page_archive on user_session_page.id = user_session_page_archive.id")->execute();

               $result = $connection->createCommand("SELECT count(*) as count FROM `user_session_page` where start_time < (SELECT CURRENT_DATE - INTERVAL 30 DAY)")->queryAll();
               $count = $result[0]['count'];

                echo 'Count     '.$count;

            }while($count > 0);

        }
        catch(Exception $e)
        {
        echo 'error while copying and deleting user_session_page analytics logs';
        }

	}


}