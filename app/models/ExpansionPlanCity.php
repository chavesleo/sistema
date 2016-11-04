<?php

class ExpansionPlanCity extends Eloquent {

	protected $table = 'expansion_plan_city';

    public function cities() {
        return $this->belongsToMany('City');
    }

    public function expansionplans() {
        return $this->belongsToMany('ExpansionPlan');
    }

}