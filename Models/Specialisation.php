<?php namespace Models;

class Specialisation extends \Illuminate\Database\Eloquent\Model {

    public $timestamps    = false;
    protected $primaryKey = 'id_Specialisation';
    protected $table      = 'specialisations';
    protected $fillable   = array( 'TitreSpecialisation' );

}