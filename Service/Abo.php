<?php
class Abo{

    public function getLastRjnYear(){

        $rjn = Product::where('title','LIKE','Recueil de jurisprudence neuchâteloise %')->orderBy('tstamp', 'desc')->skip(0)->take(1)->get();

        if(!$rjn->isEmpty())
        {
            $rjn = $rjn->first();

            $rjn_number = trim(str_replace("Recueil de jurisprudence neuchâteloise ", "", $rjn->title));

            return $rjn_number;
        }

        return false;
    }

    public function getAboUserRjn($numero,$rjn){

        $payed = Rjn_payement::where('numero','=',$numero)->where('rjn','=',$rjn)->get();

        if(!$payed->isEmpty())
        {
            return $payed->first();
        }

        return false;
    }

    public function getAllUsers()
    {
        $users = new Rjn_abo();

        return $users->with(array(
            'user' => function($query)
            {
                $query->select('fe_users.uid','fe_users.email','fe_users.gender','fe_users.first_name','fe_users.last_name');
            },
            'address' => function($query)
            {
                $query->select('tt_address.uid','tt_address.email','tt_address.gender','tt_address.first_name','tt_address.last_name');

            }))->get();
    }

    public function getUser($numero){

        $user = new Rjn_abo();

        return $user->where('numero','=',$numero)->with(array(
            'user' => function($query)
                {
                    $query->select('fe_users.uid','fe_users.email','fe_users.gender','fe_users.first_name','fe_users.last_name');
                },
            'address' => function($query)
                {
                    $query->select('tt_address.uid','tt_address.email','tt_address.gender','tt_address.first_name','tt_address.last_name');

                }))->get();
    }

    public function aboIspayedForUser($numero){

        return $this->getAboUserRjn($numero,$this->getLastRjnYear());
    }

    public function usersHaveEmail($all){

        if(!$all->isEmpty()){

            $users = $all->filter(function($user)
            {
                if ( (isset($user->user) && $user->user->email != '') || (isset($user->address) && $user->address->email != '') ) {
                    return true;
                }
            });

            return $users;
        }

        return false;
    }

}