<?php

	if(!isset($_REQUEST['token']))
	{
		die('Please provide a token in the URL');
	}

	$Token = urldecode($_REQUEST['token']);

	require 'Coupilia.php';

	$Coupilia = new Coupilia($Token);
	$Result = $Coupilia->get(array
	(
		'recordset' => 'test'
	), array
	(
		'website' => true
	));

	echo '<pre>' . count($Result) . ' results:' . "\r\n\r\n" . print_r($Result, 1) . '</pre>';
