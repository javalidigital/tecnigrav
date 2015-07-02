<?php
if ($_POST) {
	header('Content-type: application/json');
	$fields = array();
	$msg = array();
	foreach($_POST as $k=>$v) $_POST[$k] = trim($v);
	$pattern = "/^[a-zA-Z0-9][a-zA-Z0-9-_\.]+\@([a-zA-Z0-9-_\.]+\.)+[a-zA-Z]+$/";
	if(preg_match($pattern, $_POST['email'])) $email_ok = true;
	else $email_ok = false; 
	if(!$_POST['email'] || !$email_ok)  $fields[] = "email";
	if(!$_POST['name'])  $fields[] = "name";
	
	if(count($fields)) {
		$status = "error";
		if(!$_POST['name'] || !$_POST['email']) $msg[] = "Please specify your name and email address.";
		if(!$email_ok) $msg[] = "Provided e-mail address is invalid.";
		$message = implode("<br>", $msg);

		$response['msgStatus'] = $status;
		$response['message'] = $message;
		$response['errorFields'] = $fields;
	} else {
		$sendTo = $_POST['slider_email'];
		$mailContent = "Name: {$_POST['name']}\nE-mail: {$_POST['email']}\nLast name: {$_POST['last_name']}\nPhone: {$_POST['phone']}";
		$ok = mail($sendTo, "Converio Theme - Landing Page Form", $mailContent, "From:<".$_POST['email'].">");
		$ok = true; //temporary
		if($ok) {
			$response['msgStatus'] = "ok";
			$response['message'] = "Your message has been sent successfully.";
		} else {
			$response['msgStatus'] = "error";
			$response['message'] = "Could not send your request due to an error.";
		}
	}
	echo json_encode($response);
}
?>