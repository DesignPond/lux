<?php

class Rjn_abo extends Illuminate\Database\Eloquent\Model {
    public $timestamps    = false;
    protected $table      = 'abo_rjn';
    protected $fillable   = array(
        'numero','user_id','address_id','status','exemplaires','renouvellement','prix_special','date_payement','date_abonnement','date_resiliation','reference','tiers_address','tiers_user','remarque'
    );

    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'uid');
    }

    public function address()
    {
        return $this->belongsTo('Address', 'address_id', 'uid');
    }
}
