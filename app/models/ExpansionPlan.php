<?php

class ExpansionPlan extends Eloquent {

	protected $table = 'expansion_plan';

    public function company(){
        return $this->belongsTo('Company');
    }

}
