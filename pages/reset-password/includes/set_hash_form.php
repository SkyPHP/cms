<?

$message = "Enter your email address to reset your password.";
if($hash_mismatch)
	$message = "Your password reset link has expired. Enter your email address again to reset your password.";

?>

<div class="info" id="response_div"><?=$message?></div> 
<form id="email_hash">

	<input name="email_address" id="email_address" type="text" style="width:200px;" class="autoclear" default="Your Email Address" />
	
    <input type="hidden" />

    <input type="button" value="Submit"  id="submit" style="width:75px;margin-top:25px;" />

</form>