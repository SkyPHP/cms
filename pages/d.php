<?
$p->title = 'd title';
$p->template('demo','top');

print_pre($p);

?>

    <div style="margin-top:25px;">

        <input type="button" value="open skybox" onclick="$.skybox('/nav',500,250);" />

    </div>
<?
$p->template('demo','bottom');