<?php

	/* Custom PHP class for Coupilia Voucher System
	 *
	 * Author:		Ryan Boylett <http://boylett.uk/>
	 * Update:		http://boylett.uk/classes/php/coupilia/update
	 * Version:		1.0.0
	 */

	class Coupilia
	{
		public $token;
		public $type = "json";
		public $history = array();
		public $debug = false;

		public function __construct($token)
		{
			// Setup the API token on initialization
			$this->token = $token;

			return $this;
		}

		public function get($params = NULL, $type = NULL)
		{
			// If no parameters were supplied, we'll assume the user is testing the feed
			if(!$params or !is_array($params))
			{
				$params = array
				(
					"recordset" => "test"
				);
			}

			// Supply the API token
			$params["token"] = $this->token;

			// Sanitize the desired feed type (makes no real difference to the end user)
			$this->type = (preg_match("/^([\s]+)?json([\s]+)?$/i", $this->type)) ? "json" : "xml";
			if(!$type) $type = $this->type;

			// Construct the HTTP request URL
			$url = "http://www.coupilia.com/feeds/coupons_{$type}.cfm?" . http_build_query($params);

			// Add the query to our history cache
			$hist = time();
			$this->history[$hist] = array
			(
				"date" => $hist,
				"url" => $url
			);

			// Make the HTTP request (this used to use CURL, but file_get_contents is much more user-friendly)
			$response = @file_get_contents($url);

			// Clean up the response data
			if($response)
			{
				// Remove whitespace from either end of the data (slightly improves parsing speed)
				$response = trim($response);

				// Add the raw response data to the history record
				$this->history[$hist]["response"] = $response;
			}

			// Throw an exception if no data was received and debug mode is enabled
			if(!$response and $this->debug) throw new Exception("Coupilia: Failed to retrieve data");

			// Catch any exceptions - we only want to throw one if debug mode is enabled
			try
			{
				// If the type is set to JSON
				if($this->type == "json")
				{
					// Attempt to decode the data
					$response = (array) json_decode($response);
				}

				// If the type is set to XML
				else
				{
					// Attempt to decode the data
					$xml = simplexml_load_string($response);
					$response = array();

					// Convert the SimpleXML objects to standard objects (for cleanliness)
					foreach($xml->coupons->item as $c)
					{
						$coupon = new StdObject();

						foreach($c as $key => $val)
						{
							$coupon->{$key} = $val;
						}

						$response[] = $coupon;
					}
				}
			}
			catch(Exception $e)
			{
				// Throw an exception if debug mode is enabled
				if($this->debug)
				{
					echo "Coupilia: " . $e->getMessage();

					return array();
				}
			}

			// Add the parsed response data to the history record
			$this->history[$hist]["data"] = $response;

			// Return the data
			return $response;
		}

		public function getJSON($params = NULL)
		{
			// JSON-specific GET request
			return $this->get($params, "json");
		}

		public function getXML($params = NULL)
		{
			// XML-specific GET request
			return $this->get($params, "xml");
		}

		public function lastQuery()
		{
			// Return the last used query
			return end($this->history);
		}
	}
