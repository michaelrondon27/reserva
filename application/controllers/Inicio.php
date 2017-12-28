<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

	public function index()
	{
		$this->load->view('cpanel/header');
        $this->load->view('cpanel/menu');
		$this->load->view('index');
		$this->load->view('cpanel/footer');
	}

}
