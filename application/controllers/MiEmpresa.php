<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MiEmpresa extends CI_Controller
{
	function __construct()
	{
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
    $this->load->model('MiEmpresa_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url());
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('MiEmpresa', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('MiEmpresa');
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('configuracion/MiEmpresa/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function buscar_codigos()
  {
    $datos=$this->MiEmpresa_model->buscar_codigos($this->input->post('codigo'));
    echo json_encode($datos);
  }

  public function buscar_mi_empresa()
  {
    $datos=$this->MiEmpresa_model->buscar_mi_empresa();
    echo json_encode($datos);
  }

  public function actualizar_mi_empresa()
  {
    $this->reglas_mi_empresa();
    $this->mensajes_reglas_mi_empresa();
    if($this->form_validation->run() == true){
      $dataContacto=array(
        'id_codigo_postal' => $this->input->post('colonia'),
        'telefono_principal_contacto' => $this->input->post('telefono_principal_contacto'),
        'telefono_movil_contacto' => $this->input->post('telefono_principal_contacto'),
        'correo_opcional_contacto' => $this->input->post('correo_opcional_contacto'),
        'direccion_contacto' => mb_strtoupper($this->input->post('direccion_contacto'), 'UTF-8'),
        'calle_contacto' => mb_strtoupper($this->input->post('calle_contacto'), 'UTF-8'),
        'exterior_contacto' => mb_strtoupper($this->input->post('exterior_contacto')),
        'interior_contacto' => mb_strtoupper($this->input->post('interior_contacto')),
      );
      $this->MiEmpresa_model->actualizar_mi_empresa($this->input->post('id_mi_empresa'), mb_strtoupper($this->input->post('nombre_mi_empresa'), 'UTF-8'), $this->input->post('rfc_mi_empresa'), $this->input->post('id_contacto'), $dataContacto);
      echo json_encode("<span>Datos editado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_mi_empresa()
  {
    $this->form_validation->set_rules('nombre_mi_empresa','Nombre de Empresa','required');
    $this->form_validation->set_rules('rfc_mi_empresa','RFC','required|max_length[14]');
    $this->form_validation->set_rules('telefono_principal_contacto','Teléfono Principal','required');
    $this->form_validation->set_rules('correo_opcional_contacto','Correo Electrónico','required|valid_email');
    $this->form_validation->set_rules('colonia','Colonia','required');
  }

  public function mensajes_reglas_mi_empresa(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
    $this->form_validation->set_message('valid_email', 'El Campo %s debe ser un correo');
  }

}//Fin class Bancos