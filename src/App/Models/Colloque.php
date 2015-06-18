<?php namespace App\Models;

class Colloque extends \Illuminate\Database\Eloquent\Model {
    public $timestamps    = false;
    protected $table      = 'colloques';
    protected $primaryKey = 'id_Colloque';
    protected $fillable   = array(
        'organisateur','titre','soustitre','description','endroit','dateDebut','dateFin','dateActif',
        'typeColloque','DelaiInscription','remarques','refFacture','visible','nbrInscription','centre_logos'
    );

}
