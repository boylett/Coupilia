#Coupilia 1.0.3
***Updated 11:05am 28th August 2015***  
A simple PHP Class for the [Coupilia Voucher feed](http://www.coupilia.com/) API

##Installation
No messy scripts or command line access required, simply copy `Coupilia.php` into your active directory and include it with `require`

    require 'Coupilia.php';

##Usage Example

    $APIKey		= "ab123cde4-fghi-jk56-l7m8901n23";
    $Coupilia	= new Coupilia($APIKey);
    
    $Coupilia->filter = true;
    
    $vouchers	= $Coupilia->get(array
    (
        "recordset" => "all"
    ));

##Available Options

###`filter`
Currently accepts a timestamp (integer) or boolean. Filters out coupons whose end date have surpassed the timestamp provided (or the current date if a boolean was given).

##Available Methods

###`get(Array $params, String $type)`
Does the legwork for retrieving data from Coupilia's servers. `$type` can be `'json'` or `'xml'` or `NULL` (makes no difference to returned data but may affect performance depending on server setup).

`$params` accepts the following values:

| Parameter | Required | Type | Accepted Values |
|-----------|----------|------|-----------------|
| recordset | yes | string | **all** *or*<br>**test** *or*<br>**increment** |
| merchantid | optional | int | Merchant ID |
| network | optional | string | *Comma separated list of network codes:*<br>**a** (Amazon)<br>**af** (Affiliate Future)<br>**av** (AvantLink)<br>**aw** (Affiliate Window)<br>**cj** (Commission Junction)<br>**dr** (Digital River)<br>**pj** (Ebay / Pepper Jam)<br>**ir** (Impact Radius)<br>**lc** (Link Connector)<br>**ls** (Link Share)<br>**sas** (Share A Sale)<br>**td** (Trade Doubler)<br>**wg** (Web Gains)<br>**za** (Zanox) |
| category | optional | string | *Comma separated list of categoy codes:*<br>**accessories** (Accessories)<br>**adult** (Adult)<br>**apparel** (Apparel)<br>**appliances** (Appliances)<br>**crafts** (Arts and Crafts)<br>**auto** (Auto and Marine)<br>**baby** (Baby)<br>**beauty** (Beauty)<br>**bath** (Bed and Bath)<br>**book** (Books)<br>**business** (Business)<br>**cameras** (Cameras)<br>**charity** (Charity)<br>**collectibles** (Collectibles)<br>**computer** (Computers)<br>**cooking** (Cooking)<br>**costumes** (Costumes)<br>**dating** (Dating)<br>**store** (Department Stores)<br>**dvd** (DVD &amp; Video)<br>**ebook** (Ebook)<br>**education** (Education)<br>**electronic** (Electronics)<br>**events** (Events or Weddings)<br>**eyewear** (Eyewear)<br>**finance** (Finance)<br>**firearms** (Firearms/Tactical)<br>**fitness** (Fitness)<br>**gift** (Flowers and Gifts)<br>**food** (Food &amp; Drinks)<br>**furniture** (Furniture)<br>**gambling** (Gambling)<br>**gaming** (Gaming)<br>**gourmet** (Gourmet)<br>**health** (Health and Personal Care)<br>**home** (Home and Garden)<br>**hunting** (Hunting/Fishing)<br>**insurance** (Insurance)<br>**internet** (Internet/Phone Services)<br>**jewelry** (Jewelry)<br>**jobs** (Jobs)<br>**kidsfamily** (Kids/Family)<br>**kitchen** (Kitchen)<br>**legal** (Legal)<br>**lingerie** (Lingerie)<br>**malls** (Malls)<br>**marketing** (Marketing)<br>**misc** (Miscellaneous)<br>**music** (Music and Dvd)<br>**office** (Office)<br>**communities** (Online Communities)<br>**personalized** (Personalized Products)<br>**pet** (Pets)<br>**photo** (Photography)<br>**seasonal** (Seasonal)<br>**shipping** (Shipping)<br>**shoes** (Shoes)<br>**deal** (Social Deal Sites)<br>**software** (Software)<br>**sport** (Sports &amp; Recreation)<br>**sportsapparel** (Sports Apparel)<br>**entertainment** (Tickets and Entertainment)<br>**tobacco** (Tobacco)<br>**tool** (Tools)<br>**toy** (Toys and Games)<br>**travel** (Travel)<br>**vitality** (Vitality Medical)<br>**web** (Web Services)<br>**women** (Women Only) |
| dealtype | optional | string | *Comma separated list of deal types:*<br>**affiliatelink** (Affiliate Link)<br>**bogo** (Buy one get one)<br>**coupon** (Coupon)<br>**deal** (Deal, price drop)<br>**shipping** (Free shipping)<br>**genericdeal** (Generic Deal)<br>**genericoffer** (Generic Offer)<br>**genericsale** (Generic Sale)<br>**gwp** (Gift with purchase)<br>**rebate** (Rebate)<br>**sale** (Sale)<br>**sitewide** (Sitewide) |
| holiday | optional | string | *Comma separated list of holiday types:*<br>**backtoschool** (Back To School)<br>**blackfriday** (Black Friday)<br>**breastcancermonth** (Breast Cancer Month)<br>**cybermonday** (Cyber Monday)<br>**easter** (Easter)<br>**fathersday** (Fathers Day)<br>**friendsfamily** (Friends and Family)<br>**halloween** (Halloween)<br>**mothersday** (Mothers Day)<br>**thanksgiving** (Thanksgiving)<br>**valentinesday** (Valentines Day) |

Returns a list of vouchers.  
*Example:*

	Array
	(
		"0" => StdObject
		(
			"id" => [coupon id],
			"merchant" => [merchant name],
			"merchantid" => [merchant id],
			"offer" => [coupon offer],
			"restrictions" => [coupon restrictions],
			"url" => StdObject
			(
				"0" => StdObject
				(
					"location" => [url location],
					"label" => [label]
				)
			),
			"code" => [coupon code],
			"startdate" => [coupon start date],
			"enddate" => [coupon end date],
			"category" => [coupon category],
			"dealtype" => [coupon deal type],
			"holiday" => [coupon holiday],
			"rating" => [coupon rating],
			"website" => [merchant website],
			"logo" => [merchant logo],
			"network" => [coupon network],
			"networkid" => [merchant id for affiliate network],
			"keywords" => [coupon keywords],
			"country" => [coupon country]
		)
	)

###`lastQuery()`
Returns the last query's information.  
*Example:*

    Array
    (
        "date" => 1234567890,
        "url" => "http://www.coupilia.com/feeds/coupons_json.cfm?recordset=test&token=ab123cde4-fghi-jk56-l7m8901n23",
        "response" => "[]",
        "data" => Array()
    )
