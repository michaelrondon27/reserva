<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Usuarios extends CI_Controller
{
	function __construct()
	{
    parent::__construct();
    $this->load->database();
    $this->load->model('Usuarios_model');
    $this->load->library('encrypt');
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data['nacionalidades'] = $this->Usuarios_model->nacionalidades();
    $data['roles'] = $this->Usuarios_model->roles();
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu');
    $this->load->view('perfiles/Usuarios/index', $data);
    $this->load->view('cpanel/footer');
  }

  public function listado_usuarios()
  {
    $listado = $this->Usuarios_model->listar_usuarios();
    echo json_encode($listado);
  }

  public function buscar_codigos()
  {
    $datos = $this->Usuarios_model->buscar_codigos($this->input->post('codigo'));
    echo json_encode($datos);
  }

  public function registrar_usuario()
  {
    $this->reglas_usuarios('insert');
    $this->mensajes_reglas_usuarios();
    if ($this->form_validation->run() == true) {
      $usuarioArray = array(
        'id_rol' => $this->input->post('id_rol'),
        'correo_usuario' => $this->input->post('correo_usuario'),
        'clave_usuario' => $this->encrypt->encode($this->input->post('clave_usuario'))
      );
      $contactoArray = array(
        'id_codigo_postal' => $this->input->post('colonia'),
        'telefono_principal_contacto' => $this->input->post('telefono_principal_contacto'),
        'telefono_movil_contacto' => $this->input->post('telefono_principal_contacto'),
        'direccion_contacto' => strtoupper($this->input->post('direccion_contacto')),
        'calle_contacto' => strtoupper($this->input->post('calle_contacto')),
        'interior_contacto' => $this->input->post('interior_contacto'),
        'exterior_contacto' => $this->input->post('exterior_contacto'),
      );
      $personalArray = array(
        'nombre_datos_personales' => strtoupper($this->input->post('nombre_datos_personales')),
        'apellido_p_datos_personales' => strtoupper($this->input->post('apellido_p_datos_personales')),
        'apellido_m_datos_personales' => strtoupper($this->input->post('apellido_m_datos_personales')),
        'fecha_nac_datos_personales' => date("Y-m-d", strtotime($this->input->post('fecha_nac_datos_personales'))),
        'nacionalidad_datos_personales' => $this->input->post('nacionalidad_datos_personales'),
        'curp_datos_personales' => strtoupper($this->input->post('curp_datos_personales')),
        'edo_civil_datos_personales' => $this->input->post('edo_civil_datos_personales'),
        'genero_datos_personales' => $this->input->post('genero_datos_personales'),
      );
      $this->Usuarios_model->registrar_usuario($usuarioArray, $contactoArray, $personalArray);
      echo json_encode("<span>El usuario se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
    } else {
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_banco()
  {
    $this->reglas_bancos('update');
    $this->mensajes_reglas_usuarios();
    if($this->form_validation->run() == true){
      $data=array(
        'nombre_banco' => strtoupper($this->input->post('nombre_banco')),
      );
      $banco_verificado=$this->Bancos_model->verificar_banco($data); //busca si el nombre del banco esta registrado en la base de datos
      if(count($banco_verificado)>0){
        // si es mayor a cero, se verifica si el id recibido del formulario es igual al id que se verifico
        if($banco_verificado[0]['id_banco']==$this->input->post('id_banco')){
          //si son iguales, quiere decir que es el mismo registro
          $this->Bancos_model->actualizar_banco($this->input->post('id_banco'), $data);
          echo json_encode("<span>El Banco se ha editado exitosamente!</span>"); // envio de mensaje exitoso
        }else{
          //si son diferentes, quiere decir que ya el nombre del banco se encuentra en uso por otro registro
          echo "<span>El nombre del banco ingresado ya se encuentra en uso!</span>";
        }
      }else{
        // si conteo del array es igual a 0, se actualiza el registro
        $this->Bancos_model->actualizar_banco($this->input->post('id_banco'), $data);
        echo json_encode("<span>El Banco se ha editado exitosamente!</span>"); // envio de mensaje exitoso
      }
      
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_usuarios($method)
  {
    if($method=="insert"){

      // Reglas para la tabla de usuario
      $this->form_validation->set_rules('correo_usuario','Correo Electrónico','required|valid_email|is_unique[usuario.correo_usuario]');
      $this->form_validation->set_rules('correo_confirmar','Confirmar Correo Electrónico','required|valid_email|matches[correo_usuario]');
      $this->form_validation->set_rules('id_rol','Tipo de Rol','required');
      $this->form_validation->set_rules('clave_usuario','Contraseña','required');
      $this->form_validation->set_rules('repetir_clave','Repetir Contraseña','required|matches[clave_usuario]');

      // Reglas para la tabla contacto
      $this->form_validation->set_rules('colonia','Código Postal','required');
      $this->form_validation->set_rules('telefono_principal_contacto','Teléfono','required');

      // Reglas para la tabla
      $this->form_validation->set_rules('nombre_datos_personales','Nombre(s)','required');
      $this->form_validation->set_rules('apellido_p_datos_personales','Apellido Paterno','required');
      $this->form_validation->set_rules('apellido_m_datos_personales','Apellido Materno','required');
      $this->form_validation->set_rules('fecha_nac_datos_personales','Fecha de Nacimiento','required');
      $this->form_validation->set_rules('nacionalidad_datos_personales','Nacionalidad','required');
      $this->form_validation->set_rules('curp_datos_personales','C.U.R.P.','required');
      $this->form_validation->set_rules('edo_civil_datos_personales','Estado Civil','required');
      $this->form_validation->set_rules('genero_datos_personales','Género.','required');

    }else if($method=="update"){
      $this->form_validation->set_rules('nombre_banco','Nombre o Razón Social','required|max_length[200]|min_length[6]');
    }
  }

  public function mensajes_reglas_usuarios(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('min_length', 'El Campo %s debe tener un Mínimo de %d Caracteres');
    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo numeros enteros');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
    $this->form_validation->set_message('matches', 'El valor ingresado en el campo %s no coincide');
  }

  public function eliminar_banco()
  {
    $this->Bancos_model->eliminar_banco($this->input->post('id'));
  }

  public function status_banco()
  {
    $this->Bancos_model->status_banco($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_banco()
  {
    $this->Bancos_model->eliminar_multiple_banco($this->input->post('id'));
  }

  public function status_multiple_banco()
  {
    $this->Bancos_model->status_multiple_banco($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos