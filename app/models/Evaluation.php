<?php

class Evaluation extends Eloquent {

	protected $table = 'evaluation';

    public function company(){
        return $this->belongsTo('Company');
    }

}
