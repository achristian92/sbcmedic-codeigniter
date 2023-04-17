<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaccion extends CI_Model {
        
    public function registrarUsuario($typeDocument, $document, $firstname, $lastname, $email, $phone, $birthdate, $idDistrict, $address, $sex, $password, $canalVenta)
    {  
	    $sql  = " INSERT INTO users(username, password)  VALUES ";
        $sql .= " ( ";
        $sql .= $this->db->escape($document).",".$this->db->escape($password);
        $sql .= " ) ";
        $this->db->query($sql);
	
        $this->db->trans_complete();
		$ultimoId = $this->db->insert_id();
		
        //if($this->session->userdata('idUsuario'))   $usuarioCrecion = $this->session->userdata('idUsuario'); else $usuarioCrecion = $ultimoId;
		$usuarioCrecion = $this->session->userdata('idUsuario');

        if ($this->db->trans_status())
        {
            $this->db->trans_start();
            $sql  = " INSERT INTO patients(idTypeDocument, document, firstname, lastname, email, phone, birthdate, idDistrict, address, sex, idUsuario, idUsuarioCreacion, idCanalVenta)  VALUES ";
            $sql .= " ( ";
            $sql .= $typeDocument.",";
            $sql .= $this->db->escape($document).",".$this->db->escape($firstname).",".$this->db->escape($lastname).",";
            $sql .= $this->db->escape($email).",".$this->db->escape($phone).",".$this->db->escape($birthdate).",";
            $sql .= $this->db->escape($idDistrict).",".$this->db->escape($address).",".$this->db->escape($sex).",".$this->db->escape($ultimoId).",".$this->db->escape($usuarioCrecion).",".$this->db->escape($canalVenta); 
            $sql .= " ) ";
            $this->db->query($sql);  
            
            $this->db->trans_complete();

            if ($this->db->trans_status())
            {
                return true;
            }
        }
		
		return false;
    }
}
