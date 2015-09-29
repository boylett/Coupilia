<?php

	/* Custom PHP class for Coupilia Voucher System
	 *
	 * Author:		Ryan Boylett <http://boylett.uk/>
	 * URL:			https://github.com/boylett/Coupilia
	 * Version:		1.0.5
	 */

	class Coupilia
	{
		public $cache	= array();
		public $debug	= false;

		private $token;

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
			// Store the API token on initialization
			$this->token = $token;

			return $this;
		}

		public function filter($data, $filters)
		{
			// Start a new collection
			$valid_response = array();

			foreach($data as $coupon)
			{
				// Coupons are validby default
				$valid = true;

				foreach($filters as $key => $val)
				{
					switch(trim(strtolower($key)))
					{
						case 'country':
							// The coupon is valid if the country codes match
							$valid = (trim(strtoupper($val)) == strtoupper($coupon->country));
							break;

						case 'end': case 'enddate': case 'ended':
							// If the coupon has an end date
							if(isset($coupon->enddate))
							{
								// Get the exiration date as a timestamp
								$enddate = strtotime("00:00:00 " . (isset($coupon->enddate) ? $coupon->enddate : '1/1/1970'));

								// If the end date is in the future, the coupon is valid
								if((is_string($val) ? strtotime($val) : $val) < $enddate)
								{
									$valid = true;
								}
							}
							break;

						case 'haslogo': case 'logo':
							// The coupon is valid if it has a logo image
							$valid = (isset($coupon->logo) and trim($coupon->logo));
							break;

						case 'rating':
							// The coupon is valid if its rating falls between the criteria
							$inverse = preg_match("/^!/", $val);
							$comparator = preg_replace("/^!?((>|<|!|=)=?)([0-9]+)$/", "$2", $val);
							$rating = preg_replace("/[^0-9]/", "", $val);

							// Compare the ratings
							switch($comparator)
							{
								case '<': $valid = ($val < $rating); break;
								case '<=': $valid = ($val <= $rating); break;
								case '>': $valid = ($val > $rating); break;
								case '>=': $valid = ($val >= $rating); break;
								case '!': case '!=': $valid = ($val != $rating); break;
								default: $valid = ($val == $rating); break;
							}

							// If the comparator started with `!`, inverse the outcome
							if($inverse)
							{
								$valid = ($valid ? false : true);
							}
							break;

						case 'start': case 'startdate': case 'started':
							// If the coupon has a start date
							if(isset($coupon->startdate))
							{
								// Get the exiration date as a timestamp
								$startdate = strtotime("00:00:00 " . (isset($coupon->startdate) ? $coupon->startdate : '1/1/1970'));

								// If the start date is in the past, the coupon is valid
								if((is_string($val) ? strtotime($val) : $val) >= $startdate)
								{
									$valid = true;
								}
							}
							break;

						case 'haswebsite': case 'website':
							// The coupon is valid if it has a website link
							$valid = (isset($coupon->website) and trim($coupon->website));
							break;
					}
				}

				// If the coupon is still valid, keep it
				if($valid)
				{
					$valid_response[] = $coupon;
				}
			}

			// Return the filtered data
			return $valid_response;
		}

		public function get($params = NULL, $filters = array())
		{
			// If no parameters were supplied, we'll assume the user is testing the feed
			if(!$params or !is_array($params))
			{
				$params = array
				(
					"recordset" => "test"
				);
			}

			else if(is_int($params))
			{
				$params = array
				(
					"recordset" => "all",
					"couponid" => $params
				);
			}

			// Supply the API token
			$params["token"] = $this->token;

			// Check supplied filters array for any request filters we could use
			foreach($filters as $key => $val)
			{
				$sanitarykey = trim(strtolower($key));
				$sanitaryval = trim(strtolower($val));

				switch($sanitarykey)
				{
					case 'coupon': case 'couponid': case 'id': case 'merchant': case 'merchantid':
						$param = preg_match("/merchant/i", $sanitarykey) ? 'merchantid' : 'couponid';

						if(is_numeric($sanitaryval))
						{
							$params[$param] = $sanitaryval;
							unset($filters[$key]);
						}
						break;

					case 'category': case 'dealtype': case 'holiday': case 'network':
						$list = ($sanitarykey == 'category') ? 'categories' : $sanitarykey . 's';

						if(isset($this->{$list}[$sanitaryval]))
						{
							$params[$sanitarykey] = $sanitaryval;
							unset($filters[$key]);
						}
						else foreach($this->{$list} as $code => $network)
						{
							if($sanitaryval == trim(strtolower($network)))
							{
								$params[$sanitarykey] = $code;
								unset($filters[$key]);
								break;
							}
						}
						break;
				}
			}

			// Construct the HTTP request URL
			$url = "http://www.coupilia.com/feeds/coupons_json.cfm?" . http_build_query($params);

			// If a cached version of this query exists, return it to save an expensive API call
			if(isset($this->cache[$url]))
			{
				$response = $this->cache[$url]['data'];
			}

			// Otherwise, start the download & sanitation process
			else
			{
				// Add to the cache
				$this->cache[$url] = array
				(
					"date" => time(),
					"url" => $url
				);

				// Make the HTTP request (this used to use CURL, but file_get_contents is much more user-friendly)
				$response = @file_get_contents($url);

				// Clean up the response data
				if($response)
				{
					// Remove whitespace from either end of the data (slightly improves parsing speed)
					$response = trim($response);

					// Add the raw response data to the cache
					$this->cache[$url]["response"] = $response;
				}

				// Throw an exception if no data was received and debug mode is enabled
				if(!$response and $this->debug) throw new Exception("Coupilia: Failed to retrieve data");

				// Catch any exceptions - we only want to throw one if debug mode is enabled
				try
				{
					// Attempt to decode the data
					$response = (array) json_decode($response);

					// Clean up the response keys (lowercase)
					$sanitary = array();

					foreach($response as $coupon)
					{
						$sanitarycoupon = array();

						foreach($coupon as $key => $val)
						{
							$key = strtolower($key);

							if(is_string($val) and preg_match("/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $val))
							{
								$val = strtotime('00:00:00 ' . $val);
							}

							else if($key == 'url')
							{
								$val = $val[0]->location;
							}

							$sanitarycoupon[$key] = $val;
						}

						$sanitary[] = (object) $sanitarycoupon;
					}

					$response = $sanitary;
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

				// Add the parsed response data to the cache
				$this->cache[$url]["data"] = $response;
			}

			// If an error has occurred, throw an exception
			if(isset($response['error']))
			{
				// Only display an error is debug mode is on
				if($this->debug)
				{
					echo 'Coupilia: ' . $response['error'];
				}

				return array();
			}

			// Filter out any invalid coupons
			if(!empty($filters))
			{
				return $this->filter($response, $filters);
			}

			// Return the data
			return $response;
		}

		public function lastQuery()
		{
			// Return the last used query
			return end($this->cache);
		}
	}
