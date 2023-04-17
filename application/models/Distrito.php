<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distrito extends CI_Model {


        public function listByProv($idProv)
        {
                $this->load->database();
                $sql = "SELECT * FROM ubigeo_districts WHERE province_id = ".$this->db->escape($idProv);
                //$this->db->query($sql, array($idDep));
                $query = $this->db->query($sql);
                return $query->result();
        }


        public function listaTodos($departamento, $provincia)
        {
                $this->load->database();
                $sql = "SELECT id, name FROM ubigeo_districts where department_id ='".$departamento."' and province_id ='".$provincia."' order by name asc";
                $query = $this->db->query($sql);
                return $query->result();
        }

}
