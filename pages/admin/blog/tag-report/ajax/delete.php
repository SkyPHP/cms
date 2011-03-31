<?

$tag_name = str_replace('**hidedoublequote**' , '"' , $_POST["tag_name"]);

$SQL = "UPDATE blog_article_tag SET active = 0, mod_time=now() WHERE name ilike '' || $$" . $tag_name . "$$ || ''";
$r = sql($SQL);
//echo $SQL;

?>