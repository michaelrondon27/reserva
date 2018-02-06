<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class EsquemaComision_model extends CI_Model
{

    private $tabla_esquema_comision = "esquema_comision";
    private $tabla_lval = "lval";
    private $tabla_esquema = "esquemas";

    public function listado_esquema_comision()
    {
        $this->db->where('a.tabla', $this->tabla_esquema_comision);
        $this->db->select('ec.*, a.fec_regins, u.correo_usuario, a.status, iv.descriplval AS idVendedor, tv.descriplval AS tipoVendedor, tp.descriplval AS tipoPlazo');
        $this->db->from($this->tabla_esquema_comision . ' ec');
        $this->db->join('auditoria a', 'ec.id_esquema_comision = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $this->db->join($this->tabla_lval . ' iv', 'ec.id_vendedor = iv.codlval');
        $this->db->join($this->tabla_lval . ' tv', 'ec.tipo_vendedor = tv.codlval');
        $this->db->join($this->tabla_lval . ' tp', 'ec.tipo_plazo = tp.codlval');
        $resultados = $this->db->get();
        return $resultados->result();
    }   
        
    public function registrar_esquema_comision($data){
        $this->db->where('id_vendedor', $data['id_vendedor']);
        $this->db->where('tipo_vendedor', $data['tipo_vendedor']);
        $this->db->where('num_ventas_mes', $data['num_ventas_mes']);
        $this->db->where('tipo_plazo', $data['tipo_plazo']);
        $this->db->where('cod_esquema', $data['cod_esquema']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_esquema_comision);
        if ($resultados->num_rows() == 0) {
            $this->db->insert($this->tabla_esquema_comision, $data);
            $datos=array(
                'tabla' => $this->tabla_esquema_comision,
                'cod_reg' => $this->db->insert_id(),
                'usr_regins' => $this->session->userdata('id_usuario'),
                'fec_regins' => date('Y-m-d'),
            );
            $this->db->insert('auditoria', $datos);
            echo json_encode("<span>La comisión se ha registrado exitosamente!</span>");
        } else {
            echo "<span>¡Ya se encuentra registrado un esquema de comisión con las mismas características!</span>";
        }
    }

    public function actualizar_esquema_comision($id, $data)
    {
        $this->db->where('id_vendedor', $data['id_vendedor']);
        $this->db->where('tipo_vendedor', $data['tipo_vendedor']);
        $this->db->where('num_ventas_mes', $data['num_ventas_mes']);
        $this->db->where('tipo_plazo', $data['tipo_plazo']);
        $this->db->where('cod_esquema', $data['cod_esquema']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_esquema_comision);
        if ($resultados->num_rows() == 0) {
            $this->db->where('id_esquema_comision', $id);
            $this->db->update($this->tabla_esquema_comision, $data);
            $datos=array(
                'usr_regmod' => $this->session->userdata('id_usuario'),
                'fec_regmod' => date('Y-m-d'),
            );
            $this->db->insert('auditoria', $datos);
            echo json_encode("<span>El esquema de comisión se ha editado exitosamente!</span>");
        } else {
            $array = $resultados->row();
            if ($array->id_esquema_comision == $id) {
                $this->db->where('id_esquema_comision', $id);
                $this->db->update($this->tabla_esquema_comision, $data);
                $datos=array(
                    'usr_regmod' => $this->session->userdata('id_usuario'),
                    'fec_regmod' => date('Y-m-d'),
                );
                $this->db->insert('auditoria', $datos);
                echo json_encode("<span>La comisión se ha editado exitosamente!</span>");
            } else {
                echo "<span>¡Ya se encuentra registrado un esquema de comisión con las mismas características!</span>";
            }
        }
    }

    public function eliminar_esquema_comision($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_esquema_comision, array('id_esquema_comision' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_esquema_comision));
                echo json_encode("<span>La comisión se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function status_esquema_comision($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_esquema_comision);
        $this->db->update('auditoria', $datos);
    }

    public function eliminar_multiple_esquema_comision($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $esquema_comision)
        {
            if($this->db->delete($this->tabla_esquema_comision, array('id_esquema_comision' => $esquema_comision))){
                $this->db->delete('auditoria', array('cod_reg' => $esquema_comision, 'tabla' => $this->tabla_esquema_comision));
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_esquema_comision($id, $status)
    {
        $esquemas_comisiones = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $esquemas_comisiones . ") AND tabla='" . $this->tabla_esquema_comision . "'");
    }

    public function id_venderores()
    {
        $this->db->where('tipolval', 'IDVENDEDOR');
        $this->db->order_by('descriplval', 'ASC');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

    public function tipos_venderores()
    {
        $this->db->where('tipolval', 'TIPOVENDEDOR');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

    public function tipos_plazos()
    {
        $this->db->where('tipolval', 'TIPOPLAZOS');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

    public function esquemas()
    {
        $this->db->where('id_esquema', 1);
        $resultados = $this->db->get($this->tabla_esquema);
        return $resultados->result();
    }

}