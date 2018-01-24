<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class EsquemaComision_model extends CI_Model
{

    private $tabla_esquema_comision = "esquema_comision";
    private $tabla_lval = "lval";

    public function listado_esquema_comision()
    {
        $this->db->where('a.tabla', $this->tabla_esquema_comision);
        $this->db->select('ec.*, a.fec_regins, u.correo_usuario, a.status');
        $this->db->from($this->tabla_esquema_comision . ' ec');
        $this->db->join('auditoria a', 'ec.id_esquema_comision = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $resultados = $this->db->get();
        return $resultados->result();
    }   
        
    public function registrar_banco($data){
        $this->db->insert($this->nombre_tabla, $data);
        $datos=array(
            'tabla' => $this->nombre_tabla,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
    }

    public function actualizar_banco($id, $data)
    {
        $this->db->where('id_banco', $id);
        $this->db->update($this->nombre_tabla, $data);
        $datos=array(
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $datos);
    }

    public function verificar_banco($data)
    {
        $this->db->where('nombre_banco', $$data['nombre_banco']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->nombre_tabla);
        return $resultados->result_array();
    }

    public function eliminar_banco($id)
    {
        try { 
            if(!$this->db->delete($this->nombre_tabla, array('id_banco' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                echo json_encode("<span>El Banco se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function status_banco($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $datos);
    }

    public function eliminar_multiple_banco($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $banco)
        {
            if($this->db->delete($this->nombre_tabla, array('id_banco' => $banco))){
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_banco($id, $status)
    {
        $data = array(
            'status' => $status,
        );
        $bancos = str_replace(' ', ',', $id);
        $this->db->where_in('cod_reg', $bancos);
        $this->db->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $data);
    }

    public function id_venderores()
    {
        $this->db->where('tipolval', 'IDVEND');
        $this->db->order_by('descriplval', 'ASC');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

    public function tipos_venderores()
    {
        $this->db->where('tipolval', 'TIPOVEND');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

    public function tipos_plazos()
    {
        $this->db->where('tipolval', 'TIPOPLAZO');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

}