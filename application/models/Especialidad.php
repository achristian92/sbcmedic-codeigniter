<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Especialidad extends CI_Model {


        public function listAll()
        {
                $this->load->database();
                $sql = "SELECT * FROM specialties where status= 1 order by name";                
                $query = $this->db->query($sql);
                return $query->result();
        }

		public function listaAntigenos()
        {
                $this->load->database();
                $sql = "SELECT * FROM specialties where idSpecialty in(1, 25, 23, 24) and status= 1 order by name";                
                $query = $this->db->query($sql);
                return $query->result();
        }
}
