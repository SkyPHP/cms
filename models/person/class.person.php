<?php

class person extends model {

    public $_required_fields = array(
        'fname' => 'First Name',
        'lname' => 'Last Name',
        'email_address' => 'Email Address'
    );

    public $_ignore = array(
        'fields' => array('password')
    );

    public function generateUserSalt() {
        if (!$this->person_ide) return;
        return Login::generateUserSalt($this->person_ide);
    }

    public function set_password($val) {
        if (!$val) return;
        if (!$this->person_ide) return;
        $this->_data['password_hash'] = Login::generateHash($val, $this->generateUserSalt());
    }

    public function updateLastLoginTime() {
        $o = new person();
        $o->loadArray(array(
            'person_ide' => $this->person_ide,
            'last_login_time' => 'now()',
            '_token' => $this->_token
        ));
        $re = $o->save();
        if ($re['status'] == 'OK') $this->last_login_time = $o->last_login_time;
    }

}