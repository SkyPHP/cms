<?
	if (!$_POST['list']) exit('fail');
	else {
		$rs = aql::select("dup_sentence_group { group_number order by group_number desc limit 1 }");
		$num = $rs[0]['group_number'] + 1;
		foreach($_POST['list'] as $list) {
			$list = explode(',',$list);
			foreach($list as $id) {
				$data = array(
					'dup_sentence_data_id' => $id,
					'group_number' => $num
				);
				aql::insert("dup_sentence_group",$data);
			}
			$num++;
		}	
	}
	
?>