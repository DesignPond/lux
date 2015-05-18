<?php

class User extends Illuminate\Database\Eloquent\Model {
    public $timestamps    = false;
    protected $table      = 'fe_users';
    protected $fillable   = array(
        'gender','last_name','first_name', 'username','email','tx_feext_profession','tx_feext_function','company', 'telephone',
        'fax','address','tx_feext_complement','tx_feext_cp','zip', 'city','country', 'tx_feext_canton'
    );
}
