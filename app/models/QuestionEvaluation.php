<?php

class QuestionEvaluation extends Eloquent {

	protected $table = 'question_evaluation';

    public function evaluation(){
        return $this->belongsTo('Evaluation');
    }

    public function question(){
	    return $this->belongsTo('Question');
    }

}