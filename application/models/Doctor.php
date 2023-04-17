<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor extends CI_Model {


        public function listBySpe($idSpe)
        {
                $this->load->database();
                $sql = "SELECT * FROM doctors WHERE status=1 and idSpecialty = ".$this->db->escape($idSpe);
                //$this->db->query($sql, array($idDep));
                $query = $this->db->query($sql);
                return $query->result();
        }

        public function medicoEspecialista($idmedico)
        {
                $this->load->database();
                $sql = "SELECT doctors.idSpecialty, doctors.idDoctor, doctors.title,
                                CONCAT(doctors.firstname, ' ', doctors.lastname ) AS nombreMedico,
                                specialties.NAME as especialidad, specialties.codigoSala as temp, doctors.codigoSala,
	                        patients.email
                        FROM
                                doctors
                                INNER JOIN specialties ON specialties.idSpecialty = doctors.idSpecialty
                                left JOIN patients ON patients.idUsuario = doctors.idUsuario
                        WHERE
                                doctors.idDoctor = $idmedico";
                $query = $this->db->query($sql);
                return $query->result();
        }

        public function all()
        {
                $this->load->database();
                $sql = "SELECT idDoctor, firstname, lastname, cmp, concat(title, ' ', firstname, ' ', lastname) as nombreMedico FROM doctors where status=1 and idSpecialty > 0 order by firstname";
                $query = $this->db->query($sql);

                return $query->result();
        }
		
		
		public function all_medico()
        {

                $this->load->database();
                $this->db->select("idDoctor, firstname, lastname, cmp, concat(title, ' ', firstname, ' ', lastname) as nombreMedico ");
                $this->db->from('doctors');
                $this->db->where('status', 1);

                if ($this->session->userdata('rol') == 2) {
                        $this->db->where('idUsuario', $this->session->userdata('idUsuario'));
                }

                $query = $this->db->get()->result();

                return $query ;
        }
		
		public function medico_unico($usuario)
        {

                $this->load->database();
                $this->db->select("idDoctor");
                $this->db->from('doctors');
                $this->db->where('idUsuario', $usuario);

                $resultado = $this->db->get();
                
                return $resultado->row_array();
        }

        public function all_especialidad($especialidad)
        {
                $this->load->database();
                $sql = "SELECT idDoctor, firstname, lastname, cmp, concat(title, ' ', firstname, ' ', lastname) as nombreMedico FROM doctors where status=1 and idSpecialty = '$especialidad' order by firstname";
                
                $query = $this->db->query($sql);

                return $query->result();
        }


}
