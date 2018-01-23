<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->model('Menu_model');
		if (!$this->session->userdata("login")) {
			redirect(base_url());
		}
	}

	public function index()
	{
	    $data['modulos'] = $this->Menu_model->modulos();
	    $data['vistas'] = $this->Menu_model->vistas(1);
		$this->load->view('cpanel/header');
		$this->load->view('cpanel/menu', $data);
		$this->load->view('admin/index');
		$this->load->view('cpanel/footer');
	}

}
