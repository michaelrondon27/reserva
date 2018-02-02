<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Esquemas extends CI_Controller
{
	function __construct()
	{
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
    $this->load->model('Esquemas_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url());
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Esquemas', $this->session->userdata('id_rol'));
    $datos['tipos_esquemas'] = $this->Esquemas_model->tipos_esquemas();
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('Esquemas');
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/Esquemas/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_esquema()
  {
    $listado = $this->Esquemas_model->listado_esquema();
    echo json_encode($listado);
  }

  public function registrar_esquema()
  {
    $this->reglas_esquema('insert');
    $this->mensajes_reglas_esquema();
    if($this->form_validation->run() == true){
      $data = array(
        'tipo' => $this->input->post('tipo'),
        'cod_esquema' => mb_strtoupper($this->input->post('cod_esquema')),
        'descripcion' => mb_strtoupper($this->input->post('descripcion')),
      );
      $this->Esquemas_model->registrar_esquema($data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_esquema()
  {
    $this->reglas_esquema('update');
    $this->mensajes_reglas_esquema();
    if($this->form_validation->run() == true){
      $data = array(
        'tipo' => $this->input->post('tipo'),
        'cod_esquema' => mb_strtoupper($this->input->post('cod_esquema')),
        'descripcion' => mb_strtoupper($this->input->post('descripcion')),
      );
      $this->Esquemas_model->actualizar_esquema($this->input->post('id_esquema'), $data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_esquema($method)
  {
    if ($method == 'insert'){
      $this->form_validation->set_rules('tipo','Tipo','required');
      $this->form_validation->set_rules('cod_esquema','Código de Esquema','required|is_unique[esquemas.cod_esquema]');
      $this->form_validation->set_rules('descripcion','Descripción','required');
    } else if ($method == 'update'){
      $this->form_validation->set_rules('tipo','Tipo','required');
      $this->form_validation->set_rules('cod_esquema','Código de Esquema','required');
      $this->form_validation->set_rules('descripcion','Descripción','required');
    }
  }

  public function mensajes_reglas_esquema(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo números');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
  }

  public function eliminar_esquema()
  {
    $this->Esquemas_model->eliminar_esquema($this->input->post('id'));
  }

  public function status_esquema()
  {
    $this->Esquemas_model->status_esquema($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_esquema()
  {
    $this->Esquemas_model->eliminar_multiple_esquema($this->input->post('id'));
  }

  public function status_multiple_esquema()
  {
    $this->Esquemas_model->status_multiple_esquema($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos