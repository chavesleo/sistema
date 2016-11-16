<?php

class ProccessAnswer extends Eloquent {

	protected $table = 'proccess_answer';

	public function proccess() {
		return $this->belongsTo('Proccess');
	}

}