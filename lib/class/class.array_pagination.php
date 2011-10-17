<?

class array_pagination {

    private $limits = array(10,25,50,100,250,500);

    /*
     *  params array:
     *  default_limit
     *  limits
     */
    function __construct($array,$params=null) {

        if ( is_array($params['limits']) ) $this->limits = $params['limits'];

        $GLOBALS['pagination_count'][] = true;
        $this->i = count($GLOBALS['pagination_count']);
        if (!$_GET['page'.$this->i] || $_GET['page'.$this->i] < 1) {
            $_GET['page'.$this->i] = 1;
        }

        //pagination settings
        $default_limit = $params['default_limit'] ?: $this->limits[0];
        if (!$_GET['limit'.$this->i]) $_GET['limit'.$this->i] = $default_limit;
        $this->offset = $_GET['page'.$this->i] * $_GET['limit'.$this->i] - $_GET['limit'.$this->i];

        $this->total_rows = count($array);

        $this->rs = array_slice($array,$this->offset,$_GET['limit'.$this->i],true);

        $this->first_row = $this->offset + 1;
        $this->last_row = count($this->rs) + $this->first_row - 1;


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


}//array_pagination class
