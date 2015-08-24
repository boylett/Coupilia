<?php

	/* Custom PHP class for Coupilia Voucher System
	 *
	 * Author:		Ryan Boylett <http://boylett.uk/>
	 * Update:		http://boylett.uk/classes/php/coupilia/update
	 * Version:		1.0.1
	 */

	class Coupilia
	{
		public $token;
		public $type = "json";
		public $history = array();
		public $debug = false;

		// A list of all available Coupilia categories
		public $categories = array
		(
			"accessories" => "Accessories",
			"adult" => "Adult",
			"apparel" => "Apparel",
			"appliances" => "Appliances",
			"crafts" => "Arts and Crafts",
			"auto" => "Auto and Marine",
			"baby" => "Baby",
			"beauty" => "Beauty",
			"bath" => "Bed and Bath",
			"book" => "Books",
			"business" => "Business",
			"cameras" => "Cameras",
			"charity" => "Charity",
			"collectibles" => "Collectibles",
			"computer" => "Computers",
			"cooking" => "Cooking",
			"costumes" => "Costumes",
			"dating" => "Dating",
			"store" => "Department Stores",
			"dvd" => "DVD & Video",
			"ebook" => "Ebook",
			"education" => "Education",
			"electronic" => "Electronics",
			"events" => "Events or Weddings",
			"eyewear" => "Eyewear",
			"finance" => "Finance",
			"firearms" => "Firearms/Tactical",
			"fitness" => "Fitness",
			"gift" => "Flowers and Gifts",
			"food" => "Food & Drinks",
			"furniture" => "Furniture",
			"gambling" => "Gambling",
			"gaming" => "Gaming",
			"gourmet" => "Gourmet",
			"health" => "Health and Personal Care",
			"home" => "Home and Garden",
			"hunting" => "Hunting/Fishing",
			"insurance" => "Insurance",
			"internet" => "Internet/Phone Services",
			"jewelry" => "Jewelry",
			"jobs" => "Jobs",
			"kidsfamily" => "Kids/Family",
			"kitchen" => "Kitchen",
			"legal" => "Legal",
			"lingerie" => "Lingerie",
			"malls" => "Malls",
			"marketing" => "Marketing",
			"misc" => "Miscellaneous",
			"music" => "Music and Dvd",
			"office" => "Office",
			"communities" => "Online Communities",
			"personalized" => "Personalized Products",
			"pet" => "Pets",
			"photo" => "Photography",
			"seasonal" => "Seasonal",
			"shipping" => "Shipping",
			"shoes" => "Shoes",
			"deal" => "Social Deal Sites",
			"software" => "Software",
			"sport" => "Sports & Recreation",
			"sportsapparel" => "Sports Apparel",
			"entertainment" => "Tickets and Entertainment",
			"tobacco" => "Tobacco",
			"tool" => "Tools",
			"toy" => "Toys and Games",
			"travel" => "Travel",
			"vitality" => "Vitality Medical",
			"web" => "Web Services",
			"women" => "Women Only"
		);

		// A list of all available Coupilia dealtypes
		public $dealtypes = array
		(
			"affiliatelink" => "Affiliate Link",
			"bogo" => "Buy one get one",
			"coupon" => "Coupon",
			"deal" => "Deal, price drop",
			"shipping" => "Free shipping",
			"genericdeal" => "Generic Deal",
			"genericoffer" => "Generic Offer",
			"genericsale" => "Generic Sale",
			"gwp" => "Gift with purchase",
			"rebate" => "Rebate",
			"sale" => "Sale",
			"sitewide" => "Sitewide"
		);

		// A list of all available Coupilia holidays
		public $holidays = array
		(
			"backtoschool" => "Back To School",
			"blackfriday" => "Black Friday",
			"breastcancermonth" => "Breast Cancer Month",
			"cybermonday" => "Cyber Monday",
			"easter" => "Easter",
			"fathersday" => "Fathers Day",
			"friendsfamily" => "Friends and Family",
			"halloween" => "Halloween",
			"mothersday" => "Mothers Day",
			"thanksgiving" => "Thanksgiving",
			"valentinesday" => "Valentines Day"
		);

		// A list of all available Coupilia networks
		public $networks = array
		(
			"af" => "Affiliate Future",
			"aw" => "Affiliate Window",
			"an" => "Affilinet",
			"av" => "AvantLink",
			"cj" => "Commission Junction",
			"dr" => "Digital River",
			"pj" => "Ebay Enterprise Network",
			"ir" => "Impact Radius",
			"lc" => "Link Connector",
			"ls" => "Linkshare",
			"sas" => "Shareasale",
			"td" => "TradeDoubler",
			"wg" => "Webgains",
			"za" => "Zanox"
		);

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

			// Sanitize the desired feed type
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

		public function lastQuery()
		{
			// Return the last used query
			return end($this->history);
		}
	}
