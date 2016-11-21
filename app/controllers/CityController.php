<?php

class CityController extends BaseController {

	public function ajaxListByStateId($stateId){

		$retorno = '<option value="">Selecione uma Cidade</option>';

		$arrCities = City::where('state_id', $stateId)
						   ->orderBy('name', 'asc')->get();

		foreach ($arrCities as $dadosCity) {
			$retorno .= '<option value="'.$dadosCity->id.'">'.$dadosCity->name.'</option>';
		}

		echo $retorno;

	}

	/*
	* Função criada por terceiros que calcula a distância entre duas coordenadas 
	* levando em consideração a curvatura da terra
	*/
	public static function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {
		$earth_radius = 6371;
		$dLat = deg2rad($latitude2 - $latitude1);
		$dLon = deg2rad($longitude2 - $longitude1);
		$a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
		$c = 2 * asin(sqrt($a));
		$d = $earth_radius * $c;
		return $d;
	}

}
