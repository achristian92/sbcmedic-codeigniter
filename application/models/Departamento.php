<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departamento extends CI_Model {


        public function listAll()
        {
                $this->load->database();
                $query = $this->db->query('SELECT * FROM ubigeo_departments order by name asc');
                return $query->result();
        }

}
