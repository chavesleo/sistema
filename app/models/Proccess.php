<?php

class Proccess extends Eloquent {

	protected $table = 'proccess';

	public function candidate() {
		return $this->belongsTo('Candidate');
	}

	public function evaluation() {
		return $this->belongsTo('Evaluation');
	}

}