# Coupilia 1.0.5
***Updated 12:08pm 29th September 2015***  
A simple PHP Class for the [Coupilia Voucher feed](http://www.coupilia.com/) API

## Installation
No messy scripts or command line access required, simply copy `Coupilia.php` into your active directory and include it with `require`

    require 'Coupilia.php';

## Usage Example

    $APIKey		= "ab123cde4-fghi-jk56-l7m8901n23";
    $Coupilia	= new Coupilia($APIKey);

    $Coupons	= $Coupilia->get(array
    (
        "recordset" => "all"
    ));

## Available Methods

### `get(Int $couponid, [Array $filters])`
Retrieves a specific coupon from the Coupilia service.

`$couponid` must be an integer and specifies which Coupon to retrieve.  
`$filters` can be an array of parameters to filter by (**See `filter` method below**)

### `get(Array $params, [Array $filters])`
Retrieves a list of coupons from the Coupilia service.

`$params` accepts the following values:

| Parameter | Required | Type | Accepted Values |
|-----------|----------|------|-----------------|
| recordset | yes | string | **all** *or*<br>**test** *or*<br>**increment** |
| couponid | optional | int | Coupon ID |
| merchantid | optional | int | Merchant ID |
| network | optional | string | *Comma separated list of network codes:*<br>**a** (Amazon)<br>**af** (Affiliate Future)<br>**av** (AvantLink)<br>**aw** (Affiliate Window)<br>**cj** (Commission Junction)<br>**dr** (Digital River)<br>**pj** (Ebay / Pepper Jam)<br>**ir** (Impact Radius)<br>**lc** (Link Connector)<br>**ls** (Link Share)<br>**sas** (Share A Sale)<br>**td** (Trade Doubler)<br>**wg** (Web Gains)<br>**za** (Zanox) |
| category | optional | string | *Comma separated list of categoy codes:*<br>**accessories** (Accessories)<br>**adult** (Adult)<br>**apparel** (Apparel)<br>**appliances** (Appliances)<br>**crafts** (Arts and Crafts)<br>**auto** (Auto and Marine)<br>**baby** (Baby)<br>**beauty** (Beauty)<br>**bath** (Bed and Bath)<br>**book** (Books)<br>**business** (Business)<br>**cameras** (Cameras)<br>**charity** (Charity)<br>**collectibles** (Collectibles)<br>**computer** (Computers)<br>**cooking** (Cooking)<br>**costumes** (Costumes)<br>**dating** (Dating)<br>**store** (Department Stores)<br>**dvd** (DVD &amp; Video)<br>**ebook** (Ebook)<br>**education** (Education)<br>**electronic** (Electronics)<br>**events** (Events or Weddings)<br>**eyewear** (Eyewear)<br>**finance** (Finance)<br>**firearms** (Firearms/Tactical)<br>**fitness** (Fitness)<br>**gift** (Flowers and Gifts)<br>**food** (Food &amp; Drinks)<br>**furniture** (Furniture)<br>**gambling** (Gambling)<br>**gaming** (Gaming)<br>**gourmet** (Gourmet)<br>**health** (Health and Personal Care)<br>**home** (Home and Garden)<br>**hunting** (Hunting/Fishing)<br>**insurance** (Insurance)<br>**internet** (Internet/Phone Services)<br>**jewelry** (Jewelry)<br>**jobs** (Jobs)<br>**kidsfamily** (Kids/Family)<br>**kitchen** (Kitchen)<br>**legal** (Legal)<br>**lingerie** (Lingerie)<br>**malls** (Malls)<br>**marketing** (Marketing)<br>**misc** (Miscellaneous)<br>**music** (Music and Dvd)<br>**office** (Office)<br>**communities** (Online Communities)<br>**personalized** (Personalized Products)<br>**pet** (Pets)<br>**photo** (Photography)<br>**seasonal** (Seasonal)<br>**shipping** (Shipping)<br>**shoes** (Shoes)<br>**deal** (Social Deal Sites)<br>**software** (Software)<br>**sport** (Sports &amp; Recreation)<br>**sportsapparel** (Sports Apparel)<br>**entertainment** (Tickets and Entertainment)<br>**tobacco** (Tobacco)<br>**tool** (Tools)<br>**toy** (Toys and Games)<br>**travel** (Travel)<br>**vitality** (Vitality Medical)<br>**web** (Web Services)<br>**women** (Women Only) |
| dealtype | optional | string | *Comma separated list of deal types:*<br>**affiliatelink** (Affiliate Link)<br>**bogo** (Buy one get one)<br>**coupon** (Coupon)<br>**deal** (Deal, price drop)<br>**shipping** (Free shipping)<br>**genericdeal** (Generic Deal)<br>**genericoffer** (Generic Offer)<br>**genericsale** (Generic Sale)<br>**gwp** (Gift with purchase)<br>**rebate** (Rebate)<br>**sale** (Sale)<br>**sitewide** (Sitewide) |
| holiday | optional | string | *Comma separated list of holiday types:*<br>**backtoschool** (Back To School)<br>**blackfriday** (Black Friday)<br>**breastcancermonth** (Breast Cancer Month)<br>**cybermonday** (Cyber Monday)<br>**easter** (Easter)<br>**fathersday** (Fathers Day)<br>**friendsfamily** (Friends and Family)<br>**halloween** (Halloween)<br>**mothersday** (Mothers Day)<br>**thanksgiving** (Thanksgiving)<br>**valentinesday** (Valentines Day) |

`$filters` can be an array of parameters to filter by (**See `filter` method below**)

Returns a list of vouchers.
*Example:*

	Array
	(
		"0" => StdObject
		(
			"id"			=> int		[coupon id],
			"merchant"		=> string	[merchant name],
			"merchantid"	=> int		[merchant id],
			"offer"			=> string	[coupon offer],
			"restrictions"	=> int		[coupon restrictions],
			"url"			=> string	[url location],
			"code"			=> string	[coupon code],
			"startdate"		=> int		[coupon start date],
			"enddate"		=> int		[coupon end date],
			"category"		=> string	[coupon category],
			"dealtype"		=> string	[coupon deal type],
			"holiday"		=> string	[coupon holiday],
			"rating"		=> int		[coupon rating],
			"website"		=> string	[merchant website],
			"logo"			=> string	[merchant logo],
			"network"		=> string	[coupon network],
			"networkid"		=> int		[merchant id for affiliate network],
			"keywords"		=> string	[coupon keywords],
			"country"		=> string	[coupon country]
		)
	)

### `filter(Array $data, Array $filters)`
Filters an array of data

`$filters` accepts the following values:

| Parameter | Type | Effect |
|-----------|------|--------|
| id | int | Retrieve a coupon by ID |
| country | string | Filters data by country code |
| logo | bool | Removes data with no logo image |
| website | bool | Removes data with no website link |
| rating | int | Removes data that does not comply with the provided rating parameters<br><br>***You can use comparators here:***<br>`"rating" => ">2"` will result in coupons with a rating higher than 2<br>`"rating" => "<2"` will result in coupons with a rating lower than 2<br><br>**Available comparators:**<br>`>` Higher than<br>`>=` Higher than or equal to<br>`<` Lower than<br>`<=` Lower than or equal to<br>`=` Equal to<br><br>*All of the above can be inversed with `!`, for example:*<br>`"rating" => "!=3"` - Coupons with a rating that is not qual to 3 |
| enddate | string ***or***<br>int | Removes data that has passed the provided date |
| startdate | string ***or***<br>int | Removes data that has not yet passed the provided date |

### `lastQuery()`
Returns the last query's information.
*Example:*

    Array
    (
        "date" => 1234567890,
        "url" => "http://www.coupilia.com/feeds/coupons_json.cfm?recordset=test&token=ab123cde4-fghi-jk56-l7m8901n23",
        "response" => "[]",
        "data" => Array()
    )
