<?
	class dup_phrase_group extends model {
		
		public function getList($a=array()) {
			
			//. category
			//. seo_field
			//. website_id
			//. total_volume
			//. page
			//. name
			//. order_by

			$where = array();
			if (is_array($a['where'])) foreach($a['where'] as $w) $where[]=$w;
			if ($a['category']) $where[] = "category = '".addslashes($a['category'])."'";
			if ($a['seo_field']) $where[] = "seo_field = '".addslashes($a['seo_field'])."'";
			if ($a['website_id']) $where[] = "website_id = ".$a['website_id'];
			if ($a['total_volume']) $where[] = "total_volume >= ".$a['total_volume'];
			if ($a['page']) $where[] = "page = '".addslashes($a['page'])."'";
			if ($a['name']) $where[] = "name = '".addslashes($a['name'])."'";
			if ($a['order_by']) $order_by = $a['order_by'];
			
			$aql = "dup_phrase_group { }";
			$clause = array("dup_phrase_group"=>array("where"=>$where,"order by"=>$order_by));
			$rs =  aql::select($aql,$clause);
			$ids = array();
			
			foreach($rs as $r) {
				$ids[] = $r['dup_phrase_group_id'];	
			}
			return $ids;
	
		}
			
	}
?>