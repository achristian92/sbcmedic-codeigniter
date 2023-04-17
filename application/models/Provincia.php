<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Provincia extends CI_Model {


        public function listByDep($idDep)
        {
                $this->load->database();
                $sql = "SELECT * FROM ubigeo_provinces WHERE department_id = ".$this->db->escape($idDep);
                $query = $this->db->query($sql);
                return $query->result();
        }

        public function listaTodos($departamento)
        {
                $this->load->database();
                $sql = "SELECT * FROM ubigeo_provinces";
                $sql = $sql." where department_id = '".$departamento."'";
                $sql = $sql." order by name asc";
                $query = $this->db->query($sql);
                return $query->result();
        }

}
