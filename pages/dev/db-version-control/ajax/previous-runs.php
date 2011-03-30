<?
	$aql = "sky_sql_log	{
							codebase,
							sql
						}";
	$param = array('aql'=>$aql,
					'cols'=>array('codebase','sql')
	);
	aql::grid($param);
?>