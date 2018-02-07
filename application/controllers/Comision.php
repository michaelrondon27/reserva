<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Comision extends CI_Controller
{
	function __construct()
	{
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
    $this->load->model('Comision_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url());
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Comision', $this->session->userdata('id_rol'));
    $datos['id_vendedores'] = $this->Comision_model->id_venderores();
    $datos['tipos_vendedores'] = $this->Comision_model->tipos_venderores();
    $datos['tipos_plazos'] = $this->Comision_model->tipos_plazos();
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('Comision');
    $datos['esquemas'] = $this->Comision_model->esquemas();
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/Comision/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_comision()
  {
    $listado = $this->Comision_model->listado_comision();
    echo json_encode($listado);
  }

  public function registrar_comision()
  {
    $this->reglas_comision();
    $this->mensajes_reglas_comision();
    if($this->form_validation->run() == true){
      $data = array(
        'id_vendedor' => $this->input->post('id_vendedor'),
        'tipo_vendedor' => $this->input->post('tipo_vendedor'),
        'tipo_plazo' => $this->input->post('tipo_plazo'),
        'cod_esquema' => $this->input->post('cod_esquema'),
        'num_ventas_mes' => trim($this->input->post('num_ventas_mes')),
        'porctj_comision' => trim(str_replace(',', '.', $this->input->post('porctj_comision'))),
      );
      $this->Comision_model->registrar_comision($data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_comision()
  {
    $this->reglas_comision();
    $this->mensajes_reglas_comision();
    if($this->form_validation->run() == true){
      $data = array(
        'id_vendedor' => $this->input->post('id_vendedor'),
        'tipo_vendedor' => $this->input->post('tipo_vendedor'),
        'tipo_plazo' => $this->input->post('tipo_plazo'),
        'cod_esquema' => $this->input->post('cod_esquema'),
        'num_ventas_mes' => trim($this->input->post('num_ventas_mes')),
        'porctj_comision' => trim(str_replace(',', '.', $this->input->post('porctj_comision'))),
      );
      $this->Comision_model->actualizar_comision($this->input->post('id_comision'), $data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_comision()
  {
    $this->form_validation->set_rules('id_vendedor','Id de Vendedor','required');
    $this->form_validation->set_rules('tipo_vendedor','Tipo de Vendedor','required');
    $this->form_validation->set_rules('tipo_plazo','Tipo de Plazo','required');
    $this->form_validation->set_rules('num_ventas_mes','Ventas al mes','required|numeric');
    $this->form_validation->set_rules('porctj_comision','Porcentaje de Comisión','required');
  }

  public function mensajes_reglas_comision(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo números');
  }

  public function eliminar_comision()
  {
    $this->Comision_model->eliminar_comision($this->input->post('id'));
  }

  public function status_comision()
  {
    $this->Comision_model->status_comision($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_comision()
  {
    $this->Comision_model->eliminar_multiple_comision($this->input->post('id'));
  }

  public function status_multiple_comision()
  {
    $this->Comision_model->status_multiple_comision($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos