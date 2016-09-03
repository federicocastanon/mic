<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Gantt extends MY_Controller {
	function __construct() {
		parent::__construct();
		// Load the Library
		$this -> load -> helper('url');
		$this -> load -> model('Gantt_model');
	}

	public function index($id = Null) {
		$this -> template_type = 'admin';
		$rdo = '';
		$row = $this -> Gantt_model -> get_gantt_id($id);
		$total = count($row);
		for ($i = 0; $i < $total; $i++) {
			if ($row[$i] -> endIsMilestone) {
				$endMilestone = "true";
			} else {
				$endMilestone = "false";
			}
			if ($row[$i] -> startIsMilestone) {
				$startMilestone = "true";
			} else {
				$startMilestone = "false";
			}
			if ($i == 0) {
				$coma = '';
			} else {
				$coma = ",";
			}
			$aux = $i + 1;
			$var_aux = "-" . $aux;

			if ($row[$i] -> id != '') { 

				$rdo .= $coma . '{"id":' . $var_aux . ',"name":"' . $row[$i] -> name . '","code":"' . $row[$i] -> code . '","level":' . $row[$i] -> level . ',"status":"' . $row[$i] -> status . '","start":' . $row[$i] -> start . ',"duration":' . $row[$i] -> duration . ',"end":' . $row[$i] -> end . ',"startIsMilestone":' . $startMilestone . ',"endIsMilestone":' . $endMilestone . ',"assigs":[],"description":"'.$row[$i] -> description.'","progress":' . $row[$i] -> progress .', "depends":"'.$row[$i] -> depends.'"}';
			}

		}
		
		$vars = array("rdo" => $rdo, "id" => $id);
		$this -> template('gantt/gantt', $vars);
	}

	public function GuardarGantt() {

		$respuesta = array("men" => "Guardado");
		$data = json_decode($_POST["json"],true);
		$iIdArquetipo = $_POST["id"];
		print_r($data);
		die();
		$this -> Gantt_model -> unset_gantt($iIdArquetipo);
		foreach ($data["tasks"] as $key=>$value) {
			if (is_array($value)) {
				foreach ($value as $subKey=>$subValue){
					$array[$subKey] = $subValue;
				}
				$array["assigs"] = "";
				$array["id_arquetipo"] = $iIdArquetipo;
				if (isset($array["id"]) && !empty($array["id"])) {
						$this -> Gantt_model -> set_gantt($array);
					}
				//print_r($array);
			}

		}

		$this -> output -> set_content_type('application/json') -> set_output(json_encode($respuesta));
	}

}
