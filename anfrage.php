<?php
$_CONF['mail'] = 'your email here';
//$_CONF['bc'] = 'bc email here';
$_CONF['Site'] = 'your domain here';
$_CONF['logo'] = 'logo.png';

$heute = strtotime('now');
$fromPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
$requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;

$ips = array (
	'xxx.xxx.xxx.xxx',
	'xxx.xxx.xxx.xxx'
);

if (in_array ($_SERVER['REMOTE_ADDR'], $ips)) {
	header('Location: ' . $fromPage );
	die;
}

$inter = array("Name", "Firma", "Email", "Telefon", "cc", "Anmerkung");
$trace = false;
if ($trace == true) {
	echo '<pre> TRACE <br />'; print_r ($_POST); print_r ($_FILES); echo '</pre>';
	echo '<pre> From Page ' . $fromPage . '</pre>';
	echo '<pre> Remote_addr ' . $_SERVER["REMOTE_ADDR"] . '</pre>';
	echo '<pre> Request URI ' . $requestUri . '</pre>';
	die("Die Nachricht wurde nicht versendet.");
}

$isName = false; 
$isEmail = false;
$isPhone = false;

if (isset ($_POST ['reqname'])) {
	$inter ['Name'] = strip_tags (htmlspecialchars  ($_POST['reqname']));
	$isName = true;
}
if (isset ($_POST ['reqfirma'])) {
	$inter ['Firma'] = strip_tags (htmlspecialchars  ($_POST['reqfirma']));
}


if (isset ($_POST ['reqemail'])) { 
	$inter ['Email'] = strip_tags (htmlspecialchars  ($_POST['reqemail']));
	$str = $inter ['Email'] ;
	if (!preg_match("/@{1}/", $str) || !preg_match("/\.+/", $str)) {
		$isEmail = false;
	} else {
		$isEmail = true;
	}
}
if (isset ($_POST ['reqphone'])) { 
	$inter ['Telefon'] = $_POST['reqphone'];
	$str = str_replace( ' ', '', $inter ['Telefon'] ); 
	$str = str_replace( '+', '', $str ); 
	$str = str_replace( '.', '', $str ); 
	$str = str_replace( '(', '', $str ); 
	$str = str_replace( ')', '', $str );
	$str = str_replace( '-', '', $str );
	$str = str_replace( '/', '', $str );
	if (!preg_match("/[0-9]{6,22}/", $str)) {
		$isPhone = false; 
	} else {
		$isPhone = true;
	}
}

if (isset ($_POST ['reqtext'])) $inter ['Anmerkung'] = strip_tags ($_POST['reqtext']);

if ($isName && ($isEmail || $isPhone)) {
	$action = 'Senden';
	$sentemail = array();
	$sentemail = HTMLcontactemail();
	if ($trace) {
		echo '<pre>sentemail message</pre>';
		echo '<pre> 0' . utf8_decode(urldecode($sentemail[0])) . '</pre>';
		echo '<pre> 1' . utf8_decode(urldecode($sentemail[1])) . '</pre>';
		echo '<pre> 2' . utf8_decode(urldecode($sentemail[2])) . '</pre>';
		echo '<pre> 3' . utf8_decode(urldecode($sentemail[3])) . '</pre>';
		echo '<pre> 4' . utf8_decode(urldecode($sentemail[4])) . '</pre>';
		echo '<pre> 5' . utf8_decode(urldecode($sentemail[5])) . '</pre>';
	}
	$headers[] = "From: " . $inter ['Email'];
	if (isset ($_POST['reqcc'])) $headers[] = "Cc:" .  $inter ['Email'];
	$headers[] = "Bcc:" . $_CONF['bc'];
	$headers[] = "Return-Path: " . $_CONF['mail'];
	$headers[] = "X-Mailer: PHP-Mailer(" . $_CONF['Site'] . ")";
	$headers[] = "MIME-Version: 1.0";
	$headers[] = "Content-Type: text/html; charset=utf-8";
	$headers[] = "Content-transfer-encoding: Quoted-printable";
	$headers = join("\n", $headers);
	mail($_CONF['mail'], utf8_decode(urldecode($sentemail[1])), utf8_decode(urldecode($sentemail[2])), $headers)
		or die("Die Nachricht konnte nicht versendet werden.");
	header('Location: ' . $fromPage . '/?danke');
	echo "<script>location.href='" . $fromPage . "/?danke';</script>";
	exit;
} else {
	header('Location: ' . $fromPage . '/?missing');
}

function HTMLcontactemail () {
	global $_CONF;
	global $inter;
	global $trace;

	$from = $inter ['Email'] ;
	if ($trace) {
		echo '<pre>' . $inter ['Email'] .'</pre>';
		echo '<pre>' . $inter ['Name'] .'</pre>';
		echo '<pre>' . $inter ['Anmerkung'] .'</pre>';
		echo '<pre>' . $inter ['Firma'] .'</pre>';
		echo '<pre>' . $inter ['Telefon'] .'</pre>';
	}
	$subject = "++ Anfrage via " . $_CONF['Site'] . " " . $inter ['Name'] . ' (' . $inter ['Firma'] . ') ++';
	$subject = strip_tags (htmlspecialchars  (stripslashes ($subject)));
	$subject = substr ($subject, 0, strcspn ($subject, "\r\n"));

	$message = '<table style="padding: 20px;background:rgb(250,250,250);border:1px solid gainsboro;border-radius:20px">';
	$message .= '<tr><td colspan="2" style=""></td></tr>';
	$message .= '<tr><td colspan="2" style="padding:8px;">Diese Anfrage wurde über ' . $_CONF['Site'] . ' gesendet</td></tr>';
	$message .= '<tr><td style="padding:8px;"><b>Von</b> ' . $inter ['Name']  . '</td><td style="padding:8px;"><b>Firma</b> ' . $inter ['Firma'] . '</td></tr>';
	$message .= '<tr><td style="padding:8px;"><b>Meine Emailadresse</b> ' . $inter ['Email'] . '</td><td style="padding:8px;"><b>Telefon</b> ' . $inter ['Telefon'] . '</td></tr>';
	$message .= '<tr><td colspan="2" style="padding:8px;"><b>Anmerkung</b> ' . $inter ['Anmerkung'] . '</td></tr>';

	$message .= '<tr><td colspan="2" style="padding:8px; text-align: center;">-------•••------</td></tr>';

	$message .= '<tr><td style="color:#666"><b>Von Server </b> ' . $_SERVER["SERVER_NAME"] . '</td><td style="color:#666"><b>IP (REMOTE_ADDR)</b> ' . $_SERVER["REMOTE_ADDR"] . '</td></tr>';
	$datum = strftime("Angefragt %d.%m.%y", $_SERVER["REQUEST_TIME"]);
	$message .= '<tr><td style="color:#666"><b>Abgesendet am</b> ' .  $datum  .  '</td><td style="color:#666"><b>Sprache </b> ' . $_SERVER["HTTP_ACCEPT_LANGUAGE"] . '</td></tr>';
	$message .= '<tr><td colspan="2" style="color:#666"><b>Von Seite </b> ' . $_SERVER["HTTP_REFERER"] . '</td></tr>';
	$message .= '</table>';
	if ($trace) {
		echo $message;
	}
	return array ($from, utf8_encode($subject),utf8_encode($message));
}
?>
