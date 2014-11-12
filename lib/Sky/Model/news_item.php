<?php

namespace Sky\Model;

class news_item extends \Sky\Model
{

    const AQL = "
        news_item {

            insert_time,
            category,
            json,
            mod__person_id

            news_who {
                who
            }

        }
    ";

    public static $_meta = [];

    public function construct() {
        // get the fname and lname of the mod__person_id
        $r = \aql::value("person{fname,lname}",$this->mod__person_id);
        $this->addProperty('modified_by');
        $this->modified_by = $r->fname . ' ' . $r->lname;
        $this->addProperty('message');
        $this->message = $this->getMessage();
    }

    public function getLayoutPath() {
        //d($this);
        return sprintf('/lib/news/layouts/%s.php', $this->category);
    }

    public function getMessage() {
        $json = json_decode($this->json);
        ob_start();
        include $this->getLayoutPath();
        $message = ob_get_contents();
        ob_end_clean();
        return $message;
    }
}


