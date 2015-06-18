<?php namespace App\Models;

class RjnPayement extends \Illuminate\Database\Eloquent\Model {
    public $timestamps    = false;
    protected $table      = 'abo_rjn_payements';
    protected $fillable   = array( 'numero','date_payement','rjn','rappel' );

    public function numero()
    {
        return $this->belongsTo('Rjn_abo', 'numero', 'numero');
    }

}
