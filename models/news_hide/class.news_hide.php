<?

class news_hide extends model {

    public function preValidate() {
        if ( !$this->person_id ) {
            $this->person_id = PERSON_ID;
        }
    }

}