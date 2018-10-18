<?php
//to send html emails
class SendEmail {

    public static function sendmail($mailto, $from_mail, $from_name, $replyto, $subject, $message,$cc,$bcc) 
    {

        $headers="From: ".$from_name." <".$from_mail.">\r\n";
        $headers.="Reply-To: ".$replyto."\r\n";
        $headers.="CC: ".$cc."\r\n";
        $headers.="BCC: ".$bcc."\r\n";
        $headers.='MIME-Version: 1.0' . "\r\n";
        $headers.='Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers.="Return-Path:<".$from_mail.">\r\n";
        mail($mailto,$subject,$message,$headers);

    }



    public static function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message,$deletefile=false) 
    {
        $file = $path.$filename;
        $file_size = filesize($file);
        $handle = fopen($file, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $content = chunk_split(base64_encode($content));
        $uid = md5(uniqid(time()));
        $name = basename($file);
        $header = "From: ".$from_name." <".$from_mail.">\r\n";
        $header .= "Reply-To: ".$replyto."\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
        $header .= "This is a multi-part message in MIME format.\r\n";
        $header .= "--".$uid."\r\n";
        $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
        $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $header .= $message."\r\n\r\n";
        $header .= "--".$uid."\r\n";
        $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
        $header .= "Content-Transfer-Encoding: base64\r\n";
        $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
        $header .= $content."\r\n\r\n";
        $header .= "--".$uid."--";
        mail($mailto, $subject, "", $header);   

        if($deletefile==true)
        {
            unlink($file); //remove the sent file from the directory 
        }
    }


    public static function sendYiiMail($mailto, $from_mail, $from_name, $replyto, $subject, $message,$cc,$bcc)
    {
        $mail = new YiiMailer();
        $mail->IsSMTP();                                      // Set mailer to use SMTP
        $mail->Host = "email-smtp.us-east-1.amazonaws.com";  // Specify main and backup server
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username ="AKIAJ2L7EWGM2PBWXSVQ" ;                            // SMTP username
        $mail->Password = "Autg7HEGiaIaySGomcbJj88DApMn/675dNQctFJ3b+2b";
        $mail->SMTPSecure = "ssl";                            // Enable encryption, 'ssl' also accepted
        $mail->Port = "465";
        //set mail properties
        $mail->setFrom($from_mail,$from_name);
        $mail->setTo($mailto);
        $mail->setReplyTo($replyto);
        $mail->setSubject($subject);
        $mail->setCc($bcc);
        $mail->setBcc($cc);
        $mail->setBody($message, 'text/html');

        $mail->send();
    }

}