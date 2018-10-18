<?php
class AnalyticsController extends CController
{

  /*Change
        By: Abhi
        Date: 15/2/2018 1:00PM
        Change: To save user new sessions
   */
  	public function actionCreateUserSession() {
  	Yii::log('inside actionCreateUserSession');
  		$clientId = isset($_POST['webUniqueId']) ? $_POST['webUniqueId'] : "";
  		$osDetails = isset($_POST['osDetails']) ? $_POST['osDetails'] : "";
  	//	$device = isset($_POST['device']) ? $_POST['device'] : "";
  		Yii::log('User session creation to log data  '.$clientId.'', 'info', 'application.actionCreateUserSession');
  		if(!empty($clientId))
  		{
  			if($this->createNewSessionForUser($clientId,$osDetails)){
  				echo 'successfully created';
  			}
  			else {
  				echo 'failure in creation';
  				Yii::log('Failed to save user session data info', 'application.actionCreateUserSession');
  			}

  		}
  		else
  		{
  			Yii::log('api client unique id was null to create session', 'info', 'application.actionCreateUserSession');
  		}

  	}

  	 /*Change
        By: Abhi
        Date: 15/2/2018 1:00PM
        Change: To update user existing  sessions
   */


  	public function actionUpdateUserSessionLog() {
  		Yii::log('inside actionUpdateUserSessionLog2');

  		$clientId = isset($_POST['webUniqueId']) ? $_POST['webUniqueId'] : "";
  		$osDetails = isset($_POST['osDetails']) ? $_POST['osDetails'] : "";

  		$location_detected = isset($_POST['location_detected']) ? $_POST['location_detected'] : "";
  		$identifiedLocation = isset($_POST['identifiedLocation']) ? $_POST['identifiedLocation'] : "";
  		$identifiedCity = isset($_POST['identifiedCity']) ? $_POST['identifiedCity'] : "";
  		$permissionAllowWeb = isset($_POST['permissionAllowWeb']) ? $_POST['permissionAllowWeb'] : "";
  		$lat = isset($_POST['lat']) ? $_POST['lat'] : "";
  		$lng = isset($_POST['lng']) ? $_POST['lng'] : "";
  		$duration = SESSION_DURATION;
  		if($location_detected ==0) {
  			$lat = NULL;
  			$lng = NULL;
  			$identifiedLocation = NULL;
  			$identifiedCity = NULL;
  		}

        try{
  		    if(isset(Yii::app()->session['locality'])){
  		    $location_detected = 1;
  		    $identifiedLocation = Yii::app()->session['sublocality'];
  		    $identifiedCity = Yii::app()->session['locality'];
  		    $lat = Yii::app()->session['lat'];
  		    $lng = Yii::app()->session['lon'];
  		    $permissionAllowWeb = "Allow,Success";
  		    }
  		}
  	    catch(Exception $ex) {
  	    Yii::log("exception ".$ex->getMessage());
  	    }


  		Yii::log('location_detected '.$location_detected.', $identifiedLocation '.$identifiedLocation.', $identifiedCity'.$identifiedCity.
  			', permissionAllowWeb '.$permissionAllowWeb.', lat '.$lat.', lng '.$lng);
  		if(!empty($clientId) && $clientId!="")
  		{
  			Yii::log('client id is not empty');
  			$query = 'SELECT * FROM user_session where user_uniqueId ="'.$clientId.'" ORDER BY start_time DESC  LIMIT 1';
  			$model = yii::app()->analyticsDb->createCommand($query)->queryAll();
       		if(empty($model) || (!empty($model) && (strtotime($model[0]['start_time']) < strtotime('-'.$duration.' minutes')))) {
       				Yii::log('inside actionUpdateUserSessionLog 22');
       			Yii::log('inside if, model is < '.$duration.' minutes');
       		$detect = new Mobile_Detect;
  			$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'Tablet' : 'Phone') : 'Desktop');

             if(!isset($_SESSION))
                 {
                     session_start();
                 }
               $session_id = session_id();
  			$getSessionIdQuery= "SELECT * FROM user_session WHERE session_id LIKE '".$session_id."' AND start_time > (SELECT NOW() - INTERVAL 30 MINUTE) ORDER BY id DESC LIMIT 1";
             $SessionIdResult= Yii::app()->analyticsDb->createCommand($getSessionIdQuery)->queryAll();
             if(sizeof($SessionIdResult) > 0){
             $sessionId = $SessionIdResult[0]['id'];
             $usersession = UserSessionLog::model()->findByPk($sessionId);
             }
             else{
             $usersession = new UserSessionLog;
             }

  			//$usersession = new UserSessionLog;
  			//$usersession->session_id= md5(uniqid(rand(),true));
  			$usersession->session_id= $session_id;
  			$usersession->user_uniqueId = $clientId;
  			$usersession->user_type = 'Web User';
  			$usersession->device_type = $deviceType;
  			$usersession->os_details = $osDetails;
  			if(isset(Yii::app()->session['userId'])) {
  				$usersession->userId = Yii::app()->session['userId'];
  			}
  			else {
  				$usersession->userId = 0;
  			}
  			$usersession->lat = $lat;
  			$usersession->lon = $lng;
  			$usersession->identified_city = $identifiedCity;
  			$usersession->identified_location = $identifiedLocation;
  			$usersession->location_detected = $location_detected;
  			$usersession->permission_allow_web = $permissionAllowWeb;
  			$usersession->start_time = date('Y-m-d H:i:s');
  			$usersession->save();
       		}
       		else {

       		//handling change in the session id
       		if(!isset($_SESSION))
                 {
                     session_start();
                 }
                $session_id = session_id();
                $getSessionIdQuery= "SELECT * FROM user_session WHERE session_id LIKE '".$session_id."' AND start_time > (SELECT NOW() - INTERVAL 30 MINUTE) AND (user_uniqueId IS NULL OR user_uniqueId like '')ORDER BY id DESC LIMIT 1";
                $SessionIdResult= Yii::app()->analyticsDb->createCommand($getSessionIdQuery)->queryAll();
                if(sizeof($SessionIdResult) > 0){
                $sessionId = $SessionIdResult[0]['id'];
                $usersession = UserSessionLog::model()->findByPk($sessionId);

                $usersession->session_id= $session_id;
                $usersession->user_uniqueId = $clientId;
                $usersession->user_type = 'Web User';
                $usersession->os_details = $osDetails;
                if(isset(Yii::app()->session['userId'])) {
                    $usersession->userId = Yii::app()->session['userId'];
                }
                else {
                    $usersession->userId = 0;
                }
                $usersession->lat = $lat;
                $usersession->lon = $lng;
                $usersession->identified_city = $identifiedCity;
                $usersession->identified_location = $identifiedLocation;
                $usersession->location_detected = $location_detected;
                $usersession->permission_allow_web = $permissionAllowWeb;
                $usersession->save();
                }

       		Yii::log('inside actionUpdateUserSessionLog 33');

  				if(isset(Yii::app()->session['userId'])) {
  					$userId = Yii::app()->session['userId'];
  				}
  				else {
  					$userId = 0;
  				}
  				if($model[0]['userId']!=$userId && $userId!='0'){
  				    $query='UPDATE user_session SET userId='.$userId.' where id='.$model[0]['id'].' ORDER BY start_time DESC  LIMIT 1';
  				    $rowsUpdated=Yii::app()->analyticsDb->createCommand($query)->execute();
  				}
       			if(($model[0]['location_detected'] != $location_detected && $location_detected!='0')  && ($model[0]['permission_allow_web']!=$permissionAllowWeb && $permissionAllowWeb!='Not Available')) {
  					$query='UPDATE user_session SET location_detected=\''.$location_detected.'\',permission_allow_web=\''.$permissionAllowWeb.'\',lat=\''.$lat.'\' ,lon=\''.$lng.'\',identified_city=\''.$identifiedCity.'\',identified_location=\''.$identifiedLocation.'\' where id='.$model[0]['id'].' ORDER BY start_time DESC  LIMIT 1';
  					Yii::log('model is updating....');
  					$rowsUpdated=Yii::app()->analyticsDb->createCommand($query)->execute();
       				//Yii::log("# of rows updt : ".$rowsUpdated);

       			}
  				else{
  					//Yii::log("Nothing to update");
  				}
       		}
       	}

  	}

  	public function createNewSessionForUser($clientId,$osDetails) {
  			//$device_type = CommonUtil::getDeviceType();
  			$detect = new Mobile_Detect;
  			$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'Tablet' : 'Phone') : 'Desktop');
  			Yii::log('device is '.$deviceType);
  			Yii::log('os is '.$osDetails);

  			if(!isset($_SESSION))
                 {
                     session_start();
                 }
               $session_id = session_id();
            $getSessionIdQuery= "SELECT * FROM user_session WHERE session_id LIKE '".$session_id."' AND start_time > (SELECT NOW() - INTERVAL 30 MINUTE) ORDER BY id DESC LIMIT 1";
             $SessionIdResult= Yii::app()->analyticsDb->createCommand($getSessionIdQuery)->queryAll();
             if(sizeof($SessionIdResult) > 0){
             $sessionId = $SessionIdResult[0]['id'];
             $usersession = UserSessionLog::model()->findByPk($sessionId);
             }
             else{
             $usersession = new UserSessionLog;
             }

  			//$usersession = new UserSessionLog;
  			$usersession->session_id= $session_id;
  			$usersession->user_uniqueId = $clientId;
  			$usersession->user_type = 'Web User';
  			$usersession->device_type = $deviceType;
  			$usersession->os_details = $osDetails;
  			$usersession->userId = 0;
  			$usersession->start_time = date('Y-m-d H:i:s');
  			if($usersession->save()) {
  				Yii::log('saved');
  				return true;
  			}
  			else {
  				Yii::log('erros in saved');
  				return false;
  			}
  	}
  	public function actionTrackingTask(){
              try {
                     $captureType = isset($_POST['captureType']) ? $_POST['captureType'] : "";
                     $url1 = isset($_POST['URL']) ? $_POST['URL'] : "";
                     $length = strlen($url1);
                     $url;
                     if(strpos($url1,'?') == true){
                            $urlarr = explode("?",$url1);
                            $url = $urlarr[0];
                     }else if($url1[strlen($url1)-1] == '/'){
                            $url = substr($url1,0,$length-1);
                     }else{
                            $url = $url1;
                     }
                     $getpageurl= "SELECT * FROM page where url like '".$url."'";
                     $pageresult= Yii::app()->analyticsDb->createCommand($getpageurl)->queryAll();
                     $pageId;
                     $eType = 'PAGE_VIEW';
                     if(sizeof($pageresult) > 0){
                            $pageId = $pageresult[0]['id'];
                     } else {
                            $page = new PageDetails();
                            $page->url = $url;
                            $page->insert();
                            $pageId = $page->id;
                     }
                     if(!isset($_SESSION))
                                    {
                                      session_start();
                                    }
                     if($captureType == 'START'){
                           $sessionID = $this->getSessionID();
                           $startTime = date('Y-m-d H:i:s');
                           $session = new UserSessionPage();
                           $session->start_time = $startTime;
                           $session->page_id = $pageId;
                           $session->event_type = $eType;
                           $session->user_session_id = $sessionID;
                           $session->insert();
                           $session_page_id = $session->id;
                           Yii::app()->session['user_session_page_id_'.$pageId] = $session_page_id;
                           } else if( $captureType == 'END'){
                           $endTime = date('Y-m-d H:i:s');
                           $user_session_page_id = Yii::app()->session['user_session_page_id_'.$pageId];
                           $sessionTask = UserSessionPage::model()->findByPk($user_session_page_id);
                           $sessionTask->end_time = $endTime;
                           $sessionTask->update();
                     }
                   }catch(Exception $e){
                        MedinfiExceptionNotifier::notifyException($ex);
                    }
                }
                public function actionTrackEvent(){
                  try {

                         $eventData = isset($_POST['eventData']) ? $_POST['eventData'] : "";
                         $endTime = date('Y-m-d H:i:s');
                         $startTime = date('Y-m-d H:i:s');
                         $url1 = "";

                         if($eventData != ""){
                         Yii::log("$eventData: ".$eventData);
                         $dataObj = json_decode($eventData, true);
                         $url1 = $dataObj['eventUrl'];
                         $eType = $dataObj['eventType'];
                         $sessionID = $this->getSessionID();

                         $length = strlen($url1);
                         $url;
                         if(strpos($url1,'?') == true){
                                $urlarr = explode("?",$url1);
                                $url = $urlarr[0];
                         }else if($url1[strlen($url1)-1] == '/'){
                                $url = substr($url1,0,$length-1);
                         }else{
                                $url = $url1;
                         }
                         $getpageurl= "SELECT * FROM page where url LIKE '".$url."'";
                         $pageresult= Yii::app()->analyticsDb->createCommand($getpageurl)->queryAll();
                         $pageId;
                         if(sizeof($pageresult) > 0){
                                $pageId = $pageresult[0]['id'];
                         } else {
                                $page = new PageDetails();
                                $page->url = $url;
                                $page->insert();
                                $pageId = $page->id;
                         }
                         $session = new UserSessionPage();
                         $session->start_time = $startTime;
                         $session->end_time = $endTime;
                         $session->page_id = $pageId;
                         $session->event_type = $eType;
                         $session->	user_session_id = $sessionID;
                         $session->insert();
                         }

                       }catch(Exception $e){
                            MedinfiExceptionNotifier::notifyException($e);
                        }
                    }

                     public function getSessionID(){

                      if(!isset($_SESSION))
                            {
                                session_start();
                            }
                          $session_id = session_id();

                         $getSessionIdQuery= "SELECT * FROM user_session WHERE session_id LIKE '".$session_id."' AND start_time > (SELECT NOW() - INTERVAL 30 MINUTE) ORDER BY id DESC LIMIT 1";
                         $SessionIdResult= Yii::app()->analyticsDb->createCommand($getSessionIdQuery)->queryAll();
                         $sessionId = 0 ;
                         if(sizeof($SessionIdResult) > 0){
                                $sessionId = $SessionIdResult[0]['id'];
                         } else {
                                 $detect = new Mobile_Detect;
                           		 $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'Tablet' : 'Phone') : 'Desktop');
                                 $usersession = new UserSessionLog;
                                 $usersession->session_id = $session_id;
                                 $usersession->user_type = 'Web User';
                                 $usersession->device_type = $deviceType;
                                 $usersession->userId = 0;
                                 $usersession->start_time = date('Y-m-d H:i:s');
                                 if($usersession->save()) {
                                   $sessionId = $usersession->id;
                                 }
                         }
                      return $sessionId;
                     }
}

?>