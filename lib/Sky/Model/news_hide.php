<?php

namespace Sky\Model;

class news_item extends \Sky\Model
{

    const AQL = "
        news_hide {
            person_id,
            news_item_id
        }
    ";

    public static $_meta = [];

    public function beforeCheckRequiredFields() {
        if ( !$this->person_id ) {
            $this->person_id = PERSON_ID;
        }
    }

}
