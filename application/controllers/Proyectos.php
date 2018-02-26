<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Proyectos extends CI_Controller
{
	function __construct()
	{
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
    $this->load->model('Proyectos_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url());
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Proyectos', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('Proyectos');
    $datos['directores'] = $this->Proyectos_model->directores();
    $datos['inmobiliarias'] = $this->Proyectos_model->inmobiliarias();
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/Proyectos/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_proyectos()
  {
    $listado = $this->Proyectos_model->listado_proyectos();
    echo json_encode($listado);
  }

  public function registrar_proyecto()
  {
    $config['upload_path'] = "assets/cpanel/Proyectos/planos/"; //ruta donde carga el archivo
    $config['file_name'] = time(); //nombre temporal del archivo
    $config['allowed_types'] = "gif|jpg|jpeg|png";
    $config['overwrite'] = true; //sobreescribe si existe uno con ese nombre
    $config['max_size'] = "2000000"; //tamaño maximo de archivo
    $this->load->library('upload', $config);
    if($this->upload->do_upload('plano')){
      $imagen = $this->upload->data()['file_name'];
    }else{
      $imagen = "";
    }
    $this->reglas_proyectos('insert');
    $this->mensajes_reglas_proyectos();
    if ($this->form_validation->run() == true) {
      $proyectoArray = array(
        'codigo' => trim(mb_strtoupper($this->input->post('codigo'), 'UTF-8')),
        'nombre' => trim(mb_strtoupper($this->input->post('nombre'), 'UTF-8')),
        'descripcion' => trim(mb_strtoupper($this->input->post('descripcion'), 'UTF-8')),
        'director' => $this->input->post('director'),
        'plano' => $imagen,
      );
      $this->Proyectos_model->registrar_proyecto($proyectoArray);
      echo json_encode("<span>El proyecto se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
    } else {
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_proyecto()
  {
    $config['upload_path'] = "assets/cpanel/Proyectos/planos/"; //ruta donde carga el archivo
    $config['file_name'] = time(); //nombre temporal del archivo
    $config['allowed_types'] = "gif|jpg|jpeg|png";
    $config['overwrite'] = true; //sobreescribe si existe uno con ese nombre
    $config['max_size'] = "2000000"; //tamaño maximo de archivo
    $this->load->library('upload', $config);
    if($this->upload->do_upload('plano')){
      $imagen = $this->upload->data()['file_name'];
    }else{
      $imagen = "";
    }
    $this->reglas_proyectos('update');
    $this->mensajes_reglas_proyectos();
    if($this->form_validation->run() == true){
      $proyectoArray = array(
        'codigo' => trim(mb_strtoupper($this->input->post('codigo'), 'UTF-8')),
        'nombre' => trim(mb_strtoupper($this->input->post('nombre'), 'UTF-8')),
        'descripcion' => trim(mb_strtoupper($this->input->post('descripcion'), 'UTF-8')),
        'director' => $this->input->post('director'),
      );
      $proyecto_verificado=$this->Proyectos_model->verificar_proyecto($this->input->post(trim(mb_strtoupper($this->input->post('nombre'), 'UTF-8')))); //busca si el nombre del banco esta registrado en la base de datos
      if(count($proyecto_verificado)>0){
        // si es mayor a cero, se verifica si el id recibido del formulario es igual al id que se verifico
        if($proyecto_verificado[0]['id_proyecto'] == $this->input->post('id_proyecto')){
          //si son iguales, quiere decir que es el mismo registro
          $this->Proyectos_model->actualizar_proyecto($proyectoArray, $this->input->post('id_proyecto'), $imagen);
          echo json_encode("<span>El proyecto se ha editado exitosamente!</span>"); // envio de mensaje exitoso
        }else{
          //si son diferentes, quiere decir que ya el nombre del banco se encuentra en uso por otro registro
          echo "<span>El codigo del proyecto ingresado ya se encuentra en uso!</span>";
        }
      }else{
        // si conteo del array es igual a 0, se actualiza el registro
        $this->Proyectos_model->actualizar_proyecto($proyectoArray, $this->input->post('id_proyecto'), $imagen);
        echo json_encode("<span>El proyecto se ha editado exitosamente!</span>"); // envio de mensaje exitoso
      }
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_proyectos($method)
  {
    if($method=="insert"){
      $this->form_validation->set_rules('codigo','Código','required|is_unique[proyectos.codigo]');
      $this->form_validation->set_rules('nombre','Nombre','required');
      $this->form_validation->set_rules('descripcion','Descripción','required');
      $this->form_validation->set_rules('director','Director','required');
    }else if($method=="update"){
      $this->form_validation->set_rules('codigo','Código','required');
      $this->form_validation->set_rules('nombre','Nombre','required');
      $this->form_validation->set_rules('descripcion','Descripción','required');
      $this->form_validation->set_rules('director','Director','required');
    }
  }

  public function mensajes_reglas_proyectos(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo numeros enteros');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
    $this->form_validation->set_message('matches', 'El valor ingresado en el campo %s no coincide');
  }

  public function eliminar_proyecto()
  {
    $this->Proyectos_model->eliminar_proyecto($this->input->post('id'));
  }

  public function status_proyecto()
  {
    $this->Proyectos_model->status_proyecto($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_proyecto()
  {
    $this->Proyectos_model->eliminar_multiple_proyecto($this->input->post('id'));
  }

  public function status_multiple_proyecto()
  {
    $this->Proyectos_model->status_multiple_proyecto($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos