<?php

class Candidate extends Eloquent {

	protected $table = 'candidate';

    public function proccesses() {
        return $this->hasMany('Proccess');
    }

}