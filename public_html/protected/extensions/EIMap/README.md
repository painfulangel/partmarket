##Overview
The EIMap Class allows to have easy access to imap extension functions to read and parse messages from a mailbox.

###Requirements
 - [IMAP PHP](http://www.php.net/manual/es/book.imap.php) Extension 

###How to use
Extract and place the contents of the package into your extensions folder (you can place it where ever you wish, the extensions folder is for the sake of the example). 

####Examples
**reading unseen emails**  

	Yii::import('ext.EImap.EIMap', true);
	
	// please replace the server path to the one of your
	// inbox + your username and password
	$imap = new EIMap('{imap.server.com:993}/imap/ssl}INBOX', 'yourusername', 'yourpassword');
	
	if($imap->connect())
	{
		// we are set lets search for unseen
		$unseen = $imap->searchmails( EIMap::SEARCH_UNSEEN );
		
		if($unseen && is_array($unseen)) // do we have any?
		{
			// put new ones first
			rsort($unseen);
			
			foreach($unseen as $msgId)
			{
				$mail = $imap->getMail( $msgId );
				echo '<pre>'.( CVarDumper::dumpAsString( $mail ) ).'</pre>';
			}
		}
		$imap->close(); // close connection		
	}
	
**reading mails overviews**  

	Yii::import('ext.EImap.EIMap', true);
	
	// please replace the server path to the one of your
	// inbox + your username and password
	$imap = new EIMap('{imap.server.com:993}/imap/ssl}INBOX', 'yourusername', 'yourpassword');
	
	if($imap->connect())
	{
		// get mailbox info
		$mailboxCheck = $imap->getCheck();
		
		// read all messages overviews
		$result = $imap->getMailboxOverview("1:{$mailboxCheck->Nmsgs}");

		// if we have any display them
		foreach($result as $overview)
		{

			echo "#{$overview->msgno} ({$overview->date}) - From: {$overview->from} {$overview->subject}\n";
			echo "size: {$overview->size}";
			echo '<pre>'.CVarDumper::dumpAsString($overview).'</pre>';
			
			// sender again please?
			echo '<pre>'.CVarDumper::dumpAsString($imap->getSender($overview->msgno)).'</pre>';
		}
		$imap->close(); // close connection		
	}
	
====

> [![Clevertech](http://clevertech.biz/images/slir/w54-h36-c54:36/images/site/index/home/clevertech-logo.png)](http://www.clevertech.biz)    
well-built beautifully designed web applications  
[www.clevertech.biz](http://www.clevertech.biz)
