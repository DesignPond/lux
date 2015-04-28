<?php

class File extends Illuminate\Database\Eloquent\Model {
    public $timestamps  = false;
    protected $table    = 'files';
    protected $fillable = array('filename','TitreFichier','typeFile','refColloque');
}
