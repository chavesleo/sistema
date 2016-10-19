<?php

class Company extends Eloquent {

	protected $table = 'company';

    public function users() {
        return $this->hasMany('User');
    }

    public function expansionPlans() {
        return $this->hasMany('ExpansionPlan');
    }

    public function evaluations() {
        return $this->hasMany('Evaluation');
    }

    public function questions() {
        return $this->hasMany('Question');
    }

    public function segment(){
        return $this->belongsTo('Segment');
    }

}
