<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Descuento extends CI_Controller
{
	function __construct()
	{
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
    $this->load->model('Descuento_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url());
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Descuento', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $datos['tipos_plazos'] = $this->Descuento_model->tipos_plazos();
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('Descuento');
    $datos['esquemas'] = $this->Descuento_model->esquemas();
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/Descuento/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listar_descuentos()
  {
    $listado = $this->Descuento_model->listar_descuentos();
    echo json_encode($listado);
  }

  public function registrar_descuento()
  {
    $this->reglas_descuento();
    $this->mensajes_reglas_descuento();
    if($this->form_validation->run() == true){
      $data=array(
        'tipo_plazo' => $this->input->post('tipo_plazo'),
        'descuento' => trim(str_replace(',', '.', $this->input->post('descuento'))),
        'cod_esquema' => $this->input->post('cod_esquema'),
      );
      $this->Descuento_model->registrar_descuento($data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_descuento()
  {
    $this->reglas_descuento();
    $this->mensajes_reglas_descuento();
    if($this->form_validation->run() == true){
      $data=array(
        'tipo_plazo' => $this->input->post('tipo_plazo'),
        'descuento' => trim(str_replace(',', '.', $this->input->post('descuento'))),
        'cod_esquema' => $this->input->post('cod_esquema'),
      );
      $this->Descuento_model->actualizar_descuento($this->input->post('id_descuento'), $data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_descuento()
  {
    $this->form_validation->set_rules('tipo_plazo','Tipo de Plazo','required');
    $this->form_validation->set_rules('descuento','Descuento','required');
    $this->form_validation->set_rules('cod_esquema','Esquema de Descuento','required');
  }

  public function mensajes_reglas_descuento(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
  }

  public function eliminar_descuento()
  {
    $this->Descuento_model->eliminar_descuento($this->input->post('id'));
  }

  public function status_descuento()
  {
    $this->Descuento_model->status_descuento($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_descuento()
  {
    $this->Descuento_model->eliminar_multiple_descuento($this->input->post('id'));
  }

  public function status_multiple_descuento()
  {
    $this->Descuento_model->status_multiple_descuento($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos