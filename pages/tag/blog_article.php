<?

$sql = " select distinct blog_article_id,blog_article.title,blog.name,
                blog_article.post_time, blog.slug as blog_slug,
                market.slug as market_slug, blog_article_tag.iorder, blog_article.content,
                blog_article.media_item_id, person.fname, person.lname, person.username
        from blog_article_tag
        left join blog_article on blog_article_id=blog_article.id
        left join blog on blog_article.blog_id = blog.id
        left join blog_website on blog.id=blog_website.blog_id
        left join market on blog_article.market_id=market.id
        left join person on blog_article.author__person_id=person.id
        where blog_website.status='A'
        and blog_website.website_id=$website_id
        and lower(blog_article_tag.name) ilike lower('$current_tag')
        and blog_article_tag.active=1 and blog_article.active=1
        and blog_article.status='A'
        order by blog_article.post_time desc,blog_article_tag.iorder asc
        limit $limit
        offset $offset";

$rs = sql($sql);
 
   echo "<div id='tag-blog-article-results'>";   

   if($rs->EOF){
      $last_page = true;
      include('noresult.php');
   }else{
      $articles = array();

      while(!$rs->EOF){
         $articles[]=$rs->fields;

         $rs->MoveNext();
      }

        $last_page = count($articles)<=$num_per_page;

        while(count($articles)>$num_per_page){array_pop($articles);}

        blog::listing(NULL,NULL,NULL,$articles);
   }

   echo "</div>";

?>
