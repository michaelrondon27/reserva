<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->model('Usuarios_model');
	}

	public function index()
	{
		if ($this->session->userdata("login")) {
			redirect(base_url()."inicio");
		} else {
			$this->load->view('cpanel/header');
			$this->load->view('admin/login');
			$this->load->view('cpanel/footer');
		}
	}

	public function login()
	{
		$correo_usuario = $this->input->post('correo_usuario');
		$clave_usuario = $this->input->post('clave_usuario');
		$res = $this->Usuarios_model->login($correo_usuario, sha1($clave_usuario));
		if (!$res) {
			redirect(base_url());
		} else {
			$data = array(
				'id_usuario' => $res->id_usuario,
				'correo_usuario' => $res->correo_usuario,
				'avatar_usuario' => $res->avatar_usuario,
				'id_rol' => $res->id_rol,
				'nombre' => $res->nombre_datos_personales . " " . $res->apellido_p_datos_personales . " " . $res->apellido_m_datos_personales ,
				'login' => TRUE,
			);
			$this->session->set_userdata($data);
			$this->Usuarios_model->ultima_conexion($this->session->userdata('id_usuario'));
			redirect(base_url()."inicio");
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url());
	}

}