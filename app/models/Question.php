<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Question extends Eloquent {

	use SoftDeletingTrait;

	protected $table = 'question';

    public function company(){
        return $this->belongsTo('Company');
    }

    public function options() {
        return $this->hasMany('Option');
    }

}
