<?php

class Segment extends Eloquent {

	protected $table = 'segment';

    public function companies() {
        return $this->hasMany('Company');
    }

}