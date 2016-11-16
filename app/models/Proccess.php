<?php

class Proccess extends Eloquent {

	protected $table = 'proccess';

	public function candidate() {
		return $this->belongsTo('Candidate');
	}

	public function evaluation() {
		return $this->belongsTo('Evaluation');
	}

    public function answers() {
        return $this->hasMany('ProccessAnswer');
    }

    public function listAllProccessByCompanyId(){

		$processos = DB::table('proccess')
				        ->join('candidate', 'candidate.id', '=', 'proccess.candidate_id')
				        ->select('candidate.*', 'proccess.*')
				        ->where('company_id', Auth::user()->company_id)
				        ->get();

		return $processos;

    }

}