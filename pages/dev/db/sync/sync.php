<?php

global $dev_db_sync_providers;

$sql = \Cms\Apgdiff::getDump();
$db_name = \Cms\Apgdiff::getDatabaseName();

$this->title = 'Sync Database Schema';
$this->css[] = '/lib/jquery-ui/themes/smoothness/custom.css';
$this->js[] = '/lib/jquery-ui/jquery.ui.core.min.js';
$this->js[] = '/lib/jquery-ui/jquery.ui.widget.min.js';
$this->js[] = '/lib/jquery-ui/jquery.ui.tabs.min.js';
$this->js[] = '/lib/js/mustache.js';
$this->template('intranet','top');

?>

<h1><?=$this->title?></h1>

<form id="diff-form" method="post" action="">


    <div id="remote-selector">
        <div>
            Choose a remote database:
        </div>

        <select id="remote-url">
<?
        foreach ($dev_db_sync_providers as $db => $url) {
?>
            <option value="<?=$url?>"><?=$db?></option>
<?
        }
?>
        </select>

        <input id="submit-button" type="submit" value="Get Upgrade Scripts" />
    </div>

    <input type="hidden" name="db_name" value="<?=$db_name?>" />
    <textarea id="local-dump" name="sql"><?=$sql?></textarea>

</form>

<div id="diffs"></div>

<?
$this->template('intranet','bottom');
