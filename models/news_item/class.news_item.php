<?

class news_item extends model {

	public function construct() {
        // get the fname and lname of the mod__person_id
        $r = aql::value("person{fname,lname}",$this->mod__person_id);
        $this->addProperty('modified_by');
        $this->modified_by = $r['fname'] . ' ' . $r['lname'];
	}

}