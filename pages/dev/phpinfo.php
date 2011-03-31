<?
$title = 'Environment Info';
template::inc('global','top');

include(INCPATH.'/../dev-nav.php');

?>$_SESSION<?
print_a($_SESSION);


?>$_POST<?
print_a($_POST);

echo phpinfo();

template::inc('global','bottom');
?>