<?php

global $dev;

if ( $template_area == 'top' ) {
    

    $js = array(
		'/lib/js/sky.utils.js',
        '/templates/html5/cms-html5.js',
        '/lib/js/aqlForm.js',
        '/lib/js/jquery.livequery.min.js'
    );

    $this->template_js = array_merge($this->template_js, $js);

    $attrs = $this->getHTMLAttrString();

?>
<!doctype html>
<html <?=$attrs?> lang="en">
<head>
    <title><?=$this->title?></title>
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
<?php
    if ( true ) echo $this->stylesheet();
    else echo $this->consolidated_stylesheet();
    

?>
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    

<?php
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
<div data-role="page" data-title="<?=$p->title?>" data-theme="<?=$this->data_theme?$this->data_theme:'d'?>">
    <div data-role="header">
        <?=$this->heading_left?$this->heading_left:''?>
        <h1><?=$this->heading?$this->heading:$this->title?></h1>
    </div> 
    <div data-role="content" > 
        
<?php

} else if ( $template_area == 'bottom' ) {

?>
    </div>
</div>

<?php
    $css = array_diff($this->css, $this->css_added);
    foreach ($css as $file) {
        if (in_array($file, $this->css_added)) continue;
        $this->css_added[] = $file;
        if ( file_exists_incpath($file) ) {
?>
    <link rel="stylesheet" href="<?=$file?>" />
        
<?php
        }
    }
?>

    <script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
    
<?php
    if (true) echo $this->javascript();
    else echo $this->consolidated_javascript();

    global $db, $dbw, $db_host, $dbw_host;
?>

<!-- web: <?=$_SERVER['SERVER_ADDR']?> -->
<!-- db:  <?= substr($db->host,0,strpos($db->host,'.')) ?> -->
<!-- dbw: <?= substr($dbw->host,0,strpos($dbw->host,'.')) ?> -->
</body>
</html>
<?php
}//bottom