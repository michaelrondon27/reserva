<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Roles extends CI_Controller
{
	function __construct()
	{
    parent::__construct();
    $this->load->database();
    $this->load->model('Roles_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('roles', 1);
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas(1);
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
        'nombre_rol' => strtoupper($this->input->post('nombre_rol')),
        'descripcion_rol' => strtoupper($this->input->post('descripcion_rol')),
        'editable_rol' => 0,
      );
      $this->Roles_model->registrar_rol($data, $this->input->post('permisos'));
      echo json_encode("<span>El Rol se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_modulo()
  {
    $this->reglas_modulos();
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
          echo json_encode("<span>El modulo se ha editado exitosamente!</span>"); // envio de mensaje exitoso
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

  public function reglas_roles()
  {
    $this->form_validation->set_rules('nombre_rol','Nombre de Rol','required');
  }

  public function mensajes_reglas_roles(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
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

  public function operaciones_rol()
  {
    $operaciones = $this->Roles_model->operaciones_rol($this->input->post('id'));
    echo json_encode($operaciones);
  }

}//Fin class Bancos