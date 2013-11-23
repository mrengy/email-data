<html>
<head>
  <title>OAuth2 IMAP example with Gmail</title>
</head>
<body>

<?php
/** initial vars **/
$commonWords = array(
	'mrengy@gmail.com',
	'emily.ustach@gmail.com',
	'caitlin.saharek@gmail.com',
	'wrote',
	'mon',
	'tue',
	'wed',
	'thu',
	'fri',
	'sat',
	'sun',
	'jan',
	'feb',
	'mar',
	'apr',
	'may',
	'jun',
	'jul',
	'aug',
	'sep',
	'oct',
	'nov',
	'dec',
	'2010',
	'2011',
	'2012',
	'2013',
	'www.mike-eng.com',
	'mike',
	'eng',
	'www.linkedin.com/in/mikeeng401',
	'interaction',
	'designer',
	'skype',
	'mrengy',
	'michael',
	'kolendowicz',
	'michael',
	'401-234-4611',
	'805-234-1032',
	'sent',
	'AM',
	'PM',
	'sent',
	'from',
	'my',
	'iphone',
	);

foreach ($commonWords as &$word){
	$word = '/\b' . preg_quote($word, '/') . '\b/i';
}

/*
//adding length filter to end of commonWords array
array_push($commonWords, '~\b\S{30,}\b~');
*/

/**
 * Given an open and authenticated IMAP connection, displays some basic info
 * about the INBOX folder.
 */
function showInbox($mailbox, $commonWords) {
  /**
   * Print the INBOX message count and the subject of all messages
   * in the INBOX
   */
  /*
	$storage = new Zend_Mail_Storage_Imap($imap);

  include 'header.php';
  echo '<h1>Total messages: ' . $storage->countMessages() . "</h1>\n";


  echo 'First five messages: <ul>';
  for ($i = 1; $i <= $storage->countMessages() && $i <= 5; $i++ ){
    echo '<li>' . htmlentities($storage->getMessage($i)->subject) . "</li>\n";
  }
  echo '</ul>';
	*/
  /**
	*Get message IDs from Jack Smooth
  */
	//print_r($imap);
	$smoothIds = imap_search($mailbox, 'FROM "michael.kolendowicz@gmail.com"');
	/*
	echo '<br/>';
	echo 'Message IDs from Jack Smooth: ';
	echo '<br />';
	print_r($smoothIds);
	*/

	echo '<br />';
	echo 'Number of smooth messages: ';
	echo(count($smoothIds));
	
	//output each message individually
	/*
	foreach ($smoothIds as $smooth_id){
		$email_info = imap_fetch_overview($mailbox,$smooth_id,0);
        $message = imap_fetchbody($mailbox,$smooth_id,2);
        echo "Subject: " . $email_info[0]->subject . "\n";
        echo "Message: " . $message . "\n";
	}
	*/
	
	//output aggregate message
	$messageAggregate = '';
	
	foreach ($smoothIds as $smooth_id){
       $message = imap_fetchbody($mailbox,$smooth_id,2);
       $messageAggregate .= $message;
	}
	
	//$messageFilteredOnce = preg_replace('~\b\S{30,}\b~', '', $messageAggregate);
	//$messageFilteredTwice = preg_replace($commonWords, '', $MessageFilteredOnce);
	
	//$messageNoTags = strip_tags($messageAggregate);
	$messageFiltered = preg_replace($commonWords, '', $MessageNoTags);
	echo $messageFiltered;
}

/**
 * Tries to login to IMAP and show inbox stats.
 */
function tryImapLogin($email, $password, $commonWords) {
  /**
   * Make the IMAP connection and send the auth request
   */
/*
  $imap = new Zend_Mail_Protocol_Imap('imap.gmail.com', '993', true);
  if (oauth2Authenticate($imap, $email, $password)) {
    echo '<h1>Successfully authenticated!</h1>';
    showInbox($imap);
  } else {
    echo '<h1>Failed to login</h1>';
  }
*/
	$imap_host = "{imap.gmail.com:993/imap/ssl}";
	$imap_folder = "INBOX"; //it's what is called label in Gmail
	
	print_r($email);
	
	$mailbox = imap_open($imap_host . $imap_folder,$email,$password) or die('Failed to open connection with Gmail: ' . imap_last_error());
	
	if($mailbox){
		echo '<h1>Successfully authenticated!</h1>';
		showInbox($mailbox, $commonWords);		
	}
	
}

/**
 * Displays a form to collect the email address and access token.
 */
function displayForm($email, $password) {
  echo <<<END
<form method="POST" action="index.php">
  <h1>Please enter your e-mail address: </h1>
  <input type="text" name="email" value="$email"/>
  <p>
  <h1>Please enter your password: </h1>
  <input type="text" name="password" value="$password"/>
  <input type="submit"/>
</form>
<hr>
END;
}

$email = $_POST['email'];
$password = $_POST['password'];

displayForm($email, $password);

//phpinfo();

if ($email && $password) {
  tryImapLogin($email, $password, $commonWords);
}


?>
</body>
</html>
