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
select asist.asi_id,
       alu.alu_id,
       tip_asist.tipasi_descripcion,
       count(asist.asi_id),
       count(alu.alu_id),
       count(mot.mot_descripcion)
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
  group by asist.asi_id, alu.alu_id, tip_asist.tipasi_descripcion, act.act_descripcion
EOQ;

		
		$data = $this->db->query($sql);
		$data = $data->result_array();

		return $data;

	}

	public function getAllPeriods()
	{
		$data = array(
			'2013' => '2013',
			'2014' => '2014',
			'2015' => '2015'
		);

		return $data;
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