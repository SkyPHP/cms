<?

$this->setSubject('Reset Your Password');
$this->setContentType('html');

$host = $data['host'];
$ide = $data['person']->getIDE();
$hash = $data['person']->password_reset_hash;

$this->setFrom("no-reply@$host");
$this->setCredentials((object)['api'=>API_MANDRILL_SECRET]);
$this->setMethod('mandrill');

$uri = sprintf('http://%s/reset-password/%s/%s', $host, $ide, $hash);
//d($uri);
?>

<p> 
	You recently asked to reset your password. To complete your request, please follow this link:
</p>

<p>
	<?=$uri?>
</p>

<p>
	If you did not request a new password, you may disregard this message.
</p>
