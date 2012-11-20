<?

class news_hide extends Model {

    public function beforeCheckRequiredFields() {
        if ( !$this->person_id ) {
            $this->person_id = PERSON_ID;
        }
    }

}
