<?php

if ($template_area == 'top') {

    global $jquery_version;

?>
<!doctype html>
<html>
<head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/<?=$jquery_version?>/jquery.min.js"></script>
    <script>!window.jQuery && document.write(unescape('%3Cscript src="/lib/js/jquery-<?=$jquery_version?>.min.js"%3E%3C/script%3E'))</script>
</head>
<body>
<script>
    if ( typeof window.JSON === 'undefined' ) {
        document.write('<script src="/lib/history.js-1.5/json2.min.js"><\/script>');
    }
</script>
<script type="text/javascript" src="/lib/easyXDM/easyXDM.min.js"></script>


<?php
} else if ($template_area == 'bottom') {
?>


</body>
</html>

<?php
}
