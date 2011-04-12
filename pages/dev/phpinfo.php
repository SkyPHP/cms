<?
$p->title = 'Environment Info';
$p->template('html5','top');

include(INCPATH.'/../dev-nav.php');

?>$_SESSION<?
print_a($_SESSION);


?>$_POST<?
print_a($_POST);

echo phpinfo();

$p->template('html5','bottom');
?>