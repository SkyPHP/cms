<?php

namespace Sky\Model;

class person_cookie extends \Sky\Model
{

    const AQL = "
        person_cookie {
            person_id,
            cookie,
            token
        }
    ";

    /**
     *
     */
    public static $_meta = array(

        'requiredFields' => array(
            'cookie' => 'Cookie',
            'person_id' => 'Person',
            'token' => 'Token'
        )

    );

    protected $_use_token_validation = false;

    public static function getByCookie($cookie = null, $person_id = null) {
        if (!$cookie && !$person_id) {
            if (!$_COOKIE['person_ide'] && !$_COOKIE['cookie']) return;
            $person_id = decrypt($_COOKIE['person_ide'], 'person');
            if (!$person_id) return;
            $cookie = addslashes(trim($_COOKIE['cookie']));
            if (preg_match('/[^a-zA-Z0-9]/im', $cookie)) return;
        }
        return person_cookie::getMany(array(
            'where' => "cookie = '{$cookie}' and person_id = {$person_id}",
            'limit' => 1
        ));
    }

    public static function create($person_id) {
        $tmp = new person_cookie;
        $tmp->person_id = $person_id;
        $tmp->cookie = self::randomHash();
        $tmp->token = self::randomHash();
        $re = $tmp->save();
        foreach (array('person_ide', 'cookie', 'token') as $c) {
            self::setCookie($c, $tmp->{$c});
        }
        return $re;
    }

    public static function randomHash() {
        return sha1(mt_rand());
    }

    public function checkToken($token = null) {
        if (!$token) $token = $_COOKIE['token'];
        if ($token == $this->token) {
            $this->token = $this->randomHash();
            $this->save();
            self::setCookie('token', $this->token);
            return true;
        } else {
            self::unsetAllSessions($this->person_id);
            return false;
        }
    }

    public static function setCookie($key, $value = null, $time = null) {
        global $cookie_domain;
        if (!$time) $time = time() + 5184000;
        @setcookie($key, $value, $time, '/', $cookie_domain);
    }

    public static function unsetCookie($key) {
        self::setCookie($key, '', time() - 3600);
    }

    public static function unsetAllSessions($person_id) {
        $os = person_cookie::getByClause(array(
            'where' => 'person_id = '.$person_id
        ));
        foreach ($os as $o) {
            $o->delete();
        }
    }

}
