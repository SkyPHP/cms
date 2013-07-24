<?

if ( $template_area == 'top' ) {

    if ($this->is_ajax_request) {
        $this->css[] = '/templates/skybox/skybox.css';
    }

?>
    <div id="skybox_template" class="has-floats">
        <div id="skybox_drag_handle">
            <div id="skybox_template_title">
                <?=$this->title?>
            </div>
            <div id="skybox_template_close">
                <a href="javascript:history.back()" class="noajax"><img src="/images/close-x.gif" /></a>
            </div>
        </div>
<?

} else if ( $template_area == 'bottom' ) {

?>

    </div>

    <style>
        #skybox_template {
            background-color: #fff;
            border: 1px solid #999;
            padding: 10px;
            color: #000;
            -webkit-box-shadow: 0 8px 26px -6px black;
               -moz-box-shadow: 0 8px 26px -6px black;
                    box-shadow: 0 8px 26px -6px black;

            }

        #skybox_drag_handle {
            overflow: hidden;
            height: 21px;
            cursor: move;
            margin-bottom: 10px;
        }

        #skybox_template_title {
            float:left;
            font-weight:bold;
            margin-right: 15px;
            font-size: 16px;
        }

        #skybox_template_close {
            float: right;
        }
    </style>

<?
}
