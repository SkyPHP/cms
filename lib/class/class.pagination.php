<?php

class pagination {

    private $limits = array(10,25,50,100,250,500);

    function __construct($aql=null,$clause=null) {
        $this->aql = $aql;
        $this->clause = $clause;
        $GLOBALS['pagination_count'][] = true;
        $this->i = count($GLOBALS['pagination_count']);
        if (!$_GET['page'.$this->i] || $_GET['page'.$this->i] < 1) {
            $_GET['page'.$this->i] = 1;
        }
        if ($aql) $this->rs = $this->select();
    }

    function select($aql=null,$clause=null) {
        if ($aql) $this->aql = $aql;
        if ($clause) $this->clause = $clause;
        //pagination settings
        $default_limit = if_not($this->clause['default_limit'], 25);
        
        if (!$_GET['limit'.$this->i]) $_GET['limit'.$this->i] = $default_limit;
        $this->offset = $_GET['page'.$this->i] * $_GET['limit'.$this->i] - $_GET['limit'.$this->i];

        $this->clause['limit'] = $_GET['limit'.$this->i];
        $this->clause['offset'] = $this->offset;
        //$this->rs = aql::select($this->aql,$this->clause);
        $this->rs = \Sky\AQL::select($this->aql,$this->clause);
        $this->first_row = $this->offset + 1;
        $this->last_row = count($this->rs) + $this->first_row - 1;
        //$c = aql::sql($this->aql,$this->clause);
        $c = \Sky\AQL::count($this->aql,$this->clause);
        $this->total_rows = $c;
        $this->num_pages = ceil($this->total_rows / $_GET['limit'.$this->i]);
        return $this->rs;
    }


    function showing() {
?>
        <div style="overflow:hidden;">
            <div style="float:left;">Showing <?=$this->first_row?> - <?=$this->last_row?>
                of <?=$this->total_rows?>
                (page <?=$_GET['page'.$this->i]?> of <?=$this->num_pages?>)
            </div>
            <div style="float:right;">
                Show
                <select name="limit<?=$this->i?>" i="<?=$this->i?>" class="pagination-limit">
<?
                foreach ($this->limits as $limit) {
?>
                    <option value="<?=$limit?>" <? if ($_GET['limit'.$this->i]==$limit) echo 'selected="selected"'; ?>><?=$limit?></option>
<?
                }
?>
                </select>
                per page
            </div>
        </div>
<?
    }//showing


    function pages() {

        global $p;
		$url = explode('?',$p->uri);
?>
        <div class="pagination-links">
<?
        for ($i=1; $i<=$this->num_pages; $i++) {
            if ( !$_GET['page'.$this->i] || $_GET['page'.$this->i]==$i ) $selected = 'selected';
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


}//pagination class
