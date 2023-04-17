<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Model {
        
    public function buscar($username, $password)
    {
                
        $sql  = " SELECT u.*, p.* FROM users u";
        $sql .= " LEFT JOIN patients p ON u.username = p.document ";
        $sql .= " WHERE  u.status =1  and u.username = ".$this->db->escape($username) ;
        $sql .= " AND u.password = ".$this->db->escape($password) ;        
        $query = $this->db->query($sql);
        
        return $query->row_array();
    }

    public function verificar($username)
    {
                
        $sql  = " SELECT u.*, p.* FROM users u";
        $sql .= " LEFT JOIN patients p ON u.username = p.document ";
        $sql .= " WHERE u.username = ".$this->db->escape($username) ;

       
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function ubigeo($idDistrito)
    {
        $this->db->select('dis.*');
        $this->db->from('ubigeo_districts dis');
        $this->db->join('ubigeo_departments d', 'dis.department_id = d.id');
        $this->db->join('ubigeo_provinces p', 'dis.province_id = p.id');
        $this->db->where('dis.id', "$idDistrito");
        
        $resultado = $this->db->get();
 
        return $resultado->row_array();
    }

    public function datosUsuario($idUsuario)
    {
        $this->db->select("p.idUsuario, p.idTypeDocument, p.firstname, p.lastname, p.sex, DATE_FORMAT(p.birthdate, '%Y-%m-%d') as fechaNacimiento, CONCAT(TRIM(p.firstname), ' ', TRIM(p.lastname)) AS paciente, p.address, p.email, p.document, p.phone, p.birthdate, p.sex, TIMESTAMPDIFF(YEAR,p.birthdate,CURDATE()) AS edad, p.address, (select ubigeo_districts.`name` from ubigeo_districts where ubigeo_districts.id=p.idDistrict) as distrito");
        $this->db->from('users u');
        $this->db->join('patients p', 'p.idUsuario = u.idUser');
        $this->db->where('u.idUser', $idUsuario);
        
        $resultado = $this->db->get();
 
        return $resultado->row_array();
    }
	
	public function datosUsuarioCita($idCita)
    {
        $this->db->select("p.idUsuario, p.idTypeDocument, p.firstname, p.lastname, p.sex, DATE_FORMAT(p.birthdate, '%Y-%m-%d') as fechaNacimiento, CONCAT(TRIM(p.firstname), ' ', TRIM(p.lastname)) AS paciente, p.address, p.email, p.document, p.phone, p.birthdate, p.sex, TIMESTAMPDIFF(YEAR,p.birthdate,CURDATE()) AS edad, p.address, (select ubigeo_districts.`name` from ubigeo_districts where ubigeo_districts.id=p.idDistrict) as distrito");
        $this->db->from('cita ci');
        $this->db->join('patients p', 'p.idUsuario = ci.idUsuario');
        $this->db->where('ci.idCita', $idCita);
        
        $resultado = $this->db->get();
 
        return $resultado->row_array();
    }
}
