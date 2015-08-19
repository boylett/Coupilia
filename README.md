#Coupilia
A simple PHP Class for the [Coupilia Voucher feed](http://www.coupilia.com/) API

##Installation
No messy scripts or command line access required, simply copy `Coupilia.php` into your active directory and include it with `require`

    require 'Coupilia.php';

##Usage Example

    $APIKey		= "ab123cde4-fghi-jk56-l7m8901n23";
    $Coupilia	= new Coupilia($APIKey);
    
    $vouchers	= $Coupilia->get(array
    (
        "recordset" => "all"
    ));

##Available Methods

###`get(Array $params, String $type)`
Does the legwork for retrieving data from Coupilia's servers. `$type` can be `'json'` or `'xml'` or `NULL` (makes no difference to returned data but may affect performance depending on server setup).

`$params` accepts the following values:

| Parameter | Required | Type | Accepted Values |
|-----------|----------|------|-----------------|
| recordset | yes | string | all<br>test<br>increment |
| merchantid | optional | int | Merchant ID |
| network | optional | string | ***Comma separated list of network codes:***<br>**a**	(Amazon)<br>**af**	(Affiliate Future)<br>**av**	(AvantLink)<br>**aw**	(Affiliate Window)<br>**cj**	(Commission Junction)<br>**dr**	(Digital River)<br>**pj**	(Ebay / Pepper Jam)<br>**ir**	(Impact Radius)<br>**lc**	(Link Connector)<br>**ls**	(Link Share)<br>**sas**	(Share A Sale)<br>**td**	(Trade Doubler)<br>**wg**	(Web Gains)<br>**za**	(Zanox) |
| category | optional | string | ***Comma separated list of categoy codes:***<br>**accessories**<br>**adult**<br>**apparel**<br>**appliances**<br>**crafts**<br>**auto**<br>**baby**<br>**beauty**<br>**bath**<br>**book**<br>**business**<br>**cameras**<br>**charity**<br>**collectibles**<br>**computer**<br>**cooking**<br>**costumes**<br>**dating**<br>**store**<br>**dvd**<br>**ebook**<br>**education**<br>**electronic**<br>**events**<br>**eyewear**<br>**finance**<br>**firearms**<br>**fitness**<br>**gift**<br>**food**<br>**furniture**<br>**gambling**<br>**gaming**<br>**gourmet**<br>**health**<br>**home**<br>**hunting**<br>**insurance**<br>**internet**<br>**jewelry**<br>**jobs**<br>**kidsfamily**<br>**kitchen**<br>**legal**<br>**lingerie**<br>**malls**<br>**marketing**<br>**misc**<br>**music**<br>**office**<br>**communities**<br>**personalized**<br>**pet**<br>**photo**<br>**seasonal**<br>**shipping**<br>**shoes**<br>**deal**<br>**software**<br>**sport**<br>**sportsapparel**<br>**entertainment**<br>**tobacco**<br>**tool**<br>**toy**<br>**travel**<br>**vitality**<br>**web**<br>**women**<br> |

###`lastQuery()`
Returns the last query's information.  
*Example:*

    array(4) {
      ["date"] => int(1234567890)
      ["url"] => string(98) "http://www.coupilia.com/feeds/coupons_json.cfm?recordset=test&token=ab123cde4-fghi-jk56-l7m8901n23"
      ["response"]=> string(2) "[]"
      ["data"]=> array(0) {}
    }
