<?
	$where=array();
	$where[] = "website_page.level < 3";
	$order_by = "website_page.page_order, website_page_data.website_page_id, website_page_data.iorder";

// horizontal tabs
if ($_SERVER['QUERY_STRING']) $qs = '?'.$_SERVER['QUERY_STRING'];
$tabs = array(
    // name                        // href
	'All' => "/admin/seo/website-page-data/all/",
	'Opt-Phrase' => "/admin/seo/website-page-data/opt_phrase/",
	'Title' => "/admin/seo/website-page-data/title/",
	'H1' => "/admin/seo/website-page-data/h1/",
	'H1-blurb' => "/admin/seo/website-page-data/h1_blurb/",
	'Meta-description' => "/admin/seo/website-page-data/meta_description/"
				
);

	switch( IDE ){
		case 'all':
	        $title = 'All';
			break;
		case 'opt_phrase':
	        $title = 'opt_phrase';
			$where[] = "field = 'opt_phrase'";
			break;
		case 'title':
	        $title = 'title';
			$where[] = "field = 'title'";
			break;
		case 'h1':
	        $title = 'h1';
			$where[] = "field = 'h1'";
			break;
		case 'h1_blurb':
	        $title = 'h1_blurb';
			$where[] = "field = 'h1_blurb'";
			break;
		case 'meta_description':
	        $title = 'meta_description';
			$where[] = "field = 'meta_description'";
			break;			
		default:
			// tab_redirect() ensures this is never the case
			break;
	}
	$title = "Website Page Data";
	template::inc('intranet','top');
?>

<?
	snippet::tabs($tabs);
	
	$aql = aql::get_aql('website_page_data');
	$cols = "
			nickname {label: Nickname;}
			field {label: field;}
			value {label: value;}		
	";

	$clause=array(
		'website'=>array(
			'where'=>$where,
			'order by' => $order_by
		)
	);
	
	$options = array(
		'enable_sort'=>true
	);
	
	
?>
	
<?
	aql::grid($aql,$cols,$clause,$options);
	template::inc('intranet','bottom');
?>