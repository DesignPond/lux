<?php namespace App\Models;

class Address extends \Illuminate\Database\Eloquent\Model {
    public $timestamps    = false;
    protected $table      = 'tt_address';
    protected $fillable   = array(
        'pid','tstamp','gender','last_name','first_name','name','tx_feext_profession','company','phone','mobile','address',
        'tx_feext_complement', 'tx_feext_cp','zip','city', 'country','tx_feext_canton','email'
    );

    public function specialisation()
    {
        return $this->belongsToMany('\App\Models\Specialisation', 'specialisationusers', 'refUserAdresse', 'refSorteSpecialisation');
    }

    public function member()
    {
        return $this->belongsToMany('\App\Models\Membre', 'membreuser', 'refUserAdresse', 'refSorteMembre');
    }
}
