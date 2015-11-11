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

        $totalAusentes = 0;
        $totalPresente = 0;
        $totalTardanza = 0;

        $data['totalRegistros'] = $this->Ind_asistModel->totalRegistros($mes, $periodo, $aula, $actividad);
        $row['Tardanza'] = array();
        $row['Ausente'] = array();
        $row['Presente'] = array();

        foreach ($data['totalRegistros'] as $value) {
            $row[$value['tipasi_descripcion']][$value['aul_id']][] = $value['count'];
        }

        if (count($row['Tardanza']) == 0) {
            $row['Tardanza'][] = 0;
        }

        if (count($row['Ausente']) == 0) {
            $row['Ausente'][] = 0;
        }

        if (count($row['Presente']) == 0) {
            $row['Presente'][] = 0;
        }

        echo json_encode($row);
    }
}
