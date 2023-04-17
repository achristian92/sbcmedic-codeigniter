<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Disponibilidad extends CI_Model {


        public function listByDocDate($idDoc, $dateValue)
        {
                                
                $date = date("Y-m-d", strtotime($dateValue));                
                $sql  = " SELECT av.*, do.*, sp.* , st.* FROM availabilities av";                
                $sql .= " LEFT JOIN doctors do ON av.idDoctor = do.idDoctor";
                $sql .= " LEFT JOIN specialties sp ON do.idSpecialty = sp.idSpecialty";                
                $sql .= " LEFT JOIN styles st ON st.idStyle = sp.idStyle";
                $sql .= " WHERE av.idDoctor = ".$this->db->escape($idDoc);
                $sql .= " AND av.date = ".$this->db->escape($date);
                //$this->db->query($sql, array($idDep));
                $query = $this->db->query($sql);
                return $query->result();
        }

        public function listByDate($dateValue)
        {
                

                $start_date = date("Y-m-01", strtotime($dateValue));
                $end_date = date("Y-m-t", strtotime($dateValue));                
                $sql  = " SELECT av.*, do.*, sp.* , st.* FROM availabilities av";                
                $sql .= " LEFT JOIN doctors do ON av.idDoctor = do.idDoctor";                
                $sql .= " LEFT JOIN specialties sp ON do.idSpecialty = sp.idSpecialty";                
                $sql .= " LEFT JOIN styles st ON st.idStyle = sp.idStyle";
                $sql .= " WHERE av.date >= ".$this->db->escape($dateValue);
                //$this->db->query($sql, array($idDep));
                $query = $this->db->query($sql);
                return $query->result();
        }

        public function listByRange($startDate, $endDate)
        {
                
                $sql  = " SELECT av.*, do.*, sp.* , st.* FROM availabilities av";                
                $sql .= " LEFT JOIN doctors do ON av.idDoctor = do.idDoctor";                
                $sql .= " LEFT JOIN specialties sp ON do.idSpecialty = sp.idSpecialty";                
                $sql .= " LEFT JOIN styles st ON st.idStyle = sp.idStyle";
                $sql .= " WHERE av.date >= ".$this->db->escape($startDate);
                $sql .= " AND av.date <= ".$this->db->escape($endDate);
                //$this->db->query($sql, array($idDep));
                $query = $this->db->query($sql);
                return $query->result();
        }

        public function findByDate($dateValue)
        {
                

                $start_date = date("Y-m-01", strtotime($dateValue));
                $end_date = date("Y-m-t", strtotime($dateValue));                
                $sql  = " SELECT av.*, do.*, sp.* , st.* FROM availabilities av";                
                $sql .= " LEFT JOIN doctors do ON av.idDoctor = do.idDoctor";                
                $sql .= " LEFT JOIN specialties sp ON do.idSpecialty = sp.idSpecialty";                
                $sql .= " LEFT JOIN styles st ON st.idStyle = sp.idStyle";
                $sql .= " WHERE av.date = ".$this->db->escape($dateValue);
                //$this->db->query($sql, array($idDep));
                $query = $this->db->query($sql);
                return $query->result();
        }

        public function findById($idDisp)
        {
                
                
                $sql  = " SELECT av.*, do.*, sp.* , st.* FROM availabilities av";                
                $sql .= " LEFT JOIN doctors do ON av.idDoctor = do.idDoctor";                
                $sql .= " LEFT JOIN specialties sp ON do.idSpecialty = sp.idSpecialty";                
                $sql .= " LEFT JOIN styles st ON st.idStyle = sp.idStyle";
                $sql .= " WHERE av.idAvailability = ".$this->db->escape($idDisp);
                //$this->db->query($sql, array($idDep));
                $query = $this->db->query($sql);
                return $query->row_array();
        }

        public function listaPorMedico($fecha, $medico, $especialista, $tipoCita)
        {
                $formatoFechaConsulta = date('Y-m-d', strtotime($fecha));
                $fechaActual = date('Y-m-d');

                $ultimoDiaMesConsuta = date('Y-m-t', strtotime($formatoFechaConsulta));
                $ultimoDiaMesActual = date('Y-m-t', strtotime(date('Y-m-d')));

                $fechaMesInicio = date('Y-m', strtotime($fecha));

                $sql  = " SELECT av.*, doc.*, sp.* , st.* FROM availabilities av";                
                $sql .= " LEFT JOIN doctors doc ON av.idDoctor = doc.idDoctor";                
                $sql .= " LEFT JOIN specialties sp ON doc.idSpecialty = sp.idSpecialty";                
                $sql .= " LEFT JOIN styles st ON st.idStyle = sp.idStyle";
                $sql .= " WHERE av.tipoCita = '$tipoCita' and av.disponible = 1";
                $sql .= " AND doc.idDoctor = ".$this->db->escape($medico);
                $sql .= " AND sp.idSpecialty = ".$this->db->escape($especialista);

               if($fechaMesInicio == date('Y-m')) {
                        $sql .= " AND av.date BETWEEN '".$fechaActual."' and '".$fechaActual."' ";
                        $sql .= " AND av.start_time BETWEEN '".date('H:i'). ":00' and '23:59:00'";

                        $sql .= " UNION SELECT av.*, doc.*, sp.* , st.* FROM availabilities av LEFT JOIN doctors doc ON av.idDoctor = doc.idDoctor LEFT JOIN specialties sp ON doc.idSpecialty = sp.idSpecialty LEFT JOIN styles st ON st.idStyle = sp.idStyle WHERE av.tipoCita = '$tipoCita' and av.disponible = 1"." AND doc.idDoctor = ".$this->db->escape($medico)." AND sp.idSpecialty = ".$this->db->escape($especialista). "AND av.date BETWEEN '".date("Y-m-d",strtotime($fechaActual."+ 1 days"))."' and '".$ultimoDiaMesActual."' AND av.start_time BETWEEN '07:00:00' and '23:59:00'";
                
                } else {
                        $sql .= " AND av.date BETWEEN '".$fechaMesInicio."-01' and '".$ultimoDiaMesConsuta."' ";
                        $sql .= " AND av.start_time BETWEEN '07:00:00' and '23:59:00'";
                }

                $query = $this->db->query($sql);
                //print_r($this->db->last_query());
                return $query->result();
        }


        public function listarPorMedicoFecha($idDoc, $dateValue, $tipoCita)
        {
                $date = date("Y-m-d", strtotime($dateValue)); 

                $sql  = " SELECT av.*, do.*, sp.* , st.* FROM availabilities av";                
                $sql .= " LEFT JOIN doctors do ON av.idDoctor = do.idDoctor";
                $sql .= " LEFT JOIN specialties sp ON do.idSpecialty = sp.idSpecialty";                
                $sql .= " LEFT JOIN styles st ON st.idStyle = sp.idStyle";
                $sql .= " WHERE av.tipoCita='".$tipoCita."' and av.idDoctor = ".$this->db->escape($idDoc);

                if(date('Y-m-d') == $date) {
                        $sql .= " AND av.start_time BETWEEN '".date('H:i').":00' and '23:59:00'"; 
                } else {
                        $sql .= " AND av.start_time BETWEEN '07:00:00' and '23:59:00'";
                }

                $sql .= " AND av.date = ".$this->db->escape($date);
                $sql .= " AND av.disponible = 1 order by start_time asc ";
                $query = $this->db->query($sql);
                
                return $query->result();
        }
        
    

}
