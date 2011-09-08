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

    public function construct(){
        $this->addProperty('password1', 'password2', 'current_password'); //password field 1 and 2 (validate same)
    }

    public function preValidate(){
        //password validation
        //we don't give direct access to the person's password field
        //we take password1/password2 (and current password if it's person)

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
                } else { //user trying to change someone else's password
                    //does this person have the rights to change the other person's password?
                    $aql =  "
                                ct_promoter_user {
                                    count(*)
                                    where person_id = ".PERSON_ID." and access_group ilike '%admin%'
                                }
                                ct_promoter_user as u on ct_promoter_user.ct_promoter_id = u.ct_promoter_id {
                                    where person_id = {$this->person_id} and access_group not ilike '%admin%'
                                }
                            ";
                    $rs = aql::select( $aql );
                    if ($rs[0]['count'] != 0 or auth('ct_admin:*') ){
                        $this->password = $this->password1;
                    } else {
                        $this->_errors[] = "Access to change password denied";
                    }
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

        //check for current password. if not, then check that users has access to change password
        //person logged in either a ct_admin (auth('ct_admin:*'))
        //or that this person shares a ct_promoter with the user
        //

        //same password

        //$this->password = password1

        //if original password is not set, return
        //

    }

    public function generateUserSalt() {
        if (!$this->person_ide) return;
        return Login::generateUserSalt($this->person_ide);
    }

    public function set_password($val) {
        if (!$val) return;
        if (!$this->person_ide) {
            if (!$this->person_id) return;
            $this->person_ide = $ide = encrypt($this->person_id, 'person');
        }
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

    //fetches all the (quick) logins to promoters that this person has
    //as an array of hashed each with promoter_name, ct_promoter_user_id/e, ct_promoter_id/e)
    public function getLogins(){
        $aql =  "ct_promoter_user {
                    where person_id = {$this->person_id}
                    order by ct_promoter_user.iorder asc 
                    }
                    ct_promoter {
                    name as promoter_name
                }";
        return aql::select($aql);
    }

    //add quick login to some promoter to this person given that promoter_ide
    public function addLogin($ct_promoter_ide = null){
        $new_login = new ct_promoter_user();
        return $new_login->loadArray(array(
            'person_id' => $this->person_id,
            'ct_promoter_ide' => $ct_promoter_ide
        ))->save();
    }
}