<?

class dup_phrase_data extends Model {
	
	public function getList($a=array()) {
		
		//. category
		//. sub_category
		//. base
		//. volume
		//. holiday
		//. keyword

		$where = array();
		if (is_array($a['where'])) foreach($a['where'] as $w) $where[]=$w;
		if ($a['category']) $where[] = "category = '".addslashes($a['category'])."'";
		if ($a['sub_category']) $where[] = "sub_category = '".addslashes($a['sub_category'])."'";
		if ($a['base']) $where[] = "base = '".addslashes($a['base'])."'";
		if ($a['volume']) $where[] = "volume = ".$a['volume'];
		if ($a['keyword']) $where[] = "keyword = '".addslashes($a['keyword'])."'";
		if ($a['holiday']) $where[] = "holiday = '".addslashes($a['holiday'])."'";
		if ($a['order_by']) $order_by = $a['order_by'];
		
		$aql = "dup_phrase_data { }";
		$clause = array("dup_phrase_data"=>array("where"=>$where,"order by"=>$order_by));
		$rs =  aql::select($aql,$clause);
		$ids = array();
		
		foreach($rs as $r) {
			$ids[] = $r['dup_phrase_data_id'];	
		}
		return $ids;

	}
			
}