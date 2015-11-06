<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ind_asist extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('FonsoftModel');
    }

    function index()
    {
        $this->load->view('/ind_asistencia/index.php');
    }

    public function getFilterIndAsistencia($mes=10, $periodo=2015, $aula=18, $actividad=1)
    {

        $mes=$_POST['periodo_lectivo'];
        $periodo=$_POST['mes'];
        $aula=$_POST['curso'];
        $actividad=$_POST['actividad'];

        $total = $this->FonsoftModel->totalregistros($mes, $periodo, $aula, $actividad);

        var_dump($total);

        return json_encode($_POST);
    }
}
