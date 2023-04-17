<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public $data;

	public function __construct() {
		parent::__construct();		
		// Cargamos Requests y Culqi PHP
		$this->load->database();
		$this->load->library('form_validation');
		$this->load->library('session');		
		$this->load->model('Usuario');
		$this->load->model('Especialidad');
		$this->load->model('Helper');
		$this->load->helper('url'); 
		
		$this->data = array();

		$this->data['version'] = $this->Helper->configuracion();
	}
	
	public function validarSesion(){
		if(!$this->session->userdata('logged_in'))
		{
			redirect(base_url('login'));
		}
	}

	public function cargarDatosSesion(){
		$this->validarSesion();
		$idUsuario = $this->session->userdata('idUsuario');
		$rol = $this->session->userdata('rol');
		$document = $this->session->userdata('username');
		$firstname = $this->session->userdata('firstname');
		$lastname = $this->session->userdata('lastname');
		$this->data['document'] = $document;
		$this->data['firstname'] = $firstname;
		$this->data['lastname'] = $lastname;
		$this->data['idUsuario'] = $idUsuario;
		$this->data['rol'] = $rol;

		$this->data['permisosTotal'] = $this->Helper->permiso_usuario();

	}
	
	public function schedule()
	{ 
		$this->validarSesion();
 
		$this->cargarDatosSesion();
		$fechaInicial= date('Y-m-d');
		
		if($this->Helper->permiso_usuario("configuración_general") and $this->Helper->permiso_usuario("aperturar_horarios"))
		{
			$sql = "
				SELECT
					date,
					availabilities.idDoctor,
					turno,
					LEFT ( min( start_time ), 5 ) AS horaMinimo,
					LEFT ( max( end_time ), 5 ) AS horaMaxima,
				IF
					( turno = 1, 'Mañana', 'Tarde' ) AS turno,
				IF
					(
						area = 1,
						'CE-COVID',
					IF
					( area = 2, 'Reten', IF
					( area = 3, 'Presencial', IF
					( area = 5, 'Teleconsulta', IF
					( area = 6, 'Procedimientos', IF
					( area = 7, 'CAI', IF
					( area = 8, 'CE', 'COVID' ) ) ) ) ) )) AS tipo,
					concat(doctors.firstname, ' ', doctors.lastname) as lastname,
					specialties.name as especialidad
				FROM
					availabilities
					INNER JOIN doctors ON doctors.idDoctor = availabilities.idDoctor 
					INNER JOIN specialties ON specialties.idSpecialty = doctors.idSpecialty 
					where left(availabilities.date, 7) >='2022-02' and availabilities.disponible in(0, 1)
				GROUP BY
					availabilities.idDoctor,
					date,
					turno,
					area,codigo_interno 
				ORDER BY
					 date  asc 
			";
			
			
			$this->data["registros"] = $query = $this->db->query($sql)->result();
			
			//print_r($this->db->last_query());
			
			 
			$this->data['especialidades'] = $this->Especialidad->listAll();

			$this->load->view("admin/aperturar_horarios", $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}

	public function schedule2()
	{ 
		$this->validarSesion();
 
		$this->cargarDatosSesion();
		$fechaInicial= date('Y-m-d');
		
		if($this->Helper->permiso_usuario("configuración_general") and $this->Helper->permiso_usuario("aperturar_horarios"))
		{
			$this->db->select("a.idAvailability, a.date, a.start_time, a.end_time, a.monto, s.name as especialidad, concat(d.firstname, ' ', d.lastname) as medico ");

			$this->db->from('availabilities a');
			$this->db->join('doctors d', 'd.idDoctor = a.idDoctor');
			$this->db->join('specialties s', 's.idSpecialty = d.idSpecialty');
			$this->db->where("a.date >= '$fechaInicial'");
			$this->db->where('a.disponible in(1,0)' );
	
			$this->db->order_by("a.date", "asc");
	
			$this->data["registros"] = $this->db->get()->result();
			
			//print_r($this->db->last_query());
			
			 
			$this->data['especialidades'] = $this->Especialidad->listAll();

			$this->load->view("admin/aperturar_horarios", $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}

 
	public function grabarHorario()
	{
		$this->validarSesion();

		$this->cargarDatosSesion();

		if(!$this->Helper->permiso_usuario("configuración_general"))
		{
			redirect(base_url("inicio"));
		}

		$this->form_validation->set_rules('cmbMedico', "-", 'required');

		if ($this->form_validation->run() == FALSE)
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {
			
			$fechaInicio = strtotime(date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post("fechaInicio")))));
			$fechaFin = strtotime(date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post("fechaFin")))));

			$time= $this->input->post("horaInicio");
			$time2= $this->input->post("horaFinal");
  
			$apertura = new DateTime($time);
			$cierre = new DateTime($time2);
			
			$tiempo = $apertura->diff($cierre);
			
			//$hora = ($this->input->post("rangoHora") == 60)?  : $tiempo->format('%H')*2;
			$hora = null;
			if($this->input->post("rangoHora") == 60){
				$hora = $tiempo->format('%H');
			} else if($this->input->post("rangoHora") == 30) {
				$hora = $tiempo->format('%H')*2;
			} else if($this->input->post("rangoHora") == 15) {
				$hora = $tiempo->format('%H')*4;				
			} else if($this->input->post("rangoHora") == 40) {
				$hora = $tiempo->format('%H') +2;
			} else if($this->input->post("rangoHora") == 10) {
				$hora = $tiempo->format('%H')*6;				
			} else {
				$hora = $tiempo->format('%H')*3;
			}
			
			$hora = $tiempo->format('%H')*12;
			
			$codigoInterno = null;
			
			for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
				$fecha= date("Y-m-d", $i);
				$dia=date("w", strtotime($fecha));

				if($dia == 0) continue;
				
				if(is_null($codigoInterno)) {
					$codigoInterno = strtoupper(uniqid());
				}
 
				for ($ii=0; $ii <= $hora  ; $ii++) {

					if($time == $time2) { 

						$time= $this->input->post("horaInicio");
						continue;
						 
					}

					$time3= date('H:i', strtotime($time)+ 60*5);


					//if($time == "13:00" or $time == "13:30") {
						//$time = $time3;
						//continue;
					//}

					$dataHorario = array(
				
						'idUsuario' => $this->session->userdata('idUsuario'),
						'date' => $fecha,
						'start_time' => $time,
						'end_time' => $time3,
						'idDoctor' => $this->input->post("cmbMedico"),
						'tipoCita' => $this->input->post("tipoCita"),
						'turno' => $this->input->post("turno"),
						'area' => $this->input->post("area"),
						'codigo_interno' => $codigoInterno,
						'monto' => $this->input->post("monto")
					);

					$time = $time3;

					$this->db->insert("availabilities", $dataHorario);
				}
			}
  
			if ($this->db->trans_status() === true)
			{
				$response['message'] = "Se grabo el horario correctamente.";
				$response['status'] = true;
			} else {
				$response['message'] = "Error. No se registro.";
				$response['status'] = false;
			}
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function bloquearHorario()
	{ 
		$this->validarSesion();

		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("configuración_general") and $this->Helper->permiso_usuario("aperturar_horarios"))
		{
			$this->db->select("a.idAvailability, a.date, a.start_time, a.end_time, a.monto, s.name as especialidad, concat(d.firstname, ' ', d.firstname) as medico ");

			$this->db->from('availabilities a');
			$this->db->join('doctors d', 'd.idDoctor = a.idDoctor');
			$this->db->join('specialties s', 's.idSpecialty = d.idSpecialty');
			$this->db->where('a.disponible', 1);
			$this->db->where('a.state', 1);
	
			$this->db->order_by("a.date", "asc");
	
			$this->data["registros"] = $this->db->get()->result();
			$this->data['especialidades'] = $this->Especialidad->listAll();

			$this->load->view("admin/bloquear_horario", $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}
	
	public function guardarBloquearHorario()
	{
		$bloqueo = $this->input->post("bloquear");

		if($bloqueo)	$bloqueo = 2; else $bloqueo = 1;

		$this->db->set("disponible", $bloqueo);
		
		$this->db->where("idDoctor", $this->input->post("cmbMedico"));
		$this->db->where("tipoCita", $this->input->post("tipoCita"));
		$this->db->where("disponible !=", 0);
		$this->db->where("disponible !=", $bloqueo);
		$this->db->where("date BETWEEN '".date("Y-m-d",strtotime(str_replace("/", "-", $this->input->post("fechaInicio"))))."' and '".date("Y-m-d",strtotime(str_replace("/", "-", $this->input->post("fechaFin"))))."'");
		$this->db->group_start();
		$this->db->where("start_time BETWEEN '".$this->input->post("horaInicio")."' and '".$this->input->post("horaFinal")."'  and end_time BETWEEN '".$this->input->post("horaInicio")."'  and '".$this->input->post("horaFinal")."' ");
		$this->db->group_end();

		$this->db->update('availabilities');
 
		$this->db->trans_complete();

		if ($this->db->trans_status() === true)
		{
			$response['message'] = "Se bloqueo el horario correctamente.";
			$response['status'] = true;
			
			$bloqueoDetalle = array(
				'idProfesional' => $this->input->post("cmbMedico"),
				'tipoCita' => $this->input->post("tipoCita"),
				'bloqueo' => $bloqueo,
				'horaIncio' => $this->input->post("horaInicio"),
				'horaFin' => $this->input->post("horaFinal"),
				'fechaInicio' => date("Y-m-d",strtotime(str_replace("/", "-", $this->input->post("fechaInicio")))),
				'fechaFinal' => date("Y-m-d",strtotime(str_replace("/", "-", $this->input->post("fechaFin")))),
				'idUsuario' => $this->session->userdata('idUsuario')
			);

			$this->db->insert("historial_bloqueo", $bloqueoDetalle);
		} else {
			$response['message'] = "Error. No se bloqueo el horario.";
			$response['status'] = false;
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function eliminarAllHorarios()
	{
		$this->db->truncate('availabilities');

		$response['message'] = "Se elimino todo correctamente.";
		$response['status'] = true;

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function eliminarHorario()
	{
		if ($this->input->post("id") < 1)
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {
 
			$this->db->trans_start();
			$this->db->where('idAvailability', $this->input->post("id"));
			$this->db->delete('availabilities');
			$this->db->trans_complete();

			if ($this->db->trans_status() === true)
			{
				$response['message'] = "Se elimino el horario correctamente.";
				$response['status'] = true;
			} else {
				$response['message'] = "Error. No se elimino el horario.";
				$response['status'] = false;
			}
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	
	public function permisos()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("configuración_general") and $this->Helper->permiso_usuario("permiso_usuario"))
		{
			$this->db->select("u.idUser, concat(p.firstname, ' ', p.lastname) as nombreUsuario");
			$this->db->from('users u');
			$this->db->join('patients p', 'p.idUsuario = u.idUser');
			$this->db->where('u.status', 1);
			$this->db->order_by("p.lastname", "asc");
	
			$this->data["usuarios"] = $this->db->get()->result();
	  
			$this->db->select("id, descripcion");
			$this->db->from('rol');
			$this->db->order_by("descripcion", "asc");
	
			$this->data["roles"] = $this->db->get()->result();
	
			$this->db->select("id, descripcion");
			$this->db->from('permiso');
			$this->db->order_by("descripcion", "asc");
	
			$this->data["permisos"] = $this->db->get()->result();
	
			$this->db->select("idDoctor, concat(title, ' ', firstname, ' ', lastname) as nombreMedico");
			$this->db->from('doctors');
			$this->db->where('status', 1);
			$this->db->order_by("nombreMedico", "asc");
	
			$this->data["medicos"] = $this->db->get()->result();
			
			$this->load->view("admin/permisos", $this->data);
		} else {
			redirect(base_url("inicio"));
		}

	
	}

	public function usuarioRol($usuario)
	{ 
		$this->cargarDatosSesion();

		$this->db->select("idRol");
		$this->db->from("users");
		$this->db->where('idUser', $usuario);

		$query = $this->db->get();
		$row_resultado = $query->row_array();
		
        echo $row_resultado["idRol"];
	}

	public function gUsuarioRol()
	{ 
		$parametros = array (
			"idRol" => $this->input->post("rol")
		);

		$this->db->trans_start();
		$this->db->where('idUser', $this->input->post("usuario"));
		$this->db->update("users", $parametros);
		$this->db->trans_complete();

		if ($this->db->trans_status())
		{
			$response['message'] = "Se actualizo correctamente.";
			$response['status'] = true;

		} else {
			$response['message'] = "Error. No se actualizo.";
			$response['status'] = false;
		}

		$this->output->set_content_type( 'application/json' )->set_output(json_encode($response));
	}

	public function rolPermiso($rol)
	{ 
		$this->cargarDatosSesion();

		$this->db->select("idPermiso");
		$this->db->from("permiso_rol");
		$this->db->where("idRol", $rol);

		$response['data'] = $this->db->get()->result();

        $this->output->set_content_type( 'application/json' )->set_output(json_encode($response));
	}

	public function gRolPermiso()
	{
		$this->db->trans_start();
		$this->db->where('idRol', $this->input->post("rol"));
		$this->db->delete('permiso_rol');
		$this->db->trans_complete();

		foreach ($this->input->post("permisos") as $key => $value) {
			$permisosRol = array(
				'idPermiso' => $value,
				'idRol' => $this->input->post("rol")
			);

			$this->db->insert("permiso_rol", $permisosRol);
		}
	 
		$response['message'] = "Se guardo correctamente.";
		$response['status'] = true;

		$this->output->set_content_type( 'application/json' )->set_output(json_encode($response));
	}

	
	public function medicoUsuario($usuario)
	{ 
		$this->cargarDatosSesion();

		$this->db->select("idDoctor");
		$this->db->from("doctors");
		$this->db->where('idUsuario', $usuario);

		$query = $this->db->get();
		$row_resultado = $query->row_array();
		
        echo $row_resultado["idDoctor"]*1;
	}

	public function gMedicoUsuario()
	{
		$parametros = array (
			"idUsuario" => null
		);

		$this->db->trans_start();
		$this->db->where('idUsuario', $this->input->post("usuario"));
		$this->db->update("doctors", $parametros);
		$this->db->trans_complete();

		if ($this->db->trans_status())
		{
			
			$parametros = array (
				"idUsuario" => $this->input->post("usuario")
			);

			$this->db->trans_start();
			$this->db->where('idDoctor', $this->input->post("medico"));
			$this->db->update("doctors", $parametros);
			$this->db->trans_complete();
		}

		if ($this->db->trans_status())
		{
			$response['message'] = "Se actualizo correctamente.";
			$response['status'] = true;
		} else {
			$response['message'] = "Error. No se actualizo.";
			$response['status'] = false;
		}

		$this->output->set_content_type( 'application/json' )->set_output(json_encode($response));
	}

	public function recordatorioEmailCitas()
	{	
		echo "ok." ;
		/* 
		$this->db->select("c.idCita, CONCAT( p.firstname, ' ', p.lastname ) AS nombrePaciente, p.email, c.fechaCita, c.horaCita, c.tipoCita, CONCAT( d.title, ' ', d.firstname, ' ', d.lastname ) AS nombreMedico,
		s.`name` as especialidad, SUBSTRING(p.document, 1, 4) as codigoSala");
		$this->db->from("cita c");
		$this->db->join('doctors d', 'd.idDoctor = c.idMedico');
		$this->db->join('specialties s', 's.idSpecialty = c.idEspecialidad');
		$this->db->join('users u', 'u.idUser = c.idUsuario');
		$this->db->join('patients p', 'p.idUsuario = u.idUser');
        $this->db->where("c.status", 1);
        $this->db->where("c.fechaCita = CURDATE()");
        $this->db->where("c.recordatorioEmail", 0);
        $this->db->where("p.email !=''");
        $this->db->where("(
			TIME_FORMAT(
				TIMEDIFF( SUBSTRING( c.horaCita, 1, 5 ), SUBSTRING( DATE_ADD(NOW(), INTERVAL 2 HOUR), 11, 6 ) ),
				'%H%i' 
			) <= 30 
			AND TIME_FORMAT(
				TIMEDIFF( SUBSTRING( c.horaCita, 1, 5 ), SUBSTRING( DATE_ADD(NOW(), INTERVAL 2 HOUR), 11, 6 ) ),
				'%H%i'
			) >= 20 
			) ");

		$query = $this->db->get();
		
		//configuracion para gmail
		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset']   = 'utf-8';
		$config['mailtype']  = 'html';
		$config['wordwrap'] = TRUE;
		$config['priority'] = 2;

		$this->load->library('email', $config);
					
		foreach ($query->result() as $key => $row) {
		
			$data["row"] = $row;
			$this->data['contenido'] = $this->load->view('recordatorioCita', $data, TRUE);
	
			$message = $this->load->view('mensaje', $this->data, TRUE);
		
			$this->email->set_newline("\r\n");
			$this->email->from('info@sbcmedic.com', "SBCMedic");
			$this->email->to($row->email);
			$this->email->subject("Recordatorio de cita médica");
			$this->email->message($message);
			
				if ($this->email->send()) {
				echo "ok." ;
			}else{
				show_error($this->email->print_debugger());
			}
		} */
	}

	public function recordatorioEmailCitasDia()
	{	
		echo "ok." ;
	/* 
		$this->db->select("c.idCita, CONCAT( p.firstname, ' ', p.lastname ) AS nombrePaciente, p.email, c.fechaCita, c.horaCita, c.tipoCita, CONCAT( d.title, ' ', d.firstname, ' ', d.lastname ) AS nombreMedico,
		s.`name` as especialidad, SUBSTRING(p.document, 1, 4) as codigoSala");
		$this->db->from("cita c");
		$this->db->join('doctors d', 'd.idDoctor = c.idMedico');
		$this->db->join('specialties s', 's.idSpecialty = c.idEspecialidad');
		$this->db->join('users u', 'u.idUser = c.idUsuario');
		$this->db->join('patients p', 'p.idUsuario = u.idUser');
        $this->db->where("c.status", 1);
        $this->db->where("c.recordatorioEmail", 0);
        $this->db->where("p.email !=''");
        $this->db->where("DATEDIFF(c.fechaCita, CURDATE()) = 1");

		$query = $this->db->get();
		
		//configuracion para gmail
		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset']   = 'utf-8';
		$config['mailtype']  = 'html';
		$config['wordwrap'] = TRUE;
		$config['priority'] = 2;

		$this->load->library('email', $config);
					
		foreach ($query->result() as $key => $row) {
		
			$data["row"] = $row;
			$this->data['contenido'] = $this->load->view('recordatorioCita', $data, TRUE);
	
			$message = $this->load->view('mensaje', $this->data, TRUE);
		
			$this->email->set_newline("\r\n");
			$this->email->from('info@sbcmedic.com', "SBCMedic");
			$this->email->to($row->email);
			$this->email->subject("Recordatorio de cita médica");
			$this->email->message($message);
			
				if ($this->email->send()) {
				echo "ok." ;
			}else{
				show_error($this->email->print_debugger());
			}
		} */
	}

	public function reporteExcel()
	{
		$this->validarSesion();

		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("configuración_general")and $this->Helper->permiso_usuario("exportar_excel"))
		{
			$this->load->model('Doctor');
			$this->data['medicos'] = $this->Doctor->all();
			
			$this->load->view("admin/generar_excel", $this->data);
		} else {
			redirect(base_url("inicio"));
		}

	}
	
	public function exportarCitas(){

 
/*
		$sql = "SELECT  
			(select catalogo_motivo_cancelacion.descripcion from catalogo_motivo_cancelacion where catalogo_motivo_cancelacion.id = cita.idMotivoCancelar) as motivoCancelar,
			
			if(cita.codigo_procedimiento is null, (SELECT
				GROUP_CONCAT(procedimientos.descripcion)
			FROM
				historial_asignacion_cita
				INNER JOIN procedimientos ON procedimientos.codigo_interno = historial_asignacion_cita.codigo_procedimiento 
			WHERE
				historial_asignacion_cita.idCita = cita.idCita and historial_asignacion_cita.activo = 1),  (select  procedimientos.descripcion from procedimientos where procedimientos.codigo_interno = cita.codigo_procedimiento)) as procedimiento,

			  
			 (select if(min(cita2.idCita) = cita.idCita, 'Nuevo', if( cita.status = 0 and cita.idCita > min(cita2.idCita ), 'Antiguo', if( cita.status = 2 and cita.idCita > min(cita2.idCita ), 'Antiguo', if( cita.status = 1 and cita.idCita > min(cita2.idCita ), 'Antiguo' , 'No asistio')))) from cita cita2 where cita2.idUsuario=cita.idUsuario and cita2.status=0 ) as tipoPaciente,

			 ( SELECT tipo FROM motivo_cita WHERE motivo_cita.id = cita.idMotivoCita ) AS motivoTipoCita,
			( SELECT CONCAT( patients.firstname, ' ', patients.lastname ) FROM patients WHERE patients.idUsuario = cita.idUsuarioCreacion ) AS usuarioCreacion,
			users.username AS nroDocumento,
			patients.firstname,
			patients.lastname,
			patients.phone,
			(select  name from ubigeo_districts where id=patients.idDistrict) as distrito,
			patients.address,
			patients.email,
			IF
			(
				cita.tipoCita = 'CV' || cita.virtual = 1,
				'Virtual',
			IF
				( cita.tipoCita = 'CP', 'Presencial', IF ( cita.tipoCita = 'CD', 'Domiciliaria', 'Procedimiento' ) ) 
			) AS tipoCita,
			cita.fechaCita,
			cita.horaCita,
			CONCAT( doctors.title, ' ', doctors.firstname, ' ', doctors.lastname ) AS nombreMedico,
			specialties.`name` AS especialidad,
			pago.monto,
			pago.fechaModificar,
			 ( SELECT CONCAT( patients.firstname, ' ', patients.lastname ) FROM patients WHERE patients.idUsuario = pago.idUsuarioModificar ) AS usuarioPago2, 

			if(cita.idPago, ( SELECT CONCAT( patients.firstname, ' ', patients.lastname ) FROM patients WHERE patients.idUsuario = pago.idUsuarioModificar ), (SELECT CONCAT( patients.firstname, ' ', patients.lastname ) FROM patients WHERE patients.idUsuario = (select DISTINCT  idUsuarioPago  from solicitud_citas_pagos where codigo_asignacion=  cita.codigo_asignacion and pago= 1)) ) as usuarioPago,

			(SELECT DISTINCT
				procedimientos.descripcion 
			FROM
				historial_asignacion_cita
				INNER JOIN procedimientos ON procedimientos.codigo_interno = historial_asignacion_cita.code_principal 
			WHERE
				codigo_asignacion = cita.codigo_asignacion) as paquete,

			if(cita.idPago, pago.fechaModificar, IF((select count(id) as cantidad from solicitud_citas_pagos where codigo_asignacion= cita.codigo_asignacion and pago= 1) > 0 ,  (select max(fechaPago) from solicitud_citas_pagos where codigo_asignacion= cita.codigo_asignacion and pago= 1), '')) as fechaModificar,

			if(cita.idPago, if(pago.status = 1, 'SI', 'NO'), IF((select count(id) as cantidad from solicitud_citas_pagos where codigo_asignacion= cita.codigo_asignacion and pago= 1) > 0 , 'SI', 'NO')) as statusPago,

			IF
			( cita.`status` = 1, 'Sin Atender', IF ( cita.`status` = 0, 'Atendido', 'Cancelado' ) ) AS statusCita,
			cita.idDistrito,
			if(cita.gratis = 1, 'SI', 'NO') as gratis,
			cita.fechaCreacion,
			cita.idCita,
			cita.motivo AS motivoCita,
			cita.codigo_asignacion,
			cita.direccion AS direccionDomiciliaria,
			catalogo_canalventas.nombre as canalVenta,
			if(left(horaCita, 5) >= '07:30' and left(horaCita, 5) <= '13:30', 'Mañana', 'Tarde') as turno
		FROM
			cita
			INNER JOIN users ON users.idUser = cita.idUsuario
			INNER JOIN patients ON patients.idUsuario = users.idUser
			INNER JOIN catalogo_canalventas ON catalogo_canalventas.id = patients.idCanalVenta
			INNER JOIN doctors ON doctors.idDoctor = cita.idMedico
			INNER JOIN specialties ON specialties.idSpecialty = cita.idEspecialidad
			LEFT JOIN pago ON pago.idPago = cita.idPago
		WHERE
			cita.idUsuario !=5 and
			cita.fechaCita between '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."'"; */
			
			
	$sql = "
	SELECT
	( SELECT catalogo_motivo_cancelacion.descripcion FROM catalogo_motivo_cancelacion WHERE catalogo_motivo_cancelacion.id = cita.idMotivoCancelar ) AS motivoCancelar,
IF
	(
		cita.codigo_procedimiento IS NULL,
		(
		SELECT
			GROUP_CONCAT( procedimientos.descripcion ) 
		FROM
			historial_asignacion_cita
			INNER JOIN procedimientos ON procedimientos.codigo_interno = historial_asignacion_cita.codigo_procedimiento 
		WHERE
			historial_asignacion_cita.idCita = cita.idCita 
			AND historial_asignacion_cita.activo = 1 
		),
	( SELECT procedimientos.descripcion FROM procedimientos WHERE procedimientos.codigo_interno = cita.codigo_procedimiento )) AS procedimiento,

	(
		SELECT
			if(historial_asignacion_cita.code_principal, (select GROUP_CONCAT(catalogo_motivocita.descripcion)  from  catalogo_motivocita    
			INNER JOIN procedimientos ON procedimientos.idMotivocita = catalogo_motivocita.id where procedimientos.codigo_interno =   historial_asignacion_cita.code_principal) , (select GROUP_CONCAT(catalogo_motivocita.descripcion)  from  catalogo_motivocita    
			INNER JOIN procedimientos ON procedimientos.idMotivocita = catalogo_motivocita.id where procedimientos.codigo_interno =   historial_asignacion_cita.codigo_procedimiento) )
		FROM
			historial_asignacion_cita
	
		WHERE
			historial_asignacion_cita.idCita = cita.idCita  GROUP BY historial_asignacion_cita.idCita
		) AS motivoTipoCita,


	( SELECT CONCAT( patients.firstname, ' ', patients.lastname ) FROM patients WHERE patients.idUsuario = cita.idUsuarioCreacion ) AS usuarioCreacion,
	users.username AS nroDocumento,
	patients.firstname,
	patients.lastname,
	patients.phone,
	( SELECT NAME FROM ubigeo_districts WHERE id = patients.idDistrict ) AS distrito,
	patients.address,
	patients.email,
IF
	(
		cita.tipoCita = 'CV' || cita.virtual = 1,
		'Virtual',
	IF
		( cita.tipoCita = 'CP', 'Presencial', IF ( cita.tipoCita = 'CD', 'Domiciliaria', 'Procedimiento' ) ) 
	) AS tipoCita,
	cita.fechaCita,
	cita.horaCita,
	CONCAT( doctors.title, ' ', doctors.firstname, ' ', doctors.lastname ) AS nombreMedico,
	specialties.`name` AS especialidad,
	(
	SELECT
		CONCAT( patients.firstname, ' ', patients.lastname ) 
	FROM
		patients 
	WHERE
		patients.idUsuario = ( SELECT DISTINCT idUsuarioPago FROM solicitud_citas_pagos WHERE codigo_asignacion = cita.codigo_asignacion AND pago = 1 ) 
	) AS usuarioPago,
	( SELECT DISTINCT procedimientos.descripcion FROM historial_asignacion_cita INNER JOIN procedimientos ON procedimientos.codigo_interno = historial_asignacion_cita.code_principal WHERE codigo_asignacion = cita.codigo_asignacion ) AS paquete,
 
IF
	(( SELECT count( id ) AS cantidad FROM solicitud_citas_pagos WHERE codigo_asignacion = cita.codigo_asignacion AND pago = 1 ) > 0, 'SI', 'NO' ) AS statusPago,
	
	(select max(fechaPago) from solicitud_citas_pagos where codigo_asignacion= cita.codigo_asignacion and pago= 1) as fechaModificar,
	
IF
	( cita.`status` = 1, 'Sin Atender', IF ( cita.`status` = 0, 'Atendido', 'Cancelado' ) ) AS statusCita,
	cita.idDistrito,
IF
	( cita.gratis = 1, 'SI', 'NO' ) AS gratis,
	cita.fechaCreacion,
	cita.idCita,
	cita.motivo AS motivoCita,
	cita.codigo_asignacion,
	cita.direccion AS direccionDomiciliaria,
IF
	( LEFT ( horaCita, 5 ) >= '07:30' AND LEFT ( horaCita, 5 ) <= '13:30', 'Mañana', 'Tarde' ) AS turno 
FROM
	cita
	INNER JOIN users ON users.idUser = cita.idUsuario
	INNER JOIN patients ON patients.idUsuario = users.idUser
	INNER JOIN doctors ON doctors.idDoctor = cita.idMedico
	INNER JOIN specialties ON specialties.idSpecialty = cita.idEspecialidad 
	WHERE
	cita.idUsuario !=5 and
	cita.fechaCita between '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."'";
		
		
		
		if ($this->input->post('medico')) $sql = $sql." and cita.idMedico = ".$this->input->post('medico')."";
		
		$sql = $sql." ORDER BY cita.fechaCita DESC";
	 
	 
	 
		$query = $this->db->query($sql);
		 
		$resultados = $query->result();
		
	 
		$this->data["data"] = $resultados;

		$this->load->view('excel/citas_principal', $this->data);
		 
	}
	
	public function exportarPagos(){

 

	
	$sql = "

		SELECT
		solicitud_citas_pagos.tipo_solicitud as tipo,
		'' as tipoSoli,
	patients.document,
	patients.firstname,
	patients.lastname,
	patients.email,
	procedimientos.descripcion AS procedimiento,
IF
	( solicitud_citas_pagos.pago = 1, 'SI', 'NO' ) AS pago,
	solicitud_citas_pagos.fechaPago,
	( SELECT CONCAT( patients.firstname, ' ', patients.lastname ) FROM patients WHERE patients.idUsuario = solicitud_citas_pagos.idUsuarioPago ) AS usuarioPago,
	solicitud_citas_pagos.codigo_asignacion,
	solicitud_citas_pagos.precio,
	@descuentos := ( SELECT monto FROM solicitud_citas_pagos_descuento WHERE codigo_asignacion = solicitud_citas_pagos.codigo_asignacion AND activo = 1 AND idProPagoCaja = solicitud_citas_pagos.id ) AS descuento,
	'0' as transporte,
	@montoSIgv := ROUND(( solicitud_citas_pagos.precio - IF ( @descuentos, @descuentos, 0 ) ), 2 ) AS montoConIgv,
	
ROUND(@montoSIgv/1.18,
		2 
	) AS montoSinIgv,
		(select GROUP_CONCAT(ci.idCita) from cita ci where ci.codigo_asignacion = solicitud_citas_pagos.codigo_asignacion and ci.status != 2) as citas,
			( SELECT DISTINCT pro.descripcion FROM historial_asignacion_cita INNER JOIN procedimientos pro ON pro.codigo_interno = historial_asignacion_cita.code_principal WHERE historial_asignacion_cita.codigo_asignacion = cita.codigo_asignacion ) AS paquete
			
FROM
	solicitud_citas_pagos
	left JOIN cita ON cita.codigo_asignacion = solicitud_citas_pagos.codigo_asignacion
	left JOIN patients ON patients.idUsuario = solicitud_citas_pagos.idUsuario
	INNER JOIN procedimientos ON procedimientos.codigo_interno = solicitud_citas_pagos.codigo_procedimiento 
WHERE
solicitud_citas_pagos.idUsuario !=5 and
	solicitud_citas_pagos.tipo_solicitud = 'PRO' 
	AND (solicitud_citas_pagos.pago = 1 
	OR solicitud_citas_pagos.codigo_asignacion IS NOT NULL)
	and 
	date(solicitud_citas_pagos.fechaPago) between '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."' GROUP BY solicitud_citas_pagos.id
	
	union
	
	SELECT
	solicitud_citas_pagos.tipo_solicitud AS tipo,
	'' as tipoSoli,
	patients.document,
	patients.firstname,
	patients.lastname,
	patients.email,
	examen.nombre AS procedimiento,
IF
	( solicitud_citas_pagos.pago = 1, 'SI', 'NO' ) AS pago,
	solicitud_citas_pagos.fechaPago,
	( SELECT CONCAT( patients.firstname, ' ', patients.lastname ) FROM patients WHERE patients.idUsuario = solicitud_citas_pagos.idUsuarioPago ) AS usuarioPago,
	solicitud_citas_pagos.codigo_asignacion,
	solicitud_citas_pagos.precio,
	@descuentos := ROUND( descuento /( SELECT count( codigo_lab ) FROM solicitud_citas_pagos scp WHERE scp.codigo_lab = solicitud_citas_pagos.codigo_lab ), 2 ) AS descuento,
	'0' as transporte,
	@montoSIgv := ROUND(( solicitud_citas_pagos.precio - IF ( @descuentos, @descuentos, 0 ) ), 2 ) AS montoConIgv ,
	ROUND( @montoSIgv / 1.18, 2 ) AS montoSinIgv,
	'' as citas,
	'' AS paquete
FROM
	solicitud_citas_pagos
	INNER JOIN patients ON patients.idUsuario = solicitud_citas_pagos.idUsuario
	INNER JOIN examen ON examen.id = solicitud_citas_pagos.codigo_procedimiento 
WHERE
	solicitud_citas_pagos.idUsuario !=5 and
	solicitud_citas_pagos.tipo_solicitud = 'LAB'
	and
	date(solicitud_citas_pagos.fechaPago) between '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."' GROUP BY solicitud_citas_pagos.id
	
	union
	
	
	SELECT
	'ANTI' as tipo,
	@tipoSoli := ( SELECT tipo FROM gestion_paciente_prueba WHERE id = solicitud_citas_pagos.idGestionPrueba ) AS tipoSoli,
	
	( SELECT GROUP_CONCAT(dni) FROM gestion_paciente_cliente2 WHERE idGestionPaciente = solicitud_citas_pagos.codigo_procedimiento and tipo_prueba=  @tipoSoli) AS document,
	
	
	( SELECT GROUP_CONCAT(nombre) FROM gestion_paciente_cliente2 WHERE idGestionPaciente = solicitud_citas_pagos.codigo_procedimiento and tipo_prueba=  @tipoSoli) AS firstname,
	(SELECT GROUP_CONCAT(apellido) FROM gestion_paciente_cliente2 WHERE idGestionPaciente = solicitud_citas_pagos.codigo_procedimiento and tipo_prueba=  @tipoSoli ) AS lastname,
	'' AS email,
	( SELECT IF ( tipo = 2, 'PCR', 'ANTIGENO' ) FROM gestion_paciente_prueba WHERE id = solicitud_citas_pagos.idGestionPrueba ) AS procedimiento,
IF
	( solicitud_citas_pagos.pago = 1, 'SI', 'NO' ) AS pago,
	solicitud_citas_pagos.fechaPago,
	( SELECT CONCAT( patients.firstname, ' ', patients.lastname ) FROM patients WHERE patients.idUsuario = solicitud_citas_pagos.idUsuarioPago ) AS usuarioPago,
	solicitud_citas_pagos.codigo_asignacion,
	solicitud_citas_pagos.precio,

	
	@descuentos := ROUND(( SELECT descuento /( SELECT count(*) FROM gestion_paciente_prueba WHERE idGestion = solicitud_citas_pagos.codigo_procedimiento ) FROM gestion_paciente_prueba WHERE id = solicitud_citas_pagos.idGestionPrueba ), 2 ) AS descuento,
	
	@transporte := ROUND(( solicitud_citas_pagos.precio_transporte /( SELECT count(*) FROM gestion_paciente_prueba WHERE idGestion = solicitud_citas_pagos.codigo_procedimiento )), 2 ) AS transporte,
	
	@montoSIgv := ROUND(( solicitud_citas_pagos.precio + @transporte - @descuentos ), 2 ) AS montoConIgv,
	
	ROUND( @montoSIgv / 1.18, 2 ) AS montoSinIgv,
	'' as citas,
		'' AS paquete
	
FROM
	solicitud_citas_pagos 
WHERE
solicitud_citas_pagos.idUsuario !=1 and
	solicitud_citas_pagos.tipo_solicitud = 'ANT'
	and 
	date(solicitud_citas_pagos.fechaPago) between '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."'
	";
		
		//if ($this->input->post('medico')) $sql = $sql." and cita.idMedico = ".$this->input->post('medico')."";
		
		//$sql = $sql." ORDER BY cita.fechaCita DESC";
	 
		$query = $this->db->query($sql);
		 
		$resultados = $query->result();
		
	 
		$this->data["data"] = $resultados;

		$this->load->view('excel/citas_pagos', $this->data);
		 
	}
	
		public function exportar_solicitud_orden(){
	
		$sql = "
				SELECT
				'LAB' AS tipo,
				date(solicitud_citas_pagos.fechaCreacion) as fecha,
				@nroCita :=(
				SELECT
					idCita 
				FROM
					examenauxiliar_cita 
				WHERE
					id = solicitud_citas_pagos.idExamenAuxiliar 
				) AS nroCita,
			IF
				( solicitud_citas_pagos.realizado, 'SI', 'NO' ) AS registrado,
				(
				IF
					((
						SELECT
							count(*) 
						FROM
							solicitarexamen 
						WHERE
							codigo_interno = solicitud_citas_pagos.codigo_lab 
							AND idExamen = solicitud_citas_pagos.codigo_procedimiento 
							AND estado = 2 
							) = 1,
						'SI',
						'NO' 
					) 
				) AS realizado,
				(
				SELECT
					concat( patients.lastname ) 
				FROM
					cita
					INNER JOIN patients ON patients.idUsuario = cita.idUsuario 
				WHERE
					cita.idCita = @nroCita 
				) AS apellidoPaciente,
				(
				SELECT
					concat( patients.firstname ) 
				FROM
					cita
					INNER JOIN patients ON patients.idUsuario = cita.idUsuario 
				WHERE
					cita.idCita = @nroCita 
				) AS nombrePaciente,
				( SELECT patients.document FROM cita INNER JOIN patients ON patients.idUsuario = cita.idUsuario WHERE cita.idCita = @nroCita ) AS nroDocument,
				(
				SELECT
					concat( doctors.lastname, ' ', doctors.firstname ) 
				FROM
					cita
					INNER JOIN doctors ON doctors.idDoctor = cita.idMedico 
				WHERE
					cita.idCita = @nroCita 
				) AS profesionalOrigen,
				(
				SELECT
					specialties.`name` 
				FROM
					cita
					INNER JOIN doctors ON doctors.idDoctor = cita.idMedico
					INNER JOIN specialties ON specialties.idSpecialty = doctors.idSpecialty 
				WHERE
					cita.idCita = @nroCita 
				) AS especialidadOrigen,
			IF
				( solicitud_citas_pagos.pago = 1, 'SI', 'NO' ) AS pago,
				examen.nombre AS procedimiento,
				RIGHT ( solicitud_citas_pagos.norden, 4 ) AS nroOrden 
			FROM
				solicitud_citas_pagos
				INNER JOIN examen ON examen.id = solicitud_citas_pagos.codigo_procedimiento 
			WHERE
				solicitud_citas_pagos.norden IS NOT NULL 
				AND solicitud_citas_pagos.tipo_solicitud = 'LAB' 
				and date(solicitud_citas_pagos.fechaCreacion) between '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."'
				UNION
			SELECT
				'PRO' AS tipo,
				date(solicitud_citas_pagos.fechaCreacion) as fecha,
				@nroCita :=(
				SELECT
					idCita 
				FROM
					examenauxiliar_cita 
				WHERE
					id = solicitud_citas_pagos.idExamenAuxiliar 
				) AS nroCita,
			IF
				( solicitud_citas_pagos.idCita, 'SI', 'NO' ) AS registrado,
				(
				SELECT
				IF
					( count( cita.idCita ), 'SI', 'NO' ) 
				FROM
					cita
					INNER JOIN patients ON patients.idUsuario = cita.idUsuario 
				WHERE
					cita.idCita = solicitud_citas_pagos.idCita 
					AND cita.`status` = 0 
				) AS realizado,
				(
				SELECT
					concat( patients.lastname ) 
				FROM
					cita
					INNER JOIN patients ON patients.idUsuario = cita.idUsuario 
				WHERE
					cita.idCita = @nroCita 
				) AS apellidoPaciente,
				(
				SELECT
					concat( patients.firstname ) 
				FROM
					cita
					INNER JOIN patients ON patients.idUsuario = cita.idUsuario 
				WHERE
					cita.idCita = @nroCita 
				) AS nombrePaciente,
				( SELECT patients.document FROM cita INNER JOIN patients ON patients.idUsuario = cita.idUsuario WHERE cita.idCita = @nroCita ) AS nroDocument,
				(
				SELECT
					concat( doctors.lastname, ' ', doctors.firstname ) 
				FROM
					cita
					INNER JOIN doctors ON doctors.idDoctor = cita.idMedico 
				WHERE
					cita.idCita = @nroCita 
				) AS profesionalOrigen,
				(
				SELECT
					specialties.`name` 
				FROM
					cita
					INNER JOIN doctors ON doctors.idDoctor = cita.idMedico
					INNER JOIN specialties ON specialties.idSpecialty = doctors.idSpecialty 
				WHERE
					cita.idCita = @nroCita 
				) AS especialidadOrigen,
			IF
				( solicitud_citas_pagos.pago = 1, 'SI', 'NO' ) AS pago,
				procedimientos.descripcion AS procedimiento,
				RIGHT ( solicitud_citas_pagos.norden, 4 ) AS nroOrden 
			FROM
				solicitud_citas_pagos
				INNER JOIN procedimientos ON procedimientos.codigo_interno = solicitud_citas_pagos.codigo_procedimiento 
			WHERE
				norden IS NOT NULL
				AND solicitud_citas_pagos.tipo_solicitud = 'PRO' and
				date(solicitud_citas_pagos.fechaCreacion) between '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."'";
	
		$query = $this->db->query($sql);
		 
		$resultados = $query->result();
		
	 
		$this->data["data"] = $resultados;

		$this->load->view('excel/norden_solicitados', $this->data);
		 
	}
	
	public function exportarCitas_001(){

 

		$sql = "SELECT  
			(select catalogo_motivo_cancelacion.descripcion from catalogo_motivo_cancelacion where catalogo_motivo_cancelacion.id = cita.idMotivoCancelar) as motivoCancelar,
			
			(select  procedimientos.descripcion from procedimientos where procedimientos.codigo_interno = cita.codigo_procedimiento) as procedimiento,

			  
			 (select if(min(cita2.idCita) = cita.idCita, 'Nuevo', if( cita.status = 0 and cita.idCita > min(cita2.idCita ), 'Antiguo', if( cita.status = 2 and cita.idCita > min(cita2.idCita ), 'Antiguo', if( cita.status = 1 and cita.idCita > min(cita2.idCita ), 'Antiguo' , 'No asistio')))) from cita cita2 where cita2.idUsuario=cita.idUsuario and cita2.status=0 ) as tipoPaciente,
			pago.fechaModificar, ( SELECT CONCAT( patients.firstname, ' ', patients.lastname ) FROM patients WHERE patients.idUsuario = pago.idUsuarioModificar ) AS usuarioPago, ( SELECT tipo FROM motivo_cita WHERE motivo_cita.id = cita.idMotivoCita ) AS motivoTipoCita,
			( SELECT CONCAT( patients.firstname, ' ', patients.lastname ) FROM patients WHERE patients.idUsuario = cita.idUsuarioCreacion ) AS usuarioCreacion,
			users.username AS nroDocumento,
			patients.firstname,
			patients.lastname,
			patients.phone,
			(select  name from ubigeo_districts where id=patients.idDistrict) as distrito,
			patients.address,
			patients.email,
			IF
			(
				cita.tipoCita = 'CV' || cita.virtual = 1,
				'Virtual',
			IF
				( cita.tipoCita = 'CP', 'Presencial', IF ( cita.tipoCita = 'CD', 'Domiciliaria', 'Procedimiento' ) ) 
			) AS tipoCita,
			cita.fechaCita,
			cita.horaCita,
			CONCAT( doctors.title, ' ', doctors.firstname, ' ', doctors.lastname ) AS nombreMedico,
			specialties.`name` AS especialidad,
			pago.monto,
			pago.card_brand,
			pago.card_type,
			IF
			( pago.`status` = 0, 'NO', 'SI' ) AS statusPago,
			IF
			( cita.`envioBolFac` = 0, 'NO', 'SI' ) AS statusBolFac,
			cita.motivo AS motivoCita,
			IF
			( cita.`status` = 1, 'Sin Atender', IF ( cita.`status` = 0, 'Atendido', 'Cancelado' ) ) AS statusCita,
			cita.tipoComprobante,
			cita.nombreCompleto AS nombreBoleta,
			cita.razonSocial,
			cita.ruc,
			cita.direccionRuc,
			cita.sucursal,
			cita.idDistrito,
			cita.fechaCreacion,
			cita.direccion AS direccionDomiciliaria,
			catalogo_canalventas.nombre as canalVenta,
			if(left(horaCita, 5) >= '07:30' and left(horaCita, 5) <= '13:30', 'Mañana', 'Tarde') as turno
		FROM
			cita
			INNER JOIN users ON users.idUser = cita.idUsuario
			INNER JOIN patients ON patients.idUsuario = users.idUser
			INNER JOIN catalogo_canalventas ON catalogo_canalventas.id = patients.idCanalVenta
			INNER JOIN doctors ON doctors.idDoctor = cita.idMedico
			INNER JOIN specialties ON specialties.idSpecialty = cita.idEspecialidad
			LEFT JOIN pago ON pago.idPago = cita.idPago
		WHERE
			/*cita.idUsuario !=5 and*/
			cita.fechaCita between '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."'";
		
		if ($this->input->post('medico')) $sql = $sql." and cita.idMedico = ".$this->input->post('medico')."";
		
		$sql = $sql." ORDER BY cita.fechaCita DESC";
	 
		$query = $this->db->query($sql);
		 
		$resultados = $query->result();
		
	 
		$this->data["data"] = $resultados;

		$this->load->view('excel/citas', $this->data);
		 
	}
	
	public function exportar2excel(){

/* 		$this->load->library('excel');
		
		$this->excel->setActiveSheetIndex(0);         
		$this->excel->getActiveSheet()->setTitle('test worksheet');         
		$this->excel->getActiveSheet()->setCellValue('A1', 'Un poco de texto');         
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);         
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);         
		$this->excel->getActiveSheet()->mergeCells('A1:D1');           
	
		header('Content-Type: application/vnd.ms-excel');         
		header('Content-Disposition: attachment;filename="nombredelfichero.xls"');
		header('Cache-Control: max-age=0'); //no cache         
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');         
		
		// Forzamos a la descarga         
		$objWriter->save('php://output'); 
 
		*/

		$sql = "SELECT  
			(select catalogo_motivo_cancelacion.descripcion from catalogo_motivo_cancelacion where catalogo_motivo_cancelacion.id = cita.idMotivoCancelar) as motivoCancelar,
			
			(select  procedimientos.descripcion from procedimientos where procedimientos.codigo_interno = cita.codigo_procedimiento) as procedimiento,

			  
			 (select if(min(cita2.idCita) = cita.idCita, 'Nuevo', if( cita.status = 0 and cita.idCita > min(cita2.idCita ), 'Antiguo', '')) from cita cita2 where cita2.idUsuario=cita.idUsuario and cita2.status=0 ) as    tipoPaciente,
			pago.fechaModificar, ( SELECT CONCAT( patients.firstname, ' ', patients.lastname ) FROM patients WHERE patients.idUsuario = pago.idUsuarioModificar ) AS usuarioPago, ( SELECT tipo FROM motivo_cita WHERE motivo_cita.id = cita.idMotivoCita ) AS motivoTipoCita,
			( SELECT CONCAT( patients.firstname, ' ', patients.lastname ) FROM patients WHERE patients.idUsuario = cita.idUsuarioCreacion ) AS usuarioCreacion,
			users.username AS nroDocumento,
			patients.firstname,
			patients.lastname,
			patients.phone,
			(select  name from ubigeo_districts where id=patients.idDistrict) as distrito,
			patients.address,
			patients.email,
			IF
			(
				cita.tipoCita = 'CV' || cita.virtual = 1,
				'Virtual',
			IF
				( cita.tipoCita = 'CP', 'Presencial', IF ( cita.tipoCita = 'CD', 'Domiciliaria', 'Procedimiento' ) ) 
			) AS tipoCita,
			cita.fechaCita,
			cita.horaCita,
			CONCAT( doctors.title, ' ', doctors.firstname, ' ', doctors.lastname ) AS nombreMedico,
			specialties.`name` AS especialidad,
			pago.monto,
			pago.card_brand,
			pago.card_type,
			IF
			( pago.`status` = 0, 'NO', 'SI' ) AS statusPago,
			IF
			( cita.`envioBolFac` = 0, 'NO', 'SI' ) AS statusBolFac,
			cita.motivo AS motivoCita,
			IF
			( cita.`status` = 1, 'Sin Atender', IF ( cita.`status` = 0, 'Atendido', 'Cancelado' ) ) AS statusCita,
			cita.tipoComprobante,
			cita.nombreCompleto AS nombreBoleta,
			cita.razonSocial,
			cita.ruc,
			cita.direccionRuc,
			cita.sucursal,
			cita.idDistrito,
			cita.fechaCreacion,
			cita.direccion AS direccionDomiciliaria,
			catalogo_canalventas.nombre as canalVenta,
			if(left(horaCita, 5) >= '07:30' and left(horaCita, 5) <= '13:30', 'Mañana', 'Tarde') as turno
		FROM
			cita
			INNER JOIN users ON users.idUser = cita.idUsuario
			INNER JOIN patients ON patients.idUsuario = users.idUser
			INNER JOIN catalogo_canalventas ON catalogo_canalventas.id = patients.idCanalVenta
			INNER JOIN doctors ON doctors.idDoctor = cita.idMedico
			INNER JOIN specialties ON specialties.idSpecialty = cita.idEspecialidad
			INNER JOIN pago ON pago.idPago = cita.idPago
		WHERE
			cita.idUsuario !=5 and
			cita.fechaCita between '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."'";
		
		if ($this->input->post('medico')) $sql = $sql." and cita.idMedico = ".$this->input->post('medico')."";
		
		$sql = $sql." ORDER BY cita.fechaCita DESC";
	
		$query = $this->db->query($sql);
		
		$fileName = 'data-citas-'.date("Y-m-d-His").'.xlsx';
		// load excel library
			$this->load->library('excel');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			// set Header
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', "NroDocumento");
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', "NombrePaciente");
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', "ApellidoPaciente");
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', "EmailPaciente");
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', "TeléfonoPaciente");
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', "Distrito");
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', "DirecciónPaciente");
			
			$objPHPExcel->getActiveSheet()->SetCellValue('H1', "TipoCita");
			$objPHPExcel->getActiveSheet()->SetCellValue('I1', "FechaCita");       
			$objPHPExcel->getActiveSheet()->SetCellValue('J1', "HoraCita");       
			$objPHPExcel->getActiveSheet()->SetCellValue('K1', "Profesional");       
			$objPHPExcel->getActiveSheet()->SetCellValue('L1', "Especialidad");       
			$objPHPExcel->getActiveSheet()->SetCellValue('M1', "MontoPago");       
			$objPHPExcel->getActiveSheet()->SetCellValue('N1', "modeloTarjeta");       
			$objPHPExcel->getActiveSheet()->SetCellValue('O1', "tipoTarjeta");
			$objPHPExcel->getActiveSheet()->SetCellValue('P1', "statusPago");
			$objPHPExcel->getActiveSheet()->SetCellValue('Q1', "OBS.Cita");       
			$objPHPExcel->getActiveSheet()->SetCellValue('R1', "statusCita"); 			
			$objPHPExcel->getActiveSheet()->SetCellValue('S1', "TipoComprobante");       
			$objPHPExcel->getActiveSheet()->SetCellValue('T1', "NombreBoleta");       
			$objPHPExcel->getActiveSheet()->SetCellValue('U1', "RazonSocial");       
			$objPHPExcel->getActiveSheet()->SetCellValue('V1', "Ruc");       
			$objPHPExcel->getActiveSheet()->SetCellValue('W1', "DireccionRuc");       
			$objPHPExcel->getActiveSheet()->SetCellValue('X1', "EnvíoBolFac");       
			$objPHPExcel->getActiveSheet()->SetCellValue('Y1', "UsuarioCreciónCita");       
			$objPHPExcel->getActiveSheet()->SetCellValue('Z1', "FechaCreacionCita"); 
			$objPHPExcel->getActiveSheet()->SetCellValue('AA1', "FechaPago"); 
			$objPHPExcel->getActiveSheet()->SetCellValue('AB1', "UsuarioPago"); 
			$objPHPExcel->getActiveSheet()->SetCellValue('AC1', "Tipo Motivo Cita");
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AD1', "Procedimiento");
			$objPHPExcel->getActiveSheet()->SetCellValue('AE1', "MotivoCancelar");
			$objPHPExcel->getActiveSheet()->SetCellValue('AF1', "CanalVenta");
			$objPHPExcel->getActiveSheet()->SetCellValue('AG1', "Turno");
			$objPHPExcel->getActiveSheet()->SetCellValue('AH1', "tipoPaciente");
		
			// set Row
			$rowCount = 2;
			//foreach ($empInfo as $element) {
			foreach ($query->result() as $row){
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->nroDocumento);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row->firstname);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row->lastname);
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->email);
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row->phone);
				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row->distrito);
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row->address);
				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row->tipoCita);
				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row->fechaCita);
				$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row->horaCita);
				$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $row->nombreMedico);
				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $row->especialidad);
				$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $row->monto);
				$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $row->card_brand);
				$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $row->card_type);
				$objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $row->statusPago);
				$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, $row->motivoCita);
				$objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, $row->statusCita);
				$objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, $row->tipoComprobante);
				$objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, $row->nombreBoleta);
				$objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, $row->razonSocial);
				$objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, $row->ruc);
				$objPHPExcel->getActiveSheet()->SetCellValue('W' . $rowCount, $row->direccionRuc);
				$objPHPExcel->getActiveSheet()->SetCellValue('X' . $rowCount, $row->statusBolFac);
				$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $rowCount, $row->usuarioCreacion);
				$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowCount, $row->fechaCreacion);
				$objPHPExcel->getActiveSheet()->SetCellValue('AA' . $rowCount, $row->fechaModificar);
				$objPHPExcel->getActiveSheet()->SetCellValue('AB' . $rowCount, $row->usuarioPago);
				$objPHPExcel->getActiveSheet()->SetCellValue('AC' . $rowCount, $row->motivoTipoCita);
				
				$objPHPExcel->getActiveSheet()->SetCellValue('AD' . $rowCount, $row->procedimiento);
				$objPHPExcel->getActiveSheet()->SetCellValue('AE' . $rowCount, $row->motivoCancelar);
				$objPHPExcel->getActiveSheet()->SetCellValue('AF' . $rowCount, $row->canalVenta);
				$objPHPExcel->getActiveSheet()->SetCellValue('AG' . $rowCount, $row->turno);
				$objPHPExcel->getActiveSheet()->SetCellValue('AH' . $rowCount, $row->tipoPaciente);
				
				$rowCount++;
			}

			$objPHPExcel->getActiveSheet()->getStyle("A1:AH1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		// download file
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment;filename=$fileName");
  			$objWriter->save('php://output');
		 
	}
	
	
	public function exportar_examenesLab(){
		
		if($this->input->post('consolidado') == 1)
		{
		
			$sql = "
					SELECT
						soli.idExamen,
						concat( patients.firstname, ' ', patients.lastname ) AS paciente ,
						examen.tipo,
						examen.nombre,
						count( soli.idExamen ) AS cantidad,
						sum( soli.precio ) AS monto,
						soli.fechaExamen,
						soli.fechaModificar,
						ROUND( soli.descuento / ( SELECT count( id ) FROM solicitarexamen WHERE codigo_interno = soli.codigo_interno ), 2 ) AS descuento
					FROM
						solicitarexamen soli
						INNER JOIN examen ON examen.id = soli.idExamen 
						INNER JOIN patients ON patients.idUsuario = soli.idUsuario 
					WHERE
						soli.idPerfil = 0 
						and soli.status_pago = 1 and
						soli.fechaExamen between '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."' 
						GROUP BY 1, 2
						ORDER BY count( soli.idExamen )
						";
			
			$query = $this->db->query($sql);
			$resultados = $query->result();
			
		
			$this->data["data"] = $resultados;
	
			$this->load->view('excel/laboratorio_consolidado', $this->data);
  
		}
		else {
				
			$sql = "
					SELECT
						soli.codigo_interno,
						soli.fechaExamen,
						IF
						( soli.status_pago = 0, 'NO PAGO', 'SI PAGO' ) AS statusPago,
						sum( soli.precio ) AS precio,
						sum( DISTINCT soli.descuento ) AS descuento,
						sum( DISTINCT soli.costo_transporte ) AS transporte,
						(
						SELECT
							GROUP_CONCAT( examen.nombre ) 
						FROM
							solicitarexamen
							INNER JOIN examen ON examen.id = solicitarexamen.idExamen 
						WHERE
							solicitarexamen.codigo_interno = soli.codigo_interno 
							AND solicitarexamen.idPerfil = 0 
						) AS examenes,
						(
						SELECT
							GROUP_CONCAT( DISTINCT patients.firstname, ', ', patients.lastname ) 
						FROM
							solicitarexamen
							INNER JOIN patients ON patients.idUsuario = solicitarexamen.idUsuario 
						WHERE
							solicitarexamen.codigo_interno = soli.codigo_interno 
							AND solicitarexamen.idPerfil = 0 
						) AS paciente 
					FROM
						solicitarexamen soli 
					WHERE
						soli.idPerfil = 0 and
						soli.fechaExamen between '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."' 
						GROUP BY 1,2,3
						ORDER BY soli.fechaExamen ASC";
			

			$query = $this->db->query($sql);
			
			$resultados = $query->result();
			
		
			$this->data["data"] = $resultados;
	
			$this->load->view('excel/laboratorio', $this->data);
			 
		 
	}



	}
	
	public function exportar2excelLab(){
		
		if($this->input->post('consolidado') == 1)
		{
		
			$sql = "
					SELECT
						soli.idExamen,
						concat( patients.firstname, ' ', patients.lastname ) AS paciente ,
						examen.tipo,
						examen.nombre,
						count( soli.idExamen ) AS cantidad,
						sum( soli.precio ) AS monto
					FROM
						solicitarexamen soli
						INNER JOIN examen ON examen.id = soli.idExamen 
						INNER JOIN patients ON patients.idUsuario = soli.idUsuario 
					WHERE
						soli.idPerfil = 0 
						and soli.status_pago = 1 and
						soli.fechaExamen between '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."' 
						GROUP BY 1, 2
						ORDER BY count( soli.idExamen )
						";
			
			$query = $this->db->query($sql);
 
			$fileName = 'data-laboratorios-consolidado-'.date("Y-m-d-His").'.xlsx';
			// load excel library
				$this->load->library('excel');
				$objPHPExcel = new PHPExcel();
				$objPHPExcel->setActiveSheetIndex(0);
				// set Header
				$objPHPExcel->getActiveSheet()->SetCellValue('A1', "Tipo");
				$objPHPExcel->getActiveSheet()->SetCellValue('B1', "Concepto");
				$objPHPExcel->getActiveSheet()->SetCellValue('C1', "cantidad");
				$objPHPExcel->getActiveSheet()->SetCellValue('D1', "MontoTotal");
				$objPHPExcel->getActiveSheet()->SetCellValue('E1', "paciente");
					  
				// set Row
				$rowCount = 2;
				//foreach ($empInfo as $element) {
				foreach ($query->result() as $row){
					$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->tipo);
					$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row->nombre);
					$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row->cantidad);
					$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->monto);
					$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row->paciente);

					
					$rowCount++;
				}

				$objPHPExcel->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		}
		else {
				
			$sql = "
					SELECT
						soli.codigo_interno,
						soli.fechaExamen,
						IF
						( soli.status_pago = 0, 'NO PAGO', 'SI PAGO' ) AS statusPago,
						sum( soli.precio ) AS precio,
						sum( DISTINCT soli.descuento ) AS descuento,
						sum( DISTINCT soli.costo_transporte ) AS transporte,
						(
						SELECT
							GROUP_CONCAT( examen.nombre ) 
						FROM
							solicitarexamen
							INNER JOIN examen ON examen.id = solicitarexamen.idExamen 
						WHERE
							solicitarexamen.codigo_interno = soli.codigo_interno 
							AND solicitarexamen.idPerfil = 0 
						) AS examenes,
						(
						SELECT
							GROUP_CONCAT( DISTINCT patients.firstname, ', ', patients.lastname ) 
						FROM
							solicitarexamen
							INNER JOIN patients ON patients.idUsuario = solicitarexamen.idUsuario 
						WHERE
							solicitarexamen.codigo_interno = soli.codigo_interno 
							AND solicitarexamen.idPerfil = 0 
						) AS paciente 
					FROM
						solicitarexamen soli 
					WHERE
						soli.idPerfil = 0 and
						soli.fechaExamen between '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."' 
						GROUP BY 1,2,3
						ORDER BY soli.fechaExamen ASC";
			

			$query = $this->db->query($sql);

			
			$fileName = 'data-laboratorios-'.date("Y-m-d-His").'.xlsx';
			// load excel library
				$this->load->library('excel');
				$objPHPExcel = new PHPExcel();
				$objPHPExcel->setActiveSheetIndex(0);
				// set Header
				$objPHPExcel->getActiveSheet()->SetCellValue('A1', "CodigoInterno");
				$objPHPExcel->getActiveSheet()->SetCellValue('B1', "FechaExamen");
				$objPHPExcel->getActiveSheet()->SetCellValue('C1', "Exámenes");
				$objPHPExcel->getActiveSheet()->SetCellValue('D1', "NombrePaciente");
				$objPHPExcel->getActiveSheet()->SetCellValue('E1', "Precio");
				$objPHPExcel->getActiveSheet()->SetCellValue('F1', "Transporte");
				$objPHPExcel->getActiveSheet()->SetCellValue('G1', "Descuento");
				$objPHPExcel->getActiveSheet()->SetCellValue('H1', "StatusPago");
					  
				// set Row
				$rowCount = 2;
				//foreach ($empInfo as $element) {
				foreach ($query->result() as $row){
					$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->codigo_interno);
					$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row->fechaExamen);
					$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row->examenes);
					$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->paciente);
					$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row->precio);
					$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row->transporte);
					$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row->descuento);
					$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row->statusPago);

					
					$rowCount++;
				}

				$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
			}
			
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		// download file
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment;filename=$fileName");
  			$objWriter->save('php://output');
		 
	}	

	public function exportar2_excelProced(){
		
		if($this->input->post('consolidadoproc') == 1)
		{
					$sql = "
						SELECT
							appc.codigo_procedimiento,
							procedimientos.descripcion,
							count( appc.codigo_procedimiento ) AS cantidad,
							sum( appc.precio ) AS monto 
						FROM
							agregar_pago_procedimiento_caja appc
							INNER JOIN procedimientos ON procedimientos.codigo_interno = appc.codigo_procedimiento 
						WHERE
							appc.activo = 1 
							and date( appc.fechaCreacion ) BETWEEN '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."' 
							GROUP BY
							1, 2
							ORDER BY count( appc.codigo_procedimiento )";

					$query = $this->db->query($sql);

				 		$resultados = $query->result();
			
		
			$this->data["data"] = $resultados;
	
			$this->load->view('excel/procedimiento_consolidado', $this->data);
						
				} else {

					$sql = "
						SELECT
							appc.codigo_interno,
							(
							SELECT
								GROUP_CONCAT( DISTINCT patients.firstname, ', ', patients.lastname ) 
							FROM
								agregar_pago_procedimiento_caja
								INNER JOIN patients ON patients.idUsuario = agregar_pago_procedimiento_caja.idUsuario 
							WHERE
								agregar_pago_procedimiento_caja.idUsuario = appc.idUsuario 
							) AS paciente,
							(
							SELECT
								 DISTINCT patients.document
							FROM
								agregar_pago_procedimiento_caja
								INNER JOIN patients ON patients.idUsuario = agregar_pago_procedimiento_caja.idUsuario 
							WHERE
								agregar_pago_procedimiento_caja.idUsuario = appc.idUsuario 
							) AS document,							
							(
							SELECT
								GROUP_CONCAT( procedimientos.descripcion ) 
							FROM
								procedimientos
								INNER JOIN agregar_pago_procedimiento_caja ON agregar_pago_procedimiento_caja.codigo_procedimiento = procedimientos.codigo_interno 
							WHERE
								agregar_pago_procedimiento_caja.codigo_interno = appc.codigo_interno 
							) AS procedimiento,
							sum( appc.precio ) AS precio,
							( SELECT monto FROM agregar_pago_procedimiento_descuento_caja WHERE codigo_interno = appc.codigo_interno AND activo = 1 ) AS descuento,
							date( appc.fechaCreacion ) AS fechaCreacion 
						FROM
							agregar_pago_procedimiento_caja appc 
						WHERE
							appc.activo = 1 and
							appc.idUsuario != 5  
							and date( appc.fechaCreacion ) BETWEEN '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."' 
							GROUP BY
							appc.codigo_interno,
							appc.idUsuario,
							date(
							appc.fechaCreacion 
							)
							ORDER BY date( appc.fechaCreacion )";
				

					$query = $this->db->query($sql);
					 
								$resultados = $query->result();
			
		
			$this->data["data"] = $resultados;
	
			$this->load->view('excel/procedimiento', $this->data);
			 

			}	
					 
		 
	}
	
	public function exportar2excelProced(){
		
		if($this->input->post('consolidadoproc') == 1)
		{
					$sql = "
						SELECT
							appc.codigo_procedimiento,
							procedimientos.descripcion,
							count( appc.codigo_procedimiento ) AS cantidad,
							sum( appc.precio ) AS monto 
						FROM
							agregar_pago_procedimiento_caja appc
							INNER JOIN procedimientos ON procedimientos.codigo_interno = appc.codigo_procedimiento 
						WHERE
							appc.activo = 1 
							and date( appc.fechaCreacion ) BETWEEN '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."' 
							GROUP BY
							1, 2
							ORDER BY count( appc.codigo_procedimiento )";

					$query = $this->db->query($sql);

				
					$fileName = 'data-procedimientos-consolidado-'.date("Y-m-d-His").'.xlsx';
					// load excel library
					$this->load->library('excel');
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);
					// set Header
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', "CodigoInterno");
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', "Concepto");
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', "Cantidad");
					$objPHPExcel->getActiveSheet()->SetCellValue('D1', "MontoTotal");
						  
					// set Row
					$rowCount = 2;
					//foreach ($empInfo as $element) {
					foreach ($query->result() as $row){
						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->codigo_procedimiento);
						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row->descripcion);
						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row->cantidad);
						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->monto);
						
						$rowCount++;
					}

					$objPHPExcel->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
						
				} else {

					$sql = "
						SELECT
							appc.codigo_interno,
							(
							SELECT
								GROUP_CONCAT( DISTINCT patients.firstname, ', ', patients.lastname ) 
							FROM
								agregar_pago_procedimiento_caja
								INNER JOIN patients ON patients.idUsuario = agregar_pago_procedimiento_caja.idUsuario 
							WHERE
								agregar_pago_procedimiento_caja.idUsuario = appc.idUsuario 
							) AS paciente,
							(
							SELECT
								 DISTINCT patients.document
							FROM
								agregar_pago_procedimiento_caja
								INNER JOIN patients ON patients.idUsuario = agregar_pago_procedimiento_caja.idUsuario 
							WHERE
								agregar_pago_procedimiento_caja.idUsuario = appc.idUsuario 
							) AS document,							
							(
							SELECT
								GROUP_CONCAT( procedimientos.descripcion ) 
							FROM
								procedimientos
								INNER JOIN agregar_pago_procedimiento_caja ON agregar_pago_procedimiento_caja.codigo_procedimiento = procedimientos.codigo_interno 
							WHERE
								agregar_pago_procedimiento_caja.codigo_interno = appc.codigo_interno 
							) AS procedimiento,
							sum( appc.precio ) AS precio,
							( SELECT monto FROM agregar_pago_procedimiento_descuento_caja WHERE codigo_interno = appc.codigo_interno AND activo = 1 ) AS descuento,
							date( appc.fechaCreacion ) AS fechaCreacion 
						FROM
							agregar_pago_procedimiento_caja appc 
						WHERE
							appc.activo = 1 and
							appc.idUsuario != 5  
							and date( appc.fechaCreacion ) BETWEEN '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."' 
							GROUP BY
							appc.codigo_interno,
							appc.idUsuario,
							date(
							appc.fechaCreacion 
							)
							ORDER BY date( appc.fechaCreacion )";
				

					$query = $this->db->query($sql);

					
					$fileName = 'data-procedimientos-'.date("Y-m-d-His").'.xlsx';
					// load excel library
						$this->load->library('excel');
						$objPHPExcel = new PHPExcel();
						$objPHPExcel->setActiveSheetIndex(0);
						// set Header
						$objPHPExcel->getActiveSheet()->SetCellValue('A1', "CodigoInterno");
						$objPHPExcel->getActiveSheet()->SetCellValue('B1', "FechaCreacion");
						$objPHPExcel->getActiveSheet()->SetCellValue('C1', "Procedimientos");
						$objPHPExcel->getActiveSheet()->SetCellValue('D1', "NombrePaciente");
						$objPHPExcel->getActiveSheet()->SetCellValue('E1', "Precio");
						$objPHPExcel->getActiveSheet()->SetCellValue('F1', "Descuento");
						$objPHPExcel->getActiveSheet()->SetCellValue('G1', "Documento");
							  
						// set Row
						$rowCount = 2;
						//foreach ($empInfo as $element) {
						foreach ($query->result() as $row){
							$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->codigo_interno);
							$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row->fechaCreacion);
							$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row->procedimiento);
							$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->paciente);
							$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row->precio);
							$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row->descuento);
							$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row->document);
							
							$rowCount++;
						}

						$objPHPExcel->getActiveSheet()->getStyle("A1:G1")->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
				}

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		// download file
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment;filename=$fileName");
  			$objWriter->save('php://output');
		 
	}	

	
	
	function import()
 	{
		$this->load->library('excel');

		if(isset($_FILES["file"]["name"]))
		{
			$path = $_FILES["file"]["tmp_name"];
 
			$object = PHPExcel_IOFactory::load($path);
			foreach($object->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
		
				for($row=2; $row<=$highestRow; $row++)
				{
					$idDoctor = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$date = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$start_time = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$end_time = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$monto = $worksheet->getCellByColumnAndRow(4, $row)->getValue();

					if($idDoctor =="")	break;
				
					$data[] = array(
					'idDoctor'  => $idDoctor,
					'date'   => strval($date),
					'start_time'   => strval($start_time),
					'end_time'   => $end_time,
					'monto'   => $monto
					);
				}
			}
 
  			$this->db->insert_batch("availabilities", $data);
	
			if($this->db->affected_rows()) {            
				echo $this->db->affected_rows();
			}
	
    		echo "";
  		} 
 	}

	
	public function exportar_solicitud()
	{
		$sql = "
				SELECT
				'Procedimiento' AS tipo,
				c.idCita,
				concat( pac.firstname, ' ', pac.lastname ) AS paciente,
				if(pac.sex = 'M', 'Masculino', 'Femenino') as sexo,
				pac.document,
				TIMESTAMPDIFF(YEAR, pac.birthdate,CURDATE()) AS edad,
				`pro`.`descripcion` AS nombre,
				concat('PRO|',`em`.`codigoTipo`) as codigoTipo,
				`em`.`especificaciones`,
				date(`em`.`fechaCreacion`) as fecha,
				sp.name as especialidad,
				concat( doc.firstname, ' ', doc.lastname ) AS medico
				FROM
					`cita` `c`
					JOIN `patients` `pac` ON `pac`.`idUsuario` = `c`.`idUsuario`
					JOIN `examenauxiliar_cita` `em` ON `em`.`idCita` = `c`.`idCita`
					JOIN `procedimientos` `pro` ON `pro`.`codigo_interno` = `em`.`codigoTipo` 
					JOIN `doctors` `doc` ON `doc`.`idDoctor` = `c`.`idMedico`
					JOIN `specialties` `sp` ON `sp`.`idSpecialty` = `doc`.`idSpecialty`					
				WHERE
					`em`.`tipo` = 'PRO' 
					AND `c`.`status` = 0
					and date( em.fechaCreacion ) BETWEEN '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."'
					UNION
				SELECT
					'Laboratorio' AS tipo,
					c.idCita,
									concat( pac.firstname, ' ',pac.lastname ) AS paciente,
				if(pac.sex = 'M', 'Masculino', 'Femenino') as sexo,
				pac.document,
				TIMESTAMPDIFF(YEAR, pac.birthdate,CURDATE()) AS edad,
					`exa`.`nombre`,
					concat('LAB|',`em`.`codigoTipo`) as codigoTipo,
					`em`.`especificaciones`,
					date(`em`.`fechaCreacion`) as fecha,
					sp.name as especialidad,
					concat( doc.firstname, ' ', doc.lastname ) AS medico
				FROM
					`cita` `c`
					JOIN `patients` `pac` ON `pac`.`idUsuario` = `c`.`idUsuario`
					JOIN `examenauxiliar_cita` `em` ON `em`.`idCita` = `c`.`idCita`
					JOIN `examen` `exa` ON `exa`.`id` = `em`.`codigoTipo` 
					JOIN `doctors` `doc` ON `doc`.`idDoctor` = `c`.`idMedico` 
					JOIN `specialties` `sp` ON `sp`.`idSpecialty` = `doc`.`idSpecialty`
				WHERE
					`em`.`tipo` = 'LAB' 
					AND `c`.`status` = 0 
					and date( em.fechaCreacion ) BETWEEN '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."'
				order by 2, 3
				";
 
					$query = $this->db->query($sql);
 		$resultados = $query->result();
		
	 
		$this->data["data"] = $resultados;

		$this->load->view('excel/examenes_solicitados', $this->data);
		 
	}
	
	public function exams_requested()
	{
		$sql = "
				SELECT
				'Procedimiento' AS tipo,
				c.idCita,
				`pro`.`descripcion` AS nombre,
				concat('PRO|',`em`.`codigoTipo`) as codigoTipo,
				`em`.`especificaciones`,
				date(`em`.`fechaCreacion`) as fecha,
				sp.name as especialidad,
				concat( doc.firstname, ' ', doc.lastname ) AS medico
				FROM
					`cita` `c`
					JOIN `examenauxiliar_cita` `em` ON `em`.`idCita` = `c`.`idCita`
					JOIN `procedimientos` `pro` ON `pro`.`codigo_interno` = `em`.`codigoTipo` 
					JOIN `doctors` `doc` ON `doc`.`idDoctor` = `c`.`idMedico`
					JOIN `specialties` `sp` ON `sp`.`idSpecialty` = `doc`.`idSpecialty`					
				WHERE
					`em`.`tipo` = 'PRO' 
					AND `c`.`status` = 0
					and date( em.fechaCreacion ) BETWEEN '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."'
					UNION
				SELECT
					'Laboratorio' AS tipo,
					c.idCita,
					`exa`.`nombre`,
					concat('LAB|',`em`.`codigoTipo`) as codigoTipo,
					`em`.`especificaciones`,
					date(`em`.`fechaCreacion`) as fecha,
					sp.name as especialidad,
					concat( doc.firstname, ' ', doc.lastname ) AS medico
				FROM
					`cita` `c`
					JOIN `examenauxiliar_cita` `em` ON `em`.`idCita` = `c`.`idCita`
					JOIN `examen` `exa` ON `exa`.`id` = `em`.`codigoTipo` 
					JOIN `doctors` `doc` ON `doc`.`idDoctor` = `c`.`idMedico` 
					JOIN `specialties` `sp` ON `sp`.`idSpecialty` = `doc`.`idSpecialty`
				WHERE
					`em`.`tipo` = 'LAB' 
					AND `c`.`status` = 0 
					and date( em.fechaCreacion ) BETWEEN '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."'
				order by 2, 3
				";
 
					$query = $this->db->query($sql);

				
					$fileName = 'data-examens-solicitados-'.date("Y-m-d-His").'.xlsx';
					// load excel library
					$this->load->library('excel');
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);
					// set Header
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', "Tipo");
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', "Concepto");
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', "FechaSolicitud");
					$objPHPExcel->getActiveSheet()->SetCellValue('D1', "Especialidad");
					$objPHPExcel->getActiveSheet()->SetCellValue('E1', "Profesional");
					$objPHPExcel->getActiveSheet()->SetCellValue('F1', "NroCita");
						  
					// set Row
					$rowCount = 2;
					//foreach ($empInfo as $element) {
					foreach ($query->result() as $row){
						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->tipo);
						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row->nombre);
						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row->fecha);
						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->especialidad);
						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row->medico);
						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row->idCita);
						
						$rowCount++;
					}

					$objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
						

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		// download file
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment;filename=$fileName");
  			$objWriter->save('php://output');
		 
	}	

	
	public function exportar_covid()
	{
		$sql = "
		SELECT
		(
		SELECT
			GROUP_CONCAT( gestion_paciente_cliente2.nombre, ' ', gestion_paciente_cliente2.apellido ) 
		FROM
			gestion_paciente_cliente2 
		WHERE
			gestion_paciente_cliente2.idGestionPaciente = gp.id 
			AND gestion_paciente_cliente2.resultado != 3 
		) AS nombres,
		`d`.`name` AS `distrito`,
		`gp`.`email`,
	IF
		( gp.sede = 'SBC', 'SBCMedic', 'Domicilio' ) AS sede,
		`gp`.`id`,
		`gp`.`pago`,
		`gp`.`quienSolicito`,
		`gp`.`fecha`,
	IF
		( gp.pago, 'SI', 'NO' ) AS pago,
	IF
		( gp.realizado, 'SI', 'NO' ) AS realizado,
		if ( gp.sede ='SBC', 'Barranco', if ( gp.sede ='DOM', 'Domiciliaria', '' ) ) AS sede,
		`gp`.`hora`,
		`gp`.`motivo`,
		`gp`.`direccion`,
		`gp`.`telefono`,
		`gp`.`tipo_antigeno`,
		`gp`.`tipo_psr`,
		`gp`.`fechaCreacion`,
		`gp`.`cantidadPrueba_psr`,
		`gp`.`costo_cantidadPrueba_psr`,
		`gp`.`cantidadPrueba` AS `cantidadP`,
		`gp`.`costo_cantidadPrueba`,
	IF
		( gp.pruebaPromocional = 'pacmd', 'Prueba de Antigenos + Consulta Médica a Domicilio', IF ( gp.pruebaPromocional = 'pacmdv', 'Prueba de Antigenos + Consulta Méd.Domic. + Consulta Méd.Virtual', '' ) ) AS pPromocional,
		`gp`.`costo_pruebaPromocional`,
		`gp`.`costo_transporte`,
		`gp`.`porcentajeDescuento`,
		( sum( gp.costo_cantidadPrueba + gp.costo_pruebaPromocional ) - gp.porcentajeDescuento + gp.costo_transporte + gp.costo_cantidadPrueba_psr ) AS total,
		IF
		( gp.tipo_banco = 'BCP', 'Banco de Crédito', IF ( gp.tipo_banco = 'BBVA', 'BBVA Continental', IF ( gp.tipo_banco = 'SCOTBANK', 'Scotiabank', IF ( gp.tipo_banco = 'INTERB', 'Interbank', IF ( gp.tipo_banco = 'GRA', 'Gratis', IF ( gp.tipo_banco = 'EFE', 'Efectivo', IF ( gp.tipo_banco = 'TAR', 'Tarjeta', IF ( gp.tipo_banco = 'TRA', 'Transferencia', 'Efectivo' ) ) ) ) ) ) ) ) AS tipoBanco,

		(select concat(firstname, ' ', lastname) from patients where idUsuario = gp.idUsuario) as usuarioRegistro,
		(select concat(firstname, ' ', lastname) from patients where idUsuario = gp.idUsuarioPago) as usuarioPago,
		gp.fechaPago
	FROM
		`gestion_paciente` `gp`
		JOIN `ubigeo_districts` `d` ON `d`.`id` = `gp`.`idDistrito` 
	WHERE
		`gp`.`status` = 1 
		and gp.fecha  BETWEEN '".$this->input->post('fechaInicio')."' and '".$this->input->post('fechaFin')."'
	GROUP BY
		`gp`.`id` 
	ORDER BY
		`gp`.`fecha` DESC
				";
 
					$query = $this->db->query($sql);
 		$resultados = $query->result();
		
	 
		$this->data["data"] = $resultados;

		$this->load->view('excel/resultados_covid', $this->data);
		 
	}
	
	public function sendMailGmail()
	{
		//cargamos la libreria email de ci
		$this->load->library("email");

		//configuracion para gmail
		$configGmail = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			'smtp_user' => 'lacalderonc80@gmail.com',
			'smtp_pass' => '',
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		);

		//cargamos la configuración para enviar con gmail
		$this->email->initialize($configGmail);

		$this->email->from('tucorreo@gmail.com');
		$this->email->to("tucorreo@gmail.com");
		$this->email->subject('Esto es una prueba á');
		$this->email->message('<h2>Correo con imagen</h2>
			<hr><br>
			Kurt Cobain
			<br>
			<a href="http://www.facebook.com/intecsolt"><img src="'.base_url().'img/7.jpg" height="150" width="150"></a>
			<h3>Click en la imagen y dale like a mi pagina :D</h3>'
			);
				if ($this->email->send()) {
				echo "Enviado by litokurt";
			}else{
				show_error($this->email->print_debugger());
			}

	 
		
	}


}
