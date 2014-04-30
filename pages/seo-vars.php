<?
use Crave\Model\ct_holiday,
	\Crave\Model\ct_event
;

//seo variables
$p->seo['country'] = 'US';

//LOOK FOR THE CATEGORY OR THE HOLIDAY
if(sizeof($website->categories) == 1 && !$p->vars['ct_category_id']) {
	foreach ($website->categories as $cat) {
		$ct_category_id = $cat['ct_category_id'];
	}
} else if ($p->vars['ct_category_id']) {
	$ct_category_id = $p->vars['ct_category_id'];
} elseif( $website->ct_holiday_id) {
	$ct_holiday_id = $website->ct_holiday_id;
}

//LOOK FOR THE MARKET

if(sizeof($website->markets) == 1) {
	$market_id = $website->markets[0]['market_id'];
} elseif ($p->vars['market_id']) {
	$market_id = $p->vars['market_id'];
}

// LOOK FOR EVENT
if ($p->vars['ct_event_id']) {
	$seo_event = new ct_event($p->vars->ct_event_id);
	$venue_id = $seo_event->venue->venue_id;
	$venue_name = $seo_event->venue->venue_name;
	$venue_name_modifier = $seo_event->venue->name_modifier;
	$market_id = $seo_event->venue->market_id;
}

if (false && $market_id) {
	$market=new \Crave\Model\market($market_id);
	$market_name = $market->name;
	$market_state = $market->state;
	$market_state_full = $market->state_full;
	$market1 = $market->market1.$website_id;
	$market2 = $market->market2;
	$market3 = $market->market3;
	$market4 = $market->market4;
	$name_alt1 = $market->name_alt1;
	$name_alt2 = $market->name_alt2;
	$name_alt3 = $market->name_alt3;
	$market_county = $market->county;
	if (!$p->seo['ICBM']) {
		$p->seo['ICBM'] = $market->latitude.','.$market->longitude;
		$p->seo['geo-position'] = $market->latitude.';'.$market->longitude;
		$p->seo['placename'] = $market->city;
		$p->seo['city'] = $market->city;
		$p->seo['state'] = $market->state;
		$p->seo['geo-region'] = 'US-'.$market->state;
	}
}

if ($venue_id) {
	$venue=new \Crave\Model\venue($venue_id);
	$venue_name = ucwords(strtolower($venue->venue_name));
	$venue_name_modifier = ucwords(strtolower($venue->name_modifier));
	$place_type = ucwords(strtolower($venue->place_type));
	$address = ucwords(strtolower($venue->address1));
	$venue_city = ucwords(strtolower($venue->city));
	$venue_state = strtoupper($venue->state);
	$venue_zipcode = $venue->zipcode;
	$venue_fulladdress = $address.' '.$venue_city.', '.$venue_state.' '.$venue_zipcode;
	$p->seo['ICBM'] = $venue->latitude.','.$venue->longitude;
	$p->seo['geo-position'] = $venue->latitude.';'.$venue->longitude;
	$p->seo['placename'] = $venue->city;
	$p->seo['city'] = $venue->city;
	if ($venue->state)
		$p->seo['state'] = $venue->state;
	$p->seo['geo-region'] = 'US-'.$venue->state;
	$p->seo['zipcode'] = $venue->zipcode;

}
// Reset Placename is market neighborhood is set.
if ($venue->market_nbhd_id)
	$market_nbhd_id = $venue->market_nbhd_id;
if ($p->vars['market_nbhd_id'])
	$market_nbhd_id = $p->vars['market_nbhd_id'];
if($market_nbhd_id)
	$p->seo['placename'] = aql::value('market_nbhd.name',$market_nbhd_id);
	

/*	
if ($ct_category_id) {
	$ct_category=aql::profile('ct_category',$ct_category_id);
	$category_name = $ct_category['name'];
	if($ct_category->ct_holiday_id)
		$ct_holiday_id = $ct_category->ct_holiday_id;
}
*/
if ($ct_holiday_id) {
	$ct_holiday= new ct_holiday($ct_holiday_id);
	$holiday_name = $ct_holiday->name;
	$holiday1 = $ct_holiday->holiday1;
	$holiday2 = $ct_holiday->holiday2;
	$holiday3 = $ct_holiday->holiday3;
	$holiday4 = $ct_holiday->holiday4;
	$holiday5 = $ct_holiday->holiday5;
	$holiday6 = $ct_holiday->holiday6;
}



if ($ct_campaign_id) {
	$ct_campaign=aql::profile('ct_campaign',$ct_campaign_id);
	$campaign_name = $ct_campaign['name'];
}

if ($ct_contract_id) {
	$ct_contract=aql::profile('ct_contract',$ct_contract_id);
	$contract_name = $ct_contract['name'];
	$contract_open_bar_start = $ct_contract['open_bar_start'];
	$contract_open_bar_end = $ct_contract['open_bar_end'];
}


$website_name = $p->seo['domain'] = $website->website->domain;


$seo_phone = $website->website>seo_phone;

$seo_year = date('Y');
if ($ct_campaign_id == 1 || $ct_holiday_id == 1) $seo_year++;


if ($_GET['seo']) {
		echo $market_id.'zz';
	}
