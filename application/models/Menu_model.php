<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Menu_model extends CI_Model
{

    public function modulos()
    {
        $query = $this->db->query("SELECT mv.* FROM modulo_vista mv INNER JOIN auditoria a ON mv.id_modulo_vista=a.cod_reg WHERE a.status=1 AND a.tabla='modulo_vista' ORDER BY posicion_modulo_vista ASC");
        return $query->result();
    }

    public function vistas($idUsuario)
    {
        $query = $this->db->query("SELECT lv.* FROM usuario u INNER JOIN rol r ON u.id_rol=r.id_rol INNER JOIN rol_operaciones ro ON r.id_rol=ro.id_rol INNER JOIN lista_vista lv ON ro.id_lista_vista=lv.id_lista_vista WHERE u.id_usuario=$idUsuario AND lv.visibilidad_lista_vista=0 AND ro.consultar=0 ORDER BY lv.posicion_lista_vista ASC");
        return $query->result();
    }

    public function contar_modulos()
    {
        $query = $this->db->query("SELECT * FROM modulo_vista");
        return $query->result();
    }

    public function verificar_permiso_vista($modulo, $rol)
    {
        $query = $this->db->query("SELECT ro.*, lv.nombre_lista_vista, mv.nombre_modulo_vista FROM rol_operaciones ro INNER JOIN lista_vista lv ON ro.id_lista_vista=lv.id_lista_vista INNER JOIN modulo_vista mv ON lv.id_modulo_vista=mv.id_modulo_vista WHERE ro.id_rol=".$rol." AND lv.nombre_oculto_lista_vista='".$modulo."'");
        return $query->result();
    }

}