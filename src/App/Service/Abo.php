<?php namespace App\Service;

use App\Models\RjnAbo;
use App\Models\Product;
use App\Models\RjnPayement;

class Abo{

    protected $users;

    public function __construct()
    {
        $this->users = new RjnAbo();
    }

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

        $payed = RjnPayement::where('numero','=',$numero)->where('rappel','=',0)->where('rjn','=',$rjn)->get();

        if(!$payed->isEmpty())
        {
            return $payed->first();
        }

        return false;
    }

    public function getAllUsers()
    {

        return  $this->users->with(array(
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

        return  $this->users->where('numero','=',$numero)->with(array(
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

    public function aboIsActive($abo)
    {
        return ( $this->startsWith($abo->date_resiliation, '0000') ? true : false );
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle)
        {
            if ($needle != '' && strpos($haystack, $needle) === 0) return true;
        }

        return false;
    }

}