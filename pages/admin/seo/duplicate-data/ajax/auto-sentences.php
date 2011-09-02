	<div style="font-size:18px; font-weight:bold;margin-bottom: 10px;">Auto Permutation List</div>
<?
	$no_sentences = $_POST['no_sentences'];
	$sentences=array();
	for ($x = 0; $x < $no_sentences; $x++) {
		$sentences[$x] = $_POST['sentence'.$x];
	}
	if ($_POST['use_first']) { 
		$first = $sentences[0];
		$sentences = array_slice($sentences,1);
	}
	
	if ($_POST['limit']) $limit = $_POST['limit'];
	else $limit = 25;
	permutate($sentences,$limit);
	
	function permutate($items, $limit, $perms = array( )) {
		$count = 0;
		$num_items = count($items);
		$limit = $limit + $num_items + $num_items - 3;
		$permutate = function($items, $perms) use(&$limit, &$count, &$permutate) {
			// print_pre($count);
			if (empty($items)) {
				configure_perm($count,$perms);
			} else {
				$count++;
				for ($i = count($items) - 1; $i >= 0; --$i) {
					$newitems = $items;
					$newperms = $perms;
					list($foo) = array_splice($newitems, $i, 1);
					array_unshift($newperms, $foo);
					if ($limit <= $count) {
						configure_perm($count,array_merge($newitems,$newperms));
						break;
					}
					$permutate($newitems, $newperms);
				}
			}
		};
		$permutate($items, $perms);
	}

	function configure_perm($count,$sentences=array( )) {
		echo '<div class="has-floats" style="margin-bottom:15px;">';
		echo '<div style="float:left; margin-right:10px;"><input type="checkbox" ';
		$x = 0;
		foreach ($sentences as $sentence) {
			$x++;
			$rs=aql::select("dup_sentence_data { id as s_id where sentence = '".$sentence."'");
			echo 's'.$x.'="'.$rs[0]['s_id'].'" ';
		}
		echo 'version="'.$x.' class="perm_box" />Version ('.$count.')</div>';
		echo '<div style="float:left;">';
		foreach ($sentences as $sentence) {			
			echo $perm.'<br>';
		}
		echo "</div></div>";
	}
?>