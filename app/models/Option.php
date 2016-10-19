<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Option extends Eloquent {

	use SoftDeletingTrait;

	protected $table = 'option';

    public function question(){
        return $this->belongsTo('Question');
    }

}
