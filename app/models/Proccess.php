<?php

class Proccess extends Eloquent {

	protected $table = 'proccess';

	public function candidate() {
		return $this->belongsTo('Candidate');
	}

	public function evaluation() {
		return $this->belongsTo('Evaluation');
	}

	public function scopeIniciados($query) {
		return $query->where('status', 'like', 'i');
	}

}