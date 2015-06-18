<?php namespace App\Models;

class Address extends \Illuminate\Database\Eloquent\Model {
    public $timestamps    = false;
    protected $table      = 'tt_address';
    protected $fillable   = array(
        'gender','last_name','first_name','tx_feext_profession','company','phone','mobile','address',
        'tx_feext_complement', 'tx_feext_cp','zip','city', 'country','tx_feext_canton','email'
    );
}
