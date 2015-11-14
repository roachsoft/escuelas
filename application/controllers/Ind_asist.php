<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ind_asist extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ind_asistModel');
    }

    function index()
    {
        $data = array();
        $data['periodos'] = $this->Ind_asistModel->getAllPeriods();
        $data['cursos'] = $this->Ind_asistModel->getAllClassroom();
        $data['actividad'] = $this->Ind_asistModel->getAllActivities();

        $this->load->view('/ind_asistencia/index.php', $data);
    }

    public function getFilterIndAsistencia()
    {
        $periodo=$_POST['periodo_lectivo'];
        $mes=$_POST['mes'];
        $aula=$_POST['curso'];
        $actividad=$_POST['actividad'];

        $data['totalRegistros'] = $this->Ind_asistModel->totalRegistros($mes, $periodo, $aula, $actividad);

        $result = array(
            'Presente' => array(),
            'Ausente'  => array(),
            'Tardanza' => array()
        );

        $cursos = $this->Ind_asistModel->getAllClassroom();
        $i = 0;

        if ($aula === '-') {
            foreach ($cursos as $value) {
                $row = $this->getValuesByCurse($data['totalRegistros'], $value['id']);
                $this->getPercentByCurse($result, $row);
            }
        } else {
            $row = $this->getValuesByCurse($data['totalRegistros'], $aula);

            $this->getPercentByCurse($result, $row);
        }

        echo json_encode($result);
    }

    public function getValuesByCurse(&$totalRegistros, &$id)
    {
        $row['Tardanza'] = 0;
        $row['Ausente']  = 0;
        $row['Presente'] = 0;
        
        foreach ($totalRegistros as $key => $valueTotal) {
            
            if ($id == $valueTotal['aul_id']) {
                $row[$valueTotal['tipasi_descripcion']] = $valueTotal['count'];
            }
        }

        return $row;
    }

    public function getPercentByCurse(&$result, $row)
    {
        $totalAlumnCurso = 0;
        $totalAlumnCurso = $row['Presente']+$row['Tardanza']+$row['Ausente'];

        array_push($result['Presente'], ($totalAlumnCurso > 0) ? round(($row['Presente'] * 100/$totalAlumnCurso), 2) : 0);
        array_push($result['Tardanza'], ($totalAlumnCurso > 0) ? round(($row['Tardanza'] * 100/$totalAlumnCurso), 2) : 0);
        array_push($result['Ausente'],  ($totalAlumnCurso > 0) ? round(($row['Ausente']  * 100/$totalAlumnCurso), 2) : 0);

    }
}
