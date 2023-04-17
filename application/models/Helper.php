<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Helper extends CI_Model {
        
    public function promedioCalificacion($idMedico)
    {    
        $this->db->select("ROUND(AVG (valor)) as promedio");
		$this->db->from('calificacion');
		$this->db->where('idMedico', $idMedico);
        $query = $this->db->get();
		$row_resultado = $query->row_array();
        
        return $row_resultado['promedio'];
    }

    public function configuracion()
    {    
        $this->db->select("version, urlVideoCam");
		$this->db->from('configuracion');
        $query = $this->db->get();
		$row_resultado = $query->row_array();
        
        return $row_resultado;
    }

    public function permiso_usuario($permiso="")
    {    
		 
        $this->db->select("p.codigo");
        $this->db->from('permiso_rol pr');
        $this->db->join('rol r', 'r.id = pr.idRol');
        $this->db->join('permiso p', 'p.id = pr.idPermiso');
        $this->db->join('users u', 'u.idRol = r.id');
        
        if ($permiso !="") {    $this->db->where('p.codigo', $permiso); }

        $this->db->where('u.idUser', $this->session->userdata('idUsuario'));
        $query = $this->db->get();

        if ($permiso =="") { 
            $result = array();
            foreach ($query->result() as $row)
            {
                $result[] = $row->codigo;
            }
            return $result;
        }

		$row_resultado = $query->row_array();
        
		if(isset($row_resultado["codigo"])){
			$oldstatus = $row_resultado["codigo"];
		} else {
			$oldstatus = "";
		}

        return $oldstatus;
    }

    public function citaRecetaPdf($idCita, $update=false)
    {    
        $this->db->select("c.fechaUsuarioCierre, if(r.idReceta, rec.descripcion , r.nombre) as nombre, if(r.idReceta, rec.presentacion, r.presentacion) as presentacion, r.cantidad, r.via, r.dosificacion, r.tiempo_tratamiento, if(r.idReceta, concat(rec.descripcion, ' => ', rec.presentacion ), concat(r.nombre, ' => ', r.presentacion)) as receta, r.idReceta");
        $this->db->from("cita c");
        $this->db->join("receta_cita r", "r.idCita = c.idCita");
		$this->db->join("recetas rec", "rec.id = r.idReceta", "left");
        $this->db->where("c.idCita", $idCita);
        $this->db->where("c.status", 0);
        $this->db->order_by("r.id", 'ASC');
        $query = $this->db->get();

        if ($update) { 
            $result = array();
            foreach ($query->result() as $row)
            {
                $result[] = $row->nombre. "_".$row->presentacion. "_".$row->cantidad. "_".$row->via. "_".$row->dosificacion. "_".$row->tiempo_tratamiento;
            }
            return $result;
        }

        //print_r($this->db->last_query());
        return $query;
    }
    
    public function citaExamenMPdf222($idCita, $update=false)
    {    
        $this->db->select("em.nombre, em.especificaciones, em.especialidad, c.fechaUsuarioCierre");
        $this->db->from("cita c");
        $this->db->join("examenauxiliar_cita em", "em.idCita = c.idCita");
        $this->db->where("c.idCita", $idCita);
        $this->db->where("c.status", 0);
        $this->db->order_by("em.fechaCreacion", 'DESC');
        $query = $this->db->get();

        if ($update) { 
            $result = array();
            foreach ($query->result() as $row)
            {
                $result[] = $row->nombre. "_".$row->especificaciones. "_".$row->especialidad;
            }
            return $result;
        }

        return $query;
    }
	
	public function citaExamenMPdf($idCita, $update=false)
    {    
        $sqlResumen = "
                SELECT
                `pro`.`descripcion` AS nombre,
                concat('PRO|',`em`.`codigoTipo`) as codigoTipo,
                `em`.`especificaciones`,
                `pro`.`tipo` as especialidad,
                `c`.`fechaUsuarioCierre` 
                FROM
                    `cita` `c`
                    JOIN `examenauxiliar_cita` `em` ON `em`.`idCita` = `c`.`idCita`
                    JOIN `procedimientos` `pro` ON `pro`.`codigo_interno` = `em`.`codigoTipo` 
                WHERE
                    `c`.`idCita` = '$idCita' 
                    AND `em`.`tipo` = 'PRO' 
                    AND `c`.`status` = 0 UNION
                SELECT
                    `exa`.`nombre`,
                    concat('LAB|',`em`.`codigoTipo`) as codigoTipo,
                    `em`.`especificaciones`,
                    `exa`.`tipo` as especialidad,
                    `c`.`fechaUsuarioCierre` 
                FROM
                    `cita` `c`
                    JOIN `examenauxiliar_cita` `em` ON `em`.`idCita` = `c`.`idCita`
                    JOIN `examen` `exa` ON `exa`.`id` = `em`.`codigoTipo` 
                WHERE
                    `c`.`idCita` = '$idCita' 
                    AND `em`.`tipo` = 'LAB' 
                    AND `c`.`status` = 0 
                
                    UNION
                    SELECT
                        `em`.`nombre`,
                        '' as codigoTipo,
                        `em`.`especificaciones`,
                        `em`.`especialidad`,
                        `c`.`fechaUsuarioCierre` 
                    FROM
                        `cita` `c`
                        JOIN `examenauxiliar_cita` `em` ON `em`.`idCita` = `c`.`idCita`
                         
                    WHERE
                        `c`.`idCita` = '$idCita' 
                        AND `em`.`tipo`  is null
                        AND `c`.`status` = 0  
        ";

        $query = $this->db->query($sqlResumen);

        return $query;
    }
    
    public function citaDiagnosticoPdf($idCita, $update=false)
    {    
        $this->db->select("cie.id, cie.ci10, cie.descripcion, ciec.tipo, if(ciec.tipo ='DEF', 'Definitivo', if(ciec.tipo ='PRE', 'Presuntivo', '')) as nombreTipo ");
        $this->db->from("cita c");
        $this->db->join("cie_cita ciec", "ciec.idCita = c.idCita");
        $this->db->join("cie cie", "cie.id = ciec.idCie");
        $this->db->where("c.idCita", $idCita);
        $this->db->where("c.status", 0);
        $this->db->order_by("ciec.id", 'ASC');
        $query = $this->db->get();

        if ($update) { 
            $result = array();
            foreach ($query->result() as $row)
            {
                $result[] = $row->id;
            }
            return $result;
        }


        return $query;
    }
    
    public function historialMedico($idCita)
    {    
        $this->db->select("hc.fur, hc.rc, hc.gp, hc.mac, hc.antecedente_quirurgico, hc.recomendaciones, hc.medicamentoHabitual, hc.numeroCorrelativo, hc.tiempo_enfermedad, hc.relato, hc.funciones_biologicas_comentario, hc.normales, hc.antecedes_patologico, hc.antecedes_patologico_dislipidemia, hc.antecedes_patologico_diabestes, hc.antecedes_patologico_hta, hc.antecedes_patologico_asma, hc.antecedes_patologico_gastritis , hc.otros_antecedentesp, hc.antecedes_familiar, hc.otros_antecedentesf, hc.relaciones_adversas, hc.medicamentos, hc.otros_medicamentos, c.idCita");
        $this->db->from("historial_cita hc");
        $this->db->join("cita c", "c.idCita = hc.idCita");
        $this->db->where("c.idCita", $idCita);

        $resultado = $this->db->get();
 
        return $resultado->row_array();
    }
    
    public function examenFisicoCita($idCita)
    {    
        $this->db->select("ef.pa, ef.fc, ef.fr, ef.tt, ef.sato, ef.egeneral, ef.peso, ef.talla, format(peso/pow(talla, 2), 5) as imc");
        $this->db->from("cita c");
        $this->db->join("examenfisico_cita ef", "ef.idCita = c.idCita");
        $this->db->where("c.idCita", $idCita);

        $resultado = $this->db->get();
 
        return $resultado->row_array();
    }

    public function planTratamiento($idCita, $update=false)
    {    
        $this->db->select("pt.descripcion, pt.observacion");
        $this->db->from("cita c");
        $this->db->join("plantratamiento_cita pt", "pt.idCita = c.idCita");
        $this->db->where("c.idCita", $idCita);
        $this->db->where("c.status", 0);
        $this->db->order_by("pt.fechaCreacion", 'DESC');

        $query = $this->db->get();

        if ($update) { 
            $result = array();
            foreach ($query->result() as $row)
            {
                $result[] = $row->descripcion;
                $result[] = $row->observacion;
            }
            return $result;
        }

        //print_r($this->db->last_query());
        return $query;
    }
	
    public function infoCita($idCita)
    {    
        $this->db->select("fechaUsuarioCierre, (SELECT  concat(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(lastname), ' ', '_'), 'á', 'a'), 'é','e'), 'í','i'), 'ó','o'), 'ú','u'), 'ñ', 'n'), cmp) FROM doctors WHERE idDoctor=cita.idMedico) as nombreMedicoImg");
        $this->db->from("cita");
        $this->db->where("idCita", $idCita);

        $resultado = $this->db->get();
 
        return $resultado->row_array();
    }

    public function numeroHistorialClinica($idUsuario)
    {    
        $this->db->select("count(*) + 1 as cantidad");
        $this->db->from("historial_cita hc");
        $this->db->join("cita c", "c.idCita = hc.idCita");
        $this->db->where("c.idUsuario", $idUsuario);

        $resultado = $this->db->get();
 
        return $resultado->row_array();
    }
	
	public function descansoMedico($idCita)
    {
        $this->db->select("dm.descripcionTipo, DATEDIFF( dm.al, dm.del) +1 as dias, dm.del, dm.al, 	CONCAT(d.title,' ', d.firstname, ' ', d.lastname) as medico, d.cmp, sp.`name` as especialidad, (select GROUP_CONCAT(cie.descripcion) from cie_cita INNER JOIN cie on cie.id=cie_cita.idCie  where cie_cita.idCita= c.idCita) as diagnostico");
        $this->db->from("cita c");
        $this->db->join("descansomedico_cita dm", "dm.idCita = c.idCita");
        $this->db->join("doctors d", "d.idDoctor = c.idMedico");
        $this->db->join("specialties sp", "sp.idSpecialty = d.idSpecialty");
        $this->db->where("c.idCita", $idCita);

        $resultado = $this->db->get();
 
        return $resultado->row_array();
    }
	
	public function info_gestionPaciente($id)
    {    
		$this->db->select("concat(gpc.nombre, ' ', gpc.apellido) as cliente, gpc.dni, gpc.email, gpc.pasaporte, if(gpc.resultado = 0, 'Sin resultado', if(gpc.resultado = 1, 'POSITIVO', 'NEGATIVO')) as resultado, gp.fecha, gp.hora, TIMESTAMPDIFF(YEAR, gpc.fechaNacimiento,CURDATE()) AS edad, gpc.fechaNacimiento");
		$this->db->from('gestion_paciente gp');
		$this->db->join('gestion_paciente_cliente2 gpc', 'gpc.idGestionPaciente = gp.id');
		$this->db->where("gpc.id", $id);
        $resultado = $this->db->get();

        return $resultado->row_array();
    }
	
	//informes
	

    public function datosBioquimica($codigo, $tipo)
    {    
        //$where_in = array("1", "2", "3" , "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15" , "16", "17", "18", "19", "20");

        $this->db->select("fecha_envio, lab.dato1, lab.dato2, lab.dato3, lab.dato4, lab.dato5, lab.observacion, soli.fechaExamen, exa.nombre, exa.valor_referencial, lab.idExamen, exa.unidades, soli.idPerfil");
        $this->db->from("laboratorio lab");
        $this->db->join('examen exa', 'exa.id = lab.idExamen');
        $this->db->join('solicitarexamen soli', 'soli.idExamen = lab.idExamen');
        $this->db->where("soli.codigo_interno", $codigo);
        $this->db->where("exa.tipo", $tipo);
        $this->db->where("soli.estado > 0");
		$this->db->where("soli.idTipo= lab.id");
		$this->db->where("exa.nuevo", 0);
        //$this->db->where_in("lab.idExamen", $where_in);
        $query = $this->db->get();
        //print_r($this->db->last_query());
        //die();
        return $query;
    }

    public function datosOrina_completo($codigo)
    {    
        $this->db->select("fecha_envio, ori.color, ori.aspecto, ori.ph, ori.densidad, ori.nitrito, ori.urobilinogeno, ori.glucosa, ori.sangre, ori.proteina, ori.cetona, ori.bilirrubina, ori.cepitelial, ori.leucocito, ori.hematie, ori.germen, ori.observacion");
        $this->db->from("orina_completo ori");
        $this->db->join('examen exa', 'exa.id = ori.idExamen');
        $this->db->join('solicitarexamen soli', 'soli.idExamen = ori.idExamen');
        $this->db->where("soli.codigo_interno", $codigo);
        $this->db->where("exa.tipo", "ORINA");
        $this->db->where("soli.estado > 0");
        $this->db->where_in("ori.idExamen", 23);
		$this->db->where("soli.idTipo= ori.id");
		$this->db->where("exa.nuevo", 0);
        $query = $this->db->get();
        
        return $query;
    }

    public function datosHematologia($codigo)
    {    
        $this->db->select("fecha_envio, lab.dato1, lab.dato2, lab.dato3, lab.dato4, lab.dato5, lab.observacion, soli.fechaExamen, exa.nombre, exa.valor_referencial, lab.idExamen, exa.unidades");
        $this->db->from("laboratorio lab");
        $this->db->join('examen exa', 'exa.id = lab.idExamen');
        $this->db->join('solicitarexamen soli', 'soli.idExamen = lab.idExamen');
        $this->db->where("soli.codigo_interno", $codigo);
        $this->db->where("exa.tipo", "HEMATOLOGIA");
        $this->db->where("soli.estado > 0");
        $this->db->where_not_in("lab.idExamen", 25);
		$this->db->where("soli.idTipo= lab.id");
		$this->db->where("exa.nuevo", 0);
        $query = $this->db->get();

        return $query;
    }
    
    public function datosHemagrama($codigo)
    {    
        $this->db->select("fecha_envio, hemo.leu, hemo.eri, hemo.hb, hemo.htc, hemo.vcm, hemo.hcm, hemo.ccmh, hemo.plaq, hemo.mielocito, hemo.metamielocito, hemo.abastonado, hemo.segmentado, hemo.eosinofilo, hemo.basofilo, hemo.linfocito, hemo.monocito, hemo.observaciones");
        $this->db->from("hemograma hemo");
        $this->db->join('examen exa', 'exa.id = hemo.idExamen');
        $this->db->join('solicitarexamen soli', 'soli.idExamen = hemo.idExamen');
        $this->db->where("soli.codigo_interno", $codigo);
        $this->db->where("exa.tipo", "HEMATOLOGIA");
        $this->db->where("soli.estado > 0");
		$this->db->where("soli.idTipo= hemo.id");
		$this->db->where("exa.nuevo", 0);
        $query = $this->db->get();
        
        return $query;
    }
    
    public function datospSimple($codigo, $idExamen)
    {    
        $this->db->select("fecha_envio, para.color, para.aspecto, para.moco, para.sangre, para.muestra1, para.muestra2, para.muestra3, para.observacion");
        $this->db->from("parasitologico para");
        $this->db->join('examen exa', 'exa.id = para.idExamen');
        $this->db->join('solicitarexamen soli', 'soli.idExamen = para.idExamen');
        $this->db->where("soli.codigo_interno", $codigo);
        $this->db->where("exa.tipo", "MICROBIOLOGIA");
        $this->db->where("soli.estado > 0");
        $this->db->where("para.idExamen", $idExamen);
		$this->db->where("soli.idTipo= para.id");
		$this->db->where("exa.nuevo", 0);
        $query = $this->db->get();
        
        return $query;
    }
    
    public function datosTest($codigo, $idExamen)
    {    
        $this->db->select("fecha_envio, lab.dato1, lab.dato2, lab.dato3, lab.dato4, lab.dato5, lab.observacion, soli.fechaExamen, exa.nombre, exa.valor_referencial, lab.idExamen, exa.unidades");
        $this->db->from("laboratorio lab");
        $this->db->join('examen exa', 'exa.id = lab.idExamen');
        $this->db->join('solicitarexamen soli', 'soli.idExamen = lab.idExamen');
        $this->db->where("soli.codigo_interno", $codigo);
        $this->db->where("exa.tipo", "MICROBIOLOGIA");
        $this->db->where("soli.estado > 0");
        $this->db->where("lab.idExamen", $idExamen);
		$this->db->where("soli.idTipo= lab.id");
		$this->db->where("exa.nuevo", 0);
        $query = $this->db->get();

        return $query;
    }
    
    public function datosAglutinacion($codigo, $idExamen)
    {    
        $this->db->select("fecha_envio, aglu.somatico, aglu.obs_somatico, aglu.flagelar, aglu.obs_flagelar, aglu.paratificoa, aglu.obs_paratificoa, aglu.paratificob, aglu.obs_paratificob, aglu.brucella, aglu.obs_brucella, exa.unidades, exa.nombre");
        $this->db->from("aglutinacion aglu");
        $this->db->join('examen exa', 'exa.id = aglu.idExamen');
        $this->db->join('solicitarexamen soli', 'soli.idExamen = aglu.idExamen');
        $this->db->where("soli.codigo_interno", $codigo);
        $this->db->where("exa.tipo", "INMUNOLOGIA");
        $this->db->where("soli.estado > 0");
        $this->db->where("aglu.idExamen", $idExamen);
		$this->db->where("soli.idTipo= aglu.id");
		$this->db->where("exa.nuevo", 0);
        $query = $this->db->get();

        return $query;
    }

    public function datosInmunologia($codigo, $idExamen="")
    {    
        $this->db->select("fecha_envio, lab.dato1, lab.dato2, lab.dato3, lab.dato4, lab.dato5, lab.observacion, soli.fechaExamen, exa.nombre, exa.valor_referencial, lab.idExamen, exa.unidades, soli.idPerfil");
        $this->db->from("laboratorio lab");
        $this->db->join('examen exa', 'exa.id = lab.idExamen');
        $this->db->join('solicitarexamen soli', 'soli.idExamen = lab.idExamen');
        $this->db->where("soli.codigo_interno", $codigo);
        $this->db->where("exa.tipo", "INMUNOLOGIA");
        $this->db->where("soli.estado > 0");
		$this->db->where("soli.idTipo= lab.id");
		$this->db->where("exa.nuevo", 0);
        if($idExamen > 0) {
          $this->db->where("lab.idExamen", 40);
        } else {
            $this->db->where_not_in("lab.idExamen", 40);
        }

        $query = $this->db->get();
    
        return $query;
    }
	
	public function consultarMonto($idPaciente, $idEspecialidad)
    {
        $this->db->select("COUNT(c.idCita) as cantidad");
		$this->db->from('cita c');
		$this->db->where('c.idEspecialidad', $idEspecialidad);
		$this->db->where('c.idUsuario', $idPaciente);
        $this->db->join('patients user', 'user.idUsuario = c.idUsuario');
		$this->db->where("date(user.fechaCreacion) > '2021-07-07'");
		$this->db->where_in('c.status', array('0', '1'));
		$this->db->where_in('c.idEspecialidad',  array('12', '13'));
        $query = $this->db->get();
		$row_resultado = $query->row_array();
      
        return $row_resultado['cantidad'];
    }
    //
    public function consultar_precio($id, $tipo=false)
    {    
        $this->db->select("precio");
		if ($tipo){
            $this->db->from('examen');
            $this->db->where("id", $id);
        } else {
            $this->db->from('procedimientos');
            $this->db->where("codigo_interno", $id);
        }

        $query = $this->db->get();
		$row_resultado = $query->row_array();
        
        return $row_resultado;
    }
	
    public function datosPapanicolau($codigo, $idExamen)
    {    
        $this->db->select("soli.fecha_envio, pa.calidad_muestra, pa.flora_doderlein, pa.polimorfonucleares, pa.hematies, pa.filamentos_mucoides, pa.candida_albicans, pa.gardnerella_vaginalis, pa.herpes, pa.resultados, pa.observaciones");
        $this->db->from("papanicolau pa");
        $this->db->join('examen exa', 'exa.id = pa.idExamen');
        $this->db->join('solicitarexamen soli', 'soli.idExamen = pa.idExamen');
        $this->db->where("soli.codigo_interno", $codigo);
        $this->db->where("exa.tipo", "PAPANICOLAOU");
        $this->db->where("soli.estado > 0");
        $this->db->where("pa.idExamen", $idExamen);
		$this->db->where("exa.nuevo", 0);
		$this->db->where("soli.idTipo= pa.id");
		 
        $query = $this->db->get();
 
        return $query;
    }

    public function datosBiposia($codigo, $idExamen)
    {    
        $this->db->select("soli.fecha_envio, bp.muestra_remitida, bp.conclusion, bp.examen_microscopico");
        $this->db->from("biopsia_pequena bp");
        $this->db->join('examen exa', 'exa.id = bp.idExamen');
        $this->db->join('solicitarexamen soli', 'soli.idExamen = bp.idExamen');
        $this->db->where("soli.codigo_interno", $codigo);
        $this->db->where("exa.tipo", "MUESTRA_REMITIDA");
        $this->db->where("soli.estado > 0");
        $this->db->where("bp.idExamen", $idExamen);
		$this->db->where("soli.idTipo= bp.id");
		$this->db->where("exa.nuevo", 0);
        $query = $this->db->get();

        return $query;
    }
	
	public function datosBiposia_colegrama($codigo, $idExamen, $tipo)
    {    
        $this->db->select("soli.fecha_envio, lab.dato1, lab.dato2, lab.dato3, lab.dato4, lab.observacion");
        $this->db->from("laboratorio lab");
        $this->db->join('examen exa', 'exa.id = lab.idExamen');
        $this->db->join('solicitarexamen soli', 'soli.idExamen = lab.idExamen');
        $this->db->where("soli.codigo_interno", $codigo);
        $this->db->where("exa.tipo", $tipo);
        $this->db->where("soli.estado > 0");
        $this->db->where("lab.idExamen", $idExamen);
		$this->db->where("soli.idTipo= lab.id");
		$this->db->where("exa.nuevo", 0);
        $query = $this->db->get();

        return $query;
    }
	
        
    public function datosSecrecionVaginal($codigo, $idExamen)
    {    
        $this->db->select("soli.fecha_envio, sv.celulas, sv.leucocitos, sv.trichomonas, sv.levaduras, sv.fdoderlein, sv.germenes, sv.seaislo, sv.observacion");
        $this->db->from("secrecion_vaginal sv");
        $this->db->join('examen exa', 'exa.id = sv.idExamen');
        $this->db->join('solicitarexamen soli', 'soli.idExamen = sv.idExamen');
        $this->db->where("soli.codigo_interno", $codigo);
        $this->db->where("exa.tipo", "MICRO_BIOLOGIA");
        $this->db->where("soli.estado > 0");
        $this->db->where("sv.idExamen", $idExamen);
		$this->db->where("soli.idTipo= sv.id");
		$this->db->where("exa.nuevo", 0);
        $query = $this->db->get();
       
        return $query;
    }
	
    public function datos_plantillaTotal($codigo, $idExamen)
    {   
        $this->db->select("soli.fecha_envio, lab.dato1, lab.dato4, lab.observacion, exa.nombre");
        $this->db->from("laboratorio lab");
        $this->db->join('examen exa', 'exa.id = lab.idExamen');
        $this->db->join('solicitarexamen soli', 'soli.idExamen = lab.idExamen');
        $this->db->where("soli.codigo_interno", $codigo);
        $this->db->where("soli.estado > 0");

        $this->db->where("exa.nuevo", 1);
		$this->db->where("soli.idTipo= lab.id");
        $query = $this->db->get();
       
        return $query;
    }
	
  
    public function citaExamenMPdf2($idCita, $update=false)
    {    
        $sqlResumen = "
                SELECT
                `pro`.`descripcion` AS nombre,
                concat('PRO|',`em`.`codigoTipo`) as codigoTipo,
                `em`.`especificaciones`,
                `pro`.`tipo` as especialidad,
                `c`.`fechaUsuarioCierre`,
				em.id
                FROM
                    `cita` `c`
                    JOIN `examenauxiliar_cita` `em` ON `em`.`idCita` = `c`.`idCita`
                    JOIN `procedimientos` `pro` ON `pro`.`codigo_interno` = `em`.`codigoTipo` 
                WHERE
                    `c`.`idCita` = '$idCita' 
                    AND `em`.`tipo` = 'PRO' 
                    AND `c`.`status` = 0 UNION
                SELECT
                    `exa`.`nombre`,
                    concat('LAB|',`em`.`codigoTipo`) as codigoTipo,
                    `em`.`especificaciones`,
                    `exa`.`tipo` as especialidad,
                    `c`.`fechaUsuarioCierre`,
					em.id
                FROM
                    `cita` `c`
                    JOIN `examenauxiliar_cita` `em` ON `em`.`idCita` = `c`.`idCita`
                    JOIN `examen` `exa` ON `exa`.`id` = `em`.`codigoTipo` 
                WHERE
                    `c`.`idCita` = '$idCita' 
                    AND `em`.`tipo` = 'LAB' 
                    AND `c`.`status` = 0 
                
                    UNION
                    SELECT
                        `em`.`nombre`,
                        '' as codigoTipo,
                        `em`.`especificaciones`,
                        `em`.`especialidad`,
                        `c`.`fechaUsuarioCierre`,
						em.id
                    FROM
                        `cita` `c`
                        JOIN `examenauxiliar_cita` `em` ON `em`.`idCita` = `c`.`idCita`
                         
                    WHERE
                        `c`.`idCita` = '$idCita' 
                        AND `em`.`tipo`  is null
                        AND `c`.`status` = 0  
        ";

        $query = $this->db->query($sqlResumen);

        return $query;
    }
	
    public function insert_or_update($table, $form_data, $opcion=false, $user=false, $codigoInterno=false)
    {
        $where = array('idCita' => $form_data["idCita"]);

        if($opcion) {
            $where2 = array('codigoTipo' => $form_data["codigoTipo"]);
            $where = array_merge($where, $where2);
        } 
 

        $query = $this->db->get_where($table, $where);
       
        $count = $query->num_rows(); 

        if ($count === 0) {
            $this->db->insert($table, $form_data);
            $idExamenAuxi = $this->db->insert_id();



            $opcion = ($form_data["tipo"] == "LAB")? true: false;
            $costo = $this->Helper->consultar_precio($form_data["codigoTipo"], $opcion);

            $procedimientosPro = array(
                'idUsuario' => $user,
                'codigo_procedimiento' => $form_data["codigoTipo"],
                'marca_cita' => 2,
                'tipo_solicitud' => $form_data["tipo"],
                'idExamenAuxiliar' => $idExamenAuxi,
                'precio' => $costo["precio"],
                'norden' => $codigoInterno,
                'idUsuarioCreacion' => $this->session->userdata('idUsuario')
            );

            $this->db->insert("solicitud_citas_pagos", $procedimientosPro);

        } else {
            $this->db->where('idCita', $form_data["idCita"]);
            if($opcion) $this->db->where('codigoTipo', $form_data["codigoTipo"]);
            $this->db->update($table, $form_data);
        }

        return true;
    }
	
	
	public function insert_or_updateAtigeno($form_data, $user=false)
    {
        $where = array('idGestion' => $form_data["idGestion"], 'tipo' => $form_data["tipo"]);

        $query = $this->db->get_where("gestion_paciente_prueba", $where);
     
        $count = $query->num_rows(); 

        if ($count === 0) {
            unset($form_data['idUnico']);
            $this->db->insert("gestion_paciente_prueba", $form_data);
            $idGestionPrueba = $this->db->insert_id();
           
            $procedimientosPro = array(
                'idUsuario' => $form_data["idUsuario"],
				 'cantidad' => $form_data["cantidad"],
                'codigo_procedimiento' => $form_data["idGestion"],
                'marca_cita' => 2,
                'precio' => $form_data["precio"],
                'descuento' => $form_data["descuento"],
                'precio_transporte' => $form_data["precio_transporte"],
                'tipo_solicitud' => "ANT",
                'idGestionPrueba' => $idGestionPrueba,
                'idUsuarioCreacion' => $this->session->userdata('idUsuario')
            );

            $this->db->insert("solicitud_citas_pagos", $procedimientosPro);

        } else {

            $procedimientosPro = array(
                'precio' => $form_data["precio"],
				 'cantidad' => $form_data["cantidad"],
                'precio_transporte' => $form_data["precio_transporte"],
                'descuento' => $form_data["descuento"]
            );


        
           
            //$this->db->where('codigo_procedimiento', $form_data["idGestion"]);
            $this->db->where('idGestionPrueba', $form_data["idUnico"]);
            $this->db->where('tipo_solicitud', "ANT");
            $this->db->update("solicitud_citas_pagos", $procedimientosPro);

       
            //$this->db->where('idGestion', $form_data["idGestion"]);
            
            $this->db->where('id', $form_data["idUnico"]);
            unset($form_data['idUnico']);
            $this->db->where('tipo', $form_data["tipo"]);
            $this->db->update("gestion_paciente_prueba", $form_data);
        }

        return true;
    }
	
    public function insert_or_update2($table, $form_data)
    {

        $query = $this->db->get_where('examenfisico_cita', array('idCita' => $form_data["idCita"]));

        $count = $query->num_rows(); 
 
        if ($count === 0) {
            $this->db->insert('examenfisico_cita', $form_data);
        } else {
            $this->db->where('idCita', $form_data["idCita"]);
            $this->db->update('examenfisico_cita', $form_data);
        }

        return true;
    }
	
    function sanear_string($string) {
        $string = trim($string);
    
        $string = str_replace(
            array('à', 'ä', 'â', 'ª',  'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'A', 'A', 'A'),
            $string
        );
    
        $string = str_replace(
            array('è', 'ë', 'ê','È', 'Ê', 'Ë'),
            array('e', 'e', 'e','E', 'E', 'E'),
            $string
        );
    
        $string = str_replace(
            array('ì', 'ï', 'î', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'I', 'I', 'I'),
            $string
        );
    
        $string = str_replace(
            array('ò', 'ö', 'ô', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'O', 'O', 'O'),
            $string
        );
    
        $string = str_replace(
            array('ù', 'ü', 'û', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'U', 'U', 'U'),
            $string
        );
    
        $string = str_replace(
            array('ç', 'Ç'),
            array('c', 'C',),
            $string
        );
    
        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
            array("\\", "¨", "º", "-", "~",
                 "#", "@", "|", "!", "\"",
                 "·", "$", "%", "&", "/",
                 "(", ")", "?", "'", "¡",
                 "¿", "[", "^", "`", "]",
                 "+", "}", "{", "¨", "´",
                 ">", "< ", ";", ",", ":",
                 "."),
            '',
            $string
        );
    
    
        return $string;
    }	
        
}
