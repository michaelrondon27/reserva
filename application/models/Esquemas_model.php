<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Esquemas_model extends CI_Model
{

    private $tabla_esquema = "esquemas";
    private $tabla_lval = "lval";

    public function listado_esquema()
    {
        $this->db->where('a.tabla', $this->tabla_esquema);
        $this->db->select('e.*, a.fec_regins, u.correo_usuario, a.status, lv.descriplval');
        $this->db->from($this->tabla_esquema . ' e');
        $this->db->join('auditoria a', 'e.id_esquema = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $this->db->join($this->tabla_lval . ' lv', 'e.tipo = lv.codlval');
        $resultados = $this->db->get();
        return $resultados->result();
    }   
        
    public function registrar_esquema($data){
        $this->db->insert($this->tabla_esquema, $data);
        $datos=array(
            'tabla' => $this->tabla_esquema,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
        echo json_encode("<span>El esquema se ha registrado exitosamente!</span>");
    }

    public function actualizar_esquema($id, $data)
    {
        $this->db->where('cod_esquema', $data['cod_esquema']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_esquema);
        if ($resultados->num_rows() == 0) {
            $this->db->where('id_esquema', $id);
            $this->db->update($this->tabla_esquema, $data);
            $datos=array(
                'usr_regmod' => $this->session->userdata('id_usuario'),
                'fec_regmod' => date('Y-m-d'),
            );
            $this->db->insert('auditoria', $datos);
            echo json_encode("<span>El esquema se ha editado exitosamente!</span>");
        } else {
            $array = $resultados->row();
            if ($array->id_esquema == $id) {
                $this->db->where('id_esquema', $id);
                $this->db->update($this->tabla_esquema, $data);
                $datos=array(
                    'usr_regmod' => $this->session->userdata('id_usuario'),
                    'fec_regmod' => date('Y-m-d'),
                );
                $this->db->insert('auditoria', $datos);
                echo json_encode("<span>El esquema se ha editado exitosamente!</span>");
            } else {
                echo "<span>¡Ya se encuentra registrado un esquema con el código ingresado!</span>";
            }
        }
    }

    public function eliminar_esquema($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_esquema, array('id_esquema' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_esquema));
                echo json_encode("<span>El esquema se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function status_esquema($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_esquema);
        $this->db->update('auditoria', $datos);
    }

    public function eliminar_multiple_esquema($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $esquema)
        {
            if($this->db->delete($this->tabla_esquema, array('id_esquema' => $esquema))){
                $this->db->delete('auditoria', array('cod_reg' => $esquema, 'tabla' => $this->tabla_esquema));
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_esquema($id, $status)
    {
        $esquemas = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $esquemas . ") AND tabla = '" . $this->tabla_esquema . "'");
    }

    public function tipos_esquemas()
    {
        $this->db->where('tipolval', 'ESQUEMAS');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

}