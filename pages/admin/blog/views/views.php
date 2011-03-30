<?

template::inc('intranet','top');
?>
<div class="has-floats">
    <div class="float-right blog_listing">
        <div class="content_listing">

        <?

           $pvs = array(
              $blog_pv = blog::get_pageviews('blog'),
              $author_pv = blog::get_pageviews('person'),
              $tag_pv = blog::get_pageviews('blog_article_tag'),
              $article_pv = blog::get_pageviews('blog_article')
           );

           $pvs_th = array(
              array('Rank','Hits','Blog','# of Articles','Hits per Article'),
              array('Rank','Hits','Author','# of Articles','Hits per Article'),
              array('Rank','Hits','Tag','# of Articles','Hits per Article'),
              array('Rank','Hits','Article','','')
           );

           foreach($pvs as $i=>$pv){
              ?><table class='listing'>
              <tr>
              <?foreach($pvs_th[$i] as $th){
                 ?><th><?=$th?></th><?
              }
              foreach($pv as $j=>$p){
                 ?><tr <?=($j+1)%2?'':'class="alternate-row"'?>>
                      <td><?=($j+1)?></td>
                      <td><?=number_format($p['sum'])?></td>
                      <td>
                      <?
                      switch($pv){
                         case($blog_pv):
                            echo $p['name'];
                            break;
                         case($author_pv):
                            echo $p['name'];
                            break;
                         case($tag_pv):
                            echo "<a href='/tag/".preg_replace('# #','+',$p['name'])."'>".$p['name']."</a>";
                            break;
                         case($article_pv):
                            echo "<a href='/newyork/frequency/".encrypt($p['blog_article_id'],'blog_article')."'>".$p['name']."</a>";
                            break;
                         default:
                            echo $p['name'];
                      }
                      ?>
                      </td>
                      <td><?=$pv!=$article_pv?number_format($p['count']):''?></td>
                      <td><?=$pv!=$article_pv?number_format($p['ratio'],2):''?></td>
                   </tr>
                 <?
              }
              ?></table><br /><?
           }

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
