<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ListaValores extends CI_Controller {

  function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
    $this->load->model('ListasValores_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url());
    }
  }

	public function index()
	{
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('ListaValores', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('ListaValores');
    $datos['tipolval']=$this->ListasValores_model->tipolval();
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('configuracion/ListaValores/index', $datos);
    $this->load->view('cpanel/footer');
	}

  public function listado_valores()
  {
    $listado=$this->ListasValores_model->listado_valores();
    echo json_encode($listado);
  }

  public function reglas_lval($method)
  {
    if($method=="insert"){
      $this->form_validation->set_rules('tipolval','Tipo de Valor','required');
      $this->form_validation->set_rules('descriplval','Descripción','required|max_length[200]');
    }else if($method=="update"){
      $this->form_validation->set_rules('tipolval','Tipo de Valor','required');
      $this->form_validation->set_rules('descriplval','Descripción','required|max_length[200]');
    }
  }

  public function mensajes_reglaslval(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('min_length', 'El Campo %s debe tener un Mínimo de %d Caracteres');
    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
  }

	public function registrar_lval()
  {
    $this->reglas_lval('insert');
    $this->mensajes_reglaslval();
    if($this->form_validation->run() == true){
      $data=array(
        'tipolval' => $this->input->post('tipolval'),
        'descriplval' => trim(mb_strtoupper($this->input->post('descriplval'), 'UTF-8')),
      );
      $this->ListasValores_model->registrar_lval($data);
      echo json_encode("<span>Se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_lval()
  {
    $this->reglas_lval('update');
    $this->mensajes_reglaslval();
    if($this->form_validation->run() == true){
      $data=array(
        'tipolval' => $this->input->post('tipolval'),
        'descriplval' => trim((mb_strtoupper($this->input->post('descriplval'), 'UTF-8'))),
      );
      $lval_verificado=$this->ListasValores_model->verificar_lval($data); //busca si el nombre del banco esta registrado en la base de datos
      if(count($lval_verificado)>0){
        // si es mayor a cero, se verifica si el id recibido del formulario es igual al id que se verifico
        if($lval_verificado[0]['codlval']==$this->input->post('codlval')){
          //si son iguales, quiere decir que es el mismo registro
          $this->ListasValores_model->actualizar_lval($this->input->post('codlval'), $data);
          echo json_encode("<span>Se ha editado exitosamente!</span>"); // envio de mensaje exitoso
        }else{
          //si son diferentes, quiere decir que ya el nombre del banco se encuentra en uso por otro registro
          echo "<span>La descripción ingresada ya se encuentra en uso en el tipo de valor!</span>";
        }
      }else{
        // si conteo del array es igual a 0, se actualiza el registro
        $this->ListasValores_model->actualizar_lval($this->input->post('codlval'), $data);
        echo json_encode("<span>Se ha editado exitosamente!</span>"); // envio de mensaje exitoso
      }
      
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function status_lval()
  {
    $this->ListasValores_model->status_lval($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_lval()
  {
    $this->ListasValores_model->eliminar_lval($this->input->post('id'));
  }

  public function eliminar_multiple_lval()
  {
    $this->ListasValores_model->eliminar_multiple_lval($this->input->post('id'));
  }

  public function status_multiple_lval()
  {
    $this->ListasValores_model->status_multiple_lval($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}