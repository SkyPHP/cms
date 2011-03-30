<?

// add filter criteria to the where clause array
$where = array();

if ( $_GET['market_ide'] ) $where[] = "blog_article.market_ide = {$_GET['market_ide']}";
if ( $_GET['q'] ) {
	$q = addslashes($_GET['q']);
    $where[] = "(blog_article.title ilike '%{$q}%' or
                blog_article.introduction ilike '%{$q}%' or
                blog_article.content ilike '%{$q}%')
                ";
}
if ( $_GET['blog_ide'] ) $where[] = "blog_article.blog_ide = {$_GET['blog_ide']}";
if ( $_GET['author'] ) $where[] = "blog_article.author__person_id = " . decrypt($_GET['author'],'person');
if ( $_GET['blog_category_ide'] ) $where[] = "blog_article.blog_category_ide = ".$_GET['blog_category_ide'];
if ( !auth('blog_author:Editor') ) $where[] = "author__person_id = " . constant('PERSON_ID');


// count the number of records to be displayed on each of the tabs
$tab_qty = array();
$aql = "blog_article {
            count(id) as qty,
            status
            group by status
        }";
$clause = array(
    'blog_article' => array(
        'where' => $where
    )
);
$rs = aql::select( $aql, $clause );
if (is_array($rs))
foreach($rs as $tab) {
	if ( $tab['qty'] ) $tab_qty[ $tab['status'] ] = ' (' . number_format($tab['qty']) . ')';
}


// setup the tabs
if ($_SERVER['QUERY_STRING']) $qs = '?'.$_SERVER['QUERY_STRING'];
$tabs = array(
    // tab label                           // href
    'Drafts' . $tab_qty['']             => '/admin/blog/post/draft'.$qs,
    'Pending Approval' . $tab_qty['P']  => '/admin/blog/post/pending'.$qs,
    'Published' . $tab_qty['A']         => '/admin/blog/post/published'.$qs,
    'Trash' . $tab_qty['T']             => '/admin/blog/post/trash'.$qs
);
snippet::tab_redirect($tabs);


// set variables based on which tab is selected
switch( IDE ){
    case 'draft':
        $title = 'Draft Blog Posts';
        $where[] = "( blog_article.status = '' or blog_article.status is null )";
        break;
    case 'pending':
        $title = 'Blog Posts Pending Approval';
        $where[] = "blog_article.status = 'P'";
        break;
    case 'published':
        $title = 'Published Blog Posts';
        $where[] = "blog_article.status = 'A'";
        break;
    case 'trash':
        $title = 'Trashed Blog Posts';
        $where[] = "blog_article.status = 'T'";
        break;
    default:
        // tab_redirect() ensures this is never the case
        break;
}


// begin html output

template::inc('intranet','top');
?>
<div class="has-floats">
    <div class="float-right blog_listing">
        <div class="content_listing">
<?
            // display the filters
            include ( INCPATH . '/includes/listing-filter.php');

            // display the tabs
            snippet::tabs($tabs);

            $post_time_column =  IDE == 'published'?'post_time    { label:	Post Time; order by: blog_article.post_time;}':'mod_time_formatted    { label:	Edit Time; order by: blog_article.mod_time;}';


            // display the grid
            aql::grid(array(

                'model' => 'blog_article',

                'columns' => " title {
                                    label: Title;
                                    order by: title;
                               }
                               blog_name { 
                                    label: Blog;
                               }
                               fname_lname  { 
                                    label: Author;
                                    order by: person.fname;
                               }
                               market_name  { label:	Market; }
                               $post_time_column
                               note         { label:	Note; }
                               edit         { label:	Edit; }",

                'where' => $where,

                'order by' => 'post_time desc',

                'enable_sort' => true,

                'max_rows' => 50

            ));
?>
        </div>
    </div>
    <div class="left_nav">
<?
        include( INCPATH . '/../left-nav/left-nav.php');
?>
    </div>
</div>
<?
template::inc('intranet','bottom');
?>
