<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class ListaVista_model extends CI_Model
{

    private $tabla_lista_vista = "lista_vista";
    private $tabla_modulo = "modulo_vista";

    public function listado_listaVista()
    {
        $query = $this->db->query("SELECT lv.*, a.fec_regins, u.correo_usuario, a.status, mv.nombre_modulo_vista FROM ".$this->tabla_lista_vista." lv INNER JOIN auditoria a ON lv.id_lista_vista=a.cod_reg INNER JOIN usuario u ON a.usr_regins=u.id_usuario INNER JOIN ".$this->tabla_modulo." mv ON lv.id_modulo_vista=mv.id_modulo_vista WHERE a.tabla='".$this->tabla_lista_vista."'");
        return $query->result();
    }

    public function modulos()
    {
        $query = $this->db->query("SELECT id_modulo_vista, nombre_modulo_vista FROM ".$this->tabla_modulo);
        return $query->result();
    }
        
    public function registrar_lista_vista($data){
        $this->db->insert($this->tabla_lista_vista, $data);
        $datos=array(
            'tabla' => $this->tabla_lista_vista,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => '1',
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
    }

    public function actualizar_lista_vista($id, $data)
    {
        $this->db->where('id_lista_vista', $id);
        $this->db->update($this->tabla_lista_vista, $data);
        $datos=array(
            'usr_regmod' => '1',
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_lista_vista);
        $this->db->update('auditoria', $datos);
    }

    public function verificar_lista_vista($data)
    {
        $query=$this->db->query("SELECT * FROM ".$this->tabla_lista_vista." WHERE nombre_lista_vista='".$data."' LIMIT 1");
        return $query->result_array();
    }

    public function eliminar_modulo($id)
    {
        try { 
            if(!$this->db->delete($this->nombre_tabla, array('id_modulo_vista' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $query = $this->db->query("SELECT * FROM ".$this->nombre_tabla." ORDER BY posicion_modulo_vista DESC");
                $contador = sizeof($query->result());
                foreach($query->result() as $row)
                {
                    $datos = array(
                        'posicion_modulo_vista' => $contador,
                    );
                    $this->db->where('id_modulo_vista', $row->id_modulo_vista);
                    $this->db->update($this->nombre_tabla, $datos);
                    $contador--;
                }
                echo json_encode("<span>El modulo se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function status_lista_vista($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => '1',
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_lista_vista);
        $this->db->update('auditoria', $datos);
    }

    public function eliminar_multiple_modulos($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $modulo)
        {
            if(!$this->db->delete($this->nombre_tabla, array('id_modulo_vista' => $modulo))){
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        $query = $this->db->query("SELECT * FROM ".$this->nombre_tabla." ORDER BY posicion_modulo_vista DESC");
        $contador = sizeof($query->result());
        foreach($query->result() as $row)
        {
            $datos = array(
                'posicion_modulo_vista' => $contador,
            );
            $this->db->where('id_modulo_vista', $row->id_modulo_vista);
            $this->db->update($this->nombre_tabla, $datos);
            $contador--;
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_lista_vista($id, $status)
    {
        $listas_vistas=str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status=".$status." WHERE cod_reg in (".$listas_vistas.") AND tabla='".$this->tabla_lista_vista."'");
    }

    public function posicionar_lista_vista_segun_modulo($posicionar)
    {
        if ($posicionar['moduloInicial'] == $posicionar['moduloFinal'])
        {
            if ($posicionar['posicionFinal'] > $posicionar['posicionInicial'])
            {
                $this->ordenar_lista_vista_resta('posicion_lista_vista > '.$posicionar['posicionInicial'].' AND posicion_lista_vista <= '.$posicionar['posicionFinal'].' AND id_modulo_vista = '.$posicionar['moduloFinal']);
            }
            else if ($posicionar['posicionFinal'] < $posicionar['posicionInicial'])
            {
                $this->ordenar_lista_vista_suma('posicion_lista_vista >= '.$posicionar['posicionFinal'].' AND posicion_lista_vista < '.$posicionar['posicionInicial'].' AND id_modulo_vista = '.$posicionar['moduloFinal']);
            }
        }
        else if ($posicionar['moduloInicial'] != $posicionar['moduloFinal'])
        {
            $datos = array(
                'posicion' => $posicionar['posicionFinal'],
                'modulo' => $posicionar['moduloFinal'],
            );
            $this->posicionar_lista_vista_nueva($datos);
        }
    }

    public function ordenar_lista_vista_resta($sql)
    {
        $query = $this->db->query("SELECT * FROM ".$this->tabla_lista_vista." WHERE ".$sql);
        if(sizeof($query->result()) > 0){
            foreach ($query->result() as $row)
            {
                $datos=array(
                    'posicion_lista_vista' => $row->posicion_lista_vista - 1,
                );
                $this->db->where('id_lista_vista', $row->id_lista_vista);
                $this->db->update($this->tabla_lista_vista, $datos);
            }
        }
    }

    public function ordenar_lista_vista_suma($sql)
    {
        $query = $this->db->query("SELECT * FROM ".$this->tabla_lista_vista." WHERE ".$sql);
        if(sizeof($query->result()) > 0){
            foreach ($query->result() as $row)
            {
                $datos=array(
                    'posicion_lista_vista' => $row->posicion_lista_vista + 1,
                );
                $this->db->where('id_lista_vista', $row->id_lista_vista);
                $this->db->update($this->tabla_lista_vista, $datos);
            }
        }
    }

    public function posicionar_lista_vista_nueva($posicionar)
    {
        $query = $this->db->query("SELECT * FROM ".$this->tabla_lista_vista." WHERE posicion_lista_vista >= ".$posicionar['posicion']." AND id_modulo_vista = ".$posicionar['modulo']);
        if(sizeof($query->result()) > 0){
            foreach ($query->result() as $row)
            {
                $datos=array(
                    'posicion_lista_vista' => $row->posicion_lista_vista + 1,
                );
                $this->db->where('id_lista_vista', $row->id_lista_vista);
                $this->db->update($this->tabla_lista_vista, $datos);
            }
        }
    }

    public function ordernar_lista_vista($idModulo)
    {
        $query = $this->db->query("SELECT * FROM ".$this->tabla_lista_vista." WHERE id_modulo_vista = ".$idModulo." ORDER BY posicion_lista_vista DESC");
        $contador = sizeof($query->result());
        foreach($query->result() as $row)
        {
            $datos = array(
                'posicion_lista_vista' => $contador,
            );
            $this->db->where('id_lista_vista', $row->id_lista_vista);
            $this->db->update($this->tabla_lista_vista, $datos);
            $contador--;
        }
    }

}