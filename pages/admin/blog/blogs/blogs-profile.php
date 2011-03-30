<?

$blog_id = decrypt(IDE,'blog');

if( IDE == 'add-new' ) $title = "Add New Blog";
else $title = "Edit Blog";

template::inc('intranet','top');



?>

<div>
    <div>
        <a href = "/admin/blog/blogs">Back to blog listing</a>
    </div>
    <br />
    <br />
    <fieldset><legend>Blog</legend>
    <div>

    <div id='blog_form_message'></div>
    <?
    aql::form('blog');
    ?>
    <div class = "has-floats">
        <div class = "float-left">
            <input type = "button" value = "Save" onclick = "tinyMCE.triggerSave(); save_primary_profile('blog_form','blog')" />
        </div>
    </div>
</div>

<?
if ( is_numeric( $blog_id ) ) {
?>

<hr />
Logo for light background:
<div>
    <?
    media::uploader(array(
        'vfolder_path' => '/blog_id/' . $blog_id,
        'empty_message' => 'Upload a logo for this blog.  A high resolution transparent PNG file is ideal.',
        'db_field' => 'blog.logo__media_item_id',
        'db_row_id' => $blog_id

    ));
    ?>
</div>

<hr />

Logo for dark background:
<div>
    <?
    media::uploader(array(
        'vfolder_path' => '/blog_id/' . $blog_id,
        'empty_message' => 'Upload an inverse logo for this blog.  A high resolution transparent PNG file is ideal.',
        'db_field' => 'blog.inverse__media_item_id',
        'db_row_id' => $blog_id

    ));
    ?>
</div>
</div>
</fieldset>
<?
}//if

template::inc('intranet','bottom');
?>
