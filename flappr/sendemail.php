<?
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	$from = "From: ".$_POST['name']." <".$_POST['email'].">";
	$to = "bdefore@gmail.com";
	if (isset($_POST['reply']) && $_POST['reply']=="1") $message .= "\n\nReply Requested";

	/* and now mail it */
	$mail_success = mail($to, $subject, $message, $from);
	if ($mail_success == true) {
		echo '&faultCode=0&';
	} else {
		echo '&faultCode=1&';
	}
?>
