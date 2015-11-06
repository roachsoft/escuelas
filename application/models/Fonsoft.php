<?php
Class fonsoft extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('FonsoftModel');
		$this->load->library('form_validation');
		$this->load->helper(array('url','form'));
	}	

	public function grafico()
	{
		$total = $this->FonsoftModel->totalregistros();
		echo "TOTAL REGISTROS = ".$total."<br>";
		$parcial = $this->FonsoftModel->parcialregistros();
		echo "PARCIAL REGISTROS = ".$parcial."<br>";
		$porcentaje = $parcial/$total*100;
		echo "Porcentaje = ".$porcentaje."%";
	}
}