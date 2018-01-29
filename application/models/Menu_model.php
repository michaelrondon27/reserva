<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Menu_model extends CI_Model
{

    public function modulos()
    {
        $this->db->where('a.status', 1);
        $this->db->where('a.tabla', 'modulo_vista');
        $this->db->select('mv.*');
        $this->db->from('modulo_vista mv');
        $this->db->join('auditoria a', 'mv.id_modulo_vista = a.cod_reg');
        $this->db->order_by('posicion_modulo_vista', 'ASC');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function vistas($id_usuario)
    {
        $this->db->where('u.id_usuario', $id_usuario);
        $this->db->where('lv.visibilidad_lista_vista', 0);
        $this->db->where('a.tabla', 'rol');
        $this->db->where('a.status', 1);
        $this->db->where('(ro.consultar=0 OR ro.registrar=0 OR ro.actualizar=0 OR eliminar=0)');
        $this->db->select('lv.*');
        $this->db->from('usuario u');
        $this->db->join('rol r', 'u.id_rol = r.id_rol');
        $this->db->join('rol_operaciones ro', 'r.id_rol = ro.id_rol');
        $this->db->join('lista_vista lv', 'ro.id_lista_vista = lv.id_lista_vista');
        $this->db->join('auditoria a', 'a.cod_reg = r.id_rol');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function contar_modulos()
    {
        $resultados = $this->db->get('modulo_vista');
        return $resultados->result();
    }

    public function verificar_permiso_vista($url, $rol)
    {
        $this->db->where('ro.id_rol', $rol);
        $this->db->where('lv.url_lista_vista', $url);
        $this->db->where('a.tabla', 'rol');
        $this->db->select('ro.*, lv.id_lista_vista, mv.id_modulo_vista, a.status');
        $this->db->from('rol_operaciones ro');
        $this->db->join('lista_vista lv', 'ro.id_lista_vista = lv.id_lista_vista');
        $this->db->join('modulo_vista mv', 'lv.id_modulo_vista = mv.id_modulo_vista');
        $this->db->join('auditoria a', 'a.cod_reg = '. $rol);
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function contador_listaVista($id_modulo_vista)
    {
        $this->db->where('id_modulo_vista', $id_modulo_vista);
        $resultados = $this->db->get('lista_vista');
        return $resultados->result();
    }

}