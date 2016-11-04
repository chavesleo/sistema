<?php

class CityController extends BaseController {

	public function ajaxListByStateId($stateId){

		$retorno = '<option value="todas">Todas as Cidades</option>';

		$arrCities = City::where('state_id', $stateId)
						   ->orderBy('name', 'asc')->get();

		foreach ($arrCities as $dadosCity) {
			$retorno .= '<option value="'.$dadosCity->id.'">'.$dadosCity->name.'</option>';
		}

		echo $retorno;

	}

}
