<?php

include('GoogleVoice.php');

// Provide your credentials. NOTE: Full email address required.
$accountEmail = 'your.email@domain.com';
$password = 'password';
$recoveryEmail = '';   // Optional: Google may request this.

$gv = new GoogleVoice($accountEmail, $password, $recoveryEmail);

// Call a phone from one of your forwarding phones.
$gv->callNumber('9995551212', '5558675309', 'mobile');

// Cancel an in-progress call placed using callNumber.
$gv->cancelCall('9995551212', '5558675309', 'mobile');

// Send an SMS to a phone number.
$gv->sendSMS('9995551212', 'Sending a message!');

// Get all unread SMSs from your Google Voice inbox.
$sms = $gv->getUnreadSMS();
$msgIDs = array();
foreach($sms as $s) {
	echo 'Message from: '.$s->phoneNumber.' on '.$s->displayStartDateTime.': '.$s->messageText."<br><br>\n";
	if(!in_array($s->id, $msgIDs)) {
		// Mark the message as read.
		$gv->markMessageRead($s->id);
		$msgIDs[] = $s->id;
	}
}

// Get all unread messages from your Google Voice voicemail.
$voice_mails = $gv->getUnreadVoicemail();
$msgIDs = array();
foreach($voice_mails as $v) {
	echo 'Message from: '.$v->phoneNumber.' on '.$v->displayStartDateTime.': '.$v->messageText."<br><br>\n";
	if(!in_array($v->id, $msgIDs)) {
		// Mark the message as read.
		$gv->markMessageRead($v->id);
		$msgIDs[] = $v->id;
	}
}

// Download all unread Google Voice voicemails as individual MP3 files.
$voice_mails = $gv->getUnreadVoicemail();
$msgIDs = array();
foreach($voice_mails as $v) {
	// Uncomment next line if you want message texts displayed as in previous example.
	// echo 'Message from: '.$v->phoneNumber.' on '.$v->displayStartDateTime.': '.$v->messageText."<br><br>\n";
	// Download individual voicemail.
	$mp3 = $gv->getVoicemailMP3($v->id);
	// construct mp3 filename using message id as filename.
	$mp3file = $v->id.".mp3";
	// write mp3 file to disk.
	$fh = fopen($mp3file, 'w') or die("can't open file");
	fwrite($fh, $mp3);
	fclose($fh);
	if(!in_array($v->id, $msgIDs)) {
		// Mark the message as read.
		$gv->markMessageRead($v->id);
		$msgIDs[] = $v->id;
	}
}

?>
