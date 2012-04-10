<?

class news_hide extends Model {

    public function preValidate() {
        if ( !$this->person_id ) {
            $this->person_id = PERSON_ID;
        }
    }

}