<?php

class Rjn_payement extends Illuminate\Database\Eloquent\Model {
    public $timestamps    = false;
    protected $table      = 'abo_rjn_payements';
    protected $fillable   = array( 'numero','date_payement','rjn','rappel' );
}
