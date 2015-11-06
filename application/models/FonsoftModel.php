<?php
Class FonsoftModel extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}
	public function totalregistros($mes, $periodo, $aula, $actividad)
	{
		/*
		* Valores por defecto, donde en $actividad corresponde segun la tabla de la bbdd
		* 1	Clase
		* 2	Educacion Fisica
		* 3	Acto Escolar
		* 4	Otros
		*/

		$sqlMes = "";
		$sqlPeriodo = "";
		$sqlAula = "";

		if (!empty($mes) && $mes != '-1') {
			$sqlMes = " AND extract(month from asistencia.asi_fecha)= ".$mes;
		}

		if (!empty($periodo) && $periodo != '-1') {
			$sqlPeriodo = " AND inscripcion.ins_per_lectivo= ".$periodo;
		}

		if (!empty($aula) && $aula != '-1') {
			$sqlAula = " AND inscripcion.aul_id= ".$aula;
		}

		if (!empty($actividad) && $actividad != '-1') {
			$sqlActividad = " AND asistencia.act_id = ".$actividad;
		}

		$qsql = <<<EOQ
		SELECT asistencia.*, 
		       inscripcion.aul_id, 
		       inscripcion.ins_per_lectivo 
		  FROM asistencia 
		  JOIN inscripcion 
		    ON (asistencia.ins_id = inscripcion.ins_id)
		 WHERE 1=1
		  {$sqlMes}
		  {$sqlPeriodo}
		  {$sqlAula}
		  {$sqlActividad}
EOQ;

		$query = $this->db->query($qsql);

		return $query->num_rows();

	}

	public function parcialregistros($mes=10, $periodo=2015, $aula=18, $actividad=1, $tipo_asistencia=4)
	{
		/*
		* Valores por defecto de $tipo_asistencia segun la bbdd de la tabla Tipo_Asistencia
		* 4	Presente	
		* 5	Tardanza	
		* 6	Ausente	
		* 7	Media Falta	
		* 8	Doble Falta	
		*
		*/
		$qsql = 'select asistencia.*, inscripcion.aul_id, inscripcion.ins_per_lectivo from asistencia 
		JOIN inscripcion on (asistencia.ins_id = inscripcion.ins_id) where extract(month from asistencia.asi_fecha)='.$mes.
		' and (inscripcion.ins_per_lectivo='.$periodo.' and inscripcion.aul_id='.$aula.') and asistencia.act_id = '.$actividad.
		' and asistencia.tipasi_id='.$tipo_asistencia;
		$query = $this->db->query($qsql);
		return $query->num_rows();		
	}
}