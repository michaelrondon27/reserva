<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class MiEmpresa_model extends CI_Model{

    private $tabla_empresa = "mi_empresa";
    private $tabla_contacto = "contacto";
    
    public function buscar_codigos($codigo)
    {
        $estados=$this->db->query("SELECT DISTINCT d_estado FROM codigo_postal WHERE d_codigo='$codigo'");
        $estados->result();
        $ciudades=$this->db->query("SELECT DISTINCT d_ciudad FROM codigo_postal WHERE d_codigo='$codigo'");
        $ciudades->result();
        $municipios=$this->db->query("SELECT DISTINCT d_mnpio FROM codigo_postal WHERE d_codigo='$codigo'");
        $municipios->result();
        $colonias=$this->db->query("SELECT id_codigo_postal, d_asenta FROM codigo_postal WHERE d_codigo='$codigo'");
        $colonias->result();
        $data=array(
            'estados' => $estados,
            'ciudades' => $ciudades,
            'municipios' => $municipios,
            'colonias' => $colonias,
        );
        return $data;
    }

    public function buscar_mi_empresa()
    {
        $query=$this->db->query("SELECT e.*, c.*, cp.* FROM ".$this->tabla_empresa." e INNER JOIN ".$this->tabla_contacto." c ON e.id_contacto=c.id_contacto INNER JOIN codigo_postal cp ON c.id_codigo_postal=cp.id_codigo_postal LIMIT 1");
        $empresa=$query->result();
        foreach($query->result_array() as $row){
            $estados=$this->db->query("SELECT DISTINCT d_estado FROM codigo_postal WHERE d_codigo='".$row['d_codigo']."'");
            $estados->result();
            $ciudades=$this->db->query("SELECT DISTINCT d_ciudad FROM codigo_postal WHERE d_codigo='".$row['d_codigo']."'");
            $ciudades->result();
            $municipios=$this->db->query("SELECT DISTINCT d_mnpio FROM codigo_postal WHERE d_codigo='".$row['d_codigo']."'");
            $municipios->result();
            $colonias=$this->db->query("SELECT DISTINCT id_codigo_postal, d_asenta FROM codigo_postal WHERE d_codigo='".$row['d_codigo']."'");
            $colonias->result();
            $data=array(
                'empresa' => $empresa,
                'estados' => $estados,
                'ciudades' => $ciudades,
                'municipios' => $municipios,
                'colonias' => $colonias,
            );
        }
        return $data;
    }

    public function actualizar_mi_empresa($idEmpresa, $nombreEmpresa, $rfcEmpresa, $idContacto, $dataContacto){
        if($idEmpresa==0){

            /*---------------------------------------------------------------*/
            //                      CONTACTO                                 //
                $this->db->insert($this->tabla_contacto, $dataContacto);
                $idContacto=$this->db->insert_id(); // id del insert de contacto
                $datosContacto=array(
                    'tabla' => $this->tabla_contacto,
                    'cod_reg' => $idContacto,
                    'usr_regins' => '1',
                    'fec_regins' => date('Y-m-d'),
                );
                $this->db->insert('auditoria', $datosContacto);
            /*---------------------------------------------------------------*/

            /*---------------------------------------------------------------*/
            //                      EMPRESA                                  //
                $dataEmpresa=array(
                    'nombre_mi_empresa' => strtoupper($nombreEmpresa),
                    'rfc_mi_empresa' => strtoupper($rfcEmpresa),
                    'id_contacto' => $idContacto,
                );
                $this->db->insert($this->tabla_empresa, $dataEmpresa);
                $datosEmpresa=array(
                    'tabla' => $this->tabla_empresa,
                    'cod_reg' => $this->db->insert_id(),
                    'usr_regins' => '1',
                    'fec_regins' => date('Y-m-d'),
                );
                $this->db->insert('auditoria', $datosEmpresa);
            /*---------------------------------------------------------------*/

        }else{

            /*---------------------------------------------------------------*/
            //                      EMPRESA                                  //
                $this->db->where('id_mi_empresa', $idEmpresa);
                $dataEmpresa=array(
                    'nombre_mi_empresa' => strtoupper($nombreEmpresa),
                    'rfc_mi_empresa' => strtoupper($rfcEmpresa),
                );
                $this->db->update($this->tabla_empresa, $dataEmpresa);
                $datosEmpresa=array(
                    'usr_regmod' => '1',
                    'fec_regmod' => date('Y-m-d'),
                );
                $this->db->where('cod_reg', $idEmpresa)->where('tabla', $this->tabla_empresa);
                $this->db->update('auditoria', $datosEmpresa);
            /*---------------------------------------------------------------*/

            /*---------------------------------------------------------------*/
            //                      CONTACTO                                 //
                $this->db->where('id_contacto', $idContacto);
                $this->db->update($this->tabla_contacto, $dataContacto);
                $datosContacto=array(
                    'usr_regmod' => '1',
                    'fec_regmod' => date('Y-m-d'),
                );
                $this->db->where('cod_reg', $idContacto)->where('tabla', $this->tabla_contacto);
                $this->db->update('auditoria', $datosContacto);
            /*---------------------------------------------------------------*/
        }
    }
    
}
?>