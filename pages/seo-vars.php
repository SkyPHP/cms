<?
//seo variables
$GLOBALS['seo']['country'] = 'US';
krumo($website, $p);
//LOOK FOR THE CATEGORY OR THE HOLIDAY
if(sizeof($website->categories) == 1) {
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
	$market_id = $website->markets[0]['ct_category_id'];
} elseif ($p->vars['market_id']) {
	$market_id = $p->vars['market_id'];
}

// LOOK FOR EVENT
if ($p->vars['ct_event_id']) {
	$seo_event = new ct_event($p->vars['ct_event_id']);
	$venue_id = $seo_event->venue->venue_id;
	$market_id = $seo_event->venue->market_id;
}


if ($market_id) {
	$market=aql::profile('market',$market_id);
	$market_name = $market['name'];
	$market_state = $market['state'];
	$market_state_full = $market['state_full'];
	$market1 = $market['market1'].$website_id;
	$market2 = $market['market2'];
	$market3 = $market['market3'];
	$market4 = $market['market4'];
	$name_alt1 = $market['name_alt1'];
	$name_alt2 = $market['name_alt2'];
	$name_alt3 = $market['name_alt3'];
	$market_county = $market['county'];

	if (!$GLOBALS['seo']['ICBM']) {
		$GLOBALS['seo']['ICBM'] = $market['latitude'].','.$market['longitude'];
		$GLOBALS['seo']['geo-position'] = $market['latitude'].';'.$market['longitude'];
		$GLOBALS['seo']['geo-placename'] = $market['city'];
		$GLOBALS['seo']['city'] = $market['city'];
		$GLOBALS['seo']['state'] = $market['state'];
		$GLOBALS['seo']['geo-region'] = 'US-'.$market['state'];
	}

}

if ($venue_id) {
	$venue=aql::profile('venue',$venue_id);
	$venue_name = ucwords(strtolower($venue['name']));
	$venue_name_modifier = ucwords(strtolower($venue['name_modifier']));
	$place_type = ucwords(strtolower($venue['place_type']));
	$address = ucwords(strtolower($venue['address1']));
	$venue_city = ucwords(strtolower($venue['city']));
	$venue_state = strtoupper($venue['state']);
	$venue_zipcode = $venue['zipcode'];
	$venue_fulladdress = $address.' '.$venue_city.', '.$venue_state.' '.$venue_zipcode;
	
	$GLOBALS['seo']['ICBM'] = $venue['latitude'].','.$venue['longitude'];
	$GLOBALS['seo']['geo-position'] = $venue['latitude'].';'.$venue['longitude'];
	$GLOBALS['seo']['placename'] = $venue['city'];
	$GLOBALS['seo']['city'] = $venue['city'];
	$GLOBALS['seo']['state'] = $venue['state'];
	$GLOBALS['seo']['geo-region'] = 'US-'.$venue['state'];
	$GLOBALS['seo']['zipcode'] = $venue['zipcode'];
}

if ($ct_category_id) {
	$ct_category=aql::profile('ct_category',$ct_category_id);
	$category_name = $ct_category['name'];
	if($ct_category['ct_holiday_id'])
		$ct_holiday_id = $ct_category['ct_holiday_id'];
}

if ($ct_holiday_id) {
	$ct_holiday=aql::profile('ct_holiday',$ct_holiday_id);
	$holiday_name = $ct_holiday['name'];
	$holiday1 = $ct_holiday['holiday1'];
	$holiday2 = $ct_holiday['holiday2'];
	$holiday3 = $ct_holiday['holiday3'];
	$holiday4 = $ct_holiday['holiday4'];
	$holiday5 = $ct_holiday['holiday5'];
	$holiday6 = $ct_holiday['holiday6'];
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

$website_arr=aql::profile('website',$website_id);
$website_name = $website_arr['name'];
$year = date('Y');
if ($ct_campaign_id == 1 || $ct_holiday_id == 1) $year++;

$seo_field_array=array(
	'html'=>array(
		'title'=>69,
		'h1'=>100,
		'h1_blurb'=>300,
		'footer_blurb'=>1150
	),
	'meta'=>array(
		'meta_title'=>100,
		'meta_description'=>300,
		'meta_keywords'=>100,
		'meta_subject'=>100
	)
	//'Open Graph'=>array(
	//	'og:title'=>100,
	//	'og:type'=>50,
	//	'og:description'=>300,
	//	'og:site_name'=>100
	//)
	
	
);