<?php namespace Service;

use Models\User;

class Search{

    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function searchUser($params)
    {
        $user = $this->user->with(array('specialisation','member'));

        $user->where('email', '=' ,$params['email']);

        if(isset($params['first_name']) && $params['last_name'])
        {
            $user->orWhere(function ($query) use ($params){
                $query->where('first_name', 'LIKE', '%'.$params['first_name'].'%')->where('last_name', 'LIKE',  '%'.$params['last_name'] .'%');
            });
        }

        return $user->get();

    }
}