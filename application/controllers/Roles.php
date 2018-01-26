<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Roles extends CI_Controller
{
	function __construct()
	{
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
    $this->load->model('Roles_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url());
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Roles', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $datos['listasVistas'] = $this->Roles_model->listas_vistas();
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('perfiles/Roles/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_roles()
  {
    $listado = $this->Roles_model->listado_roles();
    echo json_encode($listado);
  }

  public function registrar_rol()
  {
    $this->reglas_roles();
    $this->mensajes_reglas_roles();
    if($this->form_validation->run() == true){
      $data=array(
        'nombre_rol' => mb_strtoupper($this->input->post('nombre_rol'), 'UTF-8'),
        'descripcion_rol' => mb_strtoupper($this->input->post('descripcion_rol'), 'UTF-8'),
        'editable_rol' => 0,
      );
      $this->Roles_model->registrar_rol($data, $this->input->post('permisos'));
      echo json_encode("<span>El Rol se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_rol()
  {
    $this->reglas_roles();
    $this->mensajes_reglas_roles();
    if($this->form_validation->run() == true){
      $rol = array(
        'nombre_rol' => mb_strtoupper($this->input->post('nombre_rol'), 'UTF-8'),
        'descripcion_rol' => mb_strtoupper($this->input->post('descripcion_rol'), 'UTF-8'),
      );
      $this->Roles_model->actualizar_rol($this->input->post('id_rol'), $rol, $this->input->post('permisos'));
      echo json_encode("<span>El Rol se ha editado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_roles()
  {
    $this->form_validation->set_rules('nombre_rol','Nombre de Rol','required');
  }

  public function mensajes_reglas_roles(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
  }

  public function eliminar_rol()
  {
    $this->Roles_model->eliminar_rol($this->input->post('id'));
  }

  public function status_rol()
  {
    $this->Roles_model->status_rol($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_roles()
  {
    $this->Roles_model->eliminar_multiple_roles($this->input->post('id'));
  }

  public function status_multiple_roles()
  {
    $this->Roles_model->status_multiple_roles($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function operaciones_rol()
  {
    $operaciones = $this->Roles_model->operaciones_rol($this->input->post('id'));
    echo json_encode($operaciones);
  }

  public function eliminar_rol_operacion()
  {
    $this->Roles_model->eliminar_rol_operacion($this->input->post('id'));
  }

}