<?php

class grid {

    public  $aql,
            $model,
            $data,
            $default_limit,
            $pagination,
            $columns,
            $where,
            $limit,
            $clause_array,
            $order_by,
            $table_class,
            $empty_message = 'No Results',
            $style;

    public function __construct( $a = array() ) {
        // order by fix
        if ( $a['order by'] ) {
            $a['order_by'] = $a['order by'];
            unset( $a['order by'] );
        }

        if (is_array($a))
        foreach( $a as $key => $val ) {
            $this->$key = $val;
        }
        if ( !$this->aql ) {
            if ( $this->model ) {
                $model = $this->model;
                $this->aql = $model::getAQL();
            }
        }
    }

    public function __call($method, $args) {
        if (substr($method, 0, 4) == 'set_') {
            array_unshift($args, substr($method, 4));
            return call_user_func_array(array($this, '_setter'), $args);
        } else {
            throw new Exception('Method does not exist');
        }
    }

    private function _setter($field, $value) {
        $this->$field = $value;
        return $this;
    }

    public function krumo() {
        $this->run();
        krumo( $this );
    }

    public function run() {
        if ( $this->pagination ) return;
        if ( $this->aql ) {
            $this->pagination = new pagination($this->aql,array(
                'where' => $this->where,
                'default_limit' => $this->default_limit,
                'order by' => $this->order_by
            ));
        } else if ( $this->data ) {
            $this->pagination = new array_pagination($this->data,array(
                'default_limit' => $this->default_limit
            ));
        }
    }

    public function table() {
        $this->run();
        if ($this->pagination->rs) {
?>          
            <table class="<?=$this->table_class?>">
                <thead>
                    <tr>
    <?
                    foreach ( $this->columns as $column ) {
                        if ( !$column['th_class'] ) $column['th_class'] = $column['class'];
                        if ( !$column['th_style'] ) $column['th_style'] = $column['style'];
                        $th_attr = '';
                        if ( $column['th_id'] ) $th_attr .= ' id="' . $column['th_id'] . '"';
                        if ( $column['th_class'] ) $th_attr .= ' class="' . $column['th_class'] . '"';
                        if ( $column['th_style'] ) $th_attr .= ' style="' . $column['th_style'] . '"';
    ?>
                        <th<?=$th_attr?>>
                            <?=$column['th']?>
                        </th>
    <?
                    }
    ?>
                    </tr>
                </thead>
                <tbody>
<?
            foreach ( $this->pagination->rs as &$r ) {
?>
                    <tr>
    <?  
                    foreach ( $this->columns as $column ) {
                        if ( !$column['td_class'] ) $column['td_class'] = $column['class'];
                        if ( !$column['td_style'] ) $column['td_style'] = $column['style'];
                        $td_attr = '';
                        if ( $column['td_id'] ) $td_attr .= ' id="' . $column['td_id'] . '"';
                        if ( $column['td_class'] ) $td_attr .= ' class="' . $column['td_class'] . '"';
                        if ( $column['td_style'] ) $td_attr .= ' style="' . $column['td_style'] . '"';
    ?>
                        <td<?=$td_attr?>>
    <?
                        if ( is_callable($column['td']) ) {
                            echo $column['td']($r);
                        } else if ( strpos( $column['td'], '->' )) {
                            $keys = explode('->', $column['td']);
                            $val = $r->{array_shift($keys)};
                            foreach ($keys as $key) {
                                if (is_array($val)) $val = $val[$key];
                            }
                            echo $val;
                        } else if ( strpos( $column['td'], '.php' ) ) {
                            $this->get_incpath();
                            include( $this->incpath . '/' . $column['td'] );
                        } else if ( array_key_exists($column['td'],$r) ) {
                            echo $r->{$column['td']}; 
                        } else {
                            echo $column['td'];
                        }
    ?>
                        </td>
    <?
                    }
    ?>
                    </tr>
<?
            }
?>
                <tbody>
            </table>
<?
        } else {
            echo $this->empty_message;
        }
    }

    public function showing() {
        $this->run();
        if ($this->pagination->rs) $this->pagination->showing();
    }

    public function pages() {
        $this->run();
        if ($this->pagination->rs) $this->pagination->pages();
    }

    public function get_incpath() {
        if ( !$this->incpath ) {
            // find the page that called the grid funciton
            $bt = debug_backtrace();
            foreach ( $bt as $t ) {
                $file = $t['file'];
                $needle = DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR;
                $start = strpos($file, $needle);
                if ( $start !== false ) break;
            }
            $incpath = $file;
            $incpath = substr($incpath,$start+1);
            $incpath = substr($incpath,0,strrpos($incpath,DIRECTORY_SEPARATOR));
            $this->incpath = $incpath;
        }
    }

    public function output() {
?>
    <div class="has-floats">
<?
        $this->showing();
        $this->table();
        $this->pages();
?>
    </div>
<?
    }

}//class
