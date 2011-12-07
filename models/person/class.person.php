<?php

class person extends model {

    public $_required_fields = array(
        'fname' => 'First Name',
        'lname' => 'Last Name',
        'email_address' => 'Email Address'
    );

    public function construct(){
        $this->addProperty('password1', 'password2', 'current_password'); //password field 1 and 2 (validate same)
    }

    public function set_email_address($val) {
        if (!$val) return;
        $this->_data['email_address'] = trim($val);
    }

    public function preValidate(){
        //password validation
        //we don't give direct access to the person's password field
        //we take password1/password2 (and current password if it's person)

        if ($this->isInsert()) {
            if (!$this->password1 && !$this->password2) {
                // do nothing
            } else if ($this->password1 != $this->password2) {
                $this->_errors[] = 'The passwords you entered do not match.';
            } else {
                $this->password = $this->password1;
            }
            return;
        }


        if ($this->password1) { //is there a password change request?
            if ($this->password1 != $this->password2) {
                $this->_errors[] = "Passwords do not match";
                return;
            }
            if (defined('PERSON_ID')) {
                 if (PERSON_ID == $this->person_id) { //is this a user trying to change his own pw?
                    //authenticate him (test his current_password)
                    if ( Login::generateHash($this->current_password, $this->generateUserSalt() ) !=  aql::value( 'person.password_hash', PERSON_ID )  ) {
                        $this->_errors[] = "Incorrect password";
                        return;
                    }
                    //otherwise, self password change is valid
                    //set it up for the set_password() method below
                    $this->password = $this->password1;
                } 
            } else {
                // trying to reset your own password but not logged in
                if ($this->password_reset_hash != aql::value( 'person.password_reset_hash', $this->person_id ) ) {
                    $this->_errors[] = 'Request another password reset, this token is no longer valid.';
                    $o = new person;
                    $o->person_id = $this->person_id;
                    $o->_token = $this->_token;
                    $o->password_reset_hash = null;
                    $o->save();
                    return;
                }
                $this->password = $this->password1;
            }
        }

    }

    // public function after_save($arr = array()) {
    //     return parent::after_save($arr);
    // }

    public function after_insert() {
        $this->_postSavePassword();
        $this->reload();
    }

    public function generateResetHash() {
        if (!$this->person_id) return;
        $o = new person;
        $o->person_id = $this->person_id;
        $o->_token = $o->getToken();
        $o->password_reset_hash = $this->makeResetHash();
        $re = $o->save();
        if ($re['status'] == 'OK') {
            $this->password_reset_hash = $o->password_reset_hash;
        }
        return $re;
    }

    public function makeResetHash() {
        return sha1(mt_rand());
    }

    private function _postSavePassword() {
        if (!$this->password) {
            return;
        }
        
        return $this->saveProperties(array(
            'password' => $this->password
        ));
    }

    public function generateUserSalt() {
        if (!$this->getIDE()) return;
        return Login::generateUserSalt($this->getIDE());
    }

    public function set_password($val) {
        if (!$val) return;
        if (!$this->getIDE()) return;
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