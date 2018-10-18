<?php
class MedinfiExceptionNotifier {
	
	public static function notifyException($exception) {
		if($exception && $_SERVER['HTTP_HOST']=='54.169.135.30') {
			$mailto = 'product@medinfi.com';
			$from_mail ="";
			$from_name = 'Medinfi Exception Notifier';
			$replyto="";
			$subject ="Exception in Medinfi Server Side";
			$message = $exception->getMessage()."/n/n".$exception->getTraceAsString();
			$cc='';
			$bcc='';
			SendEmail::sendYiiMail($mailto, $from_mail, $from_name, $replyto, $subject, $message,$cc,$bcc);
            
		}
	}


	}

?>