<?
include_once( $sky_install_path . 'lib/core/functions.inc.php' );
include_once( $sky_install_path . 'lib/adodb/adodb.inc.php' );
@include_once( $sky_install_path . 'config.php' );

if ( !$db_host ) $db_host = $db_domain; // for backwards compatibility

date_default_timezone_set('America/New_York');

$needle = '/media/';
$start = strlen($needle);
$end = strpos($_SERVER['REQUEST_URI'],'/', $start);
$length = $end - $start;
if ($end) $media_instance_ide = substr( $_SERVER['REQUEST_URI'], $start, $length );
else {
	$file = substr( $_SERVER['REQUEST_URI'], $start );
	$temp = explode('.',$file);
	$media_instance_ide = $temp[0];
}//if

switch ($media_instance_ide):
default:
	if ($media_instance_ide) {
		$media_instance_id = decrypt($media_instance_ide,'media_instance');

		/*
			TODO: should probably duplicate/cache media_item and media_vfolder data in the media_instance table to improve performance
		*/
		if (!is_numeric($media_instance_id)) {

			break;

		} else {

			if ( $db_name && $db_host ) {
				$db = &ADONewConnection( $db_platform );
				$db->PConnect( $db_host, $db_username, $db_password, $db_name );
			/*
				if ( !$dbw_domain ) {
					$dbw =& $db;
				} else {
					$dbw = &ADONewConnection( $db_platform );
					$dbw->PConnect( $dbw_domain, $db_username, $db_password, $db_name );
				}//if
			*/
			}//if

			$SQL = "select  media_instance.instance,
							media_instance.file_type,
                            media_item.id as media_item_id,
							media_item.slug, 
							media_vfolder.vfolder_path
					from media_instance
					left join media_item on media_item.id = media_instance.media_item_id and media_item.active = 1
					left join media_vfolder on media_item.media_vfolder_id = media_vfolder.id and media_vfolder.active = 1
					where media_instance.active = 1
					and media_instance.id = $media_instance_id";
//			die($SQL);
			$r = $db->Execute($SQL) or die("$SQL<br>".$db->ErrorMsg());
			if (!$r->EOF) {
				$slug = $r->Fields('slug');
				$instance = $r->Fields('instance');
				if ($instance) $instance_folder = '/' . $instance;
				$file_type = $r->Fields('file_type');
				$vfolder_path = $r->Fields('vfolder_path');
                $dest_dir = $sky_media_local_path . $vfolder_path . $instance_folder;
				$local_path = $dest_dir . '/' . $slug . '.' . $file_type;
				if ( !file_exists($local_path) ) {
					include_once('lib/core/class.media.php');
                    media::get_if_not_here($r->Fields('media_item_id'),$sky_media_src_path, $file_type,$local_path,$dest_dir);
                }
                if (file_exists($local_path)) {
					$content = $sky_content_type[$file_type];
					header( "Content-type: $content");
					$ft = filemtime ($local_path);
					$modified = strftime ("%a, %d %b %Y %T GMT", $ft);
					header( 'Last-Modified: ' . $modified );
					$t = strtotime("+35 days");
					$expires = strftime ("%a, %d %b %Y %T GMT", $t);
					header( 'Expires: ' . $expires );
					header( 'Content-Length: ' . filesize($local_path) );
					readfile($local_path);
					exit(0);
                }
			}//if
		}//if
	}//if
	//die( 'problem.' );
	header("HTTP/1.0 404 Not Found");
	echo '404 Error: File Not Found.';
    echo "<!--
$SQL
-->";
	exit(0);

endswitch;