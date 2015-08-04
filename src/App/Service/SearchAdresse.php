<?php namespace App\Service;

use App\Models\Address;

class SearchAdresse{

    protected $user;
    public  $specialisation;
    public $membre;
    protected $reader;

    public function __construct()
    {
        $this->user    = new Address();
        $this->specialisation = null;
        $this->membre  = null;
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
            $theuser->specialisation()->attach($specialisation, array('refUser' => 0,'refUserAdresse' => $user));
        }

        return true;
    }

    /**
     *  Add one user
     */
    public function addUser($datas,$specialisation = null){

        if(!empty($datas))
        {
            foreach($datas as $data)
            {
                $one = new Address();

                $one->gender      = (isset($data[1]) && !empty($data[1]) ? $data[1] : '');
                $one->tstamp      = time();
                $one->pid         = 44;
                $one->first_name  = (isset($data[2]) && !empty($data[2]) ? $data[2] : '');
                $one->last_name   = (isset($data[3]) && !empty($data[3]) ? $data[3] : '');
                $one->email       = (isset($data[4]) && !empty($data[4]) ? $data[4] : '');
                $one->name        = (isset($data[2]) && !empty($data[2]) && isset($data[3]) && !empty($data[3]) ? $data[2].' '.$data[3] : '');
                $one->company     = (isset($data[5]) && !empty($data[5]) ? $data[5] : '');
                $one->address     = (isset($data[9]) && !empty($data[9]) ? $data[9] : '');
                $one->tx_feext_cp = (isset($data[10]) && !empty($data[10]) ? $data[10] : '');
                $one->zip         = (isset($data[11]) && !empty($data[11]) ? $data[11] : '');
                $one->city        = (isset($data[12]) && !empty($data[12]) ? $data[12] : '');

                $one->save();

                if($specialisation)
                {
                    $this->addSpecialisation($one->id, $specialisation);
                }

                $ids[] = $one->id;
            }
        }

        return $ids;
    }
    
}