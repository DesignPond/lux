<?php namespace Models;

class Product extends \Illuminate\Database\Eloquent\Model {
    public $timestamps    = false;
    protected $table      = 'tx_commerce_products';
    protected $primaryKey = 'uid';
    protected $fillable   = array('tstamp','title');
}
