<?php
Class Ind_asistModel extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}
	public function TotalRegistros($mes, $periodo, $aula, $actividad)
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
		$sqlActividad = "";

		if (!empty($mes) && $mes != '-1') {
			$sqlMes = " AND extract(month from asistencia.asi_fecha)= ".$mes;
		}

		if (!empty($periodo) && $periodo != '-1') {
			$sqlPeriodo = " inscripcion.ins_per_lectivo= ".$periodo;
		} else {
			$sqlPeriodo = " 1=1 ";
		}

		if (!empty($aula) && $aula != '-1') {
			$sqlAula = " AND inscripcion.aul_id= ".$aula;
		} else {
			$sqlAula = " AND inscripcion.aul_id= 18 ";
		}

		if (!empty($actividad) && $actividad != '-1') {
			$sqlActividad = " AND asistencia.act_id = ".$actividad;
		}

		$sql = <<<EOQ
SELECT tipasi_descripcion, 
       total, 
       ROUND(total::numeric*100/total_general::numeric) AS porcentaje 
FROM (
      SELECT COUNT(*) as total, 
             tipasi_descripcion, 
             (SELECT count(*) 
                FROM (SELECT merge.alu_id, 
                             ta.tipasi_descripcion 
                        FROM tipo_asistencia ta,
                             (SELECT asistencia.*, 
                                     a.usr_id 
                                FROM asistencia 
                                JOIN inscripcion ON (asistencia.ins_id = inscripcion.ins_id) 
                               WHERE 1=1
                                 {$sqlMes} AND ({$sqlPeriodo} {$sqlAula}) 
                                 {$sqlActividad}) merge 
                         WHERE ta.tipasi_id = merge.tipasi_id) tablatest) AS total_general 
      FROM (SELECT merge.alu_id, 
                   ta.tipasi_descripcion 
              FROM tipo_asistencia ta,
                   (SELECT asistencia.*, 
                           inscripcion.usr_id 
                      FROM asistencia JOIN inscripcion ON (asistencia.ins_id = inscripcion.ins_id)
                     WHERE 1=1
                       {$sqlMes} 
                       AND ({$sqlPeriodo} {$sqlAula}) 
                       {$sqlActividad}) merge 
      WHERE ta.tipasi_id = merge.tipasi_id) test 
      GROUP BY tipasi_descripcion) AS foo
EOQ;

		$data = $this->db->query($sql);
		$data = $data->result_array();

		return $data;

	}

	public function getAllPeriods()
	{
		$qsql = "select distinct(ins_per_lectivo) from inscripcion";
		$query = $this->db->query($qsql);
		return $query->row_array();
	}


	public function getAllClassroom()
	{
		$sql = <<<EOQ
	SELECT aul_id
	  FROM aula
	  order by aul_id
EOQ;
		$data = $this->db->query($sql);
		$data = $data->result_array();

		foreach ($data as $value) {
			$row[] = array(
						'id' => $value['aul_id'],
						'name' => 'AULA_'.$value['aul_id']
					 );
		}

		return (count($row) < 1 ? array() : $row);
	}

	public function getAllActivities()
	{
		$sql = <<<EOQ
SELECT act_id as id, 
       act_descripcion as name 
  FROM actividad;
EOQ;
		$data = $this->db->query($sql);
		$data = $data->result_array();

		return (count($data) < 1 ? array() : $data);
	}
}
