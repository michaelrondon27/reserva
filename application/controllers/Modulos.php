<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Modulos extends CI_Controller
{
	function __construct()
	{
    parent::__construct();
    $this->load->database();
    $this->load->model('Modulos_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
  }

  public function index()
  {
    $permiso['permiso'] = $this->Menu_model->verificar_permiso_vista('modulos', 1);
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas(1);
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('perfiles/Modulos/index', $permiso);
    $this->load->view('cpanel/footer');
  }

  public function listado_modulos()
  {
    $listado = $this->Modulos_model->listar_modulos();
    echo json_encode($listado);
  }

  public function contar_modulos()
  {
    $contador = $this->Menu_model->contar_modulos();
    echo json_encode($contador);
  }

  public function registrar_modulo()
  {
    $this->reglas_modulos('insert');
    $this->mensajes_reglas_modulos();
    if($this->form_validation->run() == true){
      $posicionar = array(
        'posicion' => $this->input->post('posicion_modulo_vista'),
        'tipo' => 'insert',
      );
      $this->Modulos_model->posicionar_modulos($posicionar);
      $data=array(
        'nombre_modulo_vista' => strtoupper($this->input->post('nombre_modulo_vista')),
        'descripcion_modulo_vista' => $this->input->post('descripcion_modulo_vista'),
        'posicion_modulo_vista' => $this->input->post('posicion_modulo_vista'),
      );
      $this->Modulos_model->registrar_modulo($data);
      echo json_encode("<span>El modulo se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_modulo()
  {
    $this->reglas_modulos('update');
    $this->mensajes_reglas_modulos();
    if($this->form_validation->run() == true){
      $modulo_verificado=$this->Modulos_model->verificar_modulo(strtoupper($this->input->post('nombre_modulo_vista'))); //busca si el nombre del banco esta registrado en la base de datos
      $posicionar = array(
        'inicial' => $this->input->post('inicial'),
        'tipo' => 'update',
        'final' => $this->input->post('posicion_modulo_vista'),
      );
      $data=array(
        'nombre_modulo_vista' => strtoupper($this->input->post('nombre_modulo_vista')),
        'descripcion_modulo_vista' => $this->input->post('descripcion_modulo_vista'),
        'posicion_modulo_vista' => $this->input->post('posicion_modulo_vista'),
      );
      if(count($modulo_verificado)>0){
        // si es mayor a cero, se verifica si el id recibido del formulario es igual al id que se verifico
        if($modulo_verificado[0]['id_modulo_vista']==$this->input->post('id_modulo_vista')){
          //si son iguales, quiere decir que es el mismo registro
          $this->Modulos_model->posicionar_modulos($posicionar);
          $this->Modulos_model->actualizar_modulo($this->input->post('id_modulo_vista'), $data);
          echo json_encode("<span>El Banco se ha editado exitosamente!</span>"); // envio de mensaje exitoso
        }else{
          //si son diferentes, quiere decir que ya el nombre del banco se encuentra en uso por otro registro
          echo "<span>El nombre del modulo ingresado ya se encuentra en uso!</span>";
        }
      }else{
        $this->Modulos_model->posicionar_modulos($posicionar);
        // si conteo del array es igual a 0, se actualiza el registro
        $this->Modulos_model->actualizar_modulo($this->input->post('id_modulo_vista'), $data);
        echo json_encode("<span>El modulo se ha editado exitosamente!</span>"); // envio de mensaje exitoso
      }
      
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_modulos($method)
  {
    if($method == "insert"){
      $this->form_validation->set_rules('nombre_modulo_vista','Nombre de Modulo','required|is_unique[modulo_vista.nombre_modulo_vista]');
      $this->form_validation->set_rules('posicion_modulo_vista','Posición','required');
    }else if($method == "update"){
      $this->form_validation->set_rules('nombre_modulo_vista','Nombre de Modulo','required');
      $this->form_validation->set_rules('posicion_modulo_vista','Posición','required');
    }
  }

  public function mensajes_reglas_modulos(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
  }

  public function eliminar_modulo()
  {
    $this->Modulos_model->eliminar_modulo($this->input->post('id'));
  }

  public function status_modulo()
  {
    $this->Modulos_model->status_modulo($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_modulos()
  {
    $this->Modulos_model->eliminar_multiple_modulos($this->input->post('id'));
  }

  public function status_multiple_modulos()
  {
    $this->Modulos_model->status_multiple_modulos($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos