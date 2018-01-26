<?php

/**
 * Created by PhpStorm.
 * User: fede
 * Date: 16/01/2018
 * Time: 12:26 PM
 */
class Reportes_model extends My_Model
{

    public $before_create = array('created_at', 'updated_at');
    public $before_update = array('updated_at');
    public $protected_attributes = array('id');
    protected $soft_delete = TRUE;

    public function ejecutarReporte ($store, $parametros){
        $procedure = "CALL " . $store;
        mysqli_next_result($this->db->conn_id);
        $result =$this->db->query($procedure, $parametros);
       // $result =$this->db->query($procedure, array('',''));

        return $result->result_object();
    }

    public function obtenerTodos(){
        $sql = "select * from reporte";
        return $this->db->query($sql)->result();
    }

    public function  obtenerReporte($idReporte){
        $sql = "select * from reporte where id = " . $idReporte;
        return $this->db->query($sql)->row();
    }

    public function obtenerDefectoParametrosPorReporteId($reporteId){
        //$sql = "select (CONCAT(pr.nombre , '=>' , pr.defecto)) as par from parametro_reporte pr where pr.reporte = " . $reporteId;
        $sql = "select  pr.defecto as par from parametro_reporte pr where pr.reporte = " . $reporteId;
        mysqli_next_result($this->db->conn_id);
        $result =$this->db->query($sql)->result();

        $retorno = array();
        foreach($result as $key ){
            array_push($retorno,$key->par);
        }
        return $retorno;
    }
    public function obtenerParametrosPorReporteId($reporteId){
        //$sql = "select (CONCAT(pr.nombre , '=>' , pr.defecto)) as par from parametro_reporte pr where pr.reporte = " . $reporteId;
        $sql = "select  pr.etiqueta as eti, pr.tipo as tipo from parametro_reporte pr where pr.reporte = " . $reporteId;
        mysqli_next_result($this->db->conn_id);
        $result =$this->db->query($sql)->result();

        return $result;
    }


}