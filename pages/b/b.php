<?
$p->title = 'b title';
$p->js[] = '/lib/datepick/jquery.datepick.js'; //'/lib/datepicker/js/datepicker.js';
$p->css[] = '/lib/datepick/jquery.datepick.css'; //'/lib/datepicker/css/datepicker.css';
$p->template('demo','top');

while ( $p->minify() ) {
?>
    <a href="/a">a</a>,
    b,
    <a href="/c">c</a>
    <br />

<?
    /*
    $name = '/test//test32';
    $test = disk($name);
    if ( $test ) {
        echo $test;
    } else {
        disk( $name, rand(1,100), '1 minute' );
    }
    */
?>
    <br />
    <br />
<?
/*
    $key = 'testabc';
    mem($key,rand(1,100));
    echo mem($key);
  */


    while ( $p->cache('test','1 minute') ) {

        echo 'pcache: ' . rand(1,100);

    }


?>

    <div style="margin-top:25px;">
        <div id="datepicker"></div>
    </div>
<?


	    $list = array();
	    $allSlabs = $memcache->getExtendedStats('slabs');
	    $items = $memcache->getExtendedStats('items');
	    foreach($allSlabs as $server => $slabs) {
    	    foreach($slabs AS $slabId => $slabMeta) {
    	        $cdump = $memcache->getExtendedStats('cachedump',(int)$slabId);
    	        foreach($cdump AS $server => $entries) {
    	            if($entries) {
        	            foreach($entries AS $eName => $eData) {
        	                $list[$eName] = array(
        	                     'key' => $eName,
        	                     'server' => $server,
        	                     'slabId' => $slabId,
        	                     'detail' => $eData,
        	                     'age' => $items[$server]['items'][$slabId]['age'],
        	                     );
        	            }
    	            }
    	        }
    	    }
	    }
	    ksort($list);

}//minify

$p->template('demo','bottom');