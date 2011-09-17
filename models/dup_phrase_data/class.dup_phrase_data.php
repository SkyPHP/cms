<?
	class dup_phrase_data extends model {
		
		public static function getPhrases($a=array()) {
			
			//. category
			//. sub_category
			//. base
			//. volume
			//. holiday

			$where = array();
			if (is_array($a['where'])) foreach($a['where'] as $w) $where[]=$w;
			if ($a['category']) $where[] = "category = '".$a['category']."'";
			if ($a['sub_category']) $where[] = "sub_category = '".$a['sub_category']."'";
			if ($a['base']) $where[] = "base = '".$a['base']."'";
			if ($a['volume']) $where[] = "volume = ".$a['volume'];
			if ($a['holiday']) $where[] = "holiday = '".$a['holiday']."'";
			
			$rs = aql::select();
		}
		
		public function contruct() {
				
		}
	}
?>