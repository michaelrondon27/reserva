<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sepomex extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->model('Sepomex_model');
        $this->load->model('Menu_model');
        $this->load->library('form_validation');
        if (!$this->session->userdata("login")) {
          redirect(base_url());
        }
    }

	public function index()
    {
        $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('sepomex', $this->session->userdata('id_rol'));
        $data['modulos'] = $this->Menu_model->modulos();
        $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
        $this->load->view('cpanel/header');
        $this->load->view('cpanel/menu', $data);
        $this->load->view('configuracion/Sepomex/index', $datos);
        $this->load->view('cpanel/footer');
    }

    public function actualizar()
    {

        /*-----------------------------------------------------------------------*/
        //                           TOMAR EN CUENTA                             //
        //                                                                       //
        //  1)Aumentar la capacidad el tamaño maximo de megas permitidos en la   //
        //                                                                       //
        //                                                                       //
        //  2)Aumentar el tiempo de ejecucion de las consultas de php en la      //
        //    configuracion de apache. buscar: max_execution_time -> en segundos //
        //    Recomiendo poner 43200 segundos -> 12 horas                        //
        //                                                                       //
        //  3)Definicion de los campos obligatorios:                             //
        //      a) 0 => d_codigo                                                 //
        //      b) 1 => d_asenta                                                 //
        //      c) 2 => d_tipo_asenta                                            //
        //      d) 3 => d_mnpio                                                  //
        //      e) 4 => d_estado                                                 //
        //      f) 6 => d_CP                                                     //
        //      g) 7 => c_estado                                                 //
        //      h) 8 => c_oficina                                                //
        //      i) 10 => c_tipo_asenta                                           //
        //      j) 11 => c_mnpio                                                 //
        //      k) 12 => id_asenta_cpcons                                        //
        //      l) 13 => d_zona                                                  //
        /*-----------------------------------------------------------------------*/


        $this->form_validation->set_rules('separacion','Código Bancario','required');
        $this->form_validation->set_rules('inicio','Nombre o Razón Social','required');
        $this->form_validation->set_message('required', 'El campo %s es obligatorio');
        if($this->form_validation->run() == true){
            /////SUBIR ARCHIVO
            $config['upload_path'] = "assets/archivosCP/"; //ruta donde carga el archivo
            $config['file_name'] = "CPdescarga"; //nombre temporal del archivo
            $config['allowed_types'] = "txt"; //extensiones permitidas del archivo
            $config['overwrite'] = true; //sobreescribe si existe uno con ese nombre
            $config['max_size'] = "2000000"; //tamaño maximo de archivo
            $this->load->library('upload', $config);
            if($this->upload->do_upload('archivo')){
                $ruta = "assets/archivosCP/".$this->upload->data()['file_name'];
                $data = "";
                $contador = 1;
                $file = fopen($ruta, "r");
                while ($linea = fgets($file)) {
                    if($contador >= $this->input->post('inicio') AND (!empty(trim($linea)) AND trim($linea) != "")){
                        $array = explode($this->input->post('separacion'), $linea);
                        if(!empty($array[0]) && !empty($array[1]) && !empty($array[2]) && !empty($array[3]) && !empty($array[4]) && !empty($array[6]) && !empty($array[7]) && !empty($array[8]) && !empty($array[10]) && !empty($array[11]) && !empty($array[12]) && !empty($array[13])){
                            $data=array(
                                'd_codigo' => $array[0],
                                'd_asenta' => $array[1],
                                'd_tipo_asenta' => $array[2],
                                'd_mnpio' => $array[3],
                                'd_estado' => $array[4],
                                'd_ciudad' => $array[5],
                                'd_CP' => $array[6],
                                'c_estado' => $array[7],
                                'c_oficina' => $array[8],
                                'c_CP' => $array[9],
                                'c_tipo_asenta' => $array[10],
                                'c_mnpio' => $array[11],
                                'id_asenta_cpcons' => $array[12],
                                'd_zona' => $array[13],
                                'c_cve_ciudad' => $array[14],
                            );
                            $verificar=$this->Sepomex_model->verificar_codigo_postal($array[0], $array[12]);
                            if(count($verificar)==0){
                                $this->Sepomex_model->registrar_codigo_postal($data);
                            }else{
                                //$this->Sepomex_model->actualizar_codigo_postal($array[0], $array[12], $data);
                            }
                        }
                    }
                    $contador++;
                }
                fclose($file);
                echo json_encode("<span>Códigos postales actualizados exitosamente!</span>"); // envio de mensaje exitoso
            }else{
                echo $this->upload->display_errors();
            }
        }else{
            // enviar los errores
            echo validation_errors();
        }
    }

}
