<?

global $dev;

if ( $template_area == 'top' ) {

    $attrs  = '';
    if ($this->html_attrs) {
        foreach ($this->html_attrs as $k => $v) {
            $attrs .= " {$k}=\"{$v}\"";
        }
    }

?>
<!doctype html>
<html>
<head>
    <title><?=$this->title?></title>
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
<?
    if ( true ) echo $this->stylesheet();
    else echo $this->consolidated_stylesheet();
	

?>
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	
	<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
<?
    // echo the items in the $head_arr
	if (is_array($this->head)) {
        foreach ($this->head as $head_item) {
            echo $head_item . "\n";
        }
	} else if ( $this->head ) {
        echo $this->head . "\n";
    }
?>

</head>
<body>
<div data-role="page" data-title="<?=$p->title?>">
	<div data-role="header">
    	<?=$p->heading_left?$p->heading_left:''?>
        <h1><?=$p->heading?$p->heading:$p->title?></h1>
    </div> 
	<div data-role="content"> 
		
<?

} else if ( $template_area == 'bottom' ) {

?>
	</div>
</div>

<?
    $css = array_diff($this->css, $this->css_added);
    foreach ($css as $file) {
        if (in_array($file, $this->css_added)) continue;
        $this->css_added[] = $file;
        if ( file_exists_incpath($file) ) {
?>
    <link rel="stylesheet" href="<?=$file?>" />
<?
        }
    }
	
    if (true) echo $this->javascript();
    else echo $this->consolidated_javascript();

    global $db, $dbw, $db_host, $dbw_host;
?>

<!-- web: <?=$_SERVER['SERVER_ADDR']?> -->
<!-- db:  <?= substr($db->host,0,strpos($db->host,'.')) ?> -->
<!-- dbw: <?= substr($dbw->host,0,strpos($dbw->host,'.')) ?> -->
</body>
</html>
<?
}//bottom
