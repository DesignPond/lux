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

    public function aboIspayedForUser($numero){

        return $this->getAboUserRjn($numero,$this->getLastRjnYear());

    }

}