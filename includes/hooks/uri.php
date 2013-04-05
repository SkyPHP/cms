<?
// remember uri
// if the page has a trailing '/' or '?', redirect to the remembered uri
if ( strlen($p->uri) == strlen($p->urlpath) + 1 ) {
    if ( $_SESSION['remember_uri'][$p->page_path] ) redirect($_SESSION['remember_uri'][$p->page_path]);
    else redirect($p->urlpath);
// if the page has query folders and/or querystring, remember it
} else if ( strlen($p->uri) > strlen($p->urlpath) + 1 ) {
    $_SESSION['remember_uri'][$p->page_path] = $p->uri;
}


