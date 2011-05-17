<?
if ($template_area=='top') {

    $this->template('html5', 'top');

    $this->template_css[] = '/templates/intranet/intranet.css';

?>
<div id="container">

    <div id="wrapper-top">

        <div id="header">
            <div id="header-main">
                <div id="header-text"><?=$_SERVER['HTTP_HOST']?></div>
                <div id="header-right">
					<?=$_SESSION['login']['fname']?> <?=$_SESSION['login']['lname']?><br />
                    <a class="logout" href="javascript:void(0);" onclick="logout('<?=$_SERVER['REQUEST_URI']?>');">Logout</a>
				</div>
                <div class="clear"></div>
            </div>
        </div>

    </div>
    <div id="wrapper-page" class="has-floats">

        <div id="page-container">

            <div id="main">
<?
             /*   template::breadcrumb();  #right now there is no function equivalent*/
?>
                <h1><?=$title?></h1>

                <div id="content">

        <?
        } else if ($template_area=='bottom') {
        ?>
                </div>
            <?/*=gethostname()*/?>
            </div> <!-- END MAIN DIV -->

        </div>

    </div><!-- END PAGE WRAPPER DIVS -->

    <div id="footer">
    	<div id="footer-container">
            <div id="footer-top"></div>
            <div id="footer-bottom">
                <div id="copyright">
                     &copy; <?=date('Y')?> 
                </div>
                <div id="terms">
                    <a href="/privacy-policy">Privacy Policy</a> &nbsp;|&nbsp; <a href="/terms-and-conditions">Terms and Conditions</a>
                </div>
            </div>
		</div>
    </div>

</div>

<?
$this->template('html5', 'bottom');
}//if template
?>
