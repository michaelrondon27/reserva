<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class ListaVista_model extends CI_Model
{

    private $tabla_lista_vista = "lista_vista";
    private $tabla_modulo = "modulo_vista";

    public function listado_listaVista()
    {
        $this->db->where('a.tabla', $this->tabla_lista_vista);
        $this->db->select('lv.*, a.fec_regins, u.correo_usuario, a.status, mv.nombre_modulo_vista');
        $this->db->from($this->tabla_lista_vista . ' lv');
        $this->db->join('auditoria a', 'lv.id_lista_vista = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $this->db->join($this->tabla_modulo . ' mv', 'lv.id_modulo_vista = mv.id_modulo_vista');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function modulos()
    {
        $this->db->where('a.tabla', $this->tabla_lista_vista);
        $this->db->where('a.status', 1);
        $this->db->select('mv.id_modulo_vista, mv.nombre_modulo_vista');
        $this->db->from($this->tabla_modulo. " mv");
        $this->db->join('auditoria a', 'mv.id_modulo_vista=a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
    }
        
    public function registrar_lista_vista($data){
        $this->db->insert($this->tabla_lista_vista, $data);
        $datos=array(
            'tabla' => $this->tabla_lista_vista,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
    }

    public function actualizar_lista_vista($id, $data)
    {
        $this->db->where('id_lista_vista', $id);
        $this->db->update($this->tabla_lista_vista, $data);
        $datos=array(
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_lista_vista);
        $this->db->update('auditoria', $datos);
    }

    public function verificar_lista_vista($nombre_lista_vista)
    {
        $this->db->where('nombre_lista_vista', $nombre_lista_vista);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_lista_vista);
        return $resultados->result_array();
    }

    public function eliminar_lista_vista($data)
    {
        try { 
            if(!$this->db->delete($this->tabla_lista_vista, array('id_lista_vista' => $data['id_lista_vista']))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->ordernar_lista_vista($data['id_modulo_vista']);
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

    public function eliminar_multiple_lista_vista($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $listaVista)
        {
            if($this->db->delete($this->tabla_lista_vista, array('id_lista_vista' => $listaVista))){
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        $this->ordernar_todas_listas_vistas();
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_lista_vista($id, $status)
    {
        $listas_vistas = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $listas_vistas . ") AND tabla='" . $this->tabla_lista_vista . "'");
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
        $this->db->where($sql);
        $resultados = $this->db->get($this->tabla_lista_vista);
        if ($resultados->num_rows() > 0) {
            foreach ($resultados->result() as $row)
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
        $this->db->where($sql);
        $resultados = $this->db->get($this->tabla_lista_vista);
        if ($resultados->num_rows() > 0) {
            foreach ($resultados->result() as $row)
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
        $this->db->where('posicion_lista_vista >= ' . $posicionar['posicion'] . ' AND id_modulo_vista = ' . $posicionar['modulo']);
        $resultados = $this->db->get($this->tabla_lista_vista);
        if ($resultados->num_rows() > 0) {
            foreach ($resultados->result() as $row)
            {
                $datos=array(
                    'posicion_lista_vista' => $row->posicion_lista_vista + 1,
                );
                $this->db->where('id_lista_vista', $row->id_lista_vista);
                $this->db->update($this->tabla_lista_vista, $datos);
            }
        }
    }

    public function ordernar_lista_vista($id_modulo_vista)
    {
        $this->db->where('id_modulo_vista', $id_modulo_vista);
        $this->db->order_by('posicion_lista_vista', 'DESC');
        $resultados = $this->db->get($this->tabla_lista_vista);
        $contador = $resultados->num_rows();
        foreach ($resultados->result() as $row)
        {
            $datos = array(
                'posicion_lista_vista' => $contador,
            );
            $this->db->where('id_lista_vista', $row->id_lista_vista);
            $this->db->update($this->tabla_lista_vista, $datos);
            $contador--;
        }
    }

    public function ordernar_todas_listas_vistas()
    {
        $this->db->select('id_modulo_vista');
        $modulos = $this->db->get($this->tabla_modulo);
        if ($modulos->num_rows() > 0) {
            foreach ($modulos->result() as $modulo) {
                $this->db->where('id_modulo_vista', $modulo->id_modulo_vista);
                $this->db->order_by('posicion_lista_vista', 'DESC');
                $this->db->select('id_lista_vista');
                $listasVistas = $this->db->get($this->tabla_lista_vista);
                $contador = $listasVistas->num_rows();
                if ($contador > 0) {
                    foreach ($listasVistas->result() as $listaVista) {
                        $datos = array(
                            'posicion_lista_vista' => $contador,
                        );
                        $this->db->where('id_lista_vista', $listaVista->id_lista_vista);
                        $this->db->update($this->tabla_lista_vista, $datos);
                        $contador--;
                    }
                }
            }
        }
    }

}