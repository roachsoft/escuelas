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

        $data['totalRegistros'] = $this->Ind_asistModel->TotalRegistros($mes, $periodo, $aula, $actividad);

        

        return json_encode($_POST);
    }
}
