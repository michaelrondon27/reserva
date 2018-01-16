<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Menu_model extends CI_Model
{

    public function modulos()
    {
        $query = $this->db->query("SELECT * FROM modulo_vista ORDER BY posicion_modulo_vista ASC");
        return $query->result();
    }

    public function vistas($idUsuario)
    {
        $query = $this->db->query("SELECT lv.* FROM usuario u INNER JOIN rol r ON u.id_rol=r.id_rol INNER JOIN rol_operaciones ro ON r.id_rol=ro.id_rol INNER JOIN lista_vista lv ON ro.id_lista_vista=lv.id_lista_vista WHERE u.id_usuario=$idUsuario AND lv.visibilidad_lista_vista=0 ORDER BY posicion_lista_vista ASC");
        return $query->result();
    }

}