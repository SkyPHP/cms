<?





if ($template_area=='top') {
	$p->template('html5','top');

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

                <h1><?=$title?></h1>

                <div id="content">

        <?
        } else if ($template_area=='bottom') {
        ?>
                </div>
            <?=gethostname()?>
            </div> <!-- END MAIN DIV -->

        </div>

    </div><!-- END PAGE WRAPPER DIVS -->

</div>

<?
	$p->template('html5','bottom');
}//if template
?>
