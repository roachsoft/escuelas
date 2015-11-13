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

        $cursos = $this->Ind_asistModel->getAllClassroom();

        foreach ($cursos as $value) {

            foreach ($data['totalRegistros'] as $key => $valueTotal) {
                // echo "<pre>".print_r($valueTotal['tipasi_descripcion'], true)."</pre>";

                if ($value['id'] == $valueTotal['aul_id']) {
                    array_push($row[$valueTotal['tipasi_descripcion']], $valueTotal['count']);
                } else {
                    array_push($row[$valueTotal['tipasi_descripcion']], 0);
                }
            }

            if (!array_key_exists('Tardanza', $row['Tardanza'])) {
                $row['Tardanza'][] = 0;
            }
            if (!array_key_exists('Ausente', $row['Ausente'])) {
                $row['Ausente'][] = 0;
            }
            if (!array_key_exists('Presente', $row['Presente'])) {
                $row['Presente'][] = 0;
            }
        }

        echo json_encode($row);
    }
}
