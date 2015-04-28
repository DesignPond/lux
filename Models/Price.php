<?php

class Price extends Illuminate\Database\Eloquent\Model {
    public $timestamps    = false;
    protected $table      = 'prix';
    protected $primaryKey = 'id_Prix';
    protected $fillable   = array('refTypePrix','refId','remarquePrix','Prix','rangPrix','refColloque');
}
