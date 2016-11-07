<?php

class Evaluation extends Eloquent {

	protected $table = 'evaluation';

    public function company(){
        return $this->belongsTo('Company');
    }

    public function QuestionEvaluations() {
        return $this->hasMany('QuestionEvaluation');
    }

}
