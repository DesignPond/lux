<?php

class Auth{

    protected $user;
    protected $salt;

    public function __construct()
    {
        $this->user = new User;
        $this->salt ='whatever_you_want';
    }

    public function authUser($email,$password){

        return $this->user->where('email','=',$email)->where('password','=',$password)->select('fe_users.uid','fe_users.email','fe_users.gender','fe_users.first_name','fe_users.last_name')->get();
    }

    function simple_encrypt($text)
    {
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    function simple_decrypt($text)
    {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

}