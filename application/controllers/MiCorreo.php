<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MiCorreo extends CI_Controller
{
	function __construct()
	{
    parent::__construct();
    $this->load->database();
    $this->load->model('MiCorreo_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('mi correo', 1);
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas(1);
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('configuracion/MiCorreo/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function buscar_mi_correo()
  {
    $datos=$this->MiCorreo_model->buscar_mi_correo();
    echo json_encode($datos);
  }

  public function actualizar_mi_correo()
  {
    $this->reglas_mi_correo();
    $this->mensajes_reglas_mi_correo();
    if($this->form_validation->run() == true){
      $data=array(
        'servidor_smtp' => $this->input->post('servidor_smtp'),
        'puerto' => $this->input->post('puerto'),
        'usuario' => $this->input->post('usuario'),
        'clave' => $this->input->post('clave'),
      );
      $this->MiCorreo_model->actualizar_mi_correo($this->input->post('id_mi_correo'), $data);
      echo json_encode("<span>Correo editado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_mi_correo()
  {
    $this->form_validation->set_rules('servidor_smtp','Servidor SMTP','required');
    $this->form_validation->set_rules('puerto','Puerto','required|max_length[4]');
  }

  public function mensajes_reglas_mi_correo(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
  }

}//Fin class Bancos