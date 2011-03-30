<?

$head_arr[] = '
    <script src="/lib/CodeMirror-0.93/js/codemirror.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="/lib/CodeMirror-0.93/css/docs.css"/>

';

$title = 'Codebase Editor';
template::inc('global','top');
include(INCPATH.'/../dev-nav.php');

?>

<b>pages</b> | <a href="#">models</a> | <a href="#">templates</a> | <a href="#">validation</a> | <a href="#">config.php</a> |  <a href="#">404.php</a> | <a href="#">cron</a>
<?

$tabs = array(
    'php' => '#',
    'listing' => '#',
    'profile' => '#',
    'css' => '#',
    'js' => '#',
    'settings' => '#',
    'model' => '#',
    'components' => '#',
    'validation' => '#'
);
snippet::tabs($tabs);

?>
    <style type="text/css">
      .CodeMirror-line-numbers {
        width: 2.2em;
        color: #aaa;
        background-color: #eee;
        text-align: right;
        padding-right: .3em;
        font-size: 10pt;
        font-family: monospace;
        padding-top: .4em;
        line-height: normal;
      }
      body {
          font-family: helvetica;
          font-weight:bold;
          max-width:4000px;
      }
      a {
          color: #EB1D1D;
          text-decoration: none;
      }
      a:hover {
          text-decoration: underline;
      }
      div.border {
        border: 1px solid black;
      }
      .css-switch {
          margin-right:15px;
          padding-bottom:5px;
      }
    </style> 


    <div>
<?
    $myfiles = array();
    $codebases = get_codebase_paths();
    if (is_array($codebases))
    foreach ( $codebases as $codebase_name => $codebase ) {
        //print_a($codebase);
        $files = scandir($codebase['path']);
        unset($files[0]);
        unset($files[1]);
        if (is_array($files))
        foreach ($files as $file) {
            if ( is_dir($codebase['path'].$file) ) {
                $file .= '/';
                if (!$mydirs[$file]) $mydirs[$file] = $codebase_name;
            } else if (!$myfiles[$file]) $myfiles[$file] = $codebase_name;
        }
    }
    ksort($mydirs);
    ksort($myfiles);
    print_a($mydirs);
    print_a($myfiles);
?>
    </div>

    <div style="float:left; border: 1px solid black; padding: 3px; background-color: #F8F8F8">
    <textarea id="code" cols="120" rows="30">

<?
$test = "test";
echo $test;
?>

    </textarea>
    </div>

    <script type="text/javascript">
      var editor = CodeMirror.fromTextArea('code', {
        height: "350px",
        parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js",
                     "../contrib/php/js/tokenizephp.js", "../contrib/php/js/parsephp.js",
                     "../contrib/php/js/parsephphtmlmixed.js"],
        stylesheet: ["/lib/CodeMirror-0.93/css/xmlcolors.css", "/lib/CodeMirror-0.93/css/jscolors.css", "/lib/CodeMirror-0.93/css/csscolors.css", "/lib/CodeMirror-0.93/contrib/php/css/phpcolors.css"],
        path: "/lib/CodeMirror-0.93/js/",
        continuousScanning: 500,
        lineNumbers: true
      });
    </script>


<?

template::inc('global','bottom');
?>