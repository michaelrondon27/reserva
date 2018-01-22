<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Plaza extends CI_Controller
{
	function __construct()
	{
    parent::__construct();
    $this->load->database();
    $this->load->model('Plazas_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('plazas bancarias', 1);
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas(1);
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('configuracion/Plaza/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_plazas()
  {
    $listado=$this->Plazas_model->listar_plazas();
    echo json_encode($listado);
  }

  public function registrar_plaza()
	{
    $this->reglas_plazas('insert');
    $this->mensajes_reglas_plazas();
    if($this->form_validation->run() == true){
      $data=array(
        'cod_plaza' => $this->input->post('cod_plaza'),
        'nombre_plaza' => strtoupper($this->input->post('nombre_plaza')),
      );
      $this->Plazas_model->registrar_plaza($data);
      echo json_encode("<span>La plaza bancaria se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }
	}

  public function reglas_plazas($method)
  {
    if($method=="insert"){
      $this->form_validation->set_rules('cod_plaza','Código','required|numeric|max_length[3]|is_unique[plaza.cod_plaza]');
      $this->form_validation->set_rules('nombre_plaza','Nombre','required|max_length[200]|min_length[6]|is_unique[plaza.nombre_plaza]');
    }else if($method=="update"){
      $this->form_validation->set_rules('nombre_plaza','Nombre','required|max_length[200]|min_length[6]');
    }
  }

  public function mensajes_reglas_plazas(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('min_length', 'El Campo %s debe tener un Mínimo de %d Caracteres');
    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo numeros enteros');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
  }

  public function actualizar_plaza()
  {
    $this->reglas_plazas('update');
    $this->mensajes_reglas_plazas();
    if($this->form_validation->run() == true){
      $data=array(
        'nombre_plaza' => strtoupper($this->input->post('nombre_plaza')),
      );
      $plaza_verificada=$this->Plazas_model->verificar_plaza($data); //busca si el nombre del banco esta registrado en la base de datos
      if(count($plaza_verificada)>0){
        // si es mayor a cero, se verifica si el id recibido del formulario es igual al id que se verifico
        if($plaza_verificada[0]['id_plaza']==$this->input->post('id_plaza')){
          //si son iguales, quiere decir que es el mismo registro
          $this->Plazas_model->actualizar_plaza($this->input->post('id_plaza'), $data);
          echo json_encode("<span>La plaza se ha editado exitosamente!</span>"); // envio de mensaje exitoso
        }else{
          //si son diferentes, quiere decir que ya el nombre del banco se encuentra en uso por otro registro
          echo "<span>El nombre del banco ingresado ya se encuentra en uso!</span>";
        }
      }else{
        // si conteo del array es igual a 0, se actualiza el registro
        $this->Plazas_model->actualizar_plaza($this->input->post('id_plaza'), $data);
        echo json_encode("<span>La plaza se ha editado exitosamente!</span>"); // envio de mensaje exitoso
      }
      
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function eliminar_plaza()
  {
    $this->Plazas_model->eliminar_plaza($this->input->post('id'));
  }

  public function status_plaza()
  {
    $this->Plazas_model->status_plaza($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_plaza()
  {
    $this->Plazas_model->eliminar_multiple_plaza($this->input->post('id'));
  }

  public function status_multiple_plaza()
  {
    $this->Plazas_model->status_multiple_plaza($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Plaza