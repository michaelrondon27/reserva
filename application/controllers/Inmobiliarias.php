<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Inmobiliarias extends CI_Controller
{
	function __construct()
	{
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
    $this->load->model('Inmobiliarias_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url());
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Inmobiliarias', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('Inmobiliarias');
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/Inmobiliarias/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_inmobiliarias()
  {
    $listado=$this->Inmobiliarias_model->listado_inmobiliarias();
    echo json_encode($listado);
  }

  public function registrar_banco()
  {
    $this->reglas_bancos('insert');
    $this->mensajes_reglas_banco();
    if($this->form_validation->run() == true){
      $data=array(
        'cod_banco' => $this->input->post('cod_banco'),
        'nombre_banco' => trim(mb_strtoupper($this->input->post('nombre_banco'), 'UTF-8')),
      );
      $this->Inmobiliarias_model->registrar_banco($data);
      echo json_encode("<span>El Banco se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_banco()
  {
    $this->reglas_bancos('update');
    $this->mensajes_reglas_banco();
    if($this->form_validation->run() == true){
      $data=array(
        'nombre_banco' => trim(mb_strtoupper($this->input->post('nombre_banco'), 'UTF-8')),
      );
      $banco_verificado=$this->Inmobiliarias_model->verificar_banco($data); //busca si el nombre del banco esta registrado en la base de datos
      if(count($banco_verificado)>0){
        // si es mayor a cero, se verifica si el id recibido del formulario es igual al id que se verifico
        if($banco_verificado[0]['id_banco']==$this->input->post('id_banco')){
          //si son iguales, quiere decir que es el mismo registro
          $this->Inmobiliarias_model->actualizar_banco($this->input->post('id_banco'), $data);
          echo json_encode("<span>El Banco se ha editado exitosamente!</span>"); // envio de mensaje exitoso
        }else{
          //si son diferentes, quiere decir que ya el nombre del banco se encuentra en uso por otro registro
          echo "<span>El nombre del banco ingresado ya se encuentra en uso!</span>";
        }
      }else{
        // si conteo del array es igual a 0, se actualiza el registro
        $this->Inmobiliarias_model->actualizar_banco($this->input->post('id_banco'), $data);
        echo json_encode("<span>El Banco se ha editado exitosamente!</span>"); // envio de mensaje exitoso
      }
      
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_bancos($method)
  {
    if($method=="insert"){
      $this->form_validation->set_rules('cod_banco','Código Bancario','required|numeric|max_length[3]|is_unique[banco.cod_banco]');
      $this->form_validation->set_rules('nombre_banco','Nombre o Razón Social','required|max_length[200]|is_unique[banco.nombre_banco]');
    }else if($method=="update"){
      $this->form_validation->set_rules('nombre_banco','Nombre o Razón Social','required|max_length[200]');
    }
  }

  public function mensajes_reglas_banco(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('min_length', 'El Campo %s debe tener un Mínimo de %d Caracteres');
    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo numeros enteros');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
  }

  public function eliminar_banco()
  {
    $this->Inmobiliarias_model->eliminar_banco($this->input->post('id'));
  }

  public function status_banco()
  {
    $this->Inmobiliarias_model->status_banco($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_banco()
  {
    $this->Inmobiliarias_model->eliminar_multiple_banco($this->input->post('id'));
  }

  public function status_multiple_banco()
  {
    $this->Inmobiliarias_model->status_multiple_banco($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos