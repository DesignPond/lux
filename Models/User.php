<?php namespace Models;

class User extends \Illuminate\Database\Eloquent\Model {
    public $timestamps    = false;
    protected $table      = 'fe_users';
    protected $primaryKey = 'uid';
    protected $fillable   = array(
        'gender','password','last_name','first_name', 'username','email','tx_feext_profession','tx_feext_function','company', 'telephone',
        'fax','address','tx_feext_complement','tx_feext_cp','zip', 'city','country', 'tx_feext_canton'
    );

    public function specialisation()
    {
        return $this->belongsToMany('\Models\Specialisation', 'specialisationusers', 'refUser', 'refSorteSpecialisation');
    }

    public function member()
    {
        return $this->belongsToMany('\Models\Membre', 'membreuser', 'refUser', 'refSorteMembre');
    }
}