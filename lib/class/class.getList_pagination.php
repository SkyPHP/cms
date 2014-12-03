<?php
 use \Crave\Model\venue,
 	 \Crave\Model\ct_promoter;

class getList_pagination {

	private $limits = array(20, 25, 50, 100, 250);

	function __construct($class = null, $params = null) {
		$this->class = $class;
		$this->params = $params;
		$GLOBALS['pagination_count'][] = true;
		$this->i = count($GLOBALS['pagination_count']);
		if (!$_GET['page'.$this->i] || $_GET['page'.$this->i] < 1) {
			$_GET['page'.$this->i] = 1;
		}

		$default_limit = 25;
		$this->limit = if_not($_GET['limit'.$this->i], $default_limit);
		$this->page = $_GET['page'.$this->i];

		if ($class && $params) $this->rs = $this->select();
	}

	function select($class = null, $params = null) {

		//Kint::trace();
		if ($class) $this->class= $class;
		if ($params) $this->params = $params;

		// pagination settings;

		$this->params['limit'] = $this->limit;
		$this->params['page'] = $this->page;
		$this->params['offset'] = ($this->page - 1) * $this->limit;

		$cl = $this->class;
		//d($cl);
		$this->rs = $cl::getList($this->params);

		$this->total_rows = $cl::getList($this->params, true);
		$this->first_row = $this->offset + 1;
		$this->last_row = count($this->rs) + $this->first_row - 1;
		// krumo($this);

		$this->num_pages = ceil($this->total_rows / $this->limit);

		return $this->rs;
	}

	function showing() {?>
        <div style="overflow:hidden;">
            <div style="float:left;">Showing <?=$this->first_row?> - <?=$this->last_row?>
                of <?=$this->total_rows?>
                (page <?=$this->page?> of <?=$this->num_pages?>)
            </div>
            <div style="float:right;">
                Show
                <select name="limit<?=$this->i?>" i="<?=$this->i?>" class="pagination-limit">
<?
                foreach ($this->limits as $limit) {
?>
                    <option value="<?=$limit?>" <? if ($this->limit==$limit) echo 'selected="selected"'; ?>><?=$limit?></option>
<?
                }
?>
                </select>
                per page
            </div>
        </div>
<?
	}

	    function pages() {
		    global $p;
			$url = explode('?',$p->uri);
		?>
		    <div class="pagination-links">
		<?
		    for ($i=1; $i<=$this->num_pages; $i++) {
		        if ( !$this->page || $this->page == $i ) $selected = 'selected';
		        else $selected = '';
		        $qs = qs_remove('page'.$this->i);
		        if ($i!=1) $qs .= '&page'.$this->i.'=' . $i;
		?>
		        <a href="<? echo $url[0]; if ($qs) echo '?' . $qs; ?>" class="pagination-link <?=$selected?>"><?=$i?></a>
		<?
		    }
		?>
		    </div>
		<?
    }//pages

}
