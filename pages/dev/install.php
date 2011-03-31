<?

if ($_POST['sky_qs'][0]) $file = $_POST['sky_qs'][0];
else $file = 'install';

$sql = file_get_contents( $sky_install_path . 'db/' . $db_platform . '/' . $file . '.sql' );
$db->Execute($sql);
echo '<hr />';
echo $db->ErrorMsg();
?>