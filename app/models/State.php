<?php

class State extends Eloquent {

	protected $table = 'state';

    public function cities() {
        return $this->hasMany('City');
    }

}