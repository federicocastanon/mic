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
        $result =$this->db->query($procedure, $parametros);

        return $result->result_object();
    }

    public function obtenerTodos(){
        $sql = "select * from reporte";
        return $this->db->query($sql)->result();
    }

}