<?php namespace Models;

class Membre extends \Illuminate\Database\Eloquent\Model {

    public $timestamps    = false;
    protected $primaryKey = 'id_Membre';
    protected $table      = 'membres';
    protected $fillable   = array( 'TitreMembre' );

}