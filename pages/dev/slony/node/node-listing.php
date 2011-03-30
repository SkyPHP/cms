<?

if($_POST['sky_qs'][0]=='new-cluster'){
    redirect('/dev/slony/node/add-new');
}

template::inc('intranet','top');

?>

<div style="margin: 10px 0;">
    <!-- a href="/dev/slony/add-new" class="add-new">Add new node</a -->
</div>

<?

template::inc('intranet','bottom');

?>
