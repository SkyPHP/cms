<?

class array_pagination_qf {

    private $limits = array(10,25,50,100,250,500);

    function __construct($array) {
        global $p;
		$p->queryfolders[0] = str_replace('p','',$p->queryfolders[0]);

		if (!$p->queryfolders[0] || $p->queryfolders[0] < 1) {
            $p->queryfolders[0] = 1;
        }

        //pagination settings
        $default_limit = 10;
        if (!$_GET['limit']) $_GET['limit'] = $default_limit;
        $this->offset = $p->queryfolders[0] * $_GET['limit'] - $_GET['limit'];

        $this->total_rows = count($array);

        $this->rs = array_slice($array,$this->offset,$_GET['limit'],true);

        $this->first_row = $this->offset + 1;
        $this->last_row = count($this->rs) + $this->first_row - 1;


        $this->num_pages = ceil($this->total_rows / $_GET['limit']);
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
?>
        <div class="pagination-links">
<?
		$pages_to_show = 12;
		if($pages_to_show > $this->num_pages)
			$pages_to_show = $this->num_pages;

		if(!$p->queryfolders[0] || $p->queryfolders[0] < 6)
			$start = 1;
		elseif ( $p->queryfolders[0] > $this->num_pages-6)
			$start =  $this->num_pages-12;
		else
			$start = $p->queryfolders[0]-6;


		if (!$p->queryfolders[0] || $p->queryfolders[0] > 7 ) {
			?>
			<a href="<? echo $p->urlpath.'/p1'; ?>" class="pagination-link">&laquo; First</a>
			<?
        }
        for ($i=$start; $i<=$start+$pages_to_show-1; $i++) {
            if ( !$p->queryfolders[0] || $p->queryfolders[0]==$i ) $selected = 'selected';
            else $selected = '';
			?>
            <a href="<? echo $p->urlpath; if ($i!=1) echo '/p' . $i; ?>" class="pagination-link <?=$selected?>"><?=$i?></a>
			<?
        }
		if ( $p->queryfolders[0] <= $this->num_pages-7 ) {
			?>
			<a href="<? echo $p->urlpath.'/p'.$this->num_pages; ?>" class="pagination-link">Last &raquo;</a>
			<?
        }
?>
        </div>
<?
    }//pages


}//array_pagination_qf class