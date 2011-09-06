<?
	if ($_POST['phrase1'])
		foreach ($_POST['phrase1'] as $p1) {
			$phrase = $p1;
			if ($_POST['phrase2']) 
				foreach ($_POST['phrase2'] as $p2) {
					$phrases[] = $p1.' | '.$p2;
				}
		}
	
	if ($_POST['mods']) {
		foreach ($phrases as $phrase) {
			foreach ($mods as $mod) {
				$final[] = $phrase.' | '.$mod;
			}
		}
	}
	else $final = $phrases;
	print_a($final);
?>