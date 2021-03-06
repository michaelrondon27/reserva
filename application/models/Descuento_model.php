<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Descuento_model extends CI_Model
{

    private $tabla_descuento = "descuentos";
    private $tabla_lval = "lval";
    private $tabla_esquema = "esquemas";

    public function listar_descuentos()
    {
        $this->db->where('a.tabla', $this->tabla_descuento);
        $this->db->select('d.*, a.fec_regins, u.correo_usuario, a.status, tp.descriplval as tipoPlazo, tv.descriplval as tipoVendedor');
        $this->db->from($this->tabla_descuento . ' d');
        $this->db->join('auditoria a', 'd.id_descuento = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $this->db->join($this->tabla_lval . ' tp', 'd.tipo_plazo = tp.codlval');
        $this->db->join($this->tabla_lval . ' tv', 'd.tipo_vendedor = tv.codlval');
        $resultados = $this->db->get();
        return $resultados->result();
    }   
        
    public function registrar_descuento($data){
        $this->db->where('tipo_plazo', $data['tipo_plazo']);
        $this->db->where('tipo_vendedor', $data['tipo_vendedor']);
        $this->db->where('descuento', $data['descuento']);
        $this->db->where('cod_esquema', $data['cod_esquema']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_descuento);
        if ($resultados->num_rows() == 0) {
            $this->db->insert($this->tabla_descuento, $data);
            $datos=array(
                'tabla' => $this->tabla_descuento,
                'cod_reg' => $this->db->insert_id(),
                'usr_regins' => $this->session->userdata('id_usuario'),
                'fec_regins' => date('Y-m-d'),
            );
            $this->db->insert('auditoria', $datos);
            echo json_encode("<span>El descuento se ha registrado exitosamente!</span>");
        } else {
            echo "<span>¡Ya se encuentra registrado un descuento con las mismas características!</span>";
        }
    }

    public function actualizar_descuento($id, $data)
    {
        $this->db->where('tipo_plazo', $data['tipo_plazo']);
        $this->db->where('tipo_vendedor', $data['tipo_vendedor']);
        $this->db->where('descuento', $data['descuento']);
        $this->db->where('cod_esquema', $data['cod_esquema']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_descuento);
        if ($resultados->num_rows() == 0) {
            $this->db->where('id_descuento', $id);
            $this->db->update($this->tabla_descuento, $data);
            $datos=array(
                'usr_regmod' => $this->session->userdata('id_usuario'),
                'fec_regmod' => date('Y-m-d'),
            );
            $this->db->insert('auditoria', $datos);
            echo json_encode("<span>El descuento se ha editado exitosamente!</span>");
        } else {
            $array = $resultados->row();
            if ($array->id_descuento == $id) {
                $this->db->where('id_descuento', $id);
                $this->db->update($this->tabla_descuento, $data);
                $datos=array(
                    'usr_regmod' => $this->session->userdata('id_usuario'),
                    'fec_regmod' => date('Y-m-d'),
                );
                $this->db->insert('auditoria', $datos);
                echo json_encode("<span>El descuento se ha editado exitosamente!</span>");
            } else {
                echo "<span>¡Ya se encuentra registrado un descuento con las mismas características!</span>";
            }
        }
    }

    public function eliminar_descuento($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_descuento, array('id_descuento' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_descuento));
                echo json_encode("<span>El descuento se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function status_descuento($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_descuento);
        $this->db->update('auditoria', $datos);
    }

    public function eliminar_multiple_descuento($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $descuento)
        {
            if($this->db->delete($this->tabla_descuento, array('id_descuento' => $descuento))){
                $this->db->delete('auditoria', array('cod_reg' => $descuento, 'tabla' => $this->tabla_descuento));
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_descuento($id, $status)
    {
        $descuentos = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $descuentos . ") AND tabla='" . $this->tabla_descuento . "'");
    }

    public function tipos_plazos()
    {
        $this->db->where('tipolval', 'TIPOPLAZOS');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

    public function tipos_vendedores()
    {
        $this->db->where('tipolval', 'TIPOVENDEDOR');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

    public function esquemas()
    {
        $this->db->where('tipo', 298);
        $resultados = $this->db->get($this->tabla_esquema);
        return $resultados->result();
    }

}