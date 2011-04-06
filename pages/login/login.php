<?
if (!$_POST['skybox']) redirect('/?skybox=/login');

$p->title = 'Sign In';

$p->template('skybox','top');
?>

<form id="login_form" method="post">
    <div id="login_message"></div>
    <div class="field">
        <div class="field-label">
            Username
        </div>
        <div>
            <input name="login_username" type="text" id="login_username" autocomplete="off" />
        </div>
    </div>
    <div class="field">
        <div class="field-label">
            Password
        </div>
        <div>
            <input name="login_password" type="password" id="login_password" />
        </div>
    </div>
    <div class="login-actions">
        <div class="float-left">
            <div class="field">
                <input type="checkbox" name="remember_me" id="remember_me" />
                <label for="remember_me">Remember Me</label>
            </div>
            <div id="login-help">
                <a href="javascript:forgotpw();">Lost your password?</a>
            </div>
        </div>
        <div id="login_box">
            <input type="submit" id="login_button" value="Sign In" />
        </div>
    </div>
</form>

<?
$p->template('skybox','bottom');
?>