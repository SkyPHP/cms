<?
// this page uses the "url" table
// allows for tiny urls to redirect to longer urls
$url_ide = $_POST['sky_ide'];
$url_id = decrypt($url_ide,'url');

if ( !is_numeric($url_id) ) redirect('/');

// get the long url
$aql = "url {
			url
			where id = $url_id
		}";
$rs = aql::select($aql);
$url = $rs[0]['url'];

// increment the count
$SQL = "update url
		set count = count + 1
		where id = $url_id";
$dbw->Execute($SQL) or die("$SQL<br>".$dbw->ErrorMsg());

header ( 'HTTP/1.1 301 Moved Permanently' );
header("Location: $url");
exit();

?>