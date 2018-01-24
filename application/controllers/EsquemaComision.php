<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class EsquemaComision extends CI_Controller
{
	function __construct()
	{
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
    $this->load->model('EsquemaComision_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url());
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('esquemacomision', $this->session->userdata('id_rol'));
    $datos['id_vendedores'] = $this->EsquemaComision_model->id_venderores();
    $datos['tipos_vendedores'] = $this->EsquemaComision_model->tipos_venderores();
    $datos['tipos_plazos'] = $this->EsquemaComision_model->tipos_plazos();
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('configuracion/EsquemaComision/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_esquema_comision()
  {
    $listado = $this->EsquemaComision_model->listado_esquema_comision();
    echo json_encode($listado);
  }

  public function registrar_esquema_comision()
  {
    echo $this->input->post('porctj_comision')."<br>";
    echo number_format(int_val($this->input->post('porctj_comision')), 2, ",", ".");
    /*$this->reglas_esquema_comision();
    $this->mensajes_reglas_esquema_comision();
    if($this->form_validation->run() == true){
      $data = array(
        'id_vendedor' => $this->input->post('id_vendedor'),
        'tipo_vendedor' => $this->input->post('tipo_vendedor'),
        'tipo_plazo' => $this->input->post('tipo_plazo'),
        'num_ventas_mes' => $this->input->post('num_ventas_mes'),
        'porctj_comision' => $this->input->post('porctj_comision'),
      );
      $this->Bancos_model->registrar_banco($data);
      echo json_encode("<span>El Banco se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }*/
  }

  public function actualizar_banco()
  {
    $this->reglas_esquema_comision();
    $this->mensajes_reglas_esquema_comision();
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

  public function reglas_esquema_comision()
  {
    $this->form_validation->set_rules('id_vendedor','Id de Vendedor','required');
    $this->form_validation->set_rules('tipo_vendedor','Tipo de Vendedor','required');
    $this->form_validation->set_rules('tipo_plazo','Tipo de Plazo','required');
    $this->form_validation->set_rules('num_ventas_mes','Ventas al mes','required|numeric');
    $this->form_validation->set_rules('porctj_comision','Porcentaje de Comisión','required|numeric');
  }

  public function mensajes_reglas_esquema_comision(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo números');
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