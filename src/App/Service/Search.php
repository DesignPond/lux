<?php namespace App\Service;

use App\Models\User;

class Search{

    protected $user;
    public  $specialisation;
    public $membre;
    protected $reader;

    public function __construct()
    {
        $this->user = new User();
        $this->specialisation = null;
        $this->membre = null;
        $this->reader  = new \App\Service\Reader();
    }

    public function setSpecialisation($specialisation){

        $this->specialisation = $specialisation;

        return $this;
    }

    public function setMembre($membre){

        $this->membre = $membre;

        return $this;
    }

    public function findUser($params){

        $user = $this->user->with(array('specialisation','member'));

        if(isset($params['email']))
        {
            $user->where('email', '=' ,$params['email']);
        }

        if(isset($params['first_name']) && $params['last_name'])
        {
            $user->where(function ($query) use ($params){
                $query->where('first_name', 'LIKE', '%'.$params['first_name'].'%')->where('last_name', 'LIKE',  '%'.$params['last_name'].'%');
            });
        }

        $row = $user->get(array('uid','last_name','first_name','email'));

        if(!$row->isEmpty())
        {
            return $row->first();
        }

    }

    public function searchUser($id)
    {
        $user = $this->user->where('uid', '=', $id)->with(array('specialisation','member'));

        if($this->specialisation)
        {
            $specs = $this->specialisation;
            $user->whereHas('specialisation', function ($q) use ($specs) {
                $q->whereIn('refSorteSpecialisation', array($specs));
            });
        }

        if($this->membre)
        {
            $membres = $this->membre;
            $user->whereHas('member', function ($q) use ($membres) {
                $q->whereIn('refSorteMembre', array($membres));
            });
        }

        $row = $user->get(array('uid','last_name','first_name','email'));

        if(!$row->isEmpty())
        {
            return $row->first()->toArray();
        }
    }

    public function search($data){

        $users = array();

        if(!empty($data))
        {
            foreach($data as $line => $result)
            {
                $params['first_name'] = $result[2];
                $params['last_name']  = $result[3];
                $params['email']      = $result[4];

                $user = $this->findUser($params);

                if(!empty($user))
                {
                    $userHas = $this->searchUser($user->uid);

                    if(!empty($userHas))
                    {
                        $users['has'][$line]  = $userHas;
                    }
                    else
                    {
                        $this->addSpecialisation($user->uid, $this->specialisation);
                    }
                }
                else
                {
                    $users['notfound'][$line] = $result;
                }

            }
        }

        if(isset($users['notfound']))
        {
            $this->reader->createExcel($users['notfound']);
        }

        return $users;
    }

    public function addSpecialisation($user, $specialisation){

        $theuser  = $this->user->where('uid','=',$user)->with(array('specialisation'))->get()->first();
        $specs    = $theuser->specialisation->lists('id_Specialisation');

        if(!in_array($specialisation,$specs))
        {
            $theuser->specialisation()->attach($specialisation, array('refUser' => $user));
        }

        return true;

    }
}