<?php
Class Ind_asistModel extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}
	public function totalRegistros($mes, $periodo, $aula, $actividad)
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

		if (!empty($mes) && $mes != '-') {
			$sqlMes = " AND extract(month from asist.asi_fecha) = ".$mes;
		}

		if (!empty($periodo) && $periodo != '-') {
			$sqlPeriodo = " AND inscrip.ins_per_lectivo = ".$periodo;
		}

		if (!empty($aula) && $aula != '-') {
			$sqlAula = " AND inscrip.aul_id = ".$aula;
		}

		if (!empty($actividad) && $actividad != '-') {
			$sqlActividad = " AND asistencia.act_id = ".$actividad;
		}

		$sql = <<<EOQ
select tip_asist.tipasi_descripcion,
       inscrip.aul_id,
       count(tip_asist.tipasi_descripcion),
       count(inscrip.aul_id)
  from asistencia asist
  join alumno alu
       on alu.alu_id = asist.alu_id
  join inscripcion inscrip
       on inscrip.alu_id = alu.alu_id
  join actividad act
       on act.act_id = asist.act_id
  join motivo mot
       on mot.mot_id = asist.mot_id
  join tipo_asistencia tip_asist
       on tip_asist.tipasi_id = asist.tipasi_id
where 1=1
  {$sqlMes}
  {$sqlPeriodo}
  {$sqlAula}
  group by tip_asist.tipasi_descripcion, inscrip.aul_id
  order by inscrip.aul_id
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
