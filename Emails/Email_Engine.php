<?php

function sendMyEmails($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message,$cc)
{
    $wasSent = true;
	//Check if file was attached
	if($filename != ""){
		$file = $path.$filename;
	    $file_size = filesize($file);
	    $handle = fopen($file, "r");
	    $content = fread($handle, $file_size);
	    fclose($handle);
	    $content = chunk_split(base64_encode($content));
	    $uid = md5(uniqid(time()));
	    $name = basename($file);
	}

    //$message .= "Please, do not respond this message. This mail box is not monitored. Feel free to contact us at helpdesk@cpj.com.";

	//Setting Format
    $header = "From: ".trim($from_name)." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "CC: ".$cc."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
     //NO Attached File
    if($filename != ""){   
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
	}

    $header .= "Content-type:text/html; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";

    //NO Attached File
    if($filename != ""){
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
	}

	

    if (@mail($mailto, $subject, "", $header)) {
        $wasSent = true; // or use booleans here
    } else {
        $wasSent = false;
    }

    return $wasSent;
}

?>