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

        foreach ($data['totalRegistros'] as $key => $value) {
            switch ($value['tipasi_descripcion']) {
                case 'Tardanza':
                    $totalTardanza++;
                    break;
                case 'Presente':
                    $totalPresente++;
                    break;
                case 'Ausente':
                    $totalAusentes++;
                    break;
            }
        }

        echo json_encode($data['totalRegistros']);
    }
}
