<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public $usuario;
	public $culqi;
	public $transaccion;
	public $disponibilidad;
	public $data;
	const SECRET_KEY = "sk_test_ryZeEK1BfrvzjGUH";


	public function __construct() {
		parent::__construct();		
		include_once APPPATH . 'third_party/Requests/library/Requests.php';
		// Cargamos Requests y Culqi PHP
		Requests::register_autoloader();
		include_once APPPATH . 'third_party/Culqi/lib/culqi.php';
		$this->load->database();
		$this->load->library('form_validation');
		$this->load->library('session');		
		$this->load->model('Usuario');
		$this->load->model('Transaccion');
		$this->load->model('Especialidad');
		$this->load->model('Disponibilidad');
		$this->load->model('Departamento');	
		$this->load->model('Provincia');
		$this->load->model('Distrito');
		$this->load->model('Doctor');
		$this->load->model('Helper');
		$this->load->helper('url'); 

	
		
		$this->culqi = new Culqi\Culqi(array('api_key' => self::SECRET_KEY));  
		$this->data = array();

		$this->data['version'] = $this->Helper->configuracion();
		$this->data['urlVideoCam'] = $this->Helper->configuracion();

	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		if($this->session->userdata('logged_in'))	redirect(base_url('inicio'));

		$this->load->view('login');
	}

	public function inicio()
    {
		$this->validarSesion();

		$this->cargarDatosSesion();

		$this->db->select("count(*) as cantidad");
	
		$this->db->from('cita c');
		$this->db->join('doctors d', 'd.idDoctor = c.idMedico');
		$this->db->join('specialties s', 's.idSpecialty = c.idEspecialidad');
		$this->db->join('patients p', 'p.idUsuario = c.idUsuario');
		$this->db->where("c.fechaCita >= date(NOW())");
		
		if ($this->session->userdata('rol') == 2) {
			$this->db->where('d.idUsuario', $this->session->userdata('idUsuario'));
		} else if ($this->session->userdata('rol') == 3 || $this->session->userdata('rol') == 5 || $this->session->userdata('rol') == 6) {
			$this->db->where('c.idUsuario', $this->session->userdata('idUsuario'));
		}
		
		$this->db->where('c.status', 1);
		$this->db->order_by("c.fechaCreacion", "DESC");

 
		$resultado = $this->db->get();
		$row_resultado = $resultado->row_array(); 

		$this->data["cantidad"] = $row_resultado['cantidad'];
		
		
		
		
		$fechaInicio = $this->input->post('fechaInicio') ? $this->input->post('fechaInicio') : date("Y-m-d");
		$fechaFinal = $this->input->post('fechaFin') ? $this->input->post('fechaFin') : date("Y-m-d");
		//procedimientos

		$sql= "SELECT
				FORMAT( sum( precio ) - sum( descuento ), 2 ) AS monto 
			FROM
				`solicitud_citas_pagos` 
			WHERE
				`idUsuario` != 5 
				AND `tipo_solicitud` = 'PRO' 
				AND `pago` = 1 
				AND date( solicitud_citas_pagos.fechaPago ) between '$fechaInicio' and '$fechaFinal'";

		$query = $this->db->query($sql);
		 
		$rowPro = $query->row();


		$this->data["montoProcedimiento"] = $rowPro->monto;

		//Laboratorios

/* 		$this->db->select("FORMAT(sum(precio), 2) as monto");
		$this->db->from('solicitud_citas_pagos');
		$this->db->where("idUsuario != 5");
		$this->db->where("tipo_solicitud", "LAB");
		$this->db->where("date( solicitud_citas_pagos.fechaPago ) = '2022-01-19'");
		$resultado = $this->db->get();
		$row_resultadoLab = $resultado->row_array();  */

		$sql = "SELECT
		FORMAT(
			sum( precio ) - (
			SELECT
			IF
				( sum( monto ) > 0, sum( monto ), 0 ) 
			FROM
				solicitarexamen_descuento_transporte 
			WHERE
				codigo_interno IN (
				SELECT
					codigo_lab 
				FROM
					`solicitud_citas_pagos` 
				WHERE
					`idUsuario` != 5 
					AND `tipo_solicitud` = 'LAB' 
					AND pago = 1 
					AND date( solicitud_citas_pagos.fechaPago ) between '$fechaInicio' and '$fechaFinal' 
					AND codigo_lab IS NOT NULL 
				) 
			),
			2 
		) AS monto 
	FROM
		`solicitud_citas_pagos` 
	WHERE
		`idUsuario` != 5 
		AND `tipo_solicitud` = 'LAB' 
		AND pago = 1 
		AND date( solicitud_citas_pagos.fechaPago ) between '$fechaInicio' and '$fechaFinal'";
		$query = $this->db->query($sql);
		 
		$rowLab = $query->row();
		


		$this->data["montoLaboratorio"] = $rowLab->monto;

		//Covid


		$sql = "SELECT
		FORMAT(
			sum( precio ) - (
			SELECT
			IF
				( sum( monto ) > 0, sum( monto ), 0 ) 
			FROM
				gestion_paciente_descuento_transporte 
			WHERE
				idGestion IN ( SELECT codigo_procedimiento FROM `solicitud_citas_pagos` WHERE `idUsuario` != 1 AND `tipo_solicitud` = 'ANT' AND pago = 1 AND date( solicitud_citas_pagos.fechaPago ) between '$fechaInicio' and '$fechaFinal'  ) 
				AND tipo = 'DES' 
				) + (
			SELECT
			IF
				( sum( monto ) > 0, sum( monto ), 0 ) 
			FROM
				gestion_paciente_descuento_transporte 
			WHERE
				idGestion IN ( SELECT codigo_procedimiento FROM `solicitud_citas_pagos` WHERE `idUsuario` != 1 AND `tipo_solicitud` = 'ANT' AND pago = 1 AND date( solicitud_citas_pagos.fechaPago ) between '$fechaInicio' and '$fechaFinal'  ) 
				AND tipo = 'TRA' 
			),
			2 
		) AS monto 
	FROM
		`solicitud_citas_pagos` 
	WHERE
		`idUsuario` != 1 
		AND `tipo_solicitud` = 'ANT' 
		AND pago = 1 
		AND date( solicitud_citas_pagos.fechaPago ) between '$fechaInicio' and '$fechaFinal'";
		$query = $this->db->query($sql);
		 
		$rowCovid = $query->row();


		$this->data["montoCovid"] = $rowCovid->monto;
		
		
		$this->load->view("inicio", $this->data);
	}

	public function logout(){
		$this->session->set_userdata('logged_in', FALSE);
		$this->session->sess_destroy();
		
		redirect(base_url("login"));
	}

	public function listarProvincias($idDep)
	{
		
		$provincias = $this->Provincia->listByDep($idDep);		
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $provincias ) );
	}

	public function listarDistritos($idProv)
	{
		
		$distritos = $this->Distrito->listByProv($idProv);		
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $distritos ) );
	}

	public function registrar()
	{
		if($this->session->userdata('logged_in') and $this->session->userdata('rol') != 4 and $this->session->userdata('rol') != 1)	redirect(base_url('inicio'));
		
		$arrDatos[] = "";
		$this->db->select('idTypeDocument, description');
		$this->db->from('type_document');
		$this->db->where('status', 1);
		$this->db->order_by("orden", "asc");
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			foreach($query->result() as $row)
			   $arrDatos[htmlspecialchars($row->idTypeDocument, ENT_QUOTES)] = 
			htmlspecialchars($row->description, ENT_QUOTES);
	
			$query->free_result();
		 }
 
		$this->data['tipoDocumento'] = array_filter($arrDatos);
		$this->data['departamentos'] = $this->Departamento->listAll();
		$this->data['provincias'] = $this->Provincia->listaTodos(15);
		$this->data['distritos'] = $this->Distrito->listaTodos(15, 1501);
		
		$this->db->select("id, nombre");
		$this->db->from("catalogo_canalventas");
		$this->db->where("activo", 1);
		$this->db->order_by("nombre", 'ASC');
		$this->data["canalesVentas"] = $this->db->get()->result();
		
		$this->load->view('registro', $this->data);
	}


	public function validarUsuario()
   {
		$response = array(
			'status'  => FALSE,
			'message'     => 'Error inesperado'		
		);

		$this->form_validation->set_rules('username', 'Nombre de Usuario', 'required');        
		
        if ($this->form_validation->run() == FALSE){
            $this->session->set_flashdata('errors', validation_errors());
            //redirect(base_url('itemCRUD/create'));
        }else{
			$username = $this->input->post('username');			
			$encrypted_password = md5(base64_encode($this->input->post('password')));			
			$row = $this->Usuario->buscar($username, $encrypted_password);			
			
			if(isset($row)){

				$response['status'] = TRUE;

				$newdata = array(
					'idUsuario'  => $row['idUser'],
					'username'  => $row['document'],
					'firstname' => $row['firstname'],
					'lastname'  => $row['lastname'],
					'email'     => $row['email'],
					'rol'     => $row['idRol'],
					'logged_in' => TRUE
				);
			
				$this->session->set_userdata($newdata);

			}
			
			$response['url'] = base_url("inicio");
		}
		
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
    }
	
   public function store()
   {
		$this->validarSesion();

		$this->cargarDatosSesion();
		
		$response = array(
			'status'  => FALSE,
			'message'     => '...'		
		);

		$this->form_validation->set_rules('document', 'Documento', 'required');        
		
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
        } else {
			$type = $this->input->post('type');

			$document = $this->input->post('document');
			$firstname = $this->input->post('firstname');	
			$lastname = $this->input->post('lastname');	
			$email = $this->input->post('email');	
			$phone = $this->input->post('phone');	
			$birthdate = date('Y-m-d', strtotime($this->input->post('birthdate')));	
			$idDistrict = $this->input->post('district');	
			$address = $this->input->post('address');	
			$sex = $this->input->post('sex');			
			$encrypted_password = md5(base64_encode($this->input->post('password')));	
			$row = $this->Usuario->verificar($document);			
			
			if(isset($row)){

				$response['message'] = 'El usuario ya esta registrado';
				
			}else {
				$response['status'] = $this->Transaccion->registrarUsuario($type, $document, $firstname, $lastname, $email, $phone, $birthdate, $idDistrict, $address, $sex, $encrypted_password, $this->input->post('canalVenta'));	 
			}	
	 
			if($response['status']){

				$newdata = array(
					'username'  => $document,
					'firstname'  => $firstname,
					'lastname'  => $lastname,
					'email'     => $email,
					'logged_in' => TRUE
				);
			
				//$this->session->set_userdata($newdata);

				$subject = "Bienvenido a la familia SBC Medic";
				$data['nombres'] = $firstname . " ". $lastname;
				$this->data['contenido'] = $this->load->view('contenido_bienvenida', $data, TRUE);				
				$message = $this->load->view('mensaje', $this->data, TRUE);
				$config['protocol'] = 'sendmail';
				$config['mailpath'] = '/usr/sbin/sendmail';
				$config['charset']   = 'utf-8';
				$config['mailtype']  = 'html';
				$config['wordwrap'] = TRUE;
				$this->load->library('email', $config);
				$this->email->set_newline("\r\n");
				$this->email->from('info@sbcmedic.com', "SBCMedic");
				$this->email->to($email);
				//$this->email->cc('marketing@sbcmedic.com');
				$this->email->subject($subject);
				$this->email->message($message);
				
				if($this->email->send())
				{
				  $this->session->set_flashdata('message', 'Compruebe el correo electrónico de verificación.'); 
				}
			}
		}
		
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
    }

	public function validarSesion(){
		if(!$this->session->userdata('logged_in'))
		{
			redirect(base_url('login'));
		}
	}

//cargar data sesión
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

	public function cita()
	{
		if($this->Helper->permiso_usuario("realizar_reservas_citas"))
		{
			$this->cargarDatosSesion();
			if($this->session->userdata('rol') == 3)
			{
				$this->load->view('cita_paciente', $this->data);
			}	else {
				$this->load->view('cita', $this->data);
			}
		} else {
			redirect(base_url("inicio"));
		}
	}

	public function confirmacion()
	{
		$this->cargarDatosSesion();
				
		$this->load->view('confirmacion', $this->data);
	}

	public function bienvenida()
	{
		$this->cargarDatosSesion();				
		$this->data['contenido'] = $this->load->view('contenido_bienvenida', '', TRUE);
		//$this->load->view('mensaje', $this->data);
		$this->load->view('bienvenida', $this->data);
	}

	public function listarDoctores($idSpe)
	{
		
		$doctores = $this->Doctor->listBySpe($idSpe);		
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $doctores ) );
	}

	public function listarEventosMedicos($especialidad, $medico, $fecha, $tipoCita)
	{
		$fechaFin = date("Y-m-t", strtotime($fecha));
		$fechaComoEntero = strtotime($fecha);

		$anio = date("Y", $fechaComoEntero);
		$mes = date("m", $fechaComoEntero);
		$fechaInicio = $anio."-".$mes."-01";

		$disponibilidades = $this->Disponibilidad->listaPorMedico($fecha, $medico, $especialidad, $tipoCita);
		$result = array();

		foreach( $disponibilidades as $item){

			$newobj = new stdClass();//create a new
			//$newobj->title = $item->name;
			$newobj->start = $item->date  ;
			$newobj->end = $item->date  ;
			$newobj->rendering = 'background';
			//$newobj->allDay = true;
			$newobj->classNames = "['alert-info', 'fc-today']";
			$newobj->color = '#27CB82'; //Info (aqua)
			//$newobj->backgroundColor = '#C0DE00'; //Info (aqua)
			//$newobj->borderColor     = $item->border; //Info (aqua)
			//$newobj->url = base_url('cita/confirmar/'.$item->idAvailability);
			$aux = array();
			$aux['title'] = $item->name;
			$aux['doctor'] = $item->title.' '.$item->firstname. ' ' .$item->lastname ;
			$aux['date'] = $item->date;
			$aux['start_time'] = $item->start_time;
			$aux['end_time'] = $item->end_time;
			$newobj->description = json_encode($aux);
			$result[] = $newobj;
		}
		
		
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $result ) );
	}

	public function buscarDisponibilidad($idDoc, $dateValue)
	{
		$this->load->model('Helper');
		 
		$this->data['promedio'] = $this->Helper->promedioCalificacion($idDoc);

		$result = $this->Disponibilidad->listarPorMedicoFecha($idDoc, $dateValue, $this->input->get("tipoCita"));		
		$this->procesarDisponibilidad($result);
		$this->load->view("list", $this->data);
	}

	public function buscarDisponibilidad2($idDoc, $dateValue)
	{
		$this->load->model('Helper');

		$this->data['promedio'] = $this->Helper->promedioCalificacion($idDoc);

		$result = $this->Disponibilidad->listarPorMedicoFecha($idDoc, $dateValue);		
		$this->procesarDisponibilidad($result);
		$this->load->view("list2", $this->data);
	}

	public function procesarDisponibilidad($result){
		$this->data['especialidades'] = $this->Especialidad->listAll();	
		$disponibilidades = array();
		foreach($result as $row){
			$idDoctor = $row->idDoctor;
			$idDisponibilidad = $row->idAvailability;
			if(!array_key_exists($idDoctor, $disponibilidades)){
				$disponibilidades[$idDoctor] = array();					
				$disponibilidades[$idDoctor]['especialidad'] = $row->name;
				$disponibilidades[$idDoctor]['doctor'] = $row->title.' '.$row->firstname. ' ' .$row->lastname ;
				$disponibilidades[$idDoctor]['horarios'] = array();
			}
			$disponibilidades[$idDoctor]['horarios'][$idDisponibilidad] = array();
			$disponibilidades[$idDoctor]['horarios'][$idDisponibilidad]['inicio'] = $row->start_time;
			$disponibilidades[$idDoctor]['horarios'][$idDisponibilidad]['fin'] = $row->end_time;
			$disponibilidades[$idDoctor]['horarios'][$idDisponibilidad]['disponibilidad'] = $idDisponibilidad;
		}

		$this->data['disponibilidades'] = $disponibilidades;

	}

	public function obtenerDisponibilidadPorDia($dia){
		$this->validarSesion();		
		$result = $this->Disponibilidad->findByDate($dia);
		$this->procesarDisponibilidad($result);	
	}

	public function buscar($tipoCita)
	{
		$this->cargarDatosSesion();
		$this->validarSesion();

		if($this->Helper->permiso_usuario("realizar_reservas_citas"))
		{
			$today = date("Y-m-d");
			
			$this->data['especialidades'] = $this->Especialidad->listAll();									
			$this->data['tipoCita'] = $tipoCita; 

			
			$this->db->select("u.idUser, concat(p.firstname, ' ', p.lastname, ' - ', p.document) as nombreUsuario");
			$this->db->from('users u');
			$this->db->join('patients p', 'p.idUsuario = u.idUser');
			$this->db->where('u.status', 1);
			$this->db->where("u.idRol", 3);
			$this->db->order_by("p.lastname", "asc");

			$this->data["usuarios"] = $this->db->get()->result();

	
			$this->load->view("cita_paso_1", $this->data);

		} else {
			redirect(base_url("inicio"));
		}
		
	}

	public function promedioCalificacionView($idMedico)
    {
		$this->validarSesion();

		$this->load->model('Helper');
		$promedio = $this->Helper->promedioCalificacion($idMedico);
		
        echo  $promedio;
	}
	
	public function confirmar($idDisp=false)
	{
		$this->validarSesion();
		
		$this->cargarDatosSesion();

		$codigosHorarios = str_replace(array("[","]", '"', " "), "", json_encode($this->input->post("horarios")));
		$porciones = explode(",", $codigosHorarios);
		sort($porciones);

		if($this->Helper->permiso_usuario("realizar_reservas_citas"))
		{
			
			$user = $this->input->get_post("user");
 
			if($idDisp == "add")
			{
			
			$medico = $this->Doctor->medicoEspecialista($this->input->get("doctor"));
			
			
			$this->data['especialidad'] = $medico[0]->especialidad;
			$this->data['idMedico'] = $medico[0]->idDoctor;
			$this->data['idEspecialidad'] = $medico[0]->idSpecialty;
			$this->data['doctor'] = $medico[0]->title.' '.$medico[0]->nombreMedico;
			$this->data['monto'] = 0;
			$this->data['idDisponible'] = null;

			$this->data['fecha'] = date("Y-m-d");
			$this->data['inicio'] = date('H:i', time());
			$this->data['fin'] = date('H:i', time());

			} else {
				
				$today = date("Y-m-d");
				
				$row = $this->Doctor->medicoEspecialista($this->input->post("profesional"));
			 
				if(isset($row)){
					$this->data['idDisponible'] = null;
					$this->data['especialidad'] = $row[0]->especialidad;
					$this->data['idMedico'] = $row[0]->idDoctor;
					$this->data['idEspecialidad'] = $row[0]->idSpecialty;
					$this->data['doctor'] = $row[0]->nombreMedico ;
					$this->data['monto'] =  0;
					$this->data['fecha'] = $this->input->post("fecha");
					$this->data['inicio'] =  substr(min($porciones), 0, 5);
					$this->data['fin'] =  substr(max($porciones), 6, 5);
				}
			}
			
			/* $respuesta = $this->Helper->consultarMonto($user, $row['idSpecialty']);
			
			if($respuesta > 0){
				$this->data['monto'] = "39.00";
			} */
	
			$this->data['tipoCita'] = $this->input->get_post('tipoCita');
			$this->data['user'] = $user;
			$this->data['sucursal'] = $this->input->get('sucursal');

			$this->data['paciente'] = $this->Usuario->datosUsuario($user);

			$this->db->select("id, name");
			$this->db->from("ubigeo_districts");
			$this->db->where("id in (150108, 150104, 150140, 040110)");
			$this->db->order_by("name", 'ASC');
			$query = $this->db->get();
			
			$this->data["distritosHabilitados"]  = $query;
			
			$this->db->select("id, nombre");
			$this->db->from("motivo_cita");
			$this->db->where("activo", 1);
			$this->db->order_by("nombre", 'ASC');
			$query = $this->db->get();
			$this->data["motivoCita"]  = $query;
			
			$this->load->view("cita_paso_2", $this->data);

		} else {
			redirect(base_url("inicio"));
		}
		
	}
	
	public function confirmar2($idDisp)
	{
		$this->validarSesion();
		
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("realizar_reservas_citas"))
		{
			
			$user = ($this->input->get("user") && $this->input->get("user") !="" && $this->input->get("user") > 0)? $this->input->get("user") : $this->session->userdata('idUsuario');

			if($idDisp == "add")
			{
			
			$medico = $this->Doctor->medicoEspecialista($this->input->get("doctor"));
			
			$this->data['especialidad'] = $medico[0]->especialidad;
			$this->data['idMedico'] = $medico[0]->idDoctor;
			$this->data['idEspecialidad'] = $medico[0]->idSpecialty;
			$this->data['doctor'] = $medico[0]->title.' '.$medico[0]->nombreMedico;
			$this->data['monto'] = 0;
			$this->data['idDisponible'] = null;

			$this->data['fecha'] = date("Y-m-d");
			$this->data['inicio'] = date('H:i', time());
			$this->data['fin'] = date('H:i', time());

			} else {
				
				$today = date("Y-m-d");
				
				$row = $this->Disponibilidad->findById($idDisp);
			 
				if(isset($row)){
					$this->data['idDisponible'] = $row['idAvailability'];
					$this->data['especialidad'] = $row['name'];
					$this->data['idMedico'] = $row['idDoctor'];
					$this->data['idEspecialidad'] = $row['idSpecialty'];
					$this->data['doctor'] = $row['title'].' '.$row['firstname']. ' ' .$row['lastname'] ;
					$this->data['monto'] = $row['monto'];
					$this->data['fecha'] = $row['date'];
					$this->data['inicio'] = $row['start_time'];
					$this->data['fin'] = $row['end_time'];
				}
			}
			
			/* $respuesta = $this->Helper->consultarMonto($user, $row['idSpecialty']);
			
			if($respuesta > 0){
				$this->data['monto'] = "39.00";
			} */
	
			$this->data['tipoCita'] = $this->input->get('tipoCita');
			$this->data['user'] = $this->input->get('user');
			$this->data['sucursal'] = $this->input->get('sucursal');
			

			$this->data['paciente'] = $this->Usuario->datosUsuario($user);

			$this->db->select("id, name");
			$this->db->from("ubigeo_districts");
			$this->db->where("id in (150108, 150104, 150140, 040110)");
			$this->db->order_by("name", 'ASC');
			$query = $this->db->get();
			
			$this->data["distritosHabilitados"]  = $query;
			
			$this->db->select("id, nombre");
			$this->db->from("motivo_cita");
			$this->db->where("activo", 1);
			$this->db->order_by("nombre", 'ASC');
			$query = $this->db->get();
			$this->data["motivoCita"]  = $query;
			
			$this->load->view("cita_paso_2", $this->data);

		} else {
			redirect(base_url("inicio"));
		}
		
	}

	public function recuperarPassword()
	{
		if($this->session->userdata('logged_in'))	redirect(base_url('cita'));

		$this->load->view('recuperar_password');
	}

	public function validarEmail()
    {
		if($this->session->userdata('logged_in'))	redirect(base_url('cita'));
		
		$this->form_validation->set_rules('email', 'Ingrese su email', 'required');        
		$this->form_validation->set_rules('dni', 'Ingrese su DNI', 'required');        
		
        if ($this->form_validation->run() == FALSE){
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {

			$email = $this->input->post('email');
			$dni = $this->input->post('dni');	 

			$this->db->select("p.email, concat(p.firstname, ' ', p.lastname) as nombres");
    		$this->db->from('users u');
			$this->db->join('patients p', 'p.document = u.username');
			$this->db->where('u.username', $dni);
			$this->db->where('p.email', $email);
			
			$resultado = $this->db->get();
			$row_resultado = $resultado->row_array(); 

			if($this->db->affected_rows() == 1)
			{
				$subject = "Recuperación de contraseña";
			
				$passwordplain  = rand(999999999,9999999999);

			   	$data['token'] = md5($passwordplain);
				$data['nombres'] = $row_resultado['nombres'];

			  	$this->data['contenido'] = $this->load->view('contenido_recuperarpass', $data, TRUE);

				$message = $this->load->view('mensaje', $this->data, TRUE);
				   
			   $config['protocol'] = 'sendmail';
			   $config['mailpath'] = '/usr/sbin/sendmail';
			   $config['charset']   = 'utf-8';
			   $config['mailtype']  = 'html';
			   $config['wordwrap'] = TRUE;
			   $config['priority'] = 1;

			   $this->load->library('email', $config);
			   $this->email->set_newline("\r\n");
			   $this->email->from('info@sbcmedic.com', "SBCMedic");
			   $this->email->to($email);
			   //$this->email->cc('marketing@sbcmedic.com');
			   $this->email->subject($subject);
			   $this->email->message($message);
				
			   $response['message'] = "Se realizo correctamente.";
			   $response['status'] = TRUE;

				if($this->email->send())
				{ 
					
					$response['message'] = 'Se envio Email de verficación correctamente';

					$parametros = array (
						"token_forward" => $data['token']
					);

					$this->db->where('username', $dni);
					$this->db->update('users', $parametros);
				}
			} else {
				$response['status'] = false;
				$response['message'] = 'Credenciales inconrrectos(dni y/o email)';
			}            
		}
 
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function cambiarPassword($token)
	{
		if($this->session->userdata('logged_in'))	redirect(base_url('cita'));

		$this->db->select('u.username');
		$this->db->from('users u');
		$this->db->join('patients p', 'p.document = u.username');
		$this->db->where('u.token_forward', $token);
		
		$query = $this->db->get();

		if($this->db->affected_rows() == 1) {
			$data['token'] = $token;
			$this->load->view('cambio_password', $data);
		} else {
			$this->session->set_flashdata("token_expirado", "Token Expirado");
			redirect(base_url());
		}
	}

	public function confirmarCambioPassword()
    {
		if($this->session->userdata('logged_in'))	redirect(base_url('cita'));

		$this->form_validation->set_rules('password', 'Ingrese el password', 'required');        
		$this->form_validation->set_rules('repassword', 'Ingrese el re-password', 'required');
		$this->form_validation->set_rules('token', '', 'required');      

		if ($this->form_validation->run() == FALSE)
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {

			$token = $this->input->post('token');	 

			$this->db->select('p.firstname, p.lastname, p.email');
    		$this->db->from('users u');
			$this->db->join('patients p', 'p.document = u.username');
			$this->db->where('u.token_forward', $token);
			
			$query = $this->db->get();
			$row_resultado = $query->row_array();

			if($this->db->affected_rows() == 1)
			{
				$subject = "Cambio de contraseña";
			   	$data['nombre'] = $row_resultado['firstname'] . " ". $row_resultado['lastname'];
			   	$this->data['contenido'] = $this->load->view('confirmacion_cambio_password', $data, TRUE);

			   	$message = $this->load->view('mensaje', $this->data, TRUE);

				$config['protocol'] = 'sendmail';
				$config['mailpath'] = '/usr/sbin/sendmail';
				$config['charset']   = 'utf-8';
				$config['mailtype']  = 'html';
				$config['wordwrap'] = TRUE;
				$config['priority'] = 1;

				$this->load->library('email', $config);
				$this->email->set_newline("\r\n");
				$this->email->from('info@sbcmedic.com', "SBCMedic");
				$this->email->to($row_resultado['email']);
				//$this->email->cc('marketing@sbcmedic.com');
				$this->email->subject($subject);
				$this->email->message($message);

				$response['status'] = TRUE;
				$response['message'] = 'Se realizo el cambio de contraseña exitosamente';

				if($this->email->send())
				{
					$parametros = array (
						"token_forward" => "",
						"password" => md5(base64_encode($this->input->post('password')))
					);
	
					$this->db->where('token_forward', $token);
					$this->db->update('users', $parametros);
 				}
			
			}  else {
				$response['status'] = false;
				$response['message'] = 'Token no válido';
			}
 
			$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
		}	
	}

	public function perfil()
    {
		$this->validarSesion();

		$this->cargarDatosSesion();

		$this->db->select('u.username as username, u.idRol, p.idPatient, p.idTypeDocument, p.firstname, p.lastname, p.email, p.phone, p.birthdate, p.idDistrict, p.address, p.sex');
		$this->db->from('users u');
		$this->db->join('patients p', 'p.document = u.username');
		$this->db->where('u.username', $this->session->userdata('username'));
		
		$query = $this->db->get();
 
		if ($query->num_rows() == 1)
		{
			foreach ($query->result() as $row)
			{
				$this->data['username'] =  $row->username;
				$this->data['idTypeDocument'] =  $row->idTypeDocument;
				$this->data['firstname'] =  $row->firstname;
				$this->data['lastname'] =  $row->lastname;
				$this->data['email'] =  $row->email;
				$this->data['phone'] =  $row->phone;
				$this->data['birthdate'] =  $row->birthdate;
				$this->data['idDistrict'] =  $row->idDistrict;
				$this->data['address'] =  $row->address;
				$this->data['sex'] =  $row->sex;
				$this->data['idRol'] =  $row->idRol;
			}
		}

		$this->session->set_userdata('firstname', $this->data['firstname']);
		$this->session->set_userdata('lastname', $this->data['lastname']);
		$this->session->set_userdata('rol', $this->data['idRol']);

		$arrDatos[] = "";
 
		$this->db->select('idTypeDocument, description');
		$this->db->from('type_document');
		$this->db->where('status', 1);
		$this->db->order_by("description", "asc");
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			foreach($query->result() as $row)
			   $arrDatos[htmlspecialchars($row->idTypeDocument, ENT_QUOTES)] = 
			htmlspecialchars($row->description, ENT_QUOTES);
	
			$query->free_result();
		 }
 
		$this->data['miUbigeo'] = $this->Usuario->ubigeo($this->data['idDistrict']);
	 
		$this->data['tipoDocumento'] = array_filter($arrDatos);
		$this->data['departamentos'] = $this->Departamento->listAll();
		$this->data['provincias'] = $this->Provincia->listaTodos($this->data['miUbigeo']["department_id"]);
		$this->data['distritos'] = $this->Distrito->listaTodos($this->data['miUbigeo']["department_id"], $this->data['miUbigeo']["province_id"]);
		 
		$this->load->view("perfil", $this->data);
	}

	public function actualiZar_datos()
   	{
		$this->validarSesion();

		$this->form_validation->set_rules('firstname', 'firstname', 'required');    
		$this->form_validation->set_rules('lastname', 'lastname', 'required');    
		$this->form_validation->set_rules('tipo', 'tipo', 'required');    
		$this->form_validation->set_rules('email', 'email', 'required');    
		$this->form_validation->set_rules('phone', 'phone', 'required');    
		$this->form_validation->set_rules('sex', 'sex', 'required');    
		$this->form_validation->set_rules('birthdate', 'birthdate', 'required');    
		$this->form_validation->set_rules('address', 'address', 'required');    
		$this->form_validation->set_rules('province', 'province', 'required');    
		$this->form_validation->set_rules('distrito', 'distrito', 'required');    
		$encrypted_password = md5(base64_encode($this->input->post('password')));	

		$usuario = $this->input->post('usuario');
 
		if(!empty($usuario)){

			$nombres =  trim($this->input->post('firstname'));
			$apellidos =  trim($this->input->post('lastname'));
			$tipoDocumento =  $this->input->post('tipo');
			$email =  $this->input->post('email');
			$telefono =  $this->input->post('phone');
			$sexo =  $this->input->post('sex');
			$fechaNacimiento =  $this->input->post('birthdate');
			$direccion =  $this->input->post('address');
			$encrypted_password = md5(base64_encode($this->input->post('password')));
			$distrito =  $this->input->post('distrito');

			if($this->input->post('password') !="")
			{
				$datos = array(
					'password' => md5(base64_encode($this->input->post('password')))
				);	
				
				$this->db->where('username', $usuario)
						   ->update('users', $datos);
			}

			 
			$datos = array(
				'idTypeDocument' => $tipoDocumento,
				'firstname' => $nombres,
				'lastname' => $apellidos,
				'email' => $email,
				'phone' => $telefono,
				'birthdate' => $fechaNacimiento,
				'idDistrict' => $distrito,
				'address' => $direccion,
				'sex' => $sexo,
			);	
			
			$this->db->where('document', $usuario);
			$this->db->update('patients', $datos);

			$response['message'] = 'Usuario actualizado correctamente';
			$response['status'] = true;
			 
				
		} else {
			$response['message'] = 'Usuario No existe';
			$response['status'] = false;
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function listarDisponibilidadTotal()
	{
		$this->validarSesion();
		//$start = $this->input->get('start');
		$end = $this->input->post('end');

		$startDate = date('Y-m-d', strtotime($start));
		$endDate = date('Y-m-d', strtotime($end));

		$disponibilidades = $this->Disponibilidad->listaPorMedico($startDate, $endDate);

		$result = array();

		foreach( $disponibilidades as $item){

			$newobj = new stdClass();//create a new
			$newobj->title = $item->name;
			$newobj->start = $item->date ." ".$item->start_time;
			$newobj->end = $item->date ." ".$item->end_time;
			$newobj->allDay = false;
			$newobj->backgroundColor = $item->background; //Info (aqua)
			$newobj->borderColor     = $item->border; //Info (aqua)
			$newobj->url = base_url('cita/confirmar/'.$item->idAvailability );
			$aux = array();
			$aux['title'] = $item->name;
			$aux['doctor'] = $item->title.' '.$item->firstname. ' ' .$item->lastname ;
			$aux['date'] = $item->date;
			$aux['start_time'] = $item->start_time;
			$aux['end_time'] = $item->end_time;
			$newobj->description = json_encode($aux);
			$result[] = $newobj;
		}
		
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $result ) );
	}

	public function procesarPagoUnico()
    {
		$this->validarSesion();
		$this->cargarDatosSesion();

		$idsPrincipal = $this->input->post("ids");

		$this->db->select("count(id) as cantidad, codigo_asignacion");
		$this->db->from("solicitud_citas_pagos");
		$this->db->where("marca_cita", 1);
		$this->db->where("id in($idsPrincipal)");
		$this->db->group_by("2");
	   
		$query = $this->db->get();
		$row_resultadoDisponible = $query->row_array();
 
		if($row_resultadoDisponible['cantidad'] > 0 and ($row_resultadoDisponible['codigo_asignacion'] == $this->input->post("codeOne")) ) 
		{
			$tipoCita = "CP";

			$nombreCita["CP"] = "Cita Presencial";

			$user = $this->input->post("user");
			$fechaCita = $this->input->post("fechaCita");
			$fecha = $this->input->post("fecha");
		
			$horaCita = $this->input->post("horarios");
			$motivo = $this->input->post("observaciones");
			$idMedico = $this->input->post("profesional");
			$idMotivoCita = $this->input->post("motivoCita");
	
			$idEspecialidad = $this->input->post("idEspecialidad");

			$data['tipoCita'] = $tipoCita;

			if($this->input->post("procedimientosPrincipal") == $this->input->post("procedimientos"))	$titulo = ""; else $titulo = $this->input->post("procedimientosPrincipal");
	
			$medico = $this->Doctor->medicoEspecialista($idMedico);
			$medicoNombre = $medico[0]->nombreMedico;
			$especialidad = $medico[0]->especialidad;
			$emailMedico = $medico[0]->email;

			$row_resultadoHora1 = 0;
			$row_resultadoHora2 = 0;

			if($this->input->post("opcionCita") != "adicional")
			{
				$horasIni = explode("-", reset($horaCita));
				$horasFin = explode("-", end($horaCita));

				$horaCita = trim($horasIni[0]). "-".trim($horasFin[1]);
		

				$this->db->select("count(idCita) as cantidad");
				$this->db->from("cita");
				$this->db->where("idMedico", $idMedico);
				$this->db->where("fechaCita", $fechaCita);
				$this->db->where("adicional != 1");
				$this->db->where("status in(1, 0)");
				$this->db->where("RIGHT ( horaCita, 5 ) < '$horasFin[1]'");
				$this->db->where(" LEFT ( horaCita, 5)  BETWEEN '$horasIni[0]' and  '$horasFin[1]'");
			
			
				$query = $this->db->get();
				$row_resultadoHora1 = $query->row_array();

				$row_resultadoHora2 = 0;

				if($row_resultadoHora1['cantidad'] == 0) 
				{
					$this->db->select("count(idCita) as cantidad");
					$this->db->from("cita");
					$this->db->where("idMedico", $idMedico);
					$this->db->where("fechaCita", $fechaCita);
					$this->db->where("adicional != 1");
					$this->db->where("status in(1, 0)");
					$this->db->where("'$horasIni[0]' >= LEFT ( horaCita, 5 )");
					$this->db->where("'$horasIni[0]' < RIGHT ( horaCita, 5 )");

					$query = $this->db->get();
					$row_resultadoHora2 = $query->row_array();
				}
			}
		
			$gratis = 0;
			$virtual = 0;
			$adicional = 0;
			
			if($this->input->post("opcionCita") == "gratis")
			{
				$gratis = 1;
			} else if($this->input->post("opcionCita") == "virtual") {
				$virtual = 1;
			} else if($this->input->post("opcionCita") == "adicional") {
				$adicional = 1;
				$horaCita = date('H:i', time()) . "-". date('H:i', time());
			}

				if(($row_resultadoHora1['cantidad'] == 0 and $row_resultadoHora2['cantidad'] == 0)  || $this->input->post("adicional") == 1) {
						//insertar cita
						$datosCita = array(
							'idUsuario' => $user,
							'tipoCita' => $tipoCita,
							'fechaCita' => $fechaCita,
							'horaCita' => $horaCita,
							'idEspecialidad' => $idEspecialidad,
							'idMedico' => $idMedico,
							'motivo' => $motivo,
							'idMotivoCita' => $idMotivoCita,
							'virtual' => $virtual,
							'adicional' => $adicional,
							'gratis' => $gratis,
							'idUsuarioCreacion' => $this->session->userdata('idUsuario')
						);
  
						$this->db->trans_start();
						$this->db->insert('cita', $datosCita);
						$idCita = $this->db->insert_id();
						$this->db->trans_complete();
						
						if ($this->db->trans_status()) 
						{

								//proce
								$procedimientosIds = $this->input->post("procedimientos");
								if($procedimientosIds) {
										
									$procedimientos = explode(",", $procedimientosIds);
									
									$idFormatoProcedimientos = null;
									$codigoInternoAsig = null;
								
									if(empty($this->input->post("codeOne"))) {
										$codigoInternoAsig = strtoupper(uniqid());
									} else {
										$codigoInternoAsig = $this->input->post("codeOne");
									}
				
									for ($i=0; $i < count($procedimientos); $i++) { 
				
										$procedimientosPro = array(
											'idCita' => $idCita,
											'codigo_procedimiento' => $procedimientos[$i],
											'codigo_asignacion' => $codigoInternoAsig,
											'code_principal' => $titulo,
											'idUsuario' => $this->session->userdata('idUsuario')
										);
				
										$this->db->insert("historial_asignacion_cita", $procedimientosPro);
										
										$idFormatoProcedimientos =  $idFormatoProcedimientos.$procedimientos[$i]."','";
										
 
 
									}

									$idFormatoProcedimientos = "'".substr($idFormatoProcedimientos, 0, -2);
									
									$parametros = array(
										'codigo_asignacion' => $codigoInternoAsig
									);

									$this->db->trans_start();
									$this->db->where('idCita', $idCita);
									$this->db->update('cita', $parametros);
									$this->db->trans_complete();

									if ($this->db->trans_status())
									{
										$parametrosMarca = array(
											'marca_cita' => 2,
											'idCita' => $idCita,
											'realizado' => 1,
											'codigo_asignacion' => $codigoInternoAsig
										);
										
										if($this->input->post("procedimientosPrincipal") == "MED1000359" and !$gratis)
										{
											if(count($procedimientos) == 1)	$costo = $this->Helper->consultar_precio($procedimientos[0]);
			 
											$parametrosMarca['precio'] = $costo["precio"];
										}
										

										$ids= $this->input->post("ids");
			
										$this->db->where("id in($ids)");
										$this->db->update('solicitud_citas_pagos', $parametrosMarca);
										
										//verficar pago
 
									}
								}




						//exámenes
						$codigoInterno = null;
						
						$examenes = $this->input->post("exmanenes");

						if($examenes) {

							$examenes = explode(",", $this->input->post("exmanenes"));
							$fecha = $this->input->post("fecha");

							for ($i=0; $i < count($examenes); $i++) {
								//PERFIL LIPÍDICO
								if($examenes[$i] == 334)
								{
									$examenes67 = array(4, 5, 6, 7);
									for ($ii=0; $ii <count($examenes67) ; $ii++) { 
				
										$parametros= array(
											'fechaExamen' => $fecha,
											'idExamen' => $examenes67[$ii],
											'precio' => 0,
											'idPerfil' => 334,
											'idCita' => $idCita,
											'codigo_asignacion' => $codigoInternoAsig,
											"idUsuarioCreacion" => $this->session->userdata('idUsuario'),
										
											//"status_pago" => 1,
											'idUsuario' => $user
										);
				
										$this->db->insert("solicitarexamen", $parametros);
										$idCodigo = $this->db->insert_id();
				
										if(!($this->input->post("codigoInterno")))
										{ 
											if(is_null($codigoInterno)) {
												$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
											}
				
											$parametrosUpate= array(
												'codigo_interno' => $codigoInterno
											);
											
											$this->db->where("id", $idCodigo);
											$this->db->update("solicitarexamen", $parametrosUpate);
										}
									}
				
								}
								
								//PERFIL HEPÁTICO
								if($examenes[$i] == 337)
								{
									$examenes68 = array(8, 9, 128, 11, 108);
									for ($ii=0; $ii <count($examenes68) ; $ii++) { 
				
										$parametros= array(
											'fechaExamen' => $fecha,
											'idExamen' => $examenes68[$ii],
											'precio' => 0,
											'idPerfil' => 337,
											'idCita' => $idCita,
											'codigo_asignacion' => $codigoInternoAsig,
											"idUsuarioCreacion" => $this->session->userdata('idUsuario'),
											//"fechaModificar" => date('Y-m-d H:i:s'),
											//"status_pago" => 1,
											'idUsuario' => $user
										);
				
										$this->db->insert("solicitarexamen", $parametros);
										$idCodigo = $this->db->insert_id();
				
										if(!($this->input->post("codigoInterno")))
										{ 
											if(is_null($codigoInterno)) {
												$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
											}
				
											$parametrosUpate= array(
												'codigo_interno' => $codigoInterno
											);
											
											$this->db->where("id", $idCodigo);
											$this->db->update("solicitarexamen", $parametrosUpate);
										}
									}
				
								}
								
								//perfil diabetico
								if($examenes[$i] == 335)
								{
									$examenesDiabetes = array(1, 12);
									for ($diab=0; $diab <count($examenesDiabetes) ; $diab++) { 
				
										$parametros= array(
											'fechaExamen' => $fecha,
											'idExamen' => $examenesDiabetes[$diab],
											'precio' => 0,
											'idPerfil' => 335,
											'idCita' => $idCita,
											'codigo_asignacion' => $codigoInternoAsig,
											"idUsuarioCreacion" => $this->session->userdata('idUsuario'),
											//"fechaModificar" => date('Y-m-d H:i:s'),
											//"status_pago" => 1,
											'idUsuario' => $user
										);
				
										$this->db->insert("solicitarexamen", $parametros);
										$idCodigo = $this->db->insert_id();
				
										if(!($this->input->post("codigoInterno")))
										{ 
											if(is_null($codigoInterno)) {
												$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
											}
				
											$parametrosUpate= array(
												'codigo_interno' => $codigoInterno
											);
											
											$this->db->where("id", $idCodigo);
											$this->db->update("solicitarexamen", $parametrosUpate);
										}
									}
								}
								
								//perfil tiroideo
								if($examenes[$i] == 339)
								{
									$pTiroideo = array(42, 44);
									for ($ptiro=0; $ptiro <count($pTiroideo) ; $ptiro++) { 
				
										$parametros= array(
											'fechaExamen' => $this->input->post("fecha"),
											'idExamen' => $pTiroideo[$ptiro],
											'precio' => 0,
											'idPerfil' => 339,
											'idCita' => $idCita,
											'codigo_asignacion' => $codigoInternoAsig,
											"idUsuarioCreacion" => $this->session->userdata('idUsuario'),
											//"fechaModificar" => date('Y-m-d H:i:s'),
											//"status_pago" => 1,
											'idUsuario' => $user
										);
				
										$this->db->insert("solicitarexamen", $parametros);
										$idCodigo = $this->db->insert_id();
				
										if(!($this->input->post("codigoInterno")))
										{ 
											if(is_null($codigoInterno)) {
												$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
											}
				
											$parametrosUpate= array(
												'codigo_interno' => $codigoInterno
											);
											
											$this->db->where("id", $idCodigo);
											$this->db->update("solicitarexamen", $parametrosUpate);
										}
									}
								}	
				
				
								$parametros= array(
									'fechaExamen' => $fecha,
									'idExamen' => $examenes[$i],
									'precio' => 0,
									'idCita' => $idCita,
									'codigo_asignacion' => $codigoInternoAsig,
									"idUsuarioCreacion" => $this->session->userdata('idUsuario'),
									//"fechaModificar" => date('Y-m-d H:i:s'),
									//"status_pago" => 1,
									'idUsuario' => $user
								);
				
								$this->db->insert("solicitarexamen", $parametros);
								$idCodigo = $this->db->insert_id();
								

								
								if(!($this->input->post("codigoInterno")))
								{ 
									if(is_null($codigoInterno)) {
										$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
									}
				
									$parametrosUpate= array(
										'codigo_interno' => $codigoInterno
									);
									
									$this->db->where("id", $idCodigo);
									$this->db->update("solicitarexamen", $parametrosUpate);
								}
								
								$caja = array(
									'idUsuario' => $user,
									'codigo_procedimiento' => $examenes[$i],
									'precio' => 0,
									'tipo_solicitud' => "LAB",
									'codigo_lab' => $codigoInterno,
									'realizado' => 1,
									'descuento' => $this->input->post("descuento"),
									'codigo_asignacion' => $codigoInternoAsig,
									'idUsuarioCreacion' => $this->session->userdata('idUsuario')
								);
			
								$this->db->insert("solicitud_citas_pagos", $caja);
							}
						}

						//fin examenes
		
							$response['message'] = 'Cita registrado satisfactoriamente.';
							$response['status'] = true;	
							$response['idCita'] = $idCita;	 
							$response['code'] = $codigoInternoAsig;	 

							
							$response['codPrincipal'] = $titulo;	 

						} else {
							$response['message'] = 'Cita no registrado';
							$response['status'] = false;
						}

						$this->output->set_content_type( 'application/json' )->set_output(json_encode($response));

			
			} else {
			
				$response['message'] = "!Horario ya fue asignado, no disponible¡";
				$response['status'] = false;
				
			}
		} else {
			$response['message'] = "!Registro no disponible¡";
			$response['status'] = false;
		}

		$this->output->set_content_type( 'application/json' )->set_output(json_encode($response));
	}

	public function procesarPagoUnico_001()
    {
		$this->validarSesion();

		$idDisponible = $this->input->post("idDisponible");
		$procedimiento = $this->input->post("procedimiento");
 

	 
		$tipoCita = $this->input->post("tipoCita");
 
		$token = $this->input->post("token");
		$nombreCita["CV"] = "Cita Virtual";
		$nombreCita["CP"] = "Cita Presencial";
		$nombreCita["CD"] = "Cita Domicilio";
		$nombreCita["PR"] = "Cita Procedimiento";
		$nombreCita["CPB"] = "Cita Policlínico  Barranco";

		$user = $this->input->post("user");
 
		$sucursal = ($this->input->post("sucursal") && $this->input->post("sucursal") !="" && $this->input->post("sucursal") > 0)? $this->input->post("sucursal") : 1;

		
		$fechaCita = $this->input->post("fechaCita");
		
		$motivo = $this->input->post("motivo");
		$idMedico = $this->input->post("idMedico");
		$idMotivoCita = $this->input->post("idtipoCita");
		
		$idEspecialidad = $this->input->post("idEspecialidad");
		$monto = ($tipoCita == "CPB")? "": $this->input->post("txtMonto");

		$distrito = $this->input->post("distrito");
		$data['tipoCita'] = $tipoCita;

		$tipoComprobante = $this->input->post("tipoComprobante");
		$gratis = $this->input->post("gratis");

		$nombreCompleto = "";
		$razonSocial = "";
		$ruc = "";
		$direccionruc = "";


		if ($tipoComprobante =="BOL") {
			$nombreCompleto = $this->input->post("nombreCompleto");
		} else {
			$razonSocial = $this->input->post("razonSocial");
			$ruc = $this->input->post("ruc");
			$direccionruc = $this->input->post("direccionruc");
		}

		$direccion = $this->input->post("direccion");

		$medico = $this->Doctor->medicoEspecialista($idMedico);
		$medicoNombre = $medico[0]->nombreMedico;
		$especialidad = $medico[0]->especialidad;
		$emailMedico = $medico[0]->email;
		
		$data['codigoSala'] = $medico[0]->codigoSala;

		$row_resultadoHora1['cantidad'] = 0;
		$row_resultadoHora2['cantidad'] = 0;

		$horaCita = date('H:i', time()) . "-". date('H:i', time());
		$hora0 = "";
		$hora1 = "";
		
		if( $this->input->post("adicional") != 1 )
		{
			$horaCita = $this->input->post("horaCita");

			$horas = explode("-", $horaCita); 
			$hora0 = trim($horas[0]);
			$hora1 = trim($horas[1]);

			$this->db->select("count(idCita) as cantidad");
			$this->db->from("cita");
			$this->db->where("idMedico", $idMedico);
			$this->db->where("fechaCita", $fechaCita);
			$this->db->where("status in(1, 0)");
			$this->db->where("adicional != 1");
			$this->db->where("RIGHT ( horaCita, 5 ) < '$hora1'");
			$this->db->where(" LEFT ( horaCita, 5)  BETWEEN '$hora0' and  '$hora1'");
		
		
			$query = $this->db->get();
			$row_resultadoHora1 = $query->row_array();
			
			

			if($row_resultadoHora1['cantidad'] == 0) 
			{
				$this->db->select("count(idCita) as cantidad");
				$this->db->from("cita");
				$this->db->where("status in(1, 0)");
				$this->db->where("idMedico", $idMedico);
				$this->db->where("fechaCita", $fechaCita);
				$this->db->where("adicional != 1");
				$this->db->where("'$hora0' >= LEFT ( horaCita, 5 )");
				$this->db->where("'$hora0' < RIGHT ( horaCita, 5 )");

				$query = $this->db->get();
				$row_resultadoHora2 = $query->row_array();
		
			}

		}
		
		$horaCita = $hora0. "-". $hora1;
		
		if($this->input->post("adicional") == 1)	$horaCita = date('H:i', time()). "-". date('H:i', time());
		
		if(($row_resultadoHora1['cantidad'] == 0 and $row_resultadoHora2['cantidad'] == 0)  || $this->input->post("adicional") == 1) {

			if($token)
			{
				$descripcion = $this->input->post("descripcion");
				$card_number = $this->input->post("card_number");
				$creation_date = $this->input->post("creation_date");
				$email = $this->input->post("email");
				$last_four = $this->input->post("last_four");
				$ip_country_code_realizado = $this->input->post("ip_country_code");
				$card_brand = $this->input->post("card_brand");
				$card_category = $this->input->post("card_category");
				$card_type = $this->input->post("card_type");
				$cardName = $this->input->post("cardName");
				

				try {
				
				// Configurar tu API Key y autenticación
					$SECRET_KEY = "sk_live_070b7eaeb41152ee";
					$culqi = new Culqi\Culqi(array('api_key' => $SECRET_KEY));
				
					// Creando Cargo a una tarjeta
					$charge = $culqi->Charges->create(
						array(
							"amount" => $monto,
							"capture" => true,
							"currency_code" => "PEN",
							"description" => $descripcion,
							"installments" => 0,
							"email" => $email,
							//"metadata" => array("test"=>"test"),
							"source_id" => $token
						)
					);
				
					//insertar pago
					$datosPago = array(
						'idUsuario' => $user,
						'token' => $token,
						'monto' => $monto/100,
						'card_number' => $card_number,
						'last_four' => $last_four,
						'ip_country_code_realizado' => $ip_country_code_realizado,
						'card_brand' => $card_brand,
						'card_category' => $card_category,
						'card_type' => $card_type,
						//pago
						'creation_date' => date('Y-m-d H:i:s', $charge->creation_date/1000),
						'token_pay' => $charge->id,
						'reference_code' => $charge->reference_code,
						'authorization_code' => $charge->authorization_code,
						'idUsuarioCreacion' => $this->session->userdata('idUsuario')
					);
		
					$this->db->insert('pago', $datosPago);
					$idPago = $this->db->insert_id();
		
					//insertar cita
					$datosCita = array(
						'idUsuario' => $user,
						'tipoCita' => $tipoCita,
						'fechaCita' => $fechaCita,
						'horaCita' => $horaCita,
						'idEspecialidad' => $idEspecialidad,
						'idMedico' => $idMedico,
						'idPago' => $idPago,
						'motivo' => $motivo,
						'tipoComprobante' => $tipoComprobante,
						'nombreCompleto' => $nombreCompleto,
						'razonSocial' => $razonSocial,
						'ruc' => $ruc,
						'direccionruc' => $direccionruc,
						'direccion' => $direccion,
						'sucursal' => $sucursal,
						'idAvailability' => $idDisponible,
						'idDistrito' => $distrito,
						'virtual' => 1,
						'idMotivoCita' => $idMotivoCita,
						'idProcedimiento' => $procedimiento,
						'idUsuarioCreacion' => $this->session->userdata('idUsuario')
					);
		
					$this->db->insert('cita', $datosCita);
					
					if($this->db->affected_rows() == 1)
					{
						$parametros = array (
							"disponible" => 0
						);
		
						$this->db->where('idAvailability', $idDisponible);
						$this->db->where('disponible', 1);
						$this->db->update('availabilities', $parametros);
					}
		
					//enviar email
		
					$subject = "Confirmación de la cita";
					$data['fechaCita'] = $fechaCita;
					$data['horaCita'] = $horaCita;
					$data['medico'] = $medicoNombre;
					$data['especialista'] = $especialidad;
					$data['reference_code'] = $charge->reference_code;
					$data['tipoConsulta'] = $nombreCita[$tipoCita];
					
					//$dataUsuario['usuario'] = $this->Usuario->datosUsuario($user);
					$data['usuario'] = $this->Usuario->datosUsuario($user);
					$data["config"] = $this->Helper->configuracion();
			
					$this->data['contenido'] = $this->load->view('confirmacion_pago_cita_virtual', $data, TRUE);
			
					$message = $this->load->view('mensaje', $this->data, TRUE);
		
					$config['protocol'] = 'sendmail';
					$config['mailpath'] = '/usr/sbin/sendmail';
					$config['charset']   = 'utf-8';
					$config['mailtype']  = 'html';
					$config['wordwrap'] = TRUE;
					$config['priority'] = 1;
		
					$this->load->library('email', $config);
		
					$this->email->set_newline("\r\n");
					$this->email->from('info@sbcmedic.com', "SBCMedic");
					$this->email->to("lacalderonc80@gmail.com");
					//$this->email->to($email);
					if(!is_null($emailMedico))	$this->email->cc(array($emailMedico, "atencionalcliente@sbcmedic.com"));
					
					$this->email->subject($subject);
					$this->email->message($message);
		
					$this->email->send();
					
				// Respuesta
				echo json_encode($charge);
				
				} catch (Exception $e) {
				echo $e->getMessage();
				}
			} else {
				//insertar pago
				$datosPago = array(
					'idUsuario' => $user,
					'monto' => $monto,
					'gratis' => $gratis,
					'idUsuarioCreacion' => $this->session->userdata('idUsuario'),
					'status' => 0
				);
	
				$this->db->insert('pago', $datosPago);
				$idPago = $this->db->insert_id();
				
				//insertar cita
				$datosCita = array(
					'idUsuario' => $user,
					'tipoCita' => $tipoCita,
					'fechaCita' => $fechaCita,
					'horaCita' => $horaCita,
					'idEspecialidad' => $idEspecialidad,
					'idMedico' => $idMedico,
					'idPago' => $idPago,
					'motivo' => $motivo,
					'fechaCreacion' => date('Y-m-d H:i:s'),
					'tipoComprobante' => $tipoComprobante,
					'nombreCompleto' => $nombreCompleto,
					'razonSocial' => $razonSocial,
					'ruc' => $ruc,
					'direccionruc' => $direccionruc,
					'direccion' => $direccion,
					'sucursal' => $sucursal,
					'idAvailability' => $idDisponible,
					'idDistrito' => $distrito,
					'virtual' => $this->input->post("virtual"),
					'gratis' => $this->input->post("gratis"),
					'idMotivoCita' => $idMotivoCita,
					'codigo_procedimiento' => $procedimiento,
					'adicional' => $this->input->post("adicional"),
					'idUsuarioCreacion' => $this->session->userdata('idUsuario')
				);

				$this->db->insert('cita', $datosCita);

				$idCita = $this->db->insert_id();
				if($idCita > 0)
				{

					//enviar email
					
					if($this->input->post("virtual") == 1)	$tipoCita= "CV";

					$subject = "Confirmación de la cita";
					$data['fechaCita'] = $fechaCita;
					$data['horaCita'] = $horaCita;
					$data['medico'] = $medicoNombre;
					$data['especialista'] = $especialidad;
					$data['tipoConsulta'] = $nombreCita[$tipoCita];
					$data['reference_code'] = false;
					
					$data['monto'] = $monto;

					$data['usuario'] = $this->Usuario->datosUsuario($user);
			
					$this->data['contenido'] = $this->load->view('confirmacion_pago_cita_virtual', $data, TRUE);
			
					$message = $this->load->view('mensaje', $this->data, TRUE);


					$config['protocol'] = 'sendmail';
					$config['mailpath'] = '/usr/sbin/sendmail';
					$config['charset']   = 'utf-8';
					$config['mailtype']  = 'html';
					$config['wordwrap'] = TRUE;
					$config['priority'] = 1;

					$this->load->library('email', $config);

					$this->email->set_newline("\r\n");
					$this->email->from('info@sbcmedic.com', "SBCMedic");
					$this->email->to($data['usuario']["email"]);
					//if(!is_null($emailMedico))	$this->email->cc(array($emailMedico, "atencionalcliente@sbcmedic.com"));
					$this->email->subject($subject);
					$this->email->message($message);

					//if(!$this->input->post("adicional"))	$this->email->send();
 
					$response['message'] = 'Cita registrado satisfactoriamente.';
					$response['status'] = true;
					$response['idCita'] = $idCita;
				} else {
					$response['message'] = 'Cita no registrado';
					$response['status'] = false;
				}

				$this->output->set_content_type( 'application/json' )->set_output(json_encode($response));

			}
		} else {
			if($token) { 
				echo "!Horario no disponible¡";
			} else {
				$response['message'] = "!Horario no disponible¡";
				$response['status'] = false;

				$this->output->set_content_type( 'application/json' )->set_output(json_encode($response));
			} 
		}
	}
	
	public function procesarPagoUnico2()
    {
		$this->validarSesion();

		$idDisponible = $this->input->post("idDisponible");
		$procedimiento = $this->input->post("procedimiento");

		$this->db->select("count(*) as disponible");
		$this->db->from("availabilities");
		$this->db->where("disponible", 1);
		$this->db->where("idAvailability", $idDisponible);
		
		$query = $this->db->get();
		$row_resultado = $query->row_array();

		$disponible = $row_resultado['disponible'];
		$tipoCita = $this->input->post("tipoCita");
 
		$token = $this->input->post("token");
		$nombreCita["CV"] = "Cita Virtual";
		$nombreCita["CP"] = "Cita Presencial";
		$nombreCita["CD"] = "Cita Domicilio";
		$nombreCita["PR"] = "Cita Procedimiento";
		$nombreCita["CPB"] = "Cita Policlínico  Barranco";

		$user = ($this->input->post("user") && $this->input->post("user") !="" && $this->input->post("user") > 0)? $this->input->post("user") : $this->session->userdata('idUsuario');

		$sucursal = ($this->input->post("sucursal") && $this->input->post("sucursal") !="" && $this->input->post("sucursal") > 0)? $this->input->post("sucursal") : 1;

		
		$fechaCita = $this->input->post("fechaCita");
		$horaCita = $this->input->post("horaCita");
		$motivo = $this->input->post("motivo");
		$idMedico = $this->input->post("idMedico");
		$idMotivoCita = $this->input->post("idtipoCita");
		
		$idEspecialidad = $this->input->post("idEspecialidad");
		$monto = ($tipoCita == "CPB")? "": $this->input->post("txtMonto");

		$distrito = $this->input->post("distrito");
		$data['tipoCita'] = $tipoCita;

		$tipoComprobante = $this->input->post("tipoComprobante");
		$gratis = $this->input->post("gratis");

		$nombreCompleto = "";
		$razonSocial = "";
		$ruc = "";
		$direccionruc = "";

		if ($tipoComprobante =="BOL") {
			$nombreCompleto = $this->input->post("nombreCompleto");
		} else {
			$razonSocial = $this->input->post("razonSocial");
			$ruc = $this->input->post("ruc");
			$direccionruc = $this->input->post("direccionruc");
		}

		$direccion = $this->input->post("direccion");

		$medico = $this->Doctor->medicoEspecialista($idMedico);
		$medicoNombre = $medico[0]->nombreMedico;
		$especialidad = $medico[0]->especialidad;
		$emailMedico = $medico[0]->email;
		
		$data['codigoSala'] = $medico[0]->codigoSala;

		if($disponible ==1 || $this->input->post("adicional") == 1) {
			if($token)
			{
				$descripcion = $this->input->post("descripcion");
				$card_number = $this->input->post("card_number");
				$creation_date = $this->input->post("creation_date");
				$email = $this->input->post("email");
				$last_four = $this->input->post("last_four");
				$ip_country_code_realizado = $this->input->post("ip_country_code");
				$card_brand = $this->input->post("card_brand");
				$card_category = $this->input->post("card_category");
				$card_type = $this->input->post("card_type");
				$cardName = $this->input->post("cardName");
				

				try {
				
				// Configurar tu API Key y autenticación
					$SECRET_KEY = "sk_live_070b7eaeb41152ee";
					$culqi = new Culqi\Culqi(array('api_key' => $SECRET_KEY));
				
					// Creando Cargo a una tarjeta
					$charge = $culqi->Charges->create(
						array(
							"amount" => $monto,
							"capture" => true,
							"currency_code" => "PEN",
							"description" => $descripcion,
							"installments" => 0,
							"email" => $email,
							//"metadata" => array("test"=>"test"),
							"source_id" => $token
						)
					);
				
					//insertar pago
					$datosPago = array(
						'idUsuario' => $user,
						'token' => $token,
						'monto' => $monto/100,
						'card_number' => $card_number,
						'last_four' => $last_four,
						'ip_country_code_realizado' => $ip_country_code_realizado,
						'card_brand' => $card_brand,
						'card_category' => $card_category,
						'card_type' => $card_type,
						//pago
						'creation_date' => date('Y-m-d H:i:s', $charge->creation_date/1000),
						'token_pay' => $charge->id,
						'reference_code' => $charge->reference_code,
						'authorization_code' => $charge->authorization_code,
						'idUsuarioCreacion' => $this->session->userdata('idUsuario')
					);
		
					$this->db->insert('pago', $datosPago);
					$idPago = $this->db->insert_id();
		
					//insertar cita
					$datosCita = array(
						'idUsuario' => $user,
						'tipoCita' => $tipoCita,
						'fechaCita' => $fechaCita,
						'horaCita' => $horaCita,
						'idEspecialidad' => $idEspecialidad,
						'idMedico' => $idMedico,
						'idPago' => $idPago,
						'motivo' => $motivo,
						'tipoComprobante' => $tipoComprobante,
						'nombreCompleto' => $nombreCompleto,
						'razonSocial' => $razonSocial,
						'ruc' => $ruc,
						'direccionruc' => $direccionruc,
						'direccion' => $direccion,
						'sucursal' => $sucursal,
						'idAvailability' => $idDisponible,
						'idDistrito' => $distrito,
						'virtual' => 1,
						'idMotivoCita' => $idMotivoCita,
						'idProcedimiento' => $procedimiento,
						'idUsuarioCreacion' => $this->session->userdata('idUsuario')
					);
		
					$this->db->insert('cita', $datosCita);
					
					if($this->db->affected_rows() == 1)
					{
						$parametros = array (
							"disponible" => 0
						);
		
						$this->db->where('idAvailability', $idDisponible);
						$this->db->where('disponible', 1);
						$this->db->update('availabilities', $parametros);
					}
		
					//enviar email
		
					$subject = "Confirmación de la cita";
					$data['fechaCita'] = $fechaCita;
					$data['horaCita'] = $horaCita;
					$data['medico'] = $medicoNombre;
					$data['especialista'] = $especialidad;
					$data['reference_code'] = $charge->reference_code;
					$data['tipoConsulta'] = $nombreCita[$tipoCita];
					
					//$dataUsuario['usuario'] = $this->Usuario->datosUsuario($user);
					$data['usuario'] = $this->Usuario->datosUsuario($user);
					$data["config"] = $this->Helper->configuracion();
			
					$this->data['contenido'] = $this->load->view('confirmacion_pago_cita_virtual', $data, TRUE);
			
					$message = $this->load->view('mensaje', $this->data, TRUE);
		
					$config['protocol'] = 'sendmail';
					$config['mailpath'] = '/usr/sbin/sendmail';
					$config['charset']   = 'utf-8';
					$config['mailtype']  = 'html';
					$config['wordwrap'] = TRUE;
					$config['priority'] = 1;
		
					$this->load->library('email', $config);
		
					$this->email->set_newline("\r\n");
					$this->email->from('info@sbcmedic.com', "SBCMedic");
					$this->email->to($email);
					if(!is_null($emailMedico))	$this->email->cc(array($emailMedico, "atencionalcliente@sbcmedic.com"));
					
					$this->email->subject($subject);
					$this->email->message($message);
		
					$this->email->send();
					
				// Respuesta
				echo json_encode($charge);
				
				} catch (Exception $e) {
				echo $e->getMessage();
				}
			} else {
				//insertar pago
				$datosPago = array(
					'idUsuario' => $user,
					'monto' => $monto,
					'gratis' => $gratis,
					'idUsuarioCreacion' => $this->session->userdata('idUsuario'),
					'status' => 0
				);
	
				$this->db->insert('pago', $datosPago);
				$idPago = $this->db->insert_id();
				
				//insertar cita
				$datosCita = array(
					'idUsuario' => $user,
					'tipoCita' => $tipoCita,
					'fechaCita' => $fechaCita,
					'horaCita' => $horaCita,
					'idEspecialidad' => $idEspecialidad,
					'idMedico' => $idMedico,
					'idPago' => $idPago,
					'motivo' => $motivo,
					'fechaCreacion' => date('Y-m-d H:i:s'),
					'tipoComprobante' => $tipoComprobante,
					'nombreCompleto' => $nombreCompleto,
					'razonSocial' => $razonSocial,
					'ruc' => $ruc,
					'direccionruc' => $direccionruc,
					'direccion' => $direccion,
					'sucursal' => $sucursal,
					'idAvailability' => $idDisponible,
					'idDistrito' => $distrito,
					'virtual' => $this->input->post("virtual"),
					'idMotivoCita' => $idMotivoCita,
					'codigo_procedimiento' => $procedimiento,
					'adicional' => $this->input->post("adicional"),
					'idUsuarioCreacion' => $this->session->userdata('idUsuario')
				);

				$this->db->insert('cita', $datosCita);

				$idCita = $this->db->insert_id();
				if($idCita > 0)
				{
					$parametros = array (
						"disponible" => 0
					);

					$this->db->where('idAvailability', $idDisponible);
					$this->db->where('disponible', 1);
					$this->db->update('availabilities', $parametros);

					//enviar email
					
					if($this->input->post("virtual") == 1)	$tipoCita= "CV";

					$subject = "Confirmación de la cita";
					$data['fechaCita'] = $fechaCita;
					$data['horaCita'] = $horaCita;
					$data['medico'] = $medicoNombre;
					$data['especialista'] = $especialidad;
					$data['tipoConsulta'] = $nombreCita[$tipoCita];
					$data['reference_code'] = false;
					
					$data['monto'] = $monto;

					$data['usuario'] = $this->Usuario->datosUsuario($user);
					/*
					if($procedimiento)
					{
						$codigoInterno = null;
						$costo = $this->Helper->consultar_precio($procedimiento, false);
	
						$procedimientosPro = array(
							'idUsuario' => $user,
							'idCita' => $idCita,
							'codigo_procedimiento' => $procedimiento,
							'precio' => $costo["precio"],
							'idUsuarioCreacion' => $this->session->userdata('idUsuario')
						);

						$this->db->trans_start();
						//$this->db->insert("agregar_pago_procedimiento_caja", $procedimientosPro);
						//$idCodigo = $this->db->insert_id();
						//$this->db->trans_complete();
						
						if ($this->db->trans_status()) 
						{
							$parametros = array (
								"monto" => $costo["precio"]
							);

							//$this->db->where('idPago', $idPago);
							//$this->db->update('pago', $parametros);


							if(is_null($codigoInterno)) {
								$codigoInterno = strtoupper(uniqid());
							}
		
							$parametros = array (
								"codigo_interno" =>$codigoInterno
							);
							
							$this->db->where('id', $idCodigo);
							//$this->db->update('agregar_pago_procedimiento_caja', $parametros);
						}
					}*/
			
					$this->data['contenido'] = $this->load->view('confirmacion_pago_cita_virtual', $data, TRUE);
			
					$message = $this->load->view('mensaje', $this->data, TRUE);


					$config['protocol'] = 'sendmail';
					$config['mailpath'] = '/usr/sbin/sendmail';
					$config['charset']   = 'utf-8';
					$config['mailtype']  = 'html';
					$config['wordwrap'] = TRUE;
					$config['priority'] = 1;

					$this->load->library('email', $config);

					$this->email->set_newline("\r\n");
					$this->email->from('info@sbcmedic.com', "SBCMedic");
					$this->email->to($data['usuario']["email"]);
					//if(!is_null($emailMedico))	$this->email->cc(array($emailMedico, "atencionalcliente@sbcmedic.com"));
					$this->email->subject($subject);
					$this->email->message($message);

					if(!$this->input->post("adicional"))	$this->email->send();
					
					$response['message'] = 'Cita registrado satisfactoriamente.';
					$response['status'] = true;
					$response['idCita'] = $idCita;
				} else {
					$response['message'] = 'Cita no registrado';
					$response['status'] = false;
				}

				$this->output->set_content_type( 'application/json' )->set_output(json_encode($response));

			}
		} else {
			if($token) { 
				echo "!Horario no disponible¡";
			} else {
				$response['message'] = "!Horario no disponible¡";
				$response['status'] = false;

				$this->output->set_content_type( 'application/json' )->set_output(json_encode($response));
			} 
		}
	}
	
	public function misCitas()
    {
		$this->validarSesion();
		$this->cargarDatosSesion();
		$result = array();

		if($this->Helper->permiso_usuario("gestionar_citas"))
		{
			$this->db->select("c.gratis, c.virtual, c.idCita, c.idUsuario, c.idAvailability, c.idPago, if(c.idPago, pg.status, IF((select count(id) as cantidad from solicitud_citas_pagos where codigo_asignacion= c.codigo_asignacion and pago= 1) > 0 , 1, 0)) as statusPago, c.tipoCita as idCitaTipo, if(`c`.`tipoCita` ='CV' || `c`.virtual = 1, 'Virtual', if(`c`.`tipoCita` ='CP', 'Presencial', if(`c`.`tipoCita` ='PR', 'Procedimiento', 'Domiciliario'))) as tipoCita, c.fechaCita, c.horaCita, c.motivo, c.status, c.fechaCreacion, concat(d.firstname, ' ', d.lastname) as medico, s.name as especialidad, SUBSTRING(p.document, 1, 4) as codigoSala,  p.phone,
			
			(SELECT CONCAT(p.address, ' - ', ubigeo_districts.`name`) as direccion from ubigeo_districts  WHERE ubigeo_districts.id=p.idDistrict) as address,

			 

			concat( p.firstname, ' ', p.lastname ) AS paciente, /*DATE_ADD(NOW(), INTERVAL 2 HOUR)*/ TIME_FORMAT(TIMEDIFF(DATE_ADD(NOW(), INTERVAL 2 HOUR), DATE_SUB(concat(fechaCita , ' ', SUBSTR(horaCita,1,5), ':00'),INTERVAL 10 MINUTE)), '%H%i' )*1 as tiempoVideo, p.document,  IF
			(
				c.codigo_procedimiento is not null,
				`pro`.`descripcion`,
				( SELECT  GROUP_CONCAT(procedimientos.descripcion) FROM historial_asignacion_cita inner join procedimientos on procedimientos.codigo_interno = historial_asignacion_cita.codigo_procedimiento WHERE  historial_asignacion_cita.idCita = c.idCita ) 
			) AS nomProcedimiento ");
		
			$this->db->from('cita c');
			$this->db->join('doctors d', 'd.idDoctor = c.idMedico');
			$this->db->join('specialties s', 's.idSpecialty = c.idEspecialidad');
			$this->db->join('patients p', 'p.idUsuario = c.idUsuario');
			$this->db->join('pago pg', 'pg.idPago = c.idPago', "left");
			//$this->db->join('motivo_cita mc', 'mc.id = c.idMotivoCita', "LEFT");
			$this->db->join('procedimientos pro', 'pro.codigo_interno = c.codigo_procedimiento', "left");
	
			if(!$this->input->post("fecha") and !$this->input->post("cliente") and !$this->input->post("cmbmedico")) {
				if($this->session->userdata('rol') == 2 || $this->session->userdata('rol') == 3)
					$this->db->where("c.fechaCita >= date(NOW())");
				else
					$this->db->where("c.fechaCita = date(NOW())");
			} 
			
			if ($this->session->userdata('rol') == 2) {
			 	$this->db->where('d.idUsuario', $this->session->userdata('idUsuario'));
			} 
			
			if ($this->session->userdata('rol') == 3) {
				$this->db->where('c.idUsuario', $this->session->userdata('idUsuario'));
			}
			
			if($this->input->post("cliente")) {
				$this->db->where('c.idUsuario', $this->input->post("cliente"));
			}

			if($this->input->post("fecha")) {
				$this->db->where('c.fechaCita', date("Y-m-d", strtotime($this->input->post("fecha"))));
			}

			if($this->input->post("cmbmedico")) {
				$this->db->where('c.idMedico', $this->input->post("cmbmedico"));
			}
			
			$this->db->where('c.status', 1);
			$this->db->order_by("c.fechaCita", "ASC");
			$this->db->order_by("c.fechaCreacion", "ASC");
			
			$query = $this->db->get()->result();
			
			if ($query) {
				foreach($query as $row)
				{
					$this->db->select("cm.descripcion");
					$this->db->from("historial_asignacion_cita hac");
					$this->db->join('procedimientos p', 'p.codigo_interno = hac.codigo_procedimiento');
					$this->db->join('catalogo_motivocita cm', 'cm.id = p.idMotivocita');
					$this->db->where("hac.idCita", $row->idCita);
	 
				   
					$query = $this->db->get();
					$row_resultadoDisponible = $query->row_array();
					
					$result[]   = array(
					'idCita'	=> htmlspecialchars($row->idCita, ENT_QUOTES),
					'idCitaTipo'	=> htmlspecialchars($row->idCitaTipo, ENT_QUOTES),
					'idUsuario'	=> htmlspecialchars($row->idUsuario, ENT_QUOTES),
					'tipoCita'	=> htmlspecialchars($row->tipoCita, ENT_QUOTES),
					'fechaCita'	=> htmlspecialchars($row->fechaCita, ENT_QUOTES),
					'horaCita'	=> htmlspecialchars($row->horaCita, ENT_QUOTES),
					'especialidad'	=> htmlspecialchars($row->especialidad, ENT_QUOTES),
					'medico'	=> htmlspecialchars($row->medico, ENT_QUOTES),
					'motivo'	=> htmlspecialchars($row->motivo, ENT_QUOTES),
					'status'	=> htmlspecialchars($row->status, ENT_QUOTES),
					'paciente'	=> htmlspecialchars($row->paciente, ENT_QUOTES),
					'idPago'	=> htmlspecialchars($row->idPago, ENT_QUOTES),
					'statusPago'	=> htmlspecialchars($row->statusPago, ENT_QUOTES),
					'idAvailability'	=> htmlspecialchars($row->idAvailability, ENT_QUOTES),
					'tiempoVideo'	=> htmlspecialchars($row->tiempoVideo, ENT_QUOTES),
					'codigoSala'	=> htmlspecialchars($row->codigoSala, ENT_QUOTES),
					'address'	=> htmlspecialchars($row->address, ENT_QUOTES),
					'phone'	=> htmlspecialchars($row->phone, ENT_QUOTES),
					'virtual'	=> htmlspecialchars($row->virtual, ENT_QUOTES),
					'document'	=> htmlspecialchars($row->document, ENT_QUOTES),
					'motivoTipoCita'	=> htmlspecialchars($row_resultadoDisponible['descripcion'], ENT_QUOTES),
					'nomProcedimiento'	=> htmlspecialchars($row->nomProcedimiento, ENT_QUOTES),
					'gratis'	=> htmlspecialchars($row->gratis, ENT_QUOTES),
					'fechaCreacion'	=> htmlspecialchars($row->fechaCreacion, ENT_QUOTES)
					);
				}
				}
		
			$this->data['resultados']  = $result;
			$this->data['permisoCancelarCita']  = $this->Helper->permiso_usuario("cancelar_cita");
			$this->data['permisoVidoLlamada']  = $this->Helper->permiso_usuario("video_llamada");
			$this->data['cerrarCita']  = $this->Helper->permiso_usuario("guardar_historia_clinica");
			$this->data['realizarPago']  = $this->Helper->permiso_usuario("cambiar_status_pago");
			$this->data['consultarCita']  = $this->Helper->permiso_usuario("filtro_busqueda_cita");
			$this->data['cerrarCitaRapida']  = $this->Helper->permiso_usuario("cerrar_cita");
			$this->data['verStatusPago']  = $this->Helper->permiso_usuario("ver_status_pago");
			
			$this->data['medicos'] = $this->Doctor->all();
			
			$this->db->select("id, descripcion");
			$this->db->from('catalogo_motivo_cancelacion');
			$this->db->where("activo", 1);
			$this->db->order_by("id", "asc");
	
			$this->data["motivosCancelaciones"] = $this->db->get()->result();

			$this->load->view('mis_citas', $this->data);
		} else {
			
			redirect(base_url("inicio"));
		}
	}

	
	public function miHistorial()
    {
		$this->validarSesion();

		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("gestionar_historial"))
		{
			$this->load->model('Helper');
			$result = array();
			if($this->input->post("cmbmedico") || $this->input->post("cliente") || $this->input->post("fecha") || $this->session->userdata('rol') == 3 || $this->session->userdata('rol') == 2) {
			
			$usuarioMedico = $this->Doctor->medico_unico($this->session->userdata('idUsuario'));

			$this->db->select("(SELECT COUNT(*) FROM calificacion where calificacion.idUsuario=". $this->session->userdata('idUsuario') ." and calificacion.idMedico = c.idMedico AND calificacion.idCita = c.idCita) as calificado, c.envioBolFac, c.idCita, if(`c`.`tipoCita` ='CV' || `c`.virtual = 1, 'Virtual', if(`c`.`tipoCita` ='CP', 'Presencial', if(`c`.`tipoCita` ='PR', 'Procedimiento', 'Domiciliario'))) as tipoCita, c.fechaCita, c.horaCita, c.motivo, c.status, c.fechaCreacion, concat(d.firstname, ' ', d.lastname) as medico, d.idDoctor, s.name as especialidad, (SELECT CONCAT(p.address, ' - ', ubigeo_districts.`name`) as direccion from ubigeo_districts  WHERE ubigeo_districts.id=p.idDistrict) as address, p.phone, p.email, concat( p.firstname, ' ', p.lastname ) AS paciente, c.idUsuario, p.document ,  c.idMedico");
	
			$this->db->from('cita c');
			$this->db->join('doctors d', 'd.idDoctor = c.idMedico');
			$this->db->join('specialties s', 's.idSpecialty = c.idEspecialidad');
			$this->db->join('patients p', 'p.idUsuario = c.idUsuario');
			//$this->db->join('motivo_cita mc', 'mc.id = c.idMotivoCita', "LEFT");
			$this->db->where('c.status', 0);

			if ($this->session->userdata('rol') == 2 and (empty($this->input->post("cliente")) and empty($this->input->post("fecha")) and empty($this->input->post("cmbmedico")))) {
				$this->db->where('d.idUsuario', $this->session->userdata('idUsuario'));
			}
			
			if ($this->session->userdata('rol') == 3) {
				$this->db->where('c.idUsuario', $this->session->userdata('idUsuario'));
			}

			if($this->input->post("cliente")) {
				$this->db->where('c.idUsuario', $this->input->post("cliente"));
			}

			if($this->input->post("fecha")) {
				
				if ($this->session->userdata('rol') == 2) {
 
					$this->db->where('c.idMedico',  $usuarioMedico["idDoctor"]);
				}
				
				$this->db->where('c.fechaCita', date("Y-m-d", strtotime($this->input->post("fecha"))));
			}

			if($this->input->post("cmbmedico")) {
				$this->db->where('c.idMedico', $this->input->post("cmbmedico"));
			}


			$this->db->order_by("c.fechaCita", "desc");
	
			$query = $this->db->get()->result();
			
	
			if ($query) {
				foreach($query as $row)
				{
					$this->db->select("idUsuario");
					$this->db->from("doctors");
					$this->db->where("idDoctor", $row->idMedico);
				 
					$query = $this->db->get();
					$row_resultadoMedico = $query->row_array();
					if($row_resultadoMedico['idUsuario'] == $this->session->userdata('idUsuario'))
					{
						$readonly = "0";
					} else {
						$readonly = "true";
					}
					
										$this->db->select("cm.descripcion");
					$this->db->from("historial_asignacion_cita hac");
					$this->db->join('procedimientos p', 'p.codigo_interno = hac.codigo_procedimiento');
					$this->db->join('catalogo_motivocita cm', 'cm.id = p.idMotivocita');
					$this->db->where("hac.idCita", $row->idCita);
	 
				   
					$query = $this->db->get();
					$row_resultadoDisponible = $query->row_array();
					
					
				   $result[]   = array(
					'idCita'	=> htmlspecialchars($row->idCita, ENT_QUOTES),
					'tipoCita'	=> htmlspecialchars($row->tipoCita, ENT_QUOTES),
					'fechaCita'	=> htmlspecialchars($row->fechaCita, ENT_QUOTES),
					'horaCita'	=> htmlspecialchars($row->horaCita, ENT_QUOTES),
					'especialidad'	=> htmlspecialchars($row->especialidad, ENT_QUOTES),
					'idDoctor'	=> htmlspecialchars($row->idDoctor, ENT_QUOTES),
					'medico'	=> htmlspecialchars($row->medico, ENT_QUOTES),
					'calificacion'	=>  htmlspecialchars($this->Helper->promedioCalificacion($row->idDoctor)),
					'calificado'	=> htmlspecialchars($row->calificado, ENT_QUOTES),
					'motivo'	=> htmlspecialchars($row->motivo, ENT_QUOTES),
					'status'	=> htmlspecialchars($row->status, ENT_QUOTES),
					'paciente'	=> htmlspecialchars($row->paciente, ENT_QUOTES),
					'idUsuario'	=> htmlspecialchars($row->idUsuario, ENT_QUOTES),
					'envioBolFac'	=> htmlspecialchars($row->envioBolFac, ENT_QUOTES),
					'address'	=> htmlspecialchars($row->address, ENT_QUOTES),
					'phone'	=> htmlspecialchars($row->phone, ENT_QUOTES),
					'email'	=> htmlspecialchars($row->email, ENT_QUOTES),
					'document'	=> htmlspecialchars($row->document, ENT_QUOTES),
					'motivoTipoCita'	=> htmlspecialchars($row_resultadoDisponible["descripcion"], ENT_QUOTES),
					'readonly'	=> htmlspecialchars($readonly, ENT_QUOTES),
					'fechaCreacion'	=> htmlspecialchars($row->fechaCreacion, ENT_QUOTES)
					);
				}
			 }
			}
			
			$this->data['resultados']  = $result;
			$this->data['descargarHClinica']  = $this->Helper->permiso_usuario("descargar_historia_clinica");
			$this->data['consultarCita']  = $this->Helper->permiso_usuario("filtro_busqueda_cita");
			$this->data['envioBolFac']  = $this->Helper->permiso_usuario("envio_bol_fac");
			$this->data['cerrarCita']  = $this->Helper->permiso_usuario("guardar_historia_clinica");
			$this->data['actualizarHClinica']  = $this->Helper->permiso_usuario("actualizar_historial_clinica");
			
			$this->data['medicos'] = $this->Doctor->all_medico();
			
			if($this->input->post("cliente")) {

				$this->db->select("se.idExamen, se.idUsuario, se.fechaExamen, se.numeroPedido, exa.nombre as examen, exa.tipo, se.estado, se.codigo_interno, se.idUsuario");
				$this->db->from('solicitarexamen se');
				$this->db->join('examen exa', 'exa.id = se.idExamen');
				$this->db->where("se.idUsuario", $this->input->post("cliente"));
				$this->db->order_by("se.fechaExamen", "DESC");
				
			
		
				$this->data["resultadosLab"] = $this->db->get()->result();

			}

			$this->load->view('mis_historial', $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}
	
	public function editarHistory()
	{
		$idCita = $this->input->get("idCita");

		$response["historialMedico"] = $this->Helper->historialMedico($idCita);
		$response["examenFisicoCita"] = $this->Helper->examenFisicoCita($idCita);
		$response["citaDiagnostico"] = $this->Helper->citaDiagnosticoPdf($idCita, true);
		$response["ptratamiento"] = $this->Helper->planTratamiento($idCita, true);
		$response["dataReceta"] = $this->Helper->citaRecetaPdf($idCita, true);
		$response["dataExamenM"] = $this->Helper->citaExamenMPdf($idCita, true);

		if($response["historialMedico"]["idCita"] > 0)	$response['status'] = true; else $response['status'] = false;

		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function grabarCalificacion()
	{
		$this->validarSesion();

		$this->form_validation->set_rules('rate', 'Ingrese la calificacion', 'required');        

		if ($this->form_validation->run() == FALSE)
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {

			$datosCalificacion = array(
				'idUsuario' => $this->session->userdata('idUsuario'),
				'idMedico' => $this->input->post("idMedico"),
				'idCita' => $this->input->post("idCita"),
				'valor' => $this->input->post("rate"),
				'observacion' => $this->input->post("txtObservacion")
			);

			$this->db->trans_start();
			$this->db->insert('calificacion', $datosCalificacion);
			$this->db->trans_complete();

			if ($this->db->trans_status() === true)
			{
				$response['message'] = "Se grabo la calificación correctamente.";
				$response['status'] = true;
			} else {
				$response['message'] = "Error. No se registro.";
				$response['status'] = false;
			}
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function cerrarCita()
	{
		$this->validarSesion();
 
		if ($this->input->post("idCitaAdd") < 1)
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {
			$idCita = $this->input->post("idCitaAdd");

			$parametros = array(
				'status' => 0,
				'idUsuarioCierre' => $this->session->userdata('idUsuario'),
				'fechaUsuarioCierre' => date("Y-m-d H:m:s"),
			);

			$this->db->trans_start();
			$this->db->where('status', 1);
			$this->db->where('idCita', $idCita);
			$this->db->update('cita', $parametros);
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
				if($this->input->post("recetas")) {
					if(count(array_filter($this->input->post("recetas"))) > 0) {
						for ($i=0; $i < count(array_filter($this->input->post("recetas"))); $i++) { 

							$parametrosReceta = array(
								'idCita' => $idCita,
								//'nombre' => $this->input->post("nombre")[$i],
								//'presentacion' => $this->input->post("presentacion")[$i],
								'cantidad' => $this->input->post("cantidad")[$i],
								'via' => $this->input->post("via")[$i],
								'dosificacion' => $this->input->post("dosificacion")[$i],
								'tiempo_tratamiento' => $this->input->post("tiempo_tratamiento")[$i],
								'idReceta' => $this->input->post("recetas")[$i],
								'idUsuario' => $this->session->userdata('idUsuario')
							);

							$this->db->insert("receta_cita", $parametrosReceta);
						}
					} 
				} 

				if(count(array_filter($this->input->post("nombretwo"))) > 0) {
					for ($i=0; $i < count(array_filter($this->input->post("nombretwo"))); $i++) { 

						$parametrosReceta = array(
							'idCita' => $idCita,
							'nombre' => $this->input->post("nombretwo")[$i],
							'presentacion' => $this->input->post("presentaciontwo")[$i],
							'cantidad' => $this->input->post("cantidadtwo")[$i],
							'via' => $this->input->post("viatwo")[$i],
							'dosificacion' => $this->input->post("dosificaciontwo")[$i],
							'tiempo_tratamiento' => $this->input->post("tiempottwo")[$i],
							'idUsuario' => $this->session->userdata('idUsuario')
						);

						$this->db->insert("receta_cita", $parametrosReceta);
					}
				}
 
				if($this->input->post("diagnostico")) {
					if(count($this->input->post("diagnostico"))) {
						for ($i=0; $i < count(array_filter($this->input->post("diagnostico"))); $i++) { 

							$cieMedico= array(
								'idCita' => $idCita,
								'idCie' => $this->input->post("diagnostico")[$i],
								'tipo' => $this->input->post("tipoDiagnostico")[$i],
								'idUsuario' => $this->session->userdata('idUsuario')
							);

							$this->db->insert("cie_cita", $cieMedico);
						}
					}
				}

				$antecedentePatologico = ($this->input->post("antecedentePatologico") == "on")? "1" : "0";
				$antecedesFamiliar = ($this->input->post("antecedesFamiliar") == "on")? "1" : "0";
				$relacionesam = ($this->input->post("relacionesam") == "on")? "1" : "0";
				
				$this->load->model('Helper');

				$cantidad = $this->Helper->numeroHistorialClinica($this->input->post("idUsuario"));
 
				$cieMedico= array(
					'idCita' => $idCita,
					'tiempo_enfermedad' => $this->input->post("tiempoEnfermedad"),
					'relato' => $this->input->post("relato"),
					'funciones_biologicas_comentario' => $this->input->post("fbiologicasComentario"),
					'normales' => $this->input->post("normales"),
					'antecedes_patologico' => $antecedentePatologico,
					'otros_antecedentesp' => $this->input->post("otrosap"),
					'antecedes_patologico_dislipidemia' => $this->input->post("dislipidemia"),
					'antecedes_patologico_diabestes' => $this->input->post("diabetes"),
					'antecedes_patologico_hta' => $this->input->post("hta"),
					'antecedes_patologico_asma' => $this->input->post("asma"),
					'antecedes_patologico_gastritis' => $this->input->post("gastritis"),
					'antecedes_familiar' => $antecedesFamiliar,
					'otros_antecedentesf' => $this->input->post("otrosaf"),
					'relaciones_adversas' => $relacionesam,
					'medicamentos' => $this->input->post("medicamentos"),
					'otros_medicamentos' => $this->input->post("otrosMedicamentos"),
					'medicamentoHabitual' => $this->input->post("medicamentosHabituales"),
					'numeroCorrelativo' => $cantidad["cantidad"],
					'recomendaciones' => $this->input->post("recomendaciones"),
					'fur' => $this->input->post("fur"),
					'rc' => $this->input->post("rc"),
					'gp' => $this->input->post("gp"),
					'mac' => $this->input->post("mac"),
					'antecedente_quirurgico' => $this->input->post("antecedenteQuirurgico"),
					'idUsuario' => $this->session->userdata('idUsuario')
				);

				$this->db->insert("historial_cita", $cieMedico);

				$parametrosExamenfCita = array(
					'idCita' => $idCita,
					'pa' => $this->input->post("pa"),
					'fc' => $this->input->post("fc"),
					'fr' => $this->input->post("fr"),
					'tt' => $this->input->post("tt"),
					'sato' => $this->input->post("sato"),
					'peso' => $this->input->post("peso"),
					'talla' => $this->input->post("talla"),
					'egeneral' => $this->input->post("egenarl"),
					'idUsuario' => $this->session->userdata('idUsuario')
				);

				$this->db->insert("examenfisico_cita", $parametrosExamenfCita);

				
				if(count(array_filter($this->input->post("planTratamiento"))) > 0) {
					for ($i=0; $i < count(array_filter($this->input->post("planTratamiento"))); $i++) { 

						$planTratamiento = array(
							'idCita' => $idCita,
							'descripcion' => $this->input->post("planTratamiento")[$i],
							'observacion' => $this->input->post("observaciones"),
							'idUsuario' => $this->session->userdata('idUsuario')
						);

						$this->db->insert("plantratamiento_cita", $planTratamiento);
					}

				}

				if($this->input->post("descansoMedico") == "on"){
					$descandoMedico = array(
						'idCita' => $idCita,
						'descripcionTipo' => $this->input->post("tipoDescanso"),
						'dias' => $this->input->post("dias"),
						'del' => date("Y-m-d",strtotime($this->input->post("fechaDel"))),
						'al' => date("Y-m-d",strtotime($this->input->post("fechaAl"))),
						'idUsuario' => $this->session->userdata('idUsuario')
					);

					$this->db->insert("descansomedico_cita", $descandoMedico);
				}
				
				//interconsultas
				if($this->input->post("interconsultas")) {
					if(count($this->input->post("interconsultas"))) {
						for ($i=0; $i < count(array_filter($this->input->post("interconsultas"))); $i++) { 

							$cieMedico= array(
								'idCita' => $idCita,
								'idEspecialidad' => $this->input->post("interconsultas")[$i],
								'idUsuario' => $this->session->userdata('idUsuario')
							);

							$this->db->insert("interconsultas_cita", $cieMedico);
						}
					}
				}

				//procedimientos
				if($this->input->post("procedimiento")) {
		
					if(count($this->input->post("procedimiento"))) {
		
		
					$datos = array_filter($this->input->post("procedimiento"));
					asort($datos);
					$codigoInterno = null;

						for ($i=0; $i < count($datos); $i++) {
							
							$procedimiento = explode("|", $this->input->post("procedimiento")[$i]);
		 
							$examenAuxi = array(
								'idCita' => $idCita,
								'codigoTipo' => $procedimiento[1],
								'tipo' => $procedimiento[0],
								'especificaciones' => $this->input->post("especificaciones")[$i],
								'idUsuario' => $this->session->userdata('idUsuario')
							);
		
							$this->db->insert("examenauxiliar_cita", $examenAuxi);
							$idExamenAuxi = $this->db->insert_id();

							if(is_null($codigoInterno)) {
								$codigoInterno = strtoupper(uniqid());
							}

								$opcion = ($procedimiento[0] == "LAB")? true: false;
								$costo = $this->Helper->consultar_precio($procedimiento[1], $opcion);
			
								$procedimientosPro = array(
									'idUsuario' => $this->input->post("idUsuario"),
									'codigo_procedimiento' => $procedimiento[1],
									'marca_cita' => 2,
									'tipo_solicitud' => $procedimiento[0],
									'idExamenAuxiliar' => $idExamenAuxi,
									'precio' => $costo["precio"],
									'norden' => $codigoInterno,
									'idUsuarioCreacion' => $this->session->userdata('idUsuario')
								);
		
								$this->db->insert("solicitud_citas_pagos", $procedimientosPro);
							 

							$idExamenAuxi = "";
						}
					}
				}
		 

				$this->data['paciente'] = $this->Usuario->datosUsuario($this->input->post("idUsuario"));
 
				//$nombreArchivoPdf = str_replace(" ", "_", $this->data['paciente']["paciente"])."-".date("Y-m-d-His").".pdf";
				//$this->descargarPdf($idCita, $nombreArchivoPdf, $this->input->post("idUsuario"), false, true, "files/recetas");

				//$this->sendMail($this->data['paciente'], "Resultado del Examen Médico", "files/recetas/".$nombreArchivoPdf, "mail/envio_examen_medico");

				$response['message'] = "Se guardo la cita correctamente.";
				$response['status'] = true;
			} else {
				$response['message'] = "Error. No se guardo la cita.";
				$response['status'] = false;
			}
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function updateCita()
	{
		$this->validarSesion();
		
		$idCita = $this->input->post("idCitaUp");

		if ($idCita < 1)
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {
			

			$parametros = array(
				'usuarioActualizacion' => $this->session->userdata('idUsuario'),
				'fechaActualizacion' => date("Y-m-d H:m:s"),
			);

			$this->db->trans_start();
			$this->db->where('status', 0);
			$this->db->where('idCita', $idCita);
			$this->db->update('cita', $parametros);
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
				$this->db->where('idCita', $idCita);
				$this->db->delete('receta_cita');

				$valor = 0;


				if($this->input->post("recetas")) {
					if(count(array_filter($this->input->post("recetas"))) > 0) {

						if(count($this->input->post("recetas")) < count($this->input->post("cantidad"))) {
							$valor = 1;
						}
	
						for ($i=0; $i < count(array_filter($this->input->post("recetas"))); $i++) { 

							$parametrosReceta = array(
								'idCita' => $idCita,
								'nombre' => $this->input->post("recetas")[$i],
								'presentacion' => $this->input->post("presentacion")[$i + $valor],
								'cantidad' => $this->input->post("cantidad")[$i + $valor],
								'via' => $this->input->post("via")[$i + $valor],
								'dosificacion' => $this->input->post("dosificacion")[$i + $valor],
								'tiempo_tratamiento' => $this->input->post("tiempot")[$i + $valor],
								'idReceta' => $this->input->post("recetas")[$i],
								'idUsuario' => $this->session->userdata('idUsuario')
							);

							$this->db->insert("receta_cita", $parametrosReceta);
						}
					}
				}

				
				if(count(array_filter($this->input->post("nombretwo"))) > 0) {
					for ($i=0; $i < count(array_filter($this->input->post("nombretwo"))); $i++) { 

						$parametrosReceta = array(
							'idCita' => $idCita,
							'nombre' => $this->input->post("nombretwo")[$i],
							'presentacion' => $this->input->post("presentaciontwo")[$i],
							'cantidad' => $this->input->post("cantidadtwo")[$i],
							'via' => $this->input->post("viatwo")[$i],
							'dosificacion' => $this->input->post("dosificaciontwo")[$i],
							'tiempo_tratamiento' => $this->input->post("tiempottwo")[$i],
							'idUsuario' => $this->session->userdata('idUsuario')
						);

						$this->db->insert("receta_cita", $parametrosReceta);
					}
				}
 
				$this->db->where('idCita', $idCita);
				$this->db->delete('cie_cita');

  				if($this->input->post("diagnostico")) {

					$valorT = 0;

					if(count($this->input->post("diagnostico")) < count($this->input->post("tipoDiagnostico"))) {
						$valorT = 1;
					}

					if(count($this->input->post("diagnostico"))) {

						for ($i=0; $i < count(array_filter($this->input->post("diagnostico"))); $i++) { 

							$cieMedico= array(
								'idCita' => $idCita,
								'idCie' => $this->input->post("diagnostico")[$i],
								'tipo' => $this->input->post("tipoDiagnostico")[$i + $valorT],
								'idUsuario' => $this->session->userdata('idUsuario')
							);

							$this->db->insert("cie_cita", $cieMedico);
						}
					}
				}

				$antecedentePatologico = ($this->input->post("antecedentePatologico") == "on")? "1" : "0";
				$antecedesFamiliar = ($this->input->post("antecedesFamiliar") == "on")? "1" : "0";
				$relacionesam = ($this->input->post("relacionesam") == "on")? "1" : "0";
				
				$cieMedico= array(
					'tiempo_enfermedad' => $this->input->post("tiempoEnfermedad"),
					'relato' => $this->input->post("relato"),
					'funciones_biologicas_comentario' => $this->input->post("fbiologicasComentario"),
					'normales' => $this->input->post("normales"),
					'antecedes_patologico' => $antecedentePatologico,
					'otros_antecedentesp' => $this->input->post("otrosap"),
					'antecedes_patologico_dislipidemia' => $this->input->post("dislipidemia"),
					'antecedes_patologico_diabestes' => $this->input->post("diabetes"),
					'antecedes_patologico_hta' => $this->input->post("hta"),
					'antecedes_patologico_asma' => $this->input->post("asma"),
					'antecedes_patologico_gastritis' => $this->input->post("gastritis"),
					'antecedes_familiar' => $antecedesFamiliar,
					'otros_antecedentesf' => $this->input->post("otrosaf"),
					'relaciones_adversas' => $relacionesam,
					'medicamentos' => $this->input->post("medicamentos"),
					'otros_medicamentos' => $this->input->post("otrosMedicamentos"),
					'medicamentoHabitual' => $this->input->post("medicamentosHabituales"),
					'recomendaciones' => $this->input->post("recomendaciones"),
					'fur' => $this->input->post("fur"),
					'rc' => $this->input->post("rc"),
					'gp' => $this->input->post("gp"),
					'mac' => $this->input->post("mac"),
					'antecedente_quirurgico' => $this->input->post("antecedenteQuirurgico")
				);

				$this->db->where('idCita', $idCita);
				$this->db->update('historial_cita', $cieMedico);

				$fields = array(
							"idCita"=> $idCita, 
							"pa"=> $this->input->post("pa"), 
							"fc"=> $this->input->post("fc"),
							"fr"=> $this->input->post("fr"),
							"tt"=> $this->input->post("tt"),
							"sato"=> $this->input->post("sato"),
							"peso"=> $this->input->post("peso"),
							"talla"=> $this->input->post("talla"),
							"egeneral"=> $this->input->post("egenarl"),
							"idUsuario"=> $this->session->userdata('idUsuario')
						);
						
				if($this->input->post("pa") || $this->input->post("fc") || $this->input->post("fr") || 	$this->input->post("tt") || $this->input->post("peso")) {

					$this->Helper->insert_or_update("examenfisico_cita", $fields);
				}				
				
				if(count(array_filter($this->input->post("planTratamiento"))) > 0) {
					$this->db->trans_start();
					$this->db->where('idCita', $idCita);
					$this->db->delete('plantratamiento_cita');
					$this->db->trans_complete();

					if ($this->db->trans_status())
					{

						for ($i=0; $i < count(array_filter($this->input->post("planTratamiento"))); $i++) { 

							$planTratamiento = array(
								'idCita' => $idCita,
								'descripcion' => $this->input->post("planTratamiento")[$i],
								'observacion' => $this->input->post("observaciones"),
								'idUsuario' => $this->session->userdata('idUsuario')
							);

							$this->db->insert("plantratamiento_cita", $planTratamiento);
						}
					}
				}

				
				$this->db->trans_start();
				$this->db->where('idCita', $idCita);
				$this->db->delete('descansomedico_cita');
				$this->db->trans_complete();
				
				if($this->input->post("descansoMedico") == "on"){
				

					if ($this->db->trans_status())
					{
						$descandoMedico = array(
							'idCita' => $idCita,
							'descripcionTipo' => $this->input->post("tipoDescanso"),
							//'dias' => $this->input->post("dias"),
							'del' => date("Y-m-d",strtotime($this->input->post("fechaDel"))),
							'al' => date("Y-m-d",strtotime($this->input->post("fechaAl"))),
							'idUsuario' => $this->session->userdata('idUsuario')
						);

						$this->db->insert("descansomedico_cita", $descandoMedico);
					}
				}

				if ($this->db->trans_status())
				{
					//interconsultas
					if($this->input->post("interconsultas")) {
						if(count($this->input->post("interconsultas"))) {
							for ($i=0; $i < count(array_filter($this->input->post("interconsultas"))); $i++) { 

								$cieMedico= array(
									'idCita' => $idCita,
									'idEspecialidad' => $this->input->post("interconsultas")[$i],
									'idUsuario' => $this->session->userdata('idUsuario')
								);

								//$this->db->insert("interconsultas_cita", $cieMedico);
							}
						}
					}
				}
 
 
				//pro
				if($this->input->post("codigos") > 0) {
					$codigos = "";
					foreach ($this->input->post("codigos") as $value) {
						$codigos = $codigos.$value. ",";
					}

					$codigos = substr($codigos, 0, -1);
								
					$this->db->where("idCita", $idCita);
					$this->db->where("id not in($codigos)");
					$this->db->delete('examenauxiliar_cita');
				}
 
				if(empty($codigos))
				{
					$this->db->where('idCita', $idCita);
					$this->db->delete('examenauxiliar_cita');
				}
				


				if($this->input->post("nombreEm")) {
					if(count(array_filter($this->input->post("nombreEm"))) > 0) {
			
						$valorC = 0;

						if(count($this->input->post("nombreEm")) < count($this->input->post("especificaciones"))) {
							$valorC = 1;
						}

						//$this->db->where('idCita', $idCita);
						//$this->db->delete('examenauxiliar_cita');

						$datos = array_filter($this->input->post("nombreEm"));
						asort($datos);

						$codigoInterno = null;

						for ($i=0; $i < count($datos); $i++) {

							if(is_null($codigoInterno)) {
								$codigoInterno = strtoupper(uniqid());
							}
							
							$procedimiento = explode("|", $this->input->post("nombreEm")[$i]);

							$examenAuxi = array(
								'idCita' => $idCita,
								'codigoTipo' => isset($procedimiento[1]) ? $procedimiento[1] : null,
								'tipo' => ($procedimiento[0] == 'LAB' || $procedimiento[0] == 'PRO') ? $procedimiento[0] : null,
								'nombre' => ($procedimiento[0] == 'LAB' || $procedimiento[0] == 'PRO') ? "" : $this->input->post("nombreEm")[$i],
								'especificaciones' => $this->input->post("especificaciones")[$i + $valorC],
								'idUsuario' => $this->session->userdata('idUsuario')
							);
		
							$this->Helper->insert_or_update("examenauxiliar_cita", $examenAuxi, true, $this->input->post("idUsuario"), $codigoInterno);
						}
					}
				}
				
				$response['message'] = "Se actualizo la cita correctamente.";
				$response['status'] = true;
			} else {
				$response['message'] = "Error. No se actualizo la cita.";
				$response['status'] = false;
			}
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function descargarPdf($idCita, $filename, $idUsuario, $descargar=true, $guardar=false, $ruta="")
    {
		$this->validarSesion();
  
		$this->load->model('Helper');

		$data["dataReceta"] = $this->Helper->citaRecetaPdf($idCita);
		$data["dataExamenM"] = $this->Helper->citaExamenMPdf($idCita);
		$data["diagnostico"] = $this->Helper->citaDiagnosticoPdf($idCita);
		$data['paciente'] = $this->Usuario->datosUsuario($idUsuario);
		$data["infoCita"] = $this->Helper->infoCita($idCita);
		$data["historialMedico"] = $this->Helper->historialMedico($idCita);
		$data["descansoMedico"] = $this->Helper->descansoMedico($idCita);

		$html = $this->load->view('pdf_exports/genera_pdf_muestra', $data, TRUE);

		// Cargamos la librería
		$this->load->library('pdfgenerator');
		// generamos el PDF. Pasemos por encima de la configuración general y definamos otro tipo de papel-Letter
		$this->pdfgenerator->generate($html, $filename, $descargar, "A4", "portrait", $guardar, $ruta);
	}

	
	public function descargarPdfHistorial($idCita, $idUsuario)
    {
		$this->validarSesion();

		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("descargar_historia_clinica"))
		{
			$this->load->model('Helper');

			$data["dataReceta"] = $this->Helper->citaRecetaPdf($idCita);
			$data["dataExamenM"] = $this->Helper->citaExamenMPdf($idCita);
			$data["diagnostico"] = $this->Helper->citaDiagnosticoPdf($idCita);
			$data['paciente'] = $this->Usuario->datosUsuario($idUsuario);
			$data["historialMedico"] = $this->Helper->historialMedico($idCita);
			$data["examenFisico"] = $this->Helper->examenFisicoCita($idCita);
			$data["ptratamiento"] = $this->Helper->planTratamiento($idCita);
			$data["infoCita"] = $this->Helper->infoCita($idCita);
			$data["descansoMedico"] = $this->Helper->descansoMedico($idCita);
	
			$html = $this->load->view('pdf_exports/genera_historial_medico', $data, TRUE);
	
			$filename = 'historial-clinico-'.date("Y-m-d-His"); 
			// Cargamos la librería
			$this->load->library('pdfgenerator');
			// generamos el PDF. Pasemos por encima de la configuración general y definamos otro tipo de papel-Letter
			$this->pdfgenerator->generate($html, $filename, true);
		} else {
			redirect(base_url("inicio"));
		}
	}

	public function examenesMedicos()
    {
		$this->validarSesion();

		$this->cargarDatosSesion();
		
				$this->data["usuarioSearch"] = "";

		if($this->input->post("cmbUsuario"))
		{
			$user = $this->input->post("cmbUsuario");
			$this->data["usuarioSearch"]  = $user;
		} else {
			$user = $this->session->userdata('idUsuario');
		}
		
		if($this->Helper->permiso_usuario("gestionar_solicitar_examenes"))
		{
	 
			$nroDocumento="40633322";


			/* 
			$curl = curl_init();

			curl_setopt_array($curl, array(
				//CURLOPT_URL => "https://demo.orion-labs.com/api/v1/ordenes?filtrar[estado]=P&filtrar[paciente.numero_identificacion]=$nroDocumento",
				CURLOPT_URL => "https://demo.orion-labs.com/api/v1/ordenes?filtrar[paciente.numero_identificacion]=$nroDocumento",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				//CURLOPT_POSTFIELDS => "tkn=test&order_ids=2596%2C2595%2C907%2C035%2C3423%2C65676%2C345%2C125",
				CURLOPT_HTTPHEADER => array(
					'Accept: application/json',
					'Content-Type: application/json',
					'authorization: Bearer u38ARhE71oZBcW8xYK28bCYJv12NY0I2I9A0H9AZnp7KyB0NLdgmzFbFRXGP'
				)
			));
	
			$response = curl_exec($curl);
			$err = curl_error($curl);
	
			curl_close($curl);
	
			if ($err) {
				echo "cURL Error #:" . $err;
			} else {


				$respuestaOrden = json_decode($response);
				$respuestaOrden2 = json_decode($response, true);

				$total = count($respuestaOrden2['data']);

				if ($total >0) {
					for ($i=0; $i < $total; $i++) { 

						$idOrden[$i]= $respuestaOrden->data[$i]->id;
						$numero_orden[$i]= $respuestaOrden->data[$i]->numero_orden;
						$fecha_orden[$i]= $respuestaOrden->data[$i]->fecha_orden;
						$estado[$i]= $respuestaOrden->data[$i]->estado;
					}
				}
				
				$this->data["idOrden"] = $idOrden;
				$this->data["numero_orden"] = $numero_orden;
				$this->data["fecha_orden"] = $fecha_orden;
				$this->data["estado"] = $estado;

				$this->data["cantidad"] = $total;

			} */


 /*
			
			$this->db->select("se.idUsuario, se.fechaExamen, se.numeroPedido, exa.nombre as examen, exa.tipo");
			$this->db->from('solicitarexamen_orion se');
			$this->db->join('examen_orion exa', 'exa.codigo = se.idExamen');
			$this->db->where("se.idUsuario", $user);
			$this->db->order_by("se.fechaExamen", "DESC");
			
			$query = $this->db->get()->result();
			 
			$resultado  =  [];
 
			if (count($query) >0) {
				foreach($query as $row)
				{

					
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://policlinicobarranco.orion-labs.com/api/v1/ordenes?filtrar[numero_pedido]=$row->numeroPedido",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					'Accept: application/json',
					'Content-Type: application/json',
					'authorization: Bearer Dj2StjHonJhddocwyxDtfO4dO72yWbSJUSuJEb489UmYvKhdax3uNjxjt8j0'
				)
			));
	
			$response = curl_exec($curl);
			$err = curl_error($curl);
	
			curl_close($curl);
	
			if ($err) {
				echo "cURL Error #:" . $err;
			} else {
				$idOrden = 0;
				$estado = 0;

				$respuestaOrden = json_decode($response);
				foreach($respuestaOrden->data as $result) {
					$idOrden =  $result->id;
					$estado =  $result->estado;
				}
			}

 
					$resultado[]   = array(
					'idOrden'	=> htmlspecialchars($idOrden, ENT_QUOTES),
					'estado'	=> htmlspecialchars($estado, ENT_QUOTES),
					'fechaExamen'	=> htmlspecialchars($row->fechaExamen, ENT_QUOTES),
					'numeroPedido'	=> htmlspecialchars($row->numeroPedido, ENT_QUOTES),
					'examen'	=> htmlspecialchars($row->examen, ENT_QUOTES),
					'tipo'	=> htmlspecialchars($row->tipo, ENT_QUOTES),
					'idUsuario'	=> htmlspecialchars($row->idUsuario, ENT_QUOTES)
					);

				}
				
				} 
		
			$this->data['resultados']  = $resultado;*/
 

			$this->db->select("ea.id, ea.especificaciones, ea.nombre as examen, ea.especialidad");
			$this->db->from('examenauxiliar_cita ea');
			$this->db->join('cita ci', 'ci.idCita = ea.idCita');
			$this->db->where("ci.idUsuario", $user);
			$this->db->where("ea.realizado", 0);
			$this->db->order_by("nombre", "desc");
	
			$this->data["examenes"] = $this->db->get()->result();

			$this->data['especialidades'] = $this->Especialidad->listAll();


			$this->load->view('examenes_medicos', $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}

	//informes
	
	public function resultados()
    {
		$this->validarSesion();

		$this->cargarDatosSesion();
		
		$user = ($this->input->post("cmbUsuario"))? $this->input->post("cmbUsuario") : $this->session->userdata('idUsuario');
		
		if($this->Helper->permiso_usuario("gestionar_resultados"))
		{
			$this->db->select("se.idExamen,se.idUsuario, se.fechaExamen, se.numeroPedido, exa.nombre as examen, exa.tipo, se.estado, se.codigo_interno, se.idUsuario");
			$this->db->from('solicitarexamen se');
			$this->db->join('examen exa', 'exa.id = se.idExamen');
			$this->db->where("se.idUsuario", $user);
			$this->db->where("se.idPerfil", 0);
			//$this->db->order_by("se.idExamen", "DESC");
			$this->db->order_by("se.codigo_interno", "desc");
			
			$this->data["resultados"] = $this->db->get()->result();
						
			$this->db->select("rp.id, concat(p.firstname, ' ', p.lastname) as paciente, rp.descripcion, rp.nombreArchvioShow, rp.nombreArchivo, rp.fechaCreacion");
			$this->db->from('resultado_paciente rp');
			$this->db->join('patients p', 'p.idUsuario = rp.idPaciente');
			$this->db->where('rp.idPaciente', $user);
			$this->db->where('rp.activo', 1);
			$this->db->order_by("rp.fechaCreacion", "desc");
			
			
			$this->data["registrosSubidos"] = $this->db->get();
			
			$this->data['eliminarRegistroResultados']  = $this->Helper->permiso_usuario("eliminar_registro_resultadosUpload");
		 
			$this->load->view('resultados', $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}
	
	public function resultados_orion()
    {
		$this->validarSesion();
		$this->cargarDatosSesion();
		
		$this->data["usuarioSearch"] = "";

		if($this->input->post("cmbUsuario"))
		{
			$user = $this->input->post("cmbUsuario");
			$this->data["usuarioSearch"]  = $user;
		} else {
			$user = $this->session->userdata('idUsuario');
		}
			

		if($this->Helper->permiso_usuario("gestionar_resultados"))
		{
			$nroDocumento="40633322";


			/* 
			$curl = curl_init();

			curl_setopt_array($curl, array(
				//CURLOPT_URL => "https://demo.orion-labs.com/api/v1/ordenes?filtrar[estado]=P&filtrar[paciente.numero_identificacion]=$nroDocumento",
				CURLOPT_URL => "https://demo.orion-labs.com/api/v1/ordenes?filtrar[paciente.numero_identificacion]=$nroDocumento",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				//CURLOPT_POSTFIELDS => "tkn=test&order_ids=2596%2C2595%2C907%2C035%2C3423%2C65676%2C345%2C125",
				CURLOPT_HTTPHEADER => array(
					'Accept: application/json',
					'Content-Type: application/json',
					'authorization: Bearer u38ARhE71oZBcW8xYK28bCYJv12NY0I2I9A0H9AZnp7KyB0NLdgmzFbFRXGP'
				)
			));
	
			$response = curl_exec($curl);
			$err = curl_error($curl);
	
			curl_close($curl);
	
			if ($err) {
				echo "cURL Error #:" . $err;
			} else {


				$respuestaOrden = json_decode($response);
				$respuestaOrden2 = json_decode($response, true);

				$total = count($respuestaOrden2['data']);

				if ($total >0) {
					for ($i=0; $i < $total; $i++) { 

						$idOrden[$i]= $respuestaOrden->data[$i]->id;
						$numero_orden[$i]= $respuestaOrden->data[$i]->numero_orden;
						$fecha_orden[$i]= $respuestaOrden->data[$i]->fecha_orden;
						$estado[$i]= $respuestaOrden->data[$i]->estado;
					}
				}
				
				$this->data["idOrden"] = $idOrden;
				$this->data["numero_orden"] = $numero_orden;
				$this->data["fecha_orden"] = $fecha_orden;
				$this->data["estado"] = $estado;

				$this->data["cantidad"] = $total;

			} */


 
			
			$this->db->select("se.idUsuario, se.fechaExamen, se.numeroPedido, exa.nombre as examen, exa.tipo");
			$this->db->from('solicitarexamen_orion se');
			$this->db->join('examen_orion exa', 'exa.codigo = se.idExamen');
			$this->db->where("se.idUsuario", $user);
			$this->db->order_by("se.fechaExamen", "DESC");
			
			$query = $this->db->get()->result();
			 
			$resultado  =  [];
 
			if (count($query) >0) {
				foreach($query as $row)
				{

					
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://policlinicobarranco.orion-labs.com/api/v1/ordenes?filtrar[numero_pedido]=$row->numeroPedido",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					'Accept: application/json',
					'Content-Type: application/json',
					'authorization: Bearer Dj2StjHonJhddocwyxDtfO4dO72yWbSJUSuJEb489UmYvKhdax3uNjxjt8j0'
				)
			));
	
			$response = curl_exec($curl);
			$err = curl_error($curl);
	
			curl_close($curl);
	
			if ($err) {
				echo "cURL Error #:" . $err;
			} else {
				$idOrden = 0;
				$estado = 0;

				$respuestaOrden = json_decode($response);
				foreach($respuestaOrden->data as $result) {
					$idOrden =  $result->id;
					$estado =  $result->estado;
				}
			}

 
					$resultado[]   = array(
					'idOrden'	=> htmlspecialchars($idOrden, ENT_QUOTES),
					'estado'	=> htmlspecialchars($estado, ENT_QUOTES),
					'fechaExamen'	=> htmlspecialchars($row->fechaExamen, ENT_QUOTES),
					'numeroPedido'	=> htmlspecialchars($row->numeroPedido, ENT_QUOTES),
					'examen'	=> htmlspecialchars($row->examen, ENT_QUOTES),
					'tipo'	=> htmlspecialchars($row->tipo, ENT_QUOTES),
					'idUsuario'	=> htmlspecialchars($row->idUsuario, ENT_QUOTES)
					);

				}
				
				} 
		
			$this->data['resultados']  = $resultado;
 

			$this->db->select("ea.id, ea.especificaciones, ea.nombre as examen, ea.especialidad");
			$this->db->from('examenauxiliar_cita ea');
			$this->db->join('cita ci', 'ci.idCita = ea.idCita');
			$this->db->where("ci.idUsuario", $user);
			$this->db->where("ea.realizado", 0);
			$this->db->order_by("nombre", "desc");
	
			$this->data["examenes"] = $this->db->get()->result();

			$this->data['especialidades'] = $this->Especialidad->listAll();


			$this->load->view('resultados', $this->data);
		} else {
			redirect(base_url("inicio"));
		}

	}
	
	public function gestion_informe()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("gestiona_examenes"))
		{

			$user = $this->input->get_post("cmbUsuario");

			$this->db->select("exa.nuevo, se.idPerfil, se.codigo_interno, se.id, se.idExamen, se.idTipo, se.codigo_interno, se.estado, se.idUsuario, se.fechaExamen, exa.nombre as examen, exa.tipo");
			$this->db->from('solicitarexamen se');
			$this->db->join('examen exa', 'exa.id = se.idExamen');
			$this->db->where("se.idExamen not in(66, 67, 73, 74, 334, 335, 337, 339)");

			if($this->input->post("codigoInterno")) {
				$this->db->where("se.codigo_interno", $this->input->post("codigoInterno"));
				$user = "";
				 
			} else if($this->input->post("cmbUsuario")) {
				$this->db->where("se.idUsuario", $this->input->post("cmbUsuario"));
				$user = $this->input->post("cmbUsuario");
 
			} else {
				$this->db->where("se.idUsuario", $user);
			}
			 
			$this->db->order_by("se.codigo_interno, exa.tipo, exa.nombre", "DESC");
			
			$this->data["resultados"] = $this->db->get()->result();
	 
 
			$this->data['especialidades'] = $this->Especialidad->listAll();
	
			$this->data['dataUsuario'] = $this->Usuario->datosUsuario($user);
	
			$this->data['idUsuario'] = $user;
			
			if(isset($this->data['dataUsuario']["paciente"])){
				$oldstatus = $this->data['dataUsuario']["paciente"];
			} else {
				$oldstatus = null;
			}
		
			$this->data['usuario'] = strtoupper($oldstatus);
		 
			$this->load->view('gestionInfome/index', $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}

	public function frm_tipo()
	{
		$this->load->view('gestionInfome/formulario');
	}

	public function frm_examen($ubicacionNombre)
	{
		$idExamen = $this->input->get("idExamen");
		$dato = null;
		$dato1 = null;
		$dato2 = null;
		$dato3 = null;
		$dato4 = null;
		$dato5 = null;
		$observacion = null;

		if($idExamen == 1){
		  $dato = "Glucosa";
		  $dato1 = "Glucosa";
		} else if($idExamen == 2){
			$dato = "Urea";
			$dato1 = "Urea";
		} else if($idExamen == 3){
			$dato = "Creatina";
			$dato1 = "Creatina";
		} else if($idExamen == 4){
			$dato = "Colesterol Total";
			$dato1 = "Colesterol";
		} else if($idExamen == 5){
			$dato = "HDL Colesterol";
			$dato1 = "HDL";
		} else if($idExamen == 6){
			$dato = "LDL Colesterol";
			$dato1 = "LDL";
		} else if($idExamen == 7){
			$dato = "Trigliceridos";
			$dato1 = "Trigliceridos";
		} else if($idExamen == 8){
			$dato = "Proteinas Totales y Fraccionadas";
			$dato1 = "Proteínas";
			$dato2 = "Albumina";
		} else if($idExamen == 9){
			$dato = "Bilirrubinas Totales y Fraccionadas";
			$dato1 = "B. Total";
			$dato2 = "B. Directa";
			$dato3 = "B. Indirecta";
		} else if($idExamen == 10){
			$dato = "Transaminasa Glu. Oxalecitica";
			$dato1 = "Info";
		} else if($idExamen == 11){
			$dato = "Transaminasa Glu. Piruvica";
			$dato1 = "Glu. Piruvica";
		} else if($idExamen == 12){
			$dato = "Hemoglobina Glicosilada";
			$dato1 = "Hemoglobina";			
		} else if($idExamen == 13){
			$dato = "Tolerancia a la Glucosa";
			$dato1 = "Glucosa Basal";
			$dato2 = "Glucosa 60 Minutos";
			$dato3 = "Glucosa 120 Minutos";
		} else if($idExamen == 14){
			$dato = "Ácido Urico";
			$dato1 = "A. Urico";			
		} else if($idExamen == 15){
			$dato = "Fosfatasa Alcalina";
			$dato1 = "Fosfatasa";			
		} else if($idExamen == 16){
			$dato = "Gamma Glutamil Transferasa";
			$dato1 = "Gamma Glutamil";			
		} else if($idExamen == 19){
			$dato = "Proteina C Reactiva";
			$dato1 = "Resultado";
			$dato2 = "PCR";
		} else if($idExamen == 20){
			$dato = "Amilasa";
			$dato1 = "Amilasa";
		} else if($idExamen == 21){
			$dato = "Creatina en Orina";
			$dato1 = "Creatina";
		} else if($idExamen == 22){
			$dato = "Microalbuminuria";
			$dato1 = "MC";
			$dato2 = "TAS";
		} else if($idExamen == 24){
			$dato = "Hemoglobina";
			$dato1 = "Hemoglobina";
		} else if($idExamen == 26){
			$dato = "Grupo Sanguineo y Factor";
			$dato1 = "Grupo Sanguineo";
		} else if($idExamen == 27){
			$dato = "Vitamina B12";
			$dato1 = "B12";
		} else if($idExamen == 28){
			$dato = "Velocidad de Sedimentación";
			$dato1 = "Sedimentación";
		} else if($idExamen == 29){
			$dato = "Coagulacion";
			$dato1 = "Coagulacion";
		} else if($idExamen == 33){
			$dato = "Thevenon";
			$dato1 = "Thevenon";
		} else if($idExamen == 35){
			$dato = "Test de Graham";
			$dato2 = "Examen Microscópico";
		} else if($idExamen == 36){
			$dato = "Examen Directo de Hongos";
			$dato1 = "Muestra";
			$dato2 = "Examen Microscópico";
		} else if($idExamen == 38){
			$dato = "Coprocultivo";
			$dato1 = "Cultivo de Hongos";
		} else if($idExamen == 38){
			$dato = "Coprocultivo";
			$dato1 = "Cultivo de Hongos";
		} else if($idExamen == 39){
			$dato = "Cultivo de Hongos";
			$dato1 = "Cultivo de Hongos";
		} else if($idExamen == 40){
			$dato = "Aglutinaciones";
			$dato1 = "AG. Somático 'O'";
			$dato2 = "AG. Flagelar 'H'";
			$dato3 = "AG. Paratifico 'A'";
			$dato4 = "AG. Paratifico 'B'";
			$dato5 = "Brucella";
		} else if($idExamen == 41){
			$dato = "Factor Reumatoideo";
			$dato1 = "Factor";
		} else if($idExamen == 42){
			$dato = "Dosaje de TSH";
			$dato1 = "TSH";
		} else if($idExamen == 43){
			$dato = "FT3 Libre";
			$dato1 = "FT3";
		} else if($idExamen == 44){
			$dato = "FT4 Libre";
			$dato1 = "FT4";
		} else if($idExamen == 45){
			$dato = "PSA Total";
			$dato1 = "PSA";
		} else if($idExamen == 47){
			$dato = "FSH";
			$dato1 = "FSH";
		} else if($idExamen == 48){
			$dato = "LH";
			$dato1 = "LH";
		} else if($idExamen == 49){
			$dato = "Prolactina";
			$dato1 = "Prolactina";
		} else if($idExamen == 53){
			$dato = "Anticuerpos Helicobacter Pylori IgM";
			$dato1 = "Anticuerpos Helicobacter";
		} else if($idExamen == 56){
			$dato = "Anticuerpos AgHBS";
			$dato1 = "Anticuerpos";
		} else if($idExamen == 57){
			$dato = "Anticuerpos Hepatites HVA IgM";
			$dato1 = "Anticuerpos Hepatites";
		} else if($idExamen == 58){
			$dato = "Anticuerpos Hepatites C";
			$dato1 = "Resultado";
		} else if($idExamen == 59){
			$dato = "Prueba de Embarazo";
			$dato1 = "Resultado";
		} else if($idExamen == 61){
			$dato = "Anticuerpos HIV";
			$dato1 = "Resultado";
		} else if($idExamen == 62){
			$dato = "Tiempo Sangria";
			$dato1 = "Sangría";
		} else if($idExamen == 64 || $idExamen == 352){
			$dato = "Papanicolau";	
		} else if($idExamen == 65){
			$dato = $this->Helper->sanear_string("Biopsia Pequeña");
			$dato = $dato;
			$dato1 = "Muestra Remitida";
			$dato4 = "Conclusión";
			$observacion = "Examen Macroscópico";
		} else if($idExamen == 66){
			$dato = $this->Helper->sanear_string("COLEGRAMA_FUNCIONAL");
			$dato = $dato;
			$dato4 = "Examen Minusc";
			$observacion = "Examen Macroscópico";	
		} else if($idExamen == 68){
			$dato = $this->Helper->sanear_string("COPROLÓGICO FUNCIONAL");
			$dato = $dato;
			$dato4 = "Examen Macroscópico";
			$observacion = "Examen Microscópico";				
		} else {
			$dato = "No definido";
			$dato1 = "No definido";
		}

		$data['nombreExamen'] = $dato;
		$data['dato1'] = $dato1;
		$data['dato2'] = $dato2;
		$data['dato3'] = $dato3;
		$data['dato4'] = $dato4;
		$data['dato5'] = $dato5;
		$data['observacion'] = $observacion;
		
		$data['idSolicitud'] = $this->uri->segment(4);
		$data['idExamen'] = $idExamen;
		$data['idUsuario'] = $this->input->get("user");
		$data['idTipo'] = $this->input->get("idTipo");
		$data['idPerfil'] = $this->input->get("idPerfil");

		$this->db->select("id, dato1, dato2, dato3, dato4, dato5, observacion");
		$this->db->from('laboratorio');
		$this->db->where("id", $this->input->get("idTipo"));
		 
		$data["resultados"] = $this->db->get()->result();

		$this->db->select("id, color, aspecto, moco, sangre, muestra1, muestra2, muestra3, observacion");
		$this->db->from('parasitologico');
		$this->db->where("id", $this->input->get("idTipo"));
		 
		$data["parasitologico"] = $this->db->get()->result();

		if ($idExamen == 25){
			$this->db->select("id, leu, eri, hb, htc, vcm, hcm, ccmh, plaq, mielocito, metamielocito, abastonado, segmentado, eosinofilo, basofilo, linfocito, monocito, observaciones");
			$this->db->from('hemograma');
			$this->db->where("id", $this->input->get("idTipo"));
			 
			$data["hemograma"] = $this->db->get()->result();
		}

		if ($idExamen == 23){
			$this->db->select("id, color, aspecto, ph, densidad, nitrito, urobilinogeno, glucosa, sangre, proteina, cetona, bilirrubina, cepitelial, leucocito, hematie, germen, observacion");
			$this->db->from('orina_completo');
			$this->db->where("id", $this->input->get("idTipo"));
			 
			$data["orinaCompleto"] = $this->db->get()->result();
		}

		if ($idExamen == 40){
			$this->db->select("id, somatico, obs_somatico, flagelar, obs_flagelar, paratificoa, obs_paratificoa, paratificob, obs_paratificob, brucella, obs_brucella");
			$this->db->from('aglutinacion');
			$this->db->where("id", $this->input->get("idTipo"));
			 
			$data["aglutinacion"] = $this->db->get()->result();
		}
 
 		if ($idExamen == 64 || $idExamen == 352){
			$this->db->select("id, calidad_muestra, flora_doderlein, polimorfonucleares, hematies, filamentos_mucoides, candida_albicans, gardnerella_vaginalis, herpes, resultados, observaciones");
			$this->db->from('papanicolau');
			$this->db->where("id", $this->input->get("idTipo"));
			 
			$data["papanicolau"] = $this->db->get()->result();
		}
		
 		if ($idExamen == 75){
			$this->db->select("celulas, leucocitos, trichomonas, levaduras, fdoderlein, germenes, seaislo, observacion");
			$this->db->from('secrecion_vaginal');
			$this->db->where("id", $this->input->get("idTipo"));
			 
			$data["secrecionVaginal"] = $this->db->get()->result();
		}
		
		$nuevo = $this->input->get("nuevo");
		
		if ($nuevo == 1){
			$this->db->select("dato1, dato4, observacion");
			$this->db->from('laboratorio');
			$this->db->where("id", $this->input->get("idTipo"));
			 
			$data["plantillaTotal"] = $this->db->get()->result();
		}		

		$this->load->view("gestionInfome/$ubicacionNombre", $data);
	}

	
	public function gNewExam_orion()
	{
		$user = $this->input->post("idUsuario");

		$usuario = $this->Usuario->datosUsuario($user);
 
		$medico = $this->Doctor->all($this->input->post("medicos"));
	 
		$tipoDocumento = ($usuario["idTypeDocument"] == 1)?  "CED": "PAS" ;

		foreach ($this->input->post("cmbExamenes") as $value) {

			if($value == 1){
				 
					$arrayExamen [] =  
			
					array (
						"id_externo" =>  "AU",
						"muestra_pendiente" => true
						
					);

					$arrayExamen [] =  
			 			array (
						"id_externo" =>  "ABIL",
						"muestra_pendiente" => true
						
					) ;
						 
			} else if($value == 3){
				$arrayExamen [] =  
						array (
					"id_externo" =>  "TSH",
					"muestra_pendiente" => true
					
				) ;
				$arrayExamen [] =  
						array (
					"id_externo" =>  "FT3",
					"muestra_pendiente" => true
					
				) ;
				$arrayExamen [] =  
						array (
					"id_externo" =>  "FT4",
					"muestra_pendiente" => true
					
				) ;
	 
			} else {

				$arrayExamen [] =  
					
					array (
						"id_externo" =>  $value,
						"muestra_pendiente" => true
						
					);
			}
		}

		$data = json_encode(array(
			"sucursal_id"  => null,
			"categoria_id"  => 1,
			"tipo_atencion_id"  => 1,
			"servicio_id"  => 1,
			"numero_pedido_externo"  => null,
			"fecha_cita"  => date("Y-m-d",strtotime($this->input->post("fecha"))),
			"embarazada" => true,
			"paciente" => array (
				"tipo_identificacion" => $tipoDocumento,
				"numero_identificacion" => $usuario["document"],
				"nombres" => $usuario["firstname"],
				"apellidos" => $usuario["lastname"],
				"fecha_nacimiento" => $usuario["fechaNacimiento"],
				"sexo" => $usuario["sex"],
				"numero_historia_clinica" => null,
				"correo" => $usuario["email"]
				),
			"medico" => array (
				"id_externo" => null,
				"numero_identificacion" => $medico[0]->cmp,
				"nombres" => $medico[0]->firstname,
				"apellidos" => $medico[0]->lastname
				),
			"examenes" => $arrayExamen
			));

			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://policlinicobarranco.orion-labs.com/api/v1/pedidos",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_HTTPHEADER => array(
					'Accept: application/json',
					'Content-Type: application/json',
					'authorization: Bearer Dj2StjHonJhddocwyxDtfO4dO72yWbSJUSuJEb489UmYvKhdax3uNjxjt8j0'
				)
			));
	
			$responseCurl = curl_exec($curl);
			$err = curl_error($curl);
	
			curl_close($curl);
	
			if ($err) {
				$response['message'] = "Error. No se registro. cURL Error: ". $err;
				$response['status'] = false;

			} else {
				$respuestaOk = json_decode($responseCurl);
 
 				if($respuestaOk->data->numero_pedido > 0){

					foreach ($this->input->post("cmbExamenes") as $value) {

						$examen = array(
							'idExamen' => $value,
							'fechaExamen' => $this->input->post("fecha"),
							'numeroPedido' => $respuestaOk->data->numero_pedido,
							'idUsuario' => $user
						);

						$this->db->insert("solicitarexamen_orion", $examen);
					}
				}

				$response['message'] = "SE REGISTRO EL EXAMEN CORRECTAMENTE.";
				$response['status'] = true;
			}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );

	}

	public function gNewExam()
	{
		$user = ($this->input->post("idUsuario"))? $this->input->post("idUsuario") : $this->session->userdata('idUsuario');
		$codigoInterno = null;
 
		if($this->input->post("cmbExamenes") and count($this->input->post("cmbExamenes")) > 0) {

 
			for ($i=0; $i < count($this->input->post("cmbExamenes")); $i++) {

				$porciones = array(0, 0);


				if($this->input->post("codeExamenSolicitud"))	$porciones = explode("=", $this->input->post("cmbExamenes")[$i]);
 
				$codigoExamen = ($this->input->post("codeExamenSolicitud")) ? $porciones[1] : $this->input->post("cmbExamenes")[$i];

				//PERFIL LIPÍDICO
				if($codigoExamen == 334)
				{
					$examenes67 = array(4, 5, 6, 7);
					for ($ii=0; $ii <count($examenes67) ; $ii++) { 

						$costoPerfil = $this->Helper->consultar_precio($examenes67[$ii], true);
						$parametros= array(
							'codigo_interno' => ($this->input->post("codigoInterno"))? $this->input->post("codigoInterno"): "",
							'fechaExamen' => $this->input->post("fecha"),
							'idExamen' => $examenes67[$ii],
							'costo_transporte' => $this->input->post("costoTransporte"),
							'precio' => $costoPerfil["precio"],
							'descuento' => $this->input->post("descuento"),
							'idPerfil' => 334,
							'idUsuarioCreacion' => $this->session->userdata('idUsuario'),
							'idUsuario' => $user
						);

						$this->db->insert("solicitarexamen", $parametros);
						$idCodigo = $this->db->insert_id();

						if(!($this->input->post("codigoInterno")))
						{ 
							if(is_null($codigoInterno)) {
								$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
							}

							$parametrosUpate= array(
								'codigo_interno' => $codigoInterno
							);
							
							$this->db->where("id", $idCodigo);
							$this->db->update("solicitarexamen", $parametrosUpate);
						}
					}

				}
				
				//PERFIL HEPÁTICO
				if($codigoExamen == 337)
				{
					$examenes68 = array(8, 9, 128, 11, 108);
					for ($ii=0; $ii <count($examenes68) ; $ii++) { 

						$costoPerfil = $this->Helper->consultar_precio($examenes68[$ii], true);
						$parametros= array(
							'codigo_interno' => ($this->input->post("codigoInterno"))? $this->input->post("codigoInterno"): "",
							'fechaExamen' => $this->input->post("fecha"),
							'idExamen' => $examenes68[$ii],
							'costo_transporte' => $this->input->post("costoTransporte"),
							'precio' => $costoPerfil["precio"],
							'descuento' => $this->input->post("descuento"),
							'idPerfil' => 337,
							'idUsuarioCreacion' => $this->session->userdata('idUsuario'),
							'idUsuario' => $user
						);

						$this->db->insert("solicitarexamen", $parametros);
						$idCodigo = $this->db->insert_id();

						if(!($this->input->post("codigoInterno")))
						{ 
							if(is_null($codigoInterno)) {
								$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
							}

							$parametrosUpate= array(
								'codigo_interno' => $codigoInterno
							);
							
							$this->db->where("id", $idCodigo);
							$this->db->update("solicitarexamen", $parametrosUpate);
						}
					}

				}
				
				//perfil diabetico
				if($codigoExamen == 335)
				{
					$examenesDiabetes = array(1, 12);
					for ($diab=0; $diab <count($examenesDiabetes) ; $diab++) { 

						$costoPerfil = $this->Helper->consultar_precio($examenesDiabetes[$diab], true);
						$parametros= array(
							'codigo_interno' => ($this->input->post("codigoInterno"))? $this->input->post("codigoInterno"): "",
							'fechaExamen' => $this->input->post("fecha"),
							'idExamen' => $examenesDiabetes[$diab],
							'costo_transporte' => $this->input->post("costoTransporte"),
							'precio' => $costoPerfil["precio"],
							'descuento' => $this->input->post("descuento"),
							'idPerfil' => 335,
							'idUsuarioCreacion' => $this->session->userdata('idUsuario'),
							'idUsuario' => $user
						);

						$this->db->insert("solicitarexamen", $parametros);
						$idCodigo = $this->db->insert_id();

						if(!($this->input->post("codigoInterno")))
						{ 
							if(is_null($codigoInterno)) {
								$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
							}

							$parametrosUpate= array(
								'codigo_interno' => $codigoInterno
							);
							
							$this->db->where("id", $idCodigo);
							$this->db->update("solicitarexamen", $parametrosUpate);
						}
					}
				}
				
				//perfil tiroideo
				if($codigoExamen == 339)
				{
					$pTiroideo = array(42, 44);
					for ($ptiro=0; $ptiro <count($pTiroideo) ; $ptiro++) { 

						$costoPerfil = $this->Helper->consultar_precio($pTiroideo[$ptiro], true);
						$parametros= array(
							'codigo_interno' => ($this->input->post("codigoInterno"))? $this->input->post("codigoInterno"): "",
							'fechaExamen' => $this->input->post("fecha"),
							'idExamen' => $pTiroideo[$ptiro],
							'costo_transporte' => $this->input->post("costoTransporte"),
							'precio' => $costoPerfil["precio"],
							'descuento' => $this->input->post("descuento"),
							'idPerfil' => 339,
							'idUsuarioCreacion' => $this->session->userdata('idUsuario'),
							'idUsuario' => $user
						);

						$this->db->insert("solicitarexamen", $parametros);
						$idCodigo = $this->db->insert_id();

						if(!($this->input->post("codigoInterno")))
						{ 
							if(is_null($codigoInterno)) {
								$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
							}

							$parametrosUpate= array(
								'codigo_interno' => $codigoInterno
							);
							
							$this->db->where("id", $idCodigo);
							$this->db->update("solicitarexamen", $parametrosUpate);
						}
					}
				}	

 
				$costo = $this->Helper->consultar_precio($codigoExamen, true);
 
				$parametros= array(
					'codigo_interno' => ($this->input->post("codigoInterno"))? $this->input->post("codigoInterno"): "",
					'tipo' => $this->input->post("cmbTipos"),
					'fechaExamen' => $this->input->post("fecha"),
					'idExamen' => $codigoExamen,
					'costo_transporte' => $this->input->post("costoTransporte"),
					'precio' => $costo["precio"],
					'descuento' => $this->input->post("descuento"),
					'idUsuarioCreacion' => $this->session->userdata('idUsuario'),
					'idUsuario' => $user
				);

				$this->db->insert("solicitarexamen", $parametros);
				$idCodigo = $this->db->insert_id();
				
				if(!($this->input->post("codigoInterno")))
				{
					if(is_null($codigoInterno)) {
						$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
					}

					$parametrosUpate= array(
						'codigo_interno' => $codigoInterno
					);
					
					$this->db->where("id", $idCodigo);
					$this->db->update("solicitarexamen", $parametrosUpate);

					
 					$caja = array(
						'idUsuario' => $user,
						'codigo_procedimiento' => $codigoExamen,
						'precio' => $costo["precio"],
						'tipo_solicitud' => "LAB",
						'codigo_lab' => $codigoInterno,
						'realizado' => 1,
						'descuento' => $this->input->post("descuento"),
						'idUsuarioCreacion' => $this->session->userdata('idUsuario')
					);

					if (!$this->input->post("codeExamenSolicitud"))	$this->db->insert("solicitud_citas_pagos", $caja);
					
					if($this->input->post("descuento"))
					{
						$cajaLabdescuento = array(
							'idUsuario' => $user,
							'codigo_interno' => $codigoInterno,
							'monto' => $this->input->post("descuento"),
							'idUsuario' => $this->session->userdata('idUsuario')
						);

						$this->db->insert("solicitud_examen_pagos_descuento", $cajaLabdescuento);
					}
		
					if ($this->input->post("codeExamenSolicitud"))
					{
						$solicitudRegistrado = array(
							'marca_cita' => 0,
							'realizado' => 1,
							'codigo_lab' => $codigoInterno,
							'descuento' => $this->input->post("descuento")
						);

						$this->db->where("id", $porciones[0]);
						$this->db->update("solicitud_citas_pagos", $solicitudRegistrado);
					}
				}
			}
			
			
			if($this->input->post("descuento"))
			{
				$parametrosDes = array(
					'tipo' => "DES",
					'codigo_interno' => $codigoInterno,
					'monto' => $this->input->post("descuento"),
					'descuento_porcentaje' => $this->input->post("porcentaje"),
					'montoTotal' => $this->input->post("precioTotal"),
					'idUsuario' => $this->session->userdata('idUsuario')
				);

				$this->db->insert("solicitarexamen_descuento_transporte", $parametrosDes);
			}
			
				$examenDetalle = array(
					'descuento' => empty($this->input->post("porcentaje")) ? 0 : $this->input->post("porcentaje"),
					'fechaExamen' => $this->input->post("fecha"),
					'idUsuario' => $user,
					'codigo_interno' => $codigoInterno,
					'precio' => $this->input->post("precioTotal"),
					'idUsuarioCreacion' => $this->session->userdata('idUsuario')
				);

				$this->db->insert("solicitarexamen_detalle", $examenDetalle);
				
			$response['message'] = "SE REGISTRO EL EXAMEN CORRECTAMENTE.";
			$response['status'] = true;
		} else {
			$response['message'] = "NO SE REGISTRO EL EXAMEN.";
			$response['status'] = false;	
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function gNewExam_001()
	{
		$user = ($this->input->post("idUsuario"))? $this->input->post("idUsuario") : $this->session->userdata('idUsuario');
		$codigoInterno = null;
 
		if($this->input->post("cmbExamenes") and count($this->input->post("cmbExamenes")) > 0) {



			for ($i=0; $i < count($this->input->post("cmbExamenes")); $i++) {
				//PERFIL LIPÍDICO
				if($this->input->post("cmbExamenes")[$i] == 334)
				{
					$examenes67 = array(4, 5, 6, 7);
					for ($ii=0; $ii <count($examenes67) ; $ii++) { 

						$costoPerfil = $this->Helper->consultar_precio($examenes67[$ii], true);
						$parametros= array(
							'codigo_interno' => ($this->input->post("codigoInterno"))? $this->input->post("codigoInterno"): "",
							'fechaExamen' => $this->input->post("fecha"),
							'idExamen' => $examenes67[$ii],
							'costo_transporte' => $this->input->post("costoTransporte"),
							'precio' => $costoPerfil["precio"],
							'descuento' => $this->input->post("descuento"),
							'idPerfil' => 334,
							'idUsuarioCreacion' => $this->session->userdata('idUsuario'),
							'idUsuario' => $user
						);

						$this->db->insert("solicitarexamen", $parametros);
						$idCodigo = $this->db->insert_id();

						if(!($this->input->post("codigoInterno")))
						{ 
							if(is_null($codigoInterno)) {
								$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
							}

							$parametrosUpate= array(
								'codigo_interno' => $codigoInterno
							);
							
							$this->db->where("id", $idCodigo);
							$this->db->update("solicitarexamen", $parametrosUpate);
						}
					}

				}
				
				//PERFIL HEPÁTICO
				if($this->input->post("cmbExamenes")[$i] == 337)
				{
					$examenes68 = array(8, 9, 128, 11, 108);
					for ($ii=0; $ii <count($examenes68) ; $ii++) { 

						$costoPerfil = $this->Helper->consultar_precio($examenes68[$ii], true);
						$parametros= array(
							'codigo_interno' => ($this->input->post("codigoInterno"))? $this->input->post("codigoInterno"): "",
							'fechaExamen' => $this->input->post("fecha"),
							'idExamen' => $examenes68[$ii],
							'costo_transporte' => $this->input->post("costoTransporte"),
							'precio' => $costoPerfil["precio"],
							'descuento' => $this->input->post("descuento"),
							'idPerfil' => 337,
							'idUsuarioCreacion' => $this->session->userdata('idUsuario'),
							'idUsuario' => $user
						);

						$this->db->insert("solicitarexamen", $parametros);
						$idCodigo = $this->db->insert_id();

						if(!($this->input->post("codigoInterno")))
						{ 
							if(is_null($codigoInterno)) {
								$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
							}

							$parametrosUpate= array(
								'codigo_interno' => $codigoInterno
							);
							
							$this->db->where("id", $idCodigo);
							$this->db->update("solicitarexamen", $parametrosUpate);
						}
					}

				}
				
				//perfil diabetico
				if($this->input->post("cmbExamenes")[$i] == 335)
				{
					$examenesDiabetes = array(1, 12);
					for ($diab=0; $diab <count($examenesDiabetes) ; $diab++) { 

						$costoPerfil = $this->Helper->consultar_precio($examenesDiabetes[$diab], true);
						$parametros= array(
							'codigo_interno' => ($this->input->post("codigoInterno"))? $this->input->post("codigoInterno"): "",
							'fechaExamen' => $this->input->post("fecha"),
							'idExamen' => $examenesDiabetes[$diab],
							'costo_transporte' => $this->input->post("costoTransporte"),
							'precio' => $costoPerfil["precio"],
							'descuento' => $this->input->post("descuento"),
							'idPerfil' => 335,
							'idUsuarioCreacion' => $this->session->userdata('idUsuario'),
							'idUsuario' => $user
						);

						$this->db->insert("solicitarexamen", $parametros);
						$idCodigo = $this->db->insert_id();

						if(!($this->input->post("codigoInterno")))
						{ 
							if(is_null($codigoInterno)) {
								$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
							}

							$parametrosUpate= array(
								'codigo_interno' => $codigoInterno
							);
							
							$this->db->where("id", $idCodigo);
							$this->db->update("solicitarexamen", $parametrosUpate);
						}
					}
				}
				
				//perfil tiroideo
				if($this->input->post("cmbExamenes")[$i] == 339)
				{
					$pTiroideo = array(42, 44);
					for ($ptiro=0; $ptiro <count($pTiroideo) ; $ptiro++) { 

						$costoPerfil = $this->Helper->consultar_precio($pTiroideo[$ptiro], true);
						$parametros= array(
							'codigo_interno' => ($this->input->post("codigoInterno"))? $this->input->post("codigoInterno"): "",
							'fechaExamen' => $this->input->post("fecha"),
							'idExamen' => $pTiroideo[$ptiro],
							'costo_transporte' => $this->input->post("costoTransporte"),
							'precio' => $costoPerfil["precio"],
							'descuento' => $this->input->post("descuento"),
							'idPerfil' => 339,
							'idUsuarioCreacion' => $this->session->userdata('idUsuario'),
							'idUsuario' => $user
						);

						$this->db->insert("solicitarexamen", $parametros);
						$idCodigo = $this->db->insert_id();

						if(!($this->input->post("codigoInterno")))
						{ 
							if(is_null($codigoInterno)) {
								$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
							}

							$parametrosUpate= array(
								'codigo_interno' => $codigoInterno
							);
							
							$this->db->where("id", $idCodigo);
							$this->db->update("solicitarexamen", $parametrosUpate);
						}
					}
				}	

 
				$costo = $this->Helper->consultar_precio($this->input->post("cmbExamenes")[$i], true);
 
				$parametros= array(
					'codigo_interno' => ($this->input->post("codigoInterno"))? $this->input->post("codigoInterno"): "",
					'tipo' => $this->input->post("cmbTipos"),
					'fechaExamen' => $this->input->post("fecha"),
					'idExamen' => $this->input->post("cmbExamenes")[$i],
					'costo_transporte' => $this->input->post("costoTransporte"),
					'precio' => $costo["precio"],
					'descuento' => $this->input->post("descuento"),
					'idUsuarioCreacion' => $this->session->userdata('idUsuario'),
					'idUsuario' => $user
				);

				$this->db->insert("solicitarexamen", $parametros);
				$idCodigo = $this->db->insert_id();
				
				if(!($this->input->post("codigoInterno")))
				{ 
					if(is_null($codigoInterno)) {
						$codigoInterno = "P".str_pad($idCodigo, 5, '0', STR_PAD_LEFT);
					}

					$parametrosUpate= array(
						'codigo_interno' => $codigoInterno
					);
					
					$this->db->where("id", $idCodigo);
					$this->db->update("solicitarexamen", $parametrosUpate);
				}
			}

			$response['message'] = "SE REGISTRO EL EXAMEN CORRECTAMENTE.";
			$response['status'] = true;
		} else {
			$response['message'] = "NO SE REGISTRO EL EXAMEN.";
			$response['status'] = false;	
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function gNewExam22()
	{
		$user = ($this->input->post("idUsuario"))? $this->input->post("idUsuario") : $this->session->userdata('idUsuario');

 
		if($this->input->post("cmbExamenes") and count($this->input->post("cmbExamenes")) > 0) {



			for ($i=0; $i < count($this->input->post("cmbExamenes")); $i++) {

				$parametros= array(
					'codigo_interno' => ($this->input->post("codigoInterno"))? $this->input->post("codigoInterno"): "",
					'tipo' => $this->input->post("cmbTipos"),
					'fechaExamen' => $this->input->post("fecha"),
					'idExamen' => $this->input->post("cmbExamenes")[$i],
					'costo_transporte' => $this->input->post("costoTransporte"),
					'idUsuario' => $user
				);

				$this->db->insert("solicitarexamen", $parametros);
				$idCodigo = $this->db->insert_id();
			}

			if(!($this->input->post("codigoInterno")))
			{ 
				$parametros= array(
					'codigo_interno' => "P".str_pad($idCodigo, 4, '0', STR_PAD_LEFT)
				);
				
				$this->db->where("codigo_interno", "");
				$this->db->update("solicitarexamen", $parametros);
			}



			$response['message'] = "SE REGISTRO EL EXAMEN CORRECTAMENTE.";
			$response['status'] = true;
		} else {
			$response['message'] = "NO SE REGISTRO EL EXAMEN.";
			$response['status'] = false;	
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function gNewMicrologia()
	{
		$user = ($this->input->post("idUsuario"))? $this->input->post("idUsuario") : $this->session->userdata('idUsuario');

		if($this->input->post("color") || $this->input->post("aspecto") || $this->input->post("ph") || $this->input->post("germes") || $this->input->post("leucocitos")) {
			$parametros= array(
				'idExamen' => $this->input->post("idExamen"),
				'ef_color' => $this->input->post("color"),
				'ef_aspecto' => $this->input->post("aspecto"),
				'ef_ph' => $this->input->post("ph"),
				'ef_densidad' => $this->input->post("densidad"),
				'eb_nitrito' => $this->input->post("nitritos"),
				'eb_glucosa' => $this->input->post("glucosa"),
				'eb_proteina' => $this->input->post("proteinas"),
				'eb_bilirrubina' => $this->input->post("bilirrubinas"),
				'eb_urob' => $this->input->post("urobilinogeno"),
				'eb_sangre' => $this->input->post("sangre"),
				'eb_cetona' => $this->input->post("cetonas"),
				'em_cepite' => $this->input->post("epiteliales"),
				'em_leucocito' => $this->input->post("leucocitos"),
				'em_hematie' => $this->input->post("hematies"),
				'em_germen' => $this->input->post("germes"),
				'em_fmucoide' => $this->input->post("fmucoides"),
				'em_cristal' => $this->input->post("cristales"),
				//'idSolicitarExa' => $this->input->post("idSolicitarMed"),
				'idUsuario' => $user
			);

			$this->db->trans_start();
			$this->db->insert('microbiologia', $parametros);
			$idHematologia = $this->db->insert_id();
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
				$parametro = array (
					"estado" => 1,
					"idTipo" => $idHematologia
				);

				$this->db->where('id', $this->input->post("idSolicitarExaMicro"));
				$this->db->update("solicitarexamen", $parametro);

				$response['message'] = "SE REGISTRO CORRECTAMENTE.";
				$response['status'] = true;
			} else {
				$response['message'] = "NO SE REGISTRO.";
				$response['status'] = false;
			}


		} else {
			$response['message'] = "ERROR NO SE PUEDE PROCESAR.";
			$response['status'] = false;	
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function editarMicrologia($id)
	{
		$response["microbilogias"] = $this->Helper->datosMicrobiologia($id);

		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}
	
	public function gNewLaboratorio()
	{
		$user = ($this->input->post("idUsuario"))? $this->input->post("idUsuario") : $this->session->userdata('idUsuario');
		
		if($this->input->post("idTipo"))
		{
			$parametroUpdate = array (
				'dato1' => $this->input->post("dato1"),
				'dato2' => $this->input->post("dato2"),
				'dato3' => $this->input->post("dato3"),
				'dato4' => $this->input->post("dato4"),
				'dato5' => $this->input->post("dato5"),
				'observacion' => $this->input->post("observacion"),
				'idUsuarioActulizar' => $this->session->userdata('idUsuario'),
				'fechaActulizar' => date('Y-m-d H:i:s')
			);

			$this->db->trans_start();
			$this->db->where('id', $this->input->post("idTipo"));
			$this->db->update("laboratorio", $parametroUpdate);
			$this->db->trans_complete();
		} else {

			if($this->input->post("dato1") || $this->input->post("dato2") || $this->input->post("dato3") || $this->input->post("dato4") || $this->input->post("dato5")) {
				$parametros= array(
					'idExamen' => $this->input->post("idExamen"),
					'dato1' => $this->input->post("dato1"),
					'dato2' => $this->input->post("dato2"),
					'dato3' => $this->input->post("dato3"),
					'dato4' => $this->input->post("dato4"),
					'dato5' => $this->input->post("dato5"),
					'observacion' => $this->input->post("observacion"),
					'idUsuario' => $user,
					'idUsuarioActulizar' => $this->session->userdata('idUsuario')
				);

				$this->db->trans_start();
				$this->db->insert('laboratorio', $parametros);
				$idLab = $this->db->insert_id();
				$this->db->trans_complete();
				
			}
		}

		if ($this->db->trans_status())
		{
			if(isset($idLab))
			{
				$parametro = array (
					"estado" => 1,
					"idTipo" => $idLab
				);

				$this->db->where('id', $this->input->post("idSolicitud"));
				$this->db->update("solicitarexamen", $parametro);
				

				if($this->input->post("idPerfil"))
				{
					$this->db->select("codigo_interno");
					$this->db->from("solicitarexamen");
					$this->db->where("id", $this->input->post("idSolicitud"));
					$this->db->where("estado", 1);
					 
					$query = $this->db->get();

					$row_resultado = $query->row_array();
	
					$codigoInterno = $row_resultado['codigo_interno'];
 
					if($codigoInterno)
					{
						$parametroUpdate = array (
							"estado" => 1,
						);
						
						$this->db->where('codigo_interno', $codigoInterno);
						$this->db->where('idExamen', $this->input->post("idPerfil"));
						$this->db->where("estado", 0);
						$this->db->update("solicitarexamen", $parametroUpdate);
					}

				}				
			}

			$response['message'] = "SE REGISTRO CORRECTAMENTE.";
			$response['status'] = true;
		} else {
			$response['message'] = "NO SE REGISTRO.";
			$response['status'] = false;
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function gNewHemograma()
	{
		$user = ($this->input->post("idUsuario"))? $this->input->post("idUsuario") : $this->session->userdata('idUsuario');
		
		if($this->input->post("idTipo"))
		{
			$parametroUpdate = array (
				'leu' => $this->input->post("leu"),
				'eri' => $this->input->post("eri"),
				'hb' => $this->input->post("hb"),
				'htc' => $this->input->post("htc"),
				'vcm' => $this->input->post("vcm"),
				'hcm' => $this->input->post("hcm"),
				'ccmh' => $this->input->post("ccmh"),
				'plaq' => $this->input->post("plaq"),
				'mielocito' => $this->input->post("mielocito"),
				'metamielocito' => $this->input->post("metamielocito"),
				'abastonado' => $this->input->post("abastonado"),
				'segmentado' => $this->input->post("segmentado"),
				'eosinofilo' => $this->input->post("eosinofilo"),
				'basofilo' => $this->input->post("basofilo"),
				'linfocito' => $this->input->post("linfocito"),
				'monocito' => $this->input->post("monocito"),
				'observaciones' => $this->input->post("observaciones"),
				'idUsuarioActulizar' => $this->session->userdata('idUsuario'),
				'fechaActulizar' => date('Y-m-d H:i:s')
			);

			$this->db->trans_start();
			$this->db->where('id', $this->input->post("idTipo"));
			$this->db->update("hemograma", $parametroUpdate);
			$this->db->trans_complete();
		} else {

			if($this->input->post("leu") || $this->input->post("eri") || $this->input->post("hb") || $this->input->post("htc")) {
				$parametros= array(
					'idExamen' => $this->input->post("idExamen"),
					'leu' => $this->input->post("leu"),
					'eri' => $this->input->post("eri"),
					'hb' => $this->input->post("hb"),
					'htc' => $this->input->post("htc"),
					'vcm' => $this->input->post("vcm"),
					'hcm' => $this->input->post("hcm"),
					'ccmh' => $this->input->post("ccmh"),
					'plaq' => $this->input->post("plaq"),
					'mielocito' => $this->input->post("mielocito"),
					'metamielocito' => $this->input->post("metamielocito"),
					'abastonado' => $this->input->post("abastonado"),
					'segmentado' => $this->input->post("segmentado"),
					'eosinofilo' => $this->input->post("eosinofilo"),
					'basofilo' => $this->input->post("basofilo"),
					'linfocito' => $this->input->post("linfocito"),
					'monocito' => $this->input->post("monocito"),
					'observaciones' => $this->input->post("observaciones"),
					'idUsuario' => $user,
					'idUsuarioActulizar' => $this->session->userdata('idUsuario')
				);

				$this->db->trans_start();
				$this->db->insert('hemograma', $parametros);
				$idLab = $this->db->insert_id();
				$this->db->trans_complete();
				
			}
		}

		if ($this->db->trans_status())
		{
			if(isset($idLab))
			{
				$parametro = array (
					"estado" => 1,
					"idTipo" => $idLab
				);

				$this->db->where('id', $this->input->post("idSolicitud"));
				$this->db->update("solicitarexamen", $parametro);
			}

			$response['message'] = "SE REGISTRO CORRECTAMENTE.";
			$response['status'] = true;
		} else {
			$response['message'] = "NO SE REGISTRO.";
			$response['status'] = false;
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function gNewOrinaCompleto()
	{
		$user = ($this->input->post("idUsuario"))? $this->input->post("idUsuario") : $this->session->userdata('idUsuario');
		
		if($this->input->post("idTipo"))
		{
			$parametroUpdate = array (
				'color' => $this->input->post("color"),
				'aspecto' => $this->input->post("aspecto"),
				'ph' => $this->input->post("ph"),
				'densidad' => $this->input->post("densidad"),
				'nitrito' => $this->input->post("nitrito"),
				'urobilinogeno' => $this->input->post("urobilinogeno"),
				'glucosa' => $this->input->post("glucosa"),
				'sangre' => $this->input->post("sangre"),
				'proteina' => $this->input->post("proteina"),
				'cetona' => $this->input->post("cetona"),
				'bilirrubina' => $this->input->post("bilirrubina"),
				'cepitelial' => $this->input->post("cepitelial"),
				'leucocito' => $this->input->post("leucocito"),
				'hematie' => $this->input->post("hematie"),
				'germen' => $this->input->post("germen"),
				'observacion' => $this->input->post("observacion"),
				'idUsuarioActulizar' => $this->session->userdata('idUsuario'),
				'fechaActulizar' => date('Y-m-d H:i:s')
			);

			$this->db->trans_start();
			$this->db->where('id', $this->input->post("idTipo"));
			$this->db->update("orina_completo", $parametroUpdate);
			$this->db->trans_complete();
		} else {

			if($this->input->post("color") || $this->input->post("aspecto") || $this->input->post("nitrito") || $this->input->post("glucosa")) {
				$parametros= array(
					'idExamen' => $this->input->post("idExamen"),
					'color' => $this->input->post("color"),
					'aspecto' => $this->input->post("aspecto"),
					'ph' => $this->input->post("ph"),
					'densidad' => $this->input->post("densidad"),
					'nitrito' => $this->input->post("nitrito"),
					'urobilinogeno' => $this->input->post("urobilinogeno"),
					'glucosa' => $this->input->post("glucosa"),
					'sangre' => $this->input->post("sangre"),
					'proteina' => $this->input->post("proteina"),
					'cetona' => $this->input->post("cetona"),
					'bilirrubina' => $this->input->post("bilirrubina"),
					'cepitelial' => $this->input->post("cepitelial"),
					'leucocito' => $this->input->post("leucocito"),
					'hematie' => $this->input->post("hematie"),
					'germen' => $this->input->post("germen"),
					'observacion' => $this->input->post("observacion"),
					'idUsuario' => $user,
					'idUsuarioActulizar' => $this->session->userdata('idUsuario')
				);

				$this->db->trans_start();
				$this->db->insert('orina_completo', $parametros);
				$idLab = $this->db->insert_id();
				$this->db->trans_complete();
				
			}
		}

		if ($this->db->trans_status())
		{
			if(isset($idLab))
			{
				$parametro = array (
					"estado" => 1,
					"idTipo" => $idLab
				);

				$this->db->where('id', $this->input->post("idSolicitud"));
				$this->db->update("solicitarexamen", $parametro);
			}

			$response['message'] = "SE REGISTRO CORRECTAMENTE.";
			$response['status'] = true;
		} else {
			$response['message'] = "NO SE REGISTRO.";
			$response['status'] = false;
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function gNewLaboratorioParasotologico()
	{
		$user = ($this->input->post("idUsuario"))? $this->input->post("idUsuario") : $this->session->userdata('idUsuario');
		
		if($this->input->post("idTipo"))
		{
			$parametroUpdate = array (
				'color' => $this->input->post("color"),
				'aspecto' => $this->input->post("aspecto"),
				'moco' => $this->input->post("moco"),
				'sangre' => $this->input->post("sangre"),
				'muestra1' => $this->input->post("muestra1"),
				'muestra2' => $this->input->post("muestra2"),
				'muestra3' => $this->input->post("muestra3"),
				'observacion' => $this->input->post("observacion"),
				'idUsuarioActulizar' => $this->session->userdata('idUsuario'),
				'fechaActulizar' => date('Y-m-d H:i:s')
			);

			$this->db->trans_start();
			$this->db->where('id', $this->input->post("idTipo"));
			$this->db->update("parasitologico", $parametroUpdate);
			$this->db->trans_complete();
		} else {

			if($this->input->post("color") || $this->input->post("aspecto") || $this->input->post("sangre") || $this->input->post("moco") | $this->input->post("muestra1") | $this->input->post("muestra2")) {
				$parametros= array(
					'idExamen' => $this->input->post("idExamen"),
					'color' => $this->input->post("color"),
					'aspecto' => $this->input->post("aspecto"),
					'moco' => $this->input->post("moco"),
					'sangre' => $this->input->post("sangre"),
					'muestra1' => $this->input->post("muestra1"),
					'muestra2' => $this->input->post("muestra2"),
					'muestra3' => $this->input->post("muestra3"),
					'observacion' => $this->input->post("observacion"),
					'idUsuario' => $user,
					'idUsuarioActulizar' => $this->session->userdata('idUsuario')
				);

				$this->db->trans_start();
				$this->db->insert('parasitologico', $parametros);
				$idLab = $this->db->insert_id();
				$this->db->trans_complete();
				
			}


		}

		if ($this->db->trans_status())
		{
			if(isset($idLab))
			{
				$parametro = array (
					"estado" => 1,
					"idTipo" => $idLab
				);

				$this->db->where('id', $this->input->post("idSolicitud"));
				$this->db->update("solicitarexamen", $parametro);
			}

			$response['message'] = "SE REGISTRO CORRECTAMENTE.";
			$response['status'] = true;
		} else {
			$response['message'] = "NO SE REGISTRO.";
			$response['status'] = false;
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function gNewAglutinacion()
	{
		$user = ($this->input->post("idUsuario"))? $this->input->post("idUsuario") : $this->session->userdata('idUsuario');
		
		if($this->input->post("idTipo"))
		{
			$parametroUpdate = array (
				'somatico' => $this->input->post("somatico"),
				'obs_somatico' => $this->input->post("obs_somatico"),
				'flagelar' => $this->input->post("flagelar"),
				'obs_flagelar' => $this->input->post("obs_flagelar"),
				'paratificoa' => $this->input->post("paratificoa"),
				'obs_paratificoa' => $this->input->post("obs_paratificoa"),
				'paratificob' => $this->input->post("paratificob"),
				'obs_paratificob' => $this->input->post("obs_paratificob"),
				'brucella' => $this->input->post("brucella"),
				'obs_brucella' => $this->input->post("obs_brucella"),
				'idUsuarioActulizar' => $this->session->userdata('idUsuario'),
				'fechaActulizar' => date('Y-m-d H:i:s')
			);

			$this->db->trans_start();
			$this->db->where('id', $this->input->post("idTipo"));
			$this->db->update("aglutinacion", $parametroUpdate);
			$this->db->trans_complete();
		} else {

			if($this->input->post("somatico") || $this->input->post("flagelar") || $this->input->post("paratificoa") || $this->input->post("paratificob") || $this->input->post("brucella")) {
				$parametros= array(
					'idExamen' => $this->input->post("idExamen"),
					'somatico' => $this->input->post("somatico"),
					'obs_somatico' => $this->input->post("obs_somatico"),
					'flagelar' => $this->input->post("flagelar"),
					'obs_flagelar' => $this->input->post("obs_flagelar"),
					'paratificoa' => $this->input->post("paratificoa"),
					'obs_paratificoa' => $this->input->post("obs_paratificoa"),
					'paratificob' => $this->input->post("paratificob"),
					'obs_paratificob' => $this->input->post("obs_paratificob"),
					'brucella' => $this->input->post("brucella"),
					'obs_brucella' => $this->input->post("obs_brucella"),
					'idUsuario' => $user,
					'idUsuarioActulizar' => $this->session->userdata('idUsuario')
				);

				$this->db->trans_start();
				$this->db->insert('aglutinacion', $parametros);
				$idLab = $this->db->insert_id();
				$this->db->trans_complete();
				
			}
		}

		if ($this->db->trans_status())
		{
			if(isset($idLab))
			{
				$parametro = array (
					"estado" => 1,
					"idTipo" => $idLab
				);

				$this->db->where('id', $this->input->post("idSolicitud"));
				$this->db->update("solicitarexamen", $parametro);
			}

			$response['message'] = "SE REGISTRO CORRECTAMENTE.";
			$response['status'] = true;
		} else {
			$response['message'] = "NO SE REGISTRO.";
			$response['status'] = false;
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function buscarExamen()
	{
		$json = [];
		if(!empty($this->input->post("q")) || !empty($this->input->post("busqueda"))){

			$this->db->select("id, concat(nombre, ' = ', precio) as text");
			$this->db->from('examen');
			if($this->input->post("busqueda")){
				$this->db->where_in('id',$this->input->post("busqueda"));
			} else {
				$this->db->like('id', $this->input->post("q"));
				$this->db->or_like('nombre', $this->input->post("q"));
			}
			
			$this->db->where('status', 1);
			$this->db->order_by("nombre", "ASC");
			$this->db->limit(20);
	 
			$json = $this->db->get()->result();
		}
		
		echo json_encode($json);
	}
	
	public function resumenInforme()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("gestiona_examenes_resumen"))
		{
			$user = $this->input->post("cmbUsuario");
			$status = $this->input->post("status");
			$fechaIni = $this->input->post("fechaIni");
			$fechaFin = $this->input->post("fechaFin");
			$sql_extra = null;
			$sql_extra2 = null;

			if($user)	$sql_extra = " and solicitarexamen.idUsuario = '$user'"; 
			if($this->input->post("fechaIni") and $this->input->post("fechaFin"))
			{
				$sql_extra2 = " and solicitarexamen.fechaExamen BETWEEN '$fechaIni'  and '$fechaFin'";
			} else if(!$user) {
				$sql_extra2 = " and se.fechaExamen = date(now())";
			}

			$this->db->select("se.costo_transporte, se.status_pago, se.id, se.idExamen, se.idTipo, se.codigo_interno, se.estado, se.idUsuario, se.fechaExamen, exa.nombre as examen, exa.tipo, concat(usu.firstname, ' ',  usu.lastname) as usuario, se.precio , 
			(select concat(0,count(solicitarexamen.codigo_interno), '-', sum(solicitarexamen.precio), '-', sum(solicitarexamen.descuento) / COUNT(solicitarexamen.codigo_interno)) from examen  INNER JOIN solicitarexamen ON solicitarexamen.idExamen = examen.id where solicitarexamen.codigo_interno= se.codigo_interno $sql_extra and solicitarexamen.estado like '%$status' $sql_extra2 and
			solicitarexamen.`idPerfil` = 0) as cantidadFilas");
			$this->db->from('solicitarexamen se');
			$this->db->join('examen exa', 'exa.id = se.idExamen');
			$this->db->join('patients usu', 'usu.idUsuario = se.idUsuario');
			$this->db->where('se.idPerfil', 0);
 
			if($user)	$this->db->where('se.idUsuario', $user); 
			$this->db->like('se.estado', $this->input->post("status"), 'after'); 
			if($this->input->post("fechaIni") and $this->input->post("fechaFin"))
			{
				$this->db->where("se.fechaExamen BETWEEN '".$this->input->post("fechaIni")."' AND '".$this->input->post("fechaFin")."'");
			} else if(!$user) {
				$this->db->where("se.fechaExamen = date(now()) ");
			}

			 
			$this->db->order_by("se.codigo_interno, exa.tipo, exa.nombre", "desc");
			
			$this->data["resultados"] = $this->db->get()->result();
		 
			$this->load->view('gestionInfome/resumen', $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}
	
	public function statusPago_examen()
	{
		$this->validarSesion();

		$status = ($this->input->post("status") == "true")? "1" : "0";
 
		if(empty($this->input->post("codigoInterno")))
		{
			if($status)
			{
				$parametros = array (
					"idUsuarioPago" => $this->session->userdata('idUsuario'),
					"fechaPago" => date('Y-m-d H:i:s'),
					"pago" => $status
				);
			} else {
				$parametros = array (
					"idUsuarioPagoNo" => $this->session->userdata('idUsuario'),
					"fechaPagoNo" => date('Y-m-d H:i:s'),
					"pago" => $status
				);
			}

			$this->db->where('id', $this->input->post("idReg"));
			$this->db->update("solicitud_citas_pagos", $parametros);
		}

		$parametros = array (
			"idUsuarioModificar" => $this->session->userdata('idUsuario'),
			"fechaModificar" => date('Y-m-d H:i:s'),
			"status_pago" => $status
		);

		$this->db->trans_start();

		$this->db->where('codigo_interno', $this->input->post("codigoInterno"));
		$this->db->update("solicitarexamen", $parametros);

		$this->db->trans_complete();

		if ($this->db->trans_status())
		{
			//if($this->input->post("idCaja"))
			if($status)
			{
				$parametros = array (
					"idUsuarioPago" => $this->session->userdata('idUsuario'),
					"fechaPago" => date('Y-m-d H:i:s'),
					"pago" => $status
				);
			} else {
				$parametros = array (
					"idUsuarioPagoNo" => $this->session->userdata('idUsuario'),
					"fechaPagoNo" => date('Y-m-d H:i:s'),
					"pago" => $status
				);
			}

				$this->db->where('codigo_lab', $this->input->post("codigoInterno"));
				$this->db->update("solicitud_citas_pagos", $parametros);

			echo true;
		}

		echo false;
	}

	public function statusPago_examen_001()
	{
		$this->validarSesion();

		$status = ($this->input->post("status") == "true")? "1" : "0";

		$parametros = array (
			"idUsuarioModificar" => $this->session->userdata('idUsuario'),
			"fechaModificar" => date('Y-m-d H:i:s'),
			"status_pago" => $status
		);

		$this->db->trans_start();

		$this->db->where('codigo_interno', $this->input->post("codigoInterno"));
		$this->db->update("solicitarexamen", $parametros);

		$this->db->trans_complete();

		if ($this->db->trans_status())
		{
 
			echo true;
		}

		echo false;
	}
	
	public function printExamen()
	{
	 
		$codigoInterno = $this->uri->segment(3);

		$this->db->select("se.costo_transporte, se.descuento, se.status_pago, se.codigo_interno, se.id, se.idExamen, se.idTipo, se.codigo_interno, se.estado, se.idUsuario, se.fechaExamen, exa.nombre as examen, exa.tipo, se.precio, 
		(select concat(0,count(solicitarexamen.codigo_interno), '-', sum(solicitarexamen.precio)) from examen  INNER JOIN solicitarexamen ON solicitarexamen.idExamen = examen.id where solicitarexamen.codigo_interno='$codigoInterno'  and
		solicitarexamen.`idPerfil` = 0) as cantidadFilas");
		$this->db->from('solicitarexamen se');
		$this->db->join('examen exa', 'exa.id = se.idExamen');
		$this->db->where("se.codigo_interno", $codigoInterno);
		$this->db->where("se.idPerfil", 0);

		$this->db->order_by("se.codigo_interno, exa.tipo, exa.nombre", "ASC");
		
		$this->data["resultados"] = $this->db->get()->result();
		
		$this->data['paciente'] = $this->Usuario->datosUsuario($this->input->get("user"));

		$this->load->view('gestionInfome/print', $this->data);
		 
	}
	
	public function pdf_informe($codigoInterno, $user, $idExamen)
    {
		$this->validarSesion();
  
		$this->load->model('Helper');

		$data["bioquimicas"] = $this->Helper->datosBioquimica($codigoInterno, "BIOQUIMICA");
		$data["orinas"] = $this->Helper->datosBioquimica($codigoInterno, "ORINA");
		$data["completoOrinas"] = $this->Helper->datosOrina_completo($codigoInterno);
		$data["hematologias"] = $this->Helper->datosHematologia($codigoInterno);
		$data["hemogramas"] = $this->Helper->datosHemagrama($codigoInterno);
		$data["pSimples"] = $this->Helper->datospSimple($codigoInterno, 31);
		$data["pSeriados"] = $this->Helper->datospSimple($codigoInterno, 32);
		$data["datosThevenon"] = $this->Helper->datosTest($codigoInterno, 33);
		$data["rInflamatorias"] = $this->Helper->datospSimple($codigoInterno, 34);
		$data["datosTestGrahams"] = $this->Helper->datosTest($codigoInterno, 35);
		$data["examenDirectoHongos"] = $this->Helper->datosTest($codigoInterno, 36);
		$data["urocultivos"] = $this->Helper->datospSimple($codigoInterno, 37);
		$data["coprocultivos"] = $this->Helper->datospSimple($codigoInterno, 38);

		$data["aglutinaciones"] = $this->Helper->datosAglutinacion($codigoInterno, 40);
		$data["inmunologias"] = $this->Helper->datosInmunologia($codigoInterno, "");
		 
		$data["papanicolaus"] = $this->Helper->datosPapanicolau($codigoInterno, 64);
	 
		
		$data["biopsias"] = $this->Helper->datosBiposia_colegrama($codigoInterno, 65, "MUESTRA_REMITIDA");
		$data["coprologicoFuncionales"] = $this->Helper->datosBiposia_colegrama($codigoInterno, 68, "COPROLOGICO_FUNCIONAL");
		$data["vdrls"] = $this->Helper->datosBiposia_colegrama($codigoInterno, 72, "vdrl");
		$data["secrecionVaginales"] = $this->Helper->datosSecrecionVaginal($codigoInterno, 75);
		$data["plantillaTotales"] = $this->Helper->datos_plantillaTotal($codigoInterno, $idExamen);

		$data['dataUsuario'] = $this->Usuario->datosUsuario($user);
		 

		$html = $this->load->view('pdf_exports/generar_informe', $data, TRUE);

		// Cargamos la librería
		$this->load->library('pdfgenerator');
		// generamos el PDF. Pasemos por encima de la configuración general y definamos otro tipo de papel-Letter
		
		$this->pdfgenerator->generate($html, "", false, "A4", "portrait", false);
	}

	public function enviar_pdf_informe()
	{
		$this->validarSesion();
 
		if ($this->input->post("idUsuario") < 1 || $this->input->post("idCodigo") == '')
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {
			 
			$parametros = array (
				"estado" => 2,
				"idUsuarioEnvio" => $this->session->userdata('idUsuario'),
				"fecha_envio" => date('Y-m-d H:i:s')
			);
	
			$this->db->trans_start();

			$this->db->where('estado', 1);
			$this->db->where("codigo_interno", $this->input->post("idCodigo"));
			$this->db->update("solicitarexamen", $parametros);
	
			$this->db->trans_complete();
	
			if ($this->db->trans_status())
			{
				$nomberArchivo = md5(time()).".pdf";
						
				$this->generarPdf($this->input->post("idUsuario"), $this->input->post("idCodigo"), $nomberArchivo, $this->input->post("idExamen"));
	
				$this->data =  $this->Usuario->datosUsuario($this->input->post("idUsuario"));
				
				$this->sendMail($this->data, "RESULTADO EXAMENES DE LABORATORIO", "files/informes/".$nomberArchivo, "mail/envio_informe");

				$response['message'] = "Se envío el Informe correctamente.";
				$response['status'] = true;	
			} else {
				$response['message'] = "No se envío el Informe.";
				$response['status'] = false;	
			}
 
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function generarPdf($user, $codigoInterno, $nomberArchivo, $idExamen)
    {
		$this->validarSesion();
 
		$this->load->model('Helper');

		$data["bioquimicas"] = $this->Helper->datosBioquimica($codigoInterno, "BIOQUIMICA");
		$data["orinas"] = $this->Helper->datosBioquimica($codigoInterno, "ORINA");
		$data["completoOrinas"] = $this->Helper->datosOrina_completo($codigoInterno);
		$data["hematologias"] = $this->Helper->datosHematologia($codigoInterno);
		$data["hemogramas"] = $this->Helper->datosHemagrama($codigoInterno, 25);
		$data["pSimples"] = $this->Helper->datospSimple($codigoInterno, 31);
		$data["pSeriados"] = $this->Helper->datospSimple($codigoInterno, 32);
		$data["datosThevenon"] = $this->Helper->datosTest($codigoInterno, 33);
		$data["rInflamatorias"] = $this->Helper->datospSimple($codigoInterno, 34);
		$data["datosTestGrahams"] = $this->Helper->datosTest($codigoInterno, 35);
		$data["examenDirectoHongos"] = $this->Helper->datosTest($codigoInterno, 36);
		$data["urocultivos"] = $this->Helper->datospSimple($codigoInterno, 37);
		$data["coprocultivos"] = $this->Helper->datospSimple($codigoInterno, 38);

		$data["aglutinaciones"] = $this->Helper->datosAglutinacion($codigoInterno, 40);
		$data["inmunologias"] = $this->Helper->datosInmunologia($codigoInterno, "");
		$data["papanicolaus"] = $this->Helper->datosPapanicolau($codigoInterno, 64);
		$data["biopsias"] = $this->Helper->datosBiposia_colegrama($codigoInterno, 65, "MUESTRA_REMITIDA");
		 
		$data["coprologicoFuncionales"] = $this->Helper->datosBiposia_colegrama($codigoInterno, 68, "COPROLOGICO_FUNCIONAL");
		$data["vdrls"] = $this->Helper->datosBiposia_colegrama($codigoInterno, 72, "vdrl");
		$data["secrecionVaginales"] = $this->Helper->datosSecrecionVaginal($codigoInterno, 75);
		$data["plantillaTotales"] = $this->Helper->datos_plantillaTotal($codigoInterno, $idExamen);
				
		$data['dataUsuario'] = $this->Usuario->datosUsuario($user);

		$html = $this->load->view('pdf_exports/generar_informe', $data, TRUE);

		// Cargamos la librería
		$this->load->library('pdfgenerator');

		// generamos el PDF. Pasemos por encima de la configuración general y definamos otro tipo de papel-Letter
		$this->pdfgenerator->generate($html, $nomberArchivo, false, "A4", "portrait", true, "files/informes");
	}
	
	
	public function resultados2()
    {
		$this->validarSesion();

		$this->cargarDatosSesion();
		
		if($this->Helper->permiso_usuario("gestionar_resultados"))
		{
			$this->db->select("concat(p.firstname, ' ', p.lastname) as paciente, rp.descripcion, rp.nombreArchvioShow, rp.nombreArchivo, rp.fechaCreacion");

			$this->db->from('resultado_paciente rp');
			$this->db->join('patients p', 'p.idUsuario = rp.idPaciente');
			
			if ($this->session->userdata('rol') == 3) {
				$this->db->where('rp.idPaciente', $this->session->userdata('idUsuario'));
			} 
	
			$this->db->order_by("rp.fechaCreacion", "desc");
	
			$this->data["registros"] = $this->db->get()->result();

			$this->load->view('resultados', $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}

	public function uploadResult()
	{
		$this->validarSesion();

		$this->cargarDatosSesion();

		if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
			$fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
			$fileName = $_FILES['uploadedFile']['name'];
			$fileSize = $_FILES['uploadedFile']['size'];
			$fileType = $_FILES['uploadedFile']['type'];
			$fileNameCmps = explode(".", $fileName);
			$fileExtension = strtolower(end($fileNameCmps));

			$newFileName = md5(time() . $fileName) . '.' . $fileExtension;

			$uploadFileDir = './files/resultados/';
			$dest_path = $uploadFileDir . $newFileName;
			
			if(move_uploaded_file($fileTmpPath, $dest_path))
			{
				$idPaciente = ($this->input->post("cliente"))?  $this->input->post("cliente") :  $this->session->userdata('idUsuario');

				$resultado = array(
					'descripcion' => $this->input->post("descripcion"),
					'nombreArchvioShow' => $fileName,
					'nombreArchivo' => $newFileName,
					'idPaciente' => $idPaciente,
					'idUsuarioCreacion' => $this->session->userdata('idUsuario')
				);
	
				$this->db->insert("resultado_paciente", $resultado);

				$response['message'] = "El archivo se adjunto correctamente.";
				$response['status'] = true;
			}
			else
			{
				$response['message'] = "Error. No se adjunto el archivo.";
				$response['status'] = false;
			}

		} else {
			$response['message'] = "Error. No se adjunto el archivo.";
			$response['status'] = false;
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function farmacia()
    {
		$this->validarSesion();

		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("gestionar_farmacias"))
		{
			$this->load->view('farmacia', $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}

	public function gpagoStatus()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		$status = ($this->input->post("status") == "true")? "1" : "0";

		if(empty($this->input->post("idPago")))
		{
			$idReg = $this->input->post("idReg");
	 
			if($status)
			{
				$parametros = array (
					"idUsuarioPago" => $this->session->userdata('idUsuario'),
					"fechaPago" => date('Y-m-d H:i:s'),
					"pago" => $status
				);
			} else {
				$parametros = array (
					"idUsuarioPagoNo" => $this->session->userdata('idUsuario'),
					"fechaPagoNo" => date('Y-m-d H:i:s'),
					"pago" => $status
				);
			}
	
			$this->db->trans_start();
			if($this->input->post("code"))	$this->db->where('codigo_asignacion', $this->input->post("code")); else $this->db->where('id', $idReg);
			//$this->db->where("pago != $status");
			$this->db->update("solicitud_citas_pagos", $parametros);
			$this->db->trans_complete();
	
			if ($this->db->trans_status())
			{
				echo true;
			}
		} else {
			

			$parametros = array (
				"idUsuarioModificar" => $this->session->userdata('idUsuario'),
				"fechaModificar" => date('Y-m-d H:i:s'),
				"status" => $status
			);
	
			$this->db->trans_start();
	
			$this->db->where('idPago', $this->input->post("idPago"));
			$this->db->where("status != $status");
			$this->db->update("pago", $parametros);
	
			$this->db->trans_complete();
	
			if ($this->db->trans_status())
			{
				echo true;
			}
	
		}

		echo false;
	}

	public function gBolFacStatus()
	{
		$this->validarSesion();

		$status = ($this->input->post("status") == "true")? "1" : "0";

		$parametros = array (
			//"idUsuarioModificar" => $this->session->userdata('idUsuario'),
			//"fechaModificar" => date('Y-m-d H:i:s'),
			"envioBolFac" => $status
		);

		$this->db->trans_start();

		$this->db->where('idCita', $this->input->post("idCita"));
		$this->db->where("envioBolFac != $status");
		$this->db->update("cita", $parametros);

		$this->db->trans_complete();

		if ($this->db->trans_status())
		{
			echo true;
		}

		echo false;
	}

	public function gCancelarCita()
	{
		$this->validarSesion();
 
		if ($this->input->post("idCita") < 1)
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {

			$parametros = array(
				'idMotivoCancelar' => $this->input->post("idMotivoCancelar"),
				'observacion_cancelar' => $this->input->post("motivoCancelar"),
				'status' => 2,
				'idUsuarioCancelar' => $this->session->userdata('idUsuario'),
				'fechaUsuarioCancelar' => date("Y-m-d H:m:s"),
			);

			$this->db->trans_start();
			$this->db->where("status != 2");
			$this->db->where('idCita', $this->input->post("idCita"));
			$this->db->update('cita', $parametros);
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
				$parametros = array(
					"disponible" => 1
				);
				
				if($this->input->post("idDisponible"))
				{
					$this->db->where('idAvailability', $this->input->post("idDisponible"));
					$this->db->update('availabilities', $parametros);
				}

				$response['message'] = "Se cancelo la cita correctamente.";
				$response['status'] = true;
			} else {
				$response['message'] = "Error. No se cancelo la cita.";
				$response['status'] = false;
			}
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	
	public function gCancelarCita2()
	{
		$this->validarSesion();
 
		if ($this->input->post("idCita") < 1)
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {

			$parametros = array(
				'idMotivoCancelar' => $this->input->post("idMotivoCancelar"),
				'observacion_cancelar' => $this->input->post("motivoCancelar"),
				'status' => 2,
				'idUsuarioCancelar' => $this->session->userdata('idUsuario'),
				'fechaUsuarioCancelar' => date("Y-m-d H:m:s"),
			);

			$this->db->trans_start();
			$this->db->where("status != 2");
			$this->db->where('idCita', $this->input->post("idCita"));
			$this->db->update('cita', $parametros);
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
				$parametros = array(
					"disponible" => 1
				);
	
				$this->db->where('idAvailability', $this->input->post("idDisponible"));
				$this->db->update('availabilities', $parametros);

				$response['message'] = "Se cancelo la cita correctamente.";
				$response['status'] = true;
			} else {
				$response['message'] = "Error. No se cancelo la cita.";
				$response['status'] = false;
			}
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function gCerrarHistorial()
	{
		$this->validarSesion();
 
		if ($this->input->post("idCita") < 1)
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {

			$cieMedico= array(
				'idCita' => $this->input->post("idCita"),
				'tiempo_enfermedad' => "",
				'relato' => "Cita Cerrada directamente",
				'funciones_biologicas_comentario' => "",
				'normales' => "",
				'antecedes_patologico' => "",
				'otros_antecedentesp' => "",
				'antecedes_patologico_dislipidemia' => "",
				'antecedes_patologico_diabestes' => "",
				'antecedes_patologico_hta' => "",
				'antecedes_patologico_asma' => "",
				'antecedes_patologico_gastritis' => "",
				'antecedes_familiar' => "",
				'otros_antecedentesf' => "",
				'relaciones_adversas' => "",
				'medicamentos' => "",
				'otros_medicamentos' => "",
				'medicamentoHabitual' => "",
				'numeroCorrelativo' => 0,
				'recomendaciones' => "",
				'idUsuario' => $this->session->userdata('idUsuario')
			);

			
			$this->db->trans_start();
			$this->db->insert("historial_cita", $cieMedico);
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
				$parametros = array(
					'status' => 0,
					'idUsuarioCerrar' => $this->session->userdata('idUsuario'),
					'fechaUsuarioCerrar' => date("Y-m-d H:m:s"),
				);
	
				$this->db->trans_start();
				$this->db->where("status != 2");
				$this->db->where('idCita', $this->input->post("idCita"));
				$this->db->update('cita', $parametros);
				$this->db->trans_complete();

				$response['message'] = "Se cerrar la cita correctamente.";
				$response['status'] = true;
				
			} else {
				$response['message'] = "Error. No se cerro la cita.";
				$response['status'] = false;
			}
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function buscarCliente()
	{
		$json = [];
		if(!empty($this->input->post("q"))){

			$this->db->select("u.idUser as id, concat(p.firstname, ' ', p.lastname, '-', p.document) as text");
	
			$this->db->from('patients p');
			$this->db->join('users u', 'u.idUser = p.idUsuario');
			$this->db->where('u.status', 1);
			//$this->db->where('u.idRol in(2, 3, 4, 5, 7)');
			$this->db->group_start();
			$this->db->like([ 'p.firstname' =>  $this->input->post("q") ]);
			$this->db->or_like('p.lastname', $this->input->post("q"));
			$this->db->or_like('p.document', $this->input->post("q"));
			$this->db->group_end(); 
			
			$this->db->order_by("p.firstname", "ASC");
			$this->db->limit(10);
	 
			$json = $this->db->get()->result();
		}
		
		echo json_encode($json);
	}

	public function buscarCie()
	{
		$json = [];
		if(!empty($this->input->post("q")) || !empty($this->input->post("busqueda"))){

			$this->db->select("id, concat(ci10, '-' , descripcion) as text");
			$this->db->from('cie');
			if($this->input->post("busqueda")){
				$this->db->where_in('id',$this->input->post("busqueda"));
			} else {
				$this->db->like('ci10', $this->input->post("q"));
				$this->db->or_like('descripcion', $this->input->post("q"));
			}

			$this->db->order_by("descripcion", "ASC");
			$this->db->limit(20);
	 
			$json = $this->db->get()->result();
		}
		
		echo json_encode($json);
	}

	
	public function sendMail($data, $subject, $ruta, $viewFile) {
	 
		$this->data['contenido'] = $this->load->view($viewFile, $data, TRUE);
			
		$message = $this->load->view("mail/mensaje", $this->data, TRUE);


 		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset']   = 'utf-8';
		$config['mailtype']  = 'html';
		$config['wordwrap'] = TRUE;
		$config['priority'] = 1;

		$this->load->library('email', $config);

		$this->email->set_newline("\r\n"); 

		$this->load->library("email");

		if($subject == "RESULTADO PRUEBA DE ANTÍGENO" || $subject == "RESULTADO EXAMENES DE LABORATORIO" || $subject == "RESULTADO PRUEBA DE PCR"){
			$this->email->from('sbclab@sbcmedic.com', "SBC Lab");
			$this->email->cc('sbclab@sbcmedic.com');
		} else if($subject == "Resultado del Examen Médico"){
			$this->email->from('admision@sbcmedic.com', "Admisión");
			$this->email->cc('admision@sbcmedic.com');
		}
		
		$this->email->to($data["email"]);
		$this->email->subject($subject);
		$this->email->attach($ruta);
		$this->email->message($message);

		$this->email->send();
	}
	

	public function gestionAntigeno()
	{
		if($this->Helper->permiso_usuario("pruebas_antigenos"))
		{
			$this->cargarDatosSesion();				
			$this->load->view('gestionAntigeno', $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}

	public function patientManagement() {
		$this->validarSesion();

		if($this->Helper->permiso_usuario("registro_paciente_antigeno"))
		{
			$arrDatos[] = "";
 
			$this->db->select('idTypeDocument, description');
			$this->db->from('type_document');
			$this->db->where('status', 1);
			$this->db->order_by("description", "asc");
			$query = $this->db->get();
	
			if ($query->num_rows() > 0) {
				foreach($query->result() as $row)
				   $arrDatos[htmlspecialchars($row->idTypeDocument, ENT_QUOTES)] = 
				htmlspecialchars($row->description, ENT_QUOTES);
		
				$query->free_result();
			 }
	 
		 
			$this->data['tipoDocumento'] = array_filter($arrDatos);

			$this->db->select("id, name as nombre ");
			$this->db->from('ubigeo_districts');
			$this->db->where("province_id", 1501);
			$this->db->where("department_id", 15);
			$this->db->order_by("name", "desc");
	
			$this->data["distritos"] = $this->db->get()->result();

			
			$this->load->view('gestionPaciente/gestion', $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}
	
		public function editPatientManagement($id) {
		$this->validarSesion();

		if($this->Helper->permiso_usuario("gestionar_farmacias"))
		{
			$arrDatos[] = "";
 
			$this->db->select('idTypeDocument, description');
			$this->db->from('type_document');
			$this->db->where('status', 1);
			$this->db->order_by("description", "asc");
			$query = $this->db->get();
	
			if ($query->num_rows() > 0) {
				foreach($query->result() as $row)
				   $arrDatos[htmlspecialchars($row->idTypeDocument, ENT_QUOTES)] = 
				htmlspecialchars($row->description, ENT_QUOTES);
		
				$query->free_result();
			 }
	 
		 
			$this->data['tipoDocumento'] = array_filter($arrDatos);

			$arrDatosPaciente[] = "";
 
			$this->db->select('tipo_antigeno, quienSolicito, email, direccion, idDistrito, telefono, hora, fecha, tipo_banco, motivo, cantidadPrueba, costo_cantidadPrueba, pruebaPromocional, costo_pruebaPromocional, transporte, costo_transporte, porcentajeDescuento, ( sum( costo_cantidadPrueba + costo_pruebaPromocional ) - porcentajeDescuento + costo_transporte + costo_cantidadPrueba_psr) AS totalCosto, tipo_psr, cantidadPrueba_psr, costo_cantidadPrueba_psr, sede');
			$this->db->from('gestion_paciente');
			$this->db->where('id', $id);
			$query = $this->db->get();
	
 
			if ($query->num_rows() == 1)
			{
				foreach ($query->result() as $row)
				{
					$this->data['quienSolicito'] =  $row->quienSolicito;
					$this->data['email'] =  $row->email;
					$this->data['direccion'] =  $row->direccion;
					$this->data['idDistrito'] =  $row->idDistrito;
					$this->data['telefono'] =  $row->telefono;
					$this->data['hora'] =  $row->hora;
					$this->data['fecha'] =  $row->fecha;
					$this->data['tipo_banco'] =  $row->tipo_banco;
					$this->data['motivo'] =  $row->motivo;
					$this->data['cantidadPrueba'] =  $row->cantidadPrueba;
					$this->data['costo_cantidadPrueba'] =  $row->costo_cantidadPrueba;
					$this->data['pruebaPromocional'] =  $row->pruebaPromocional;
					$this->data['costo_pruebaPromocional'] =  $row->costo_pruebaPromocional;
					$this->data['transporte'] =  $row->transporte;
					$this->data['costo_transporte'] =  $row->costo_transporte;
					$this->data['porcentajeDescuento'] =  $row->porcentajeDescuento;
					$this->data['totalCosto'] =  $row->totalCosto;
					$this->data['tipo_antigeno'] =  $row->tipo_antigeno;
					$this->data['tipo_psr'] =  $row->tipo_psr;
					$this->data['sede'] =  $row->sede;
					$this->data['cantidadPrueba_psr'] =  $row->cantidadPrueba_psr;
					$this->data['costo_cantidadPrueba_psr'] =  $row->costo_cantidadPrueba_psr;
				}
			}
 
			$this->data["resultadoPaciente"] = array_filter($arrDatosPaciente);

			$this->db->select("id, name as nombre ");
			$this->db->from('ubigeo_districts');
			$this->db->where("province_id", 1501);
			$this->db->where("department_id", 15);
			$this->db->order_by("name", "desc");
	
			$this->data["distritos"] = $this->db->get()->result();

			$this->db->select("nombre, apellido, dni, email, pasaporte, fechaNacimiento, resultado, tipo_prueba, telefono");
			$this->db->from('gestion_paciente_cliente2');
			$this->db->where('resultado !=3');
			$this->db->where('idGestionPaciente', $id);
			$this->db->order_by("fechaCreacion", "asc");
			
			$this->data["registros_gpc"] = $this->db->get()->result();

			$this->db->select("id, tipo, cantidad, precio");
			$this->db->from('gestion_paciente_prueba');
			$this->db->where('idGestion', $id);
			$this->db->order_by("fechaCreacion", "desc");
			
			$this->data["registros_pruebas"] = $this->db->get();

			$this->load->view('gestionPaciente/editarGestion', $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	} 
	

	public function editPatientManagement2($id) {
		$this->validarSesion();

		if($this->Helper->permiso_usuario("gestionar_farmacias"))
		{
			$arrDatos[] = "";
 
			$this->db->select('idTypeDocument, description');
			$this->db->from('type_document');
			$this->db->where('status', 1);
			$this->db->order_by("description", "asc");
			$query = $this->db->get();
	
			if ($query->num_rows() > 0) {
				foreach($query->result() as $row)
				   $arrDatos[htmlspecialchars($row->idTypeDocument, ENT_QUOTES)] = 
				htmlspecialchars($row->description, ENT_QUOTES);
		
				$query->free_result();
			 }
	 
		 
			$this->data['tipoDocumento'] = array_filter($arrDatos);

			$arrDatosPaciente[] = "";
 
			$this->db->select('tipo_antigeno, quienSolicito, email, direccion, idDistrito, telefono, hora, fecha, tipo_banco, motivo, cantidadPrueba, costo_cantidadPrueba, pruebaPromocional, costo_pruebaPromocional, transporte, costo_transporte, porcentajeDescuento, ( sum( costo_cantidadPrueba + costo_pruebaPromocional ) - sum(costo_cantidadPrueba + costo_pruebaPromocional ) * (porcentajeDescuento / 100 ) + costo_transporte + costo_cantidadPrueba_psr) AS totalCosto, tipo_psr, cantidadPrueba_psr, costo_cantidadPrueba_psr');
			$this->db->from('gestion_paciente');
			$this->db->where('id', $id);
			$query = $this->db->get();
	
 
			if ($query->num_rows() == 1)
			{
				foreach ($query->result() as $row)
				{
					$this->data['quienSolicito'] =  $row->quienSolicito;
					$this->data['email'] =  $row->email;
					$this->data['direccion'] =  $row->direccion;
					$this->data['idDistrito'] =  $row->idDistrito;
					$this->data['telefono'] =  $row->telefono;
					$this->data['hora'] =  $row->hora;
					$this->data['fecha'] =  $row->fecha;
					$this->data['tipo_banco'] =  $row->tipo_banco;
					$this->data['motivo'] =  $row->motivo;
					$this->data['cantidadPrueba'] =  $row->cantidadPrueba;
					$this->data['costo_cantidadPrueba'] =  $row->costo_cantidadPrueba;
					$this->data['pruebaPromocional'] =  $row->pruebaPromocional;
					$this->data['costo_pruebaPromocional'] =  $row->costo_pruebaPromocional;
					$this->data['transporte'] =  $row->transporte;
					$this->data['costo_transporte'] =  $row->costo_transporte;
					$this->data['porcentajeDescuento'] =  $row->porcentajeDescuento;
					$this->data['totalCosto'] =  $row->totalCosto;
					$this->data['tipo_antigeno'] =  $row->tipo_antigeno;
					$this->data['tipo_psr'] =  $row->tipo_psr;
					$this->data['cantidadPrueba_psr'] =  $row->cantidadPrueba_psr;
					$this->data['costo_cantidadPrueba_psr'] =  $row->costo_cantidadPrueba_psr;
				}
			}
 
			$this->data["resultadoPaciente"] = array_filter($arrDatosPaciente);

			$this->db->select("id, name as nombre ");
			$this->db->from('ubigeo_districts');
			$this->db->where("province_id", 1501);
			$this->db->where("department_id", 15);
			$this->db->order_by("name", "desc");
	
			$this->data["distritos"] = $this->db->get()->result();

			$this->db->select("nombre, apellido, dni, email, pasaporte, fechaNacimiento, resultado, tipo_prueba");
			$this->db->from('gestion_paciente_cliente2');
			$this->db->where('idGestionPaciente', $id);
			$this->db->order_by("fechaCreacion", "asc");
			
			$this->data["registros_gpc"] = $this->db->get()->result();
			$this->load->view('gestionPaciente/editarGestion', $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}

	public function updatePatientManagement()
	{
		$this->validarSesion();
 
		if ($this->input->post("qsolicito") =="")
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {

			$parametros = array(
				'direccion' => $this->input->post("direccion"),
				'idDistrito' => $this->input->post("distrito"),
				'telefono' => $this->input->post("telefono"),
				'hora' => $this->input->post("hora"),
				'tipo_antigeno' => $this->input->post("tipoAntigeo"),
				'cantidadPrueba' => (empty($this->input->post("quantity")))? 0 : $this->input->post("quantity"), 
				'costo_cantidadPrueba' => (empty($this->input->post("cPrueba")))? 0 : $this->input->post("cPrueba"),
				'pruebaPromocional' => $this->input->post("pPromocionales"),
				'costo_pruebaPromocional' => (empty($this->input->post("cpromocionales")))? 0 : $this->input->post("cpromocionales"),
				'tipo_psr' => $this->input->post("tipoPsr"),
				'cantidadPrueba_psr' => (empty($this->input->post("quantityPsr")))? 0 : $this->input->post("quantityPsr"), 
				'costo_cantidadPrueba_psr' => (empty($this->input->post("cPruebaPsr")))? 0 : $this->input->post("cPruebaPsr"),
				'transporte' => $this->input->post("transporte"),
				'costo_transporte' => (empty($this->input->post("cTransporte")))? 0 : $this->input->post("cTransporte"),
				'porcentajeDescuento' => (empty($this->input->post("porcentajeD")))? 0 : $this->input->post("porcentajeD"),
				'fecha' => $this->input->post("fecha"),
				'tipo_banco' => $this->input->post("tipoBanco"),
				'motivo' => $this->input->post("motivo"),
				'quienSolicito' => $this->input->post("qsolicito"),
				'email' => $this->input->post("emalqs"),
				'idUsuario' => $this->session->userdata('idUsuario'),
			);
			
			$idGestion = $this->input->post("codigoGestion");
			
			$this->db->trans_start();
			$this->db->where('id', $idGestion);
			$this->db->update('gestion_paciente', $parametros);
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
			 

				if(!empty($this->input->post("idUnico")))
				{
					$idsUnicos = $this->input->post("idUnico");
					$codigos= "";
	
					foreach ($idsUnicos as $value) {
						$codigos = $codigos.$value.",";
					}

					$codigos = substr($codigos, 0, -1);
				}


				
				
			 

				$this->db->trans_start();
				$this->db->where('idGestion', $idGestion);
				if(!empty($this->input->post("idUnico")))	$this->db->where("id not in ($codigos)");
				$this->db->delete('gestion_paciente_prueba');
				$this->db->trans_complete();

				if(!empty($this->input->post("idUnico"))){
					$this->db->where("idGestionPrueba not in ($codigos)");
					$this->db->where('codigo_procedimiento', $idGestion);
					$this->db->where('tipo_solicitud', "ANT");
					$this->db->delete('solicitud_citas_pagos');
				}


				if ($this->db->trans_status())
				{
					if(count(array_filter($this->input->post("pruebas"))) > 0) {
						for ($ii=0; $ii < count(array_filter($this->input->post("pruebas"))); $ii++) {
							
							$parametrosTipoPruebas = array(
								'tipo' => $this->input->post("pruebas")[$ii],
								'idGestion' => $idGestion,
								'cantidad' => $this->input->post("cantidad")[$ii],
								'descuento' => $this->input->post("porcentajeD"),
								'precio_transporte' => $this->input->post("cTransporte"),
								'precio' => $this->input->post("precio")[$ii],
								'idUnico' => $this->input->post("idUnico")[$ii],
								'idUsuario' => $this->session->userdata('idUsuario')
							);
		 
							//$this->db->insert("gestion_paciente_prueba", $parametrosTipoPruebas);

							$this->Helper->insert_or_updateAtigeno($parametrosTipoPruebas);
							
							if($this->input->post("pruebas")[$ii] == 1)
							{
								
								$parametros = array(
									'tipo_antigeno' => $this->input->post("pruebas")[$ii],
									'cantidadPrueba' => $this->input->post("cantidad")[$ii],
									'costo_cantidadPrueba' => $this->input->post("precio")[$ii] 
								);
	
								$this->db->where('id', $idGestion);
								$this->db->update('gestion_paciente', $parametros);
							}

							if($this->input->post("pruebas")[$ii] == 2) {
								$parametros = array(
									'tipo_psr' => $this->input->post("pruebas")[$ii],
									'cantidadPrueba_psr' => $this->input->post("cantidad")[$ii],
									'costo_cantidadPrueba_psr' => $this->input->post("precio")[$ii] 
								);
	
								$this->db->where('id', $idGestion);
								$this->db->update('gestion_paciente', $parametros);
							}
						}
						 
					}
				}

				$this->db->trans_start();
				$this->db->where('idGestionPaciente', $idGestion);
				$this->db->delete('gestion_paciente_cliente2');
				$this->db->trans_complete();

				if ($this->db->trans_status())
				{
					if(count(array_filter($this->input->post("nombre"))) > 0) {
			 

						for ($i=0; $i < count(array_filter($this->input->post("nombre"))); $i++) {

							$parametrosCliente = array(
								'idGestionPaciente' => $idGestion,
								'nombre' => $this->input->post("nombre")[$i],
								'apellido' => $this->input->post("apellido")[$i],
								'dni' => $this->input->post("dni")[$i],
								'email' => $this->input->post("email")[$i],
								'pasaporte' => $this->input->post("pasaporte")[$i],
								'resultado' => $this->input->post("consultaMedicas")[$i],
								'tipo_prueba' => $this->input->post("pcrs")[$i],
								'fechaNacimiento' => $this->input->post("fechaNacimiento")[$i],
								'telefono' => $this->input->post("telefonoPaciente")[$i],
								'idUsuario' => $this->session->userdata('idUsuario')
							);
							
							$this->db->insert("gestion_paciente_cliente2", $parametrosCliente);
						}
					}
				}
				
				$this->db->where('idGestion', $idGestion);
				$this->db->delete('gestion_paciente_descuento_transporte');

				if($this->input->post("porcentajeD") and $this->input->post("porcentajeD") > 0)
				{
					$parametrosDes = array(
						'tipo' => "DES",
						'idGestion' => $idGestion,
						'monto' => $this->input->post("porcentajeD"),
						'idUsuario' => $this->session->userdata('idUsuario')
					);
	
					$this->db->insert("gestion_paciente_descuento_transporte", $parametrosDes);
				}
	
				if($this->input->post("cTransporte") and $this->input->post("cTransporte") > 0)
				{
					$parametrosDes = array(
						'tipo' => "TRA",
						'idGestion' => $idGestion,
						'monto' => $this->input->post("cTransporte"),
						'idUsuario' => $this->session->userdata('idUsuario')
					);
	
					$this->db->insert("gestion_paciente_descuento_transporte", $parametrosDes);
				}

				$response['message'] = "Se actualizo al paciente correctamente.";
				$response['status'] = true;
				$response['idgestion'] = $idGestion;
			} else {
				$response['message'] = "Error. No se actualizo al paciente.";
				$response['status'] = false;
			}
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($response ));
		
	}
	
	public function updatePatientManagement2()
	{
		$this->validarSesion();
 
		if ($this->input->post("qsolicito") =="")
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {

			$parametros = array(
				'direccion' => $this->input->post("direccion"),
				'idDistrito' => $this->input->post("distrito"),
				'telefono' => $this->input->post("telefono"),
				'hora' => $this->input->post("hora"),
				'tipo_antigeno' => $this->input->post("tipoAntigeo"),
				'cantidadPrueba' => $this->input->post("quantity"),
				'costo_cantidadPrueba' => (empty($this->input->post("cPrueba")))? 0 : $this->input->post("cPrueba"),
				'pruebaPromocional' => $this->input->post("pPromocionales"),
				'costo_pruebaPromocional' => (empty($this->input->post("cpromocionales")))? 0 : $this->input->post("cpromocionales"),
				'tipo_psr' => $this->input->post("tipoPsr"),
				'cantidadPrueba_psr' => $this->input->post("quantityPsr"),
				'costo_cantidadPrueba_psr' => (empty($this->input->post("cPruebaPsr")))? 0 : $this->input->post("cPruebaPsr"),
				'transporte' => $this->input->post("transporte"),
				'costo_transporte' => (empty($this->input->post("cTransporte")))? 0 : $this->input->post("cTransporte"),
				'porcentajeDescuento' => (empty($this->input->post("porcentajeD")))? 0 : $this->input->post("porcentajeD"),
				'fecha' => $this->input->post("fecha"),
				'tipo_banco' => $this->input->post("tipoBanco"),
				'motivo' => $this->input->post("motivo"),
				'quienSolicito' => $this->input->post("qsolicito"),
				'email' => $this->input->post("emalqs"),
				'idUsuario' => $this->session->userdata('idUsuario'),
			);
			
			$idGestion = $this->input->post("codigoGestion");
			
			$this->db->trans_start();
			$this->db->where('id', $idGestion);
			$this->db->update('gestion_paciente', $parametros);
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
				$this->db->trans_start();
				$this->db->where('idGestionPaciente', $idGestion);
				$this->db->delete('gestion_paciente_cliente2');
				$this->db->trans_complete();

				if ($this->db->trans_status())
				{
					if(count(array_filter($this->input->post("nombre"))) > 0) {
						for ($i=0; $i < count(array_filter($this->input->post("nombre"))); $i++) {

							$parametrosCliente = array(
								'idGestionPaciente' => $idGestion,
								'nombre' => $this->input->post("nombre")[$i],
								'apellido' => $this->input->post("apellido")[$i],
								'dni' => $this->input->post("dni")[$i],
								'email' => $this->input->post("email")[$i],
								'pasaporte' => $this->input->post("pasaporte")[$i],
								'resultado' => $this->input->post("consultaMedicas")[$i],
								'tipo_prueba' => $this->input->post("pcrs")[$i],
								'fechaNacimiento' => $this->input->post("fechaNacimiento")[$i],
								'idUsuario' => $this->session->userdata('idUsuario')
							);
							
							$this->db->insert("gestion_paciente_cliente2", $parametrosCliente);
						}
					}
				}

				$response['message'] = "Se actualizo al paciente correctamente.";
				$response['status'] = true;
			} else {
				$response['message'] = "Error. No se actualizo al paciente.";
				$response['status'] = false;
			}
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($response ));
		
	}

		public function patientSearchManagement()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("envío_resultado_antigeno"))
		{
			$this->db->select("gp.id as codPrincipal,gpc.tipo_prueba, gpc.email, gpc.id, gpc.nombre, gpc.apellido, gpc.dni, gpc.pasaporte, gpc.resultado, gp.fecha, gp.hora, (select email from gestion_paciente_cliente2 where gp.id=gestion_paciente_cliente2.idGestionPaciente limit 1) as emailPrincipal, TIMESTAMPDIFF(YEAR, gpc.fechaNacimiento,CURDATE()) AS edad, gpc.nombre_pdf, (select concat(firstname, ' ', lastname) from patients where idUsuario = gpc.idUsuarioModificar) as usuarioEnvio ");
			$this->db->from('gestion_paciente gp');
			$this->db->join('gestion_paciente_cliente2 gpc', 'gpc.idGestionPaciente = gp.id');
			//$this->db->where("gpc.resultado", 0);
			$this->db->where("gpc.resultado != 3");
			
			if($this->input->post("fechaBusqueda") and empty($this->input->post("nombreDni")))	
			{
				$this->db->where("gp.fecha = '". $this->input->post("fechaBusqueda")."'");
			} else if(!$this->input->post("fechaBusqueda"))	{ 
				$this->db->where("gp.fecha = date(now())");
			}

			if($this->input->post("nombreDni"))
			{
				$this->db->group_start();
				$this->db->where("gpc.apellido like '%". $this->input->post("nombreDni")."%'");
				$this->db->or_where("gpc.nombre like '%". $this->input->post("nombreDni")."%'");
				$this->db->or_where("gpc.dni like '%". $this->input->post("nombreDni")."%'");
				$this->db->group_end();
			} 
			
			$this->db->order_by("gp.fecha", "desc");
	
			$this->data["registros"] = $this->db->get()->result();
			
			$this->load->view('gestionPaciente/index', $this->data);
		} else {
			redirect(base_url("inicio"));
		}

		$this->validarSesion();
	}
	
		public function patientSearchManagementNew()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("envío_resultado_antigeno"))
		{
			$this->data['especialidades'] = $this->Especialidad->listaAntigenos();

			$this->db->select("
			(select GROUP_CONCAT(gestion_paciente_cliente2.nombre, ' ', gestion_paciente_cliente2.apellido)  from gestion_paciente_cliente2 where gestion_paciente_cliente2.idGestionPaciente=gp.id and gestion_paciente_cliente2.resultado !=3) as nombres,
			d.`name` as distrito,
			gp.email,
			if(gp.sede = 'SBC', 'SBCMedic', 'Domicilio') as sede,
			gp.id,
			gp.pago,
			gp.quienSolicito,
			gp.fecha,
			gp.realizado,
			gp.hora,
			gp.motivo,
			gp.direccion,
			gp.telefono,
			gp.tipo_antigeno,
			gp.tipo_psr,
			gp.cantidadPrueba_psr,
			gp.costo_cantidadPrueba_psr,
			gp.cantidadPrueba AS cantidadP,
			gp.costo_cantidadPrueba,
			(select concat(firstname, ', ', lastname) from patients where idUsuario = gp.idUsuarioPago) as usuarioPago,
			gp.fechaPago,
			(select concat(firstname, ' ', lastname) from patients where idUsuario = gp.idUsuario) as usuarioRegistro,
			IF 
				( gp.pruebaPromocional = 'pacmd', 'Prueba de Antigenos + Consulta Médica a Domicilio', IF ( gp.pruebaPromocional = 'pacmdv', 'Prueba de Antigenos + Consulta Méd.Domic. + Consulta Méd.Virtual', '' ) ) AS pPromocional,
				gp.costo_pruebaPromocional,
				gp.costo_transporte,
				gp.porcentajeDescuento,
				( sum( gp.costo_cantidadPrueba + gp.costo_pruebaPromocional ) - gp.porcentajeDescuento + gp.costo_transporte + gp.costo_cantidadPrueba_psr) AS total,
			IF
				( gp.tipo_banco = 'BCP', 'Banco de Crédito', IF ( gp.tipo_banco = 'BBVA', 'BBVA Continental', IF ( gp.tipo_banco = 'SCOTBANK', 'Scotiabank', IF ( gp.tipo_banco = 'INTERB', 'Interbank', IF ( gp.tipo_banco = 'GRA', 'Gratis', IF ( gp.tipo_banco = 'EFE', 'Efectivo', IF ( gp.tipo_banco = 'TAR', 'Tarjeta', IF ( gp.tipo_banco = 'TRA', 'Transferencia', 'Efectivo' ) ) ) ) ) ) ) ) AS tipoBanco ");
			$this->db->from('gestion_paciente gp');
			$this->db->join('ubigeo_districts d', 'd.id = gp.idDistrito');
	 
			$this->db->where("gp.status", 1);
			if($this->input->post("fechaBusqueda")) $this->db->where("gp.fecha >= '". $this->input->post("fechaBusqueda")."'"); else	$this->db->where("gp.fecha >= date(now())");
			$this->db->group_by("gp.id");
			$this->db->order_by("gp.fecha desc");

			$this->data["registros"] = $this->db->get()->result();

			 
			$this->data['aPaciente_antigeno']  = $this->Helper->permiso_usuario("actualizar_paciente_antigeno");
			
			$this->load->view('gestionPaciente/resumen_new', $this->data);
		} else {
			redirect(base_url("inicio"));
		}

		
	}
	
	public function patientSearchManagement2()
	{
				$this->validarSesion();
		$this->cargarDatosSesion();
		
		if($this->Helper->permiso_usuario("envío_resultado_antigeno"))
		{
			$this->db->select("gp.id as codPrincipal, gpc.tipo_prueba, gp.email, gpc.id, gpc.nombre, gpc.apellido, gpc.dni, gpc.pasaporte, gpc.resultado, gp.fecha, gp.hora, (select email from gestion_paciente_cliente2 where gp.id=gestion_paciente_cliente2.idGestionPaciente limit 1) as emailPrincipal, TIMESTAMPDIFF(YEAR, gpc.fechaNacimiento,CURDATE()) AS edad, gpc.nombre_pdf");
			$this->db->from('gestion_paciente gp');
			$this->db->join('gestion_paciente_cliente2 gpc', 'gpc.idGestionPaciente = gp.id');
			//$this->db->where("gpc.resultado", 0);
			$this->db->where("gp.status", 1);
			$this->db->where("gpc.resultado != 3");
			$this->db->where("gp.fechaCreacion >='2021-08-06'");
			$this->db->order_by("gp.fecha", "desc");
	
			$this->data["registros"] = $this->db->get()->result();
			
			$this->load->view('gestionPaciente/index', $this->data);
		} else {
			redirect(base_url("inicio"));
		}

		$this->validarSesion();
	}
	
	public function verResultadoPdf()
	{
		$nombre = $this->uri->segment(2);
		
		$ruta =   base_url()."files/antigenos/$nombre";
 
		header("Content-type: application/pdf");
		header("Content-Disposition: inline; filename=$nombre");
		readfile($ruta);
	}

	public function patientSummaryManagement()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("resumen_antígeno"))
		{
			$this->data['especialidades'] = $this->Especialidad->listaAntigenos();
			
			$this->db->select("
			(select GROUP_CONCAT(gestion_paciente_cliente2.nombre, ' ', gestion_paciente_cliente2.apellido)  from gestion_paciente_cliente2 where gestion_paciente_cliente2.idGestionPaciente=gp.id) as nombres,
			d.`name` as distrito,
			gp.email,
			gp.id,
			if(gp.sede = 'SBC', 'SBCMedic', if(gp.sede ='DOM', 'Domicilio','')) as sede,
			gp.pago,
			gp.quienSolicito,
			gp.fecha,
			gp.realizado,
			gp.hora,
			gp.motivo,
			gp.direccion,
			gp.telefono,
			gp.tipo_antigeno,
			gp.tipo_psr,
			gp.cantidadPrueba_psr,
			gp.costo_cantidadPrueba_psr,
			gp.cantidadPrueba AS cantidadP,
			gp.costo_cantidadPrueba,
			IF 
				( gp.pruebaPromocional = 'pacmd', 'Prueba de Antigenos + Consulta Médica a Domicilio', IF ( gp.pruebaPromocional = 'pacmdv', 'Prueba de Antigenos + Consulta Méd.Domic. + Consulta Méd.Virtual', '' ) ) AS pPromocional,
				gp.costo_pruebaPromocional,
				gp.costo_transporte,
				gp.porcentajeDescuento,
				( sum( gp.costo_cantidadPrueba ) -   gp.porcentajeDescuento     + gp.costo_transporte + gp.costo_cantidadPrueba_psr ) AS total,
			IF
				( gp.tipo_banco = 'BCP', 'Banco de Crédito', IF ( gp.tipo_banco = 'BBVA', 'BBVA Continental', IF ( gp.tipo_banco = 'SCOTBANK', 'Scotiabank', IF ( gp.tipo_banco = 'INTERB', 'Interbank', 'Efectivo' ) ) ) ) AS tipoBanco ");
			$this->db->from('gestion_paciente gp');
			$this->db->join('ubigeo_districts d', 'd.id = gp.idDistrito');
			$this->db->where("gp.status", 1);
			$this->db->group_by("gp.id");
			$this->db->order_by("gp.fecha desc");
			$this->db->order_by("gp.hora desc");

			$this->data["registros"] = $this->db->get()->result();
			$this->data['aPaciente_antigeno']  = $this->Helper->permiso_usuario("actualizar_paciente_antigeno");
			
			$this->load->view('gestionPaciente/resumen', $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}

	public function patientSummaryManagementReady()
	{
		$parametros = array(
			'realizado' => $this->input->post("valor"),
			'idUsuarioRealizado' => $this->session->userdata('idUsuario'),
			'fechaRealizado' => date("Y-m-d H:m:s")
		);

		$this->db->trans_start();
		//$this->db->where("realizado = 0");
		$this->db->where('id', $this->input->post("codigo"));
		$this->db->update('gestion_paciente', $parametros);
		$this->db->trans_complete();

		if ($this->db->trans_status())
		{
			$response['status'] = true;
		} else {
			$response['status'] = false;
		}
	

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

		public function saveManagement()
	{
		$this->validarSesion();
 
		if ($this->input->post("nombre") =="")
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {

			$parametros = array(
				'direccion' => $this->input->post("direccion"),
				'idDistrito' => $this->input->post("distrito"),
				'telefono' => $this->input->post("telefono"),
				'hora' => $this->input->post("hora"),
				'tipo_antigeno' => $this->input->post("tipoAntigeo"),
				'cantidadPrueba' => (empty($this->input->post("quantity")))? 0 : $this->input->post("quantity"),
				'costo_cantidadPrueba' => (empty($this->input->post("cPrueba")))? 0 : $this->input->post("cPrueba"),
				'pruebaPromocional' => $this->input->post("pPromocionales"),
				'costo_pruebaPromocional' => (empty($this->input->post("cpromocionales")))? 0 : $this->input->post("cpromocionales"),
				'tipo_psr' => $this->input->post("tipoPsr"),
				'cantidadPrueba_psr' => (empty($this->input->post("quantityPsr")))? 0 : $this->input->post("quantityPsr"),
				'costo_cantidadPrueba_psr' => (empty($this->input->post("cPruebaPsr")))? 0 : $this->input->post("cPruebaPsr"),
				'transporte' => $this->input->post("transporte"),
				'costo_transporte' => (empty($this->input->post("cTransporte")))? 0 : $this->input->post("cTransporte"),
				'porcentajeDescuento' => (empty($this->input->post("porcentajeD")))? 0 : $this->input->post("porcentajeD"),
				'fecha' => $this->input->post("fecha"),
				'tipo_banco' => $this->input->post("tipoBanco"),
				'motivo' => $this->input->post("motivo"),
				'quienSolicito' => strtoupper($this->input->post("qsolicito")),
				'email' => $this->input->post("emalqs"),
				'sede' => $this->input->post("sede"),
				'idUsuario' => $this->session->userdata('idUsuario'),
			);

			$this->db->trans_start();
			$this->db->insert('gestion_paciente', $parametros);
			$idGestion = $this->db->insert_id();
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
				if(count(array_filter($this->input->post("pruebas"))) > 0) {
					for ($ii=0; $ii < count(array_filter($this->input->post("pruebas"))); $ii++) {
						$parametrosTipoPruebas = array(
							'tipo' => $this->input->post("pruebas")[$ii],
							'idGestion' => $idGestion,
							'cantidad' => $this->input->post("cantidad")[$ii],
							'descuento' => $this->input->post("porcentajeD"),
							'precio' => $this->input->post("precio")[$ii],
							'precio_transporte' => $this->input->post("cTransporte"),
							'idUsuario' => $this->session->userdata('idUsuario')
						);
	 
						$this->Helper->insert_or_updateAtigeno($parametrosTipoPruebas);

						//$this->db->insert("gestion_paciente_prueba", $parametrosTipoPruebas);

						if($this->input->post("pruebas")[$ii] == 1)
						{
							
							$parametros = array(
								'tipo_antigeno' => $this->input->post("pruebas")[$ii],
								'cantidadPrueba' => $this->input->post("cantidad")[$ii],
								'costo_cantidadPrueba' => $this->input->post("precio")[$ii] 
							);

							$this->db->where('id', $idGestion);
							$this->db->update('gestion_paciente', $parametros);
						}

						if($this->input->post("pruebas")[$ii] == 2) {
							$parametros = array(
								'tipo_psr' => $this->input->post("pruebas")[$ii],
								'cantidadPrueba_psr' => $this->input->post("cantidad")[$ii],
								'costo_cantidadPrueba_psr' => $this->input->post("precio")[$ii] 
							);

							$this->db->where('id', $idGestion);
							$this->db->update('gestion_paciente', $parametros);
						}
					}
				}

				if(count(array_filter($this->input->post("nombre"))) > 0) {
					for ($i=0; $i < count(array_filter($this->input->post("nombre"))); $i++) {

						$parametrosCliente = array(
							'idGestionPaciente' => $idGestion,
							'nombre' => ucfirst($this->input->post("nombre")[$i]),
							'apellido' => ucfirst($this->input->post("apellido")[$i]),
							'dni' => $this->input->post("dni")[$i],
							'email' => $this->input->post("email")[$i],
							'pasaporte' => $this->input->post("pasaporte")[$i],
							'fechaNacimiento' => $this->input->post("fechaNacimiento")[$i],
							//'consultaMedica' => $this->input->post("consultaMedicas")[$i],
							'tipo_prueba' => $this->input->post("consultaMedicas")[$i],
							'telefono' => $this->input->post("telefonoPaciente")[$i],
							'idUsuario' => $this->session->userdata('idUsuario')
						);
						
						if ($this->input->post("dni")[$i] !=""){
							$tipoDocumento = 1;
							$documento = $this->input->post("dni")[$i];
						} else if ($this->input->post("dni")[$i] =="" and $this->input->post("pasaporte")[$i] !=""){
							$tipoDocumento = 2;
							$documento = $this->input->post("pasaporte")[$i];
						} 

						$this->db->insert("gestion_paciente_cliente2", $parametrosCliente);
						$response['status'] = false;
						/*
						if($this->input->post("consultaMedicas")[$i] ==1 and $this->input->post("nombre")[$i] !="" and $this->input->post("apellido")[$i] !="" and $documento !="" and $this->input->post("email")[$i] !="" and $this->input->post("fechaNacimiento")[$i] !="") {
							$encrypted_password = md5(base64_encode($documento));

							$response['status'] = $this->Transaccion->registrarUsuario($tipoDocumento, $documento, $this->input->post("nombre")[$i], $this->input->post("apellido")[$i], $this->input->post("email")[$i], "", $this->input->post("fechaNacimiento")[$i], "", "", "", $encrypted_password);

						}
	 
						if($response['status']) {

							$subject = "Bienvenido a la familia SBC Medic";
							$data['nombres'] = $this->input->post("nombre")[$i] . " ". $this->input->post("apellido")[$i];
							$this->data['contenido'] = $this->load->view('contenido_bienvenida', $data, TRUE);				
							$message = $this->load->view('mensaje', $this->data, TRUE);
							$config['protocol'] = 'sendmail';
							$config['mailpath'] = '/usr/sbin/sendmail';
							$config['charset']   = 'utf-8';
							$config['mailtype']  = 'html';
							$config['wordwrap'] = TRUE;
							
							$this->load->library('email', $config);
							
							$this->email->set_newline("\r\n");
							$this->email->from('info@sbcmedic.com', "SBCMedic");
							$this->email->to($this->input->post("email")[$i]);
							$this->email->subject($subject);
							$this->email->message($message);
							
							$this->email->send();
						}
						*/
				}

			}
			
			
			if($this->input->post("porcentajeD") and $this->input->post("porcentajeD") > 0)
			{
				$parametrosDes = array(
					'tipo' => "DES",
					'idGestion' => $idGestion,
					'monto' => $this->input->post("porcentajeD"),
					'idUsuario' => $this->session->userdata('idUsuario')
				);

				$this->db->insert("gestion_paciente_descuento_transporte", $parametrosDes);
			}

			if($this->input->post("cTransporte") and $this->input->post("cTransporte") > 0)
			{
				$parametrosDes = array(
					'tipo' => "TRA",
					'idGestion' => $idGestion,
					'monto' => $this->input->post("cTransporte"),
					'idUsuario' => $this->session->userdata('idUsuario')
				);

				$this->db->insert("gestion_paciente_descuento_transporte", $parametrosDes);
			}
			

				$response['message'] = "Se guardo al paciente correctamente.";
				$response['status'] = true;
				$response['idgestion'] = $idGestion;
			} else {
				$response['message'] = "Error. No se guardo al paciente.";
				$response['status'] = false;
			}
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($response ));
		
	}
	
	public function saveManagement2()
	{
		$this->validarSesion();
 
		if ($this->input->post("nombre") =="")
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {

			$parametros = array(
				'direccion' => $this->input->post("direccion"),
				'idDistrito' => $this->input->post("distrito"),
				'telefono' => $this->input->post("telefono"),
				'hora' => $this->input->post("hora"),
				'tipo_antigeno' => $this->input->post("tipoAntigeo"),
				'cantidadPrueba' => $this->input->post("quantity"),
				'costo_cantidadPrueba' => (empty($this->input->post("cPrueba")))? 0 : $this->input->post("cPrueba"),
				'pruebaPromocional' => $this->input->post("pPromocionales"),
				'costo_pruebaPromocional' => (empty($this->input->post("cpromocionales")))? 0 : $this->input->post("cpromocionales"),
				'tipo_psr' => $this->input->post("tipoPsr"),
				'cantidadPrueba_psr' => $this->input->post("quantityPsr"),
				'costo_cantidadPrueba_psr' => (empty($this->input->post("cPruebaPsr")))? 0 : $this->input->post("cPruebaPsr"),
				'transporte' => $this->input->post("transporte"),
				'costo_transporte' => (empty($this->input->post("cTransporte")))? 0 : $this->input->post("cTransporte"),
				'porcentajeDescuento' => (empty($this->input->post("porcentajeD")))? 0 : $this->input->post("porcentajeD"),
				'fecha' => $this->input->post("fecha"),
				'tipo_banco' => $this->input->post("tipoBanco"),
				'motivo' => $this->input->post("motivo"),
				'quienSolicito' => $this->input->post("qsolicito"),
				'email' => $this->input->post("emalqs"),
				'idUsuario' => $this->session->userdata('idUsuario'),
			);

			$this->db->trans_start();
			$this->db->insert('gestion_paciente', $parametros);
			$idGestion = $this->db->insert_id();
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
				if(count(array_filter($this->input->post("nombre"))) > 0) {
					for ($i=0; $i < count(array_filter($this->input->post("nombre"))); $i++) {

						$parametrosCliente = array(
							'idGestionPaciente' => $idGestion,
							'nombre' => $this->input->post("nombre")[$i],
							'apellido' => $this->input->post("apellido")[$i],
							'dni' => $this->input->post("dni")[$i],
							'email' => $this->input->post("email")[$i],
							'pasaporte' => $this->input->post("pasaporte")[$i],
							'fechaNacimiento' => $this->input->post("fechaNacimiento")[$i],
							//'consultaMedica' => $this->input->post("consultaMedicas")[$i],
							'tipo_prueba' => $this->input->post("consultaMedicas")[$i],
							'idUsuario' => $this->session->userdata('idUsuario')
						);
						
						if ($this->input->post("dni")[$i] !=""){
							$tipoDocumento = 1;
							$documento = $this->input->post("dni")[$i];
						} else if ($this->input->post("dni")[$i] =="" and $this->input->post("pasaporte")[$i] !=""){
							$tipoDocumento = 2;
							$documento = $this->input->post("pasaporte")[$i];
						}

						$this->db->insert("gestion_paciente_cliente2", $parametrosCliente);
						$response['status'] = false;
						/*
						if($this->input->post("consultaMedicas")[$i] ==1 and $this->input->post("nombre")[$i] !="" and $this->input->post("apellido")[$i] !="" and $documento !="" and $this->input->post("email")[$i] !="" and $this->input->post("fechaNacimiento")[$i] !="") {
							$encrypted_password = md5(base64_encode($documento));

							$response['status'] = $this->Transaccion->registrarUsuario($tipoDocumento, $documento, $this->input->post("nombre")[$i], $this->input->post("apellido")[$i], $this->input->post("email")[$i], "", $this->input->post("fechaNacimiento")[$i], "", "", "", $encrypted_password);

						}
	 
						if($response['status']) {

							$subject = "Bienvenido a la familia SBC Medic";
							$data['nombres'] = $this->input->post("nombre")[$i] . " ". $this->input->post("apellido")[$i];
							$this->data['contenido'] = $this->load->view('contenido_bienvenida', $data, TRUE);				
							$message = $this->load->view('mensaje', $this->data, TRUE);
							$config['protocol'] = 'sendmail';
							$config['mailpath'] = '/usr/sbin/sendmail';
							$config['charset']   = 'utf-8';
							$config['mailtype']  = 'html';
							$config['wordwrap'] = TRUE;
							
							$this->load->library('email', $config);
							
							$this->email->set_newline("\r\n");
							$this->email->from('info@sbcmedic.com', "SBCMedic");
							$this->email->to($this->input->post("email")[$i]);
							$this->email->subject($subject);
							$this->email->message($message);
							
							$this->email->send();
						}
						*/
				}

			}

				$response['message'] = "Se guardo al paciente correctamente.";
				$response['status'] = true;
			} else {
				$response['message'] = "Error. No se guardo al paciente.";
				$response['status'] = false;
			}
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($response ));
		
	}
	
	public function cancelResult()
	{
		$this->validarSesion();
 
		if ($this->input->post("idGestion") < 1)
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {

			$parametros = array(
				'resultado' => 3,
				'idUsuarioModificar' => $this->session->userdata('idUsuario'),
				'fechaModificar' => date("Y-m-d H:m:s"),
			);

			$this->db->trans_start();
			$this->db->where("resultado = 0");
			$this->db->where('id', $this->input->post("idGestion"));
			$this->db->update('gestion_paciente_cliente2', $parametros);
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
				
				$this->db->select("count(idGestionPaciente) as cantidad");
				$this->db->from("gestion_paciente_cliente2");
				$this->db->where("resultado !=3");
				$this->db->where("idGestionPaciente", $this->input->post("idPrincipal"));
			   
				$query = $this->db->get();
				$row_resultadoDisponible = $query->row_array();
		 
				if($row_resultadoDisponible['cantidad'] == 0)
				{
					$this->db->trans_start();
					$this->db->where('id', $this->input->post("idPrincipal"));
					$this->db->update('gestion_paciente', array('status' => 0));
					$this->db->trans_complete();

					if ($this->db->trans_status())
					{
						$this->db->where('codigo_procedimiento', $this->input->post("idPrincipal"));
						$this->db->where('tipo_solicitud', "ANT");
						$this->db->delete('solicitud_citas_pagos');
					}
				}
				
				$response['message'] = "Se cancelo el resultado correctamente.";
				$response['status'] = true;
			} else {
				$response['message'] = "Error. No se cancelo el resultado.";
				$response['status'] = false;
			}
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function saveResultManagement()
	{
		$this->validarSesion();
 
		if ($this->input->post("idResultado") < 1)
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {
			 
			$parametros = array (
				"idUsuarioModificar" => $this->session->userdata('idUsuario'),
				"fechaModificar" => date('Y-m-d H:i:s'),
				"resultado" => $this->input->post("resultado")
			);
	
			$this->db->trans_start();

			$this->db->where('id', $this->input->post("idResultado"));
			$this->db->where("resultado", 0);
			$this->db->update("gestion_paciente_cliente2", $parametros);
	
			$this->db->trans_complete();
	
			if ($this->db->trans_status())
			{
				$nomberArchivo = md5(time()).".pdf";
 
				$this->patientManagementPdf($this->input->post("idResultado"), $nomberArchivo, $this->input->post("tipoPrueba"));
	
				 
				$this->data['email'] = $this->input->post("email");
				
				if($this->input->post("tipoPrueba") == 2) {

					$this->sendMail($this->data, "RESULTADO PRUEBA DE PCR", "files/antigenos/".$nomberArchivo, "mail/envio_antigeno_medico_pcr");
				} else {

					$this->sendMail($this->data, "RESULTADO PRUEBA DE ANTÍGENO", "files/antigenos/".$nomberArchivo, "mail/envio_antigeno_medico");
				}
				
				$this->data['email'] = $this->input->post("email");

				$parametrosPdf = array (
					"nombre_pdf" => $nomberArchivo
				);

				$this->db->where('id', $this->input->post("idResultado"));
				$this->db->update("gestion_paciente_cliente2", $parametrosPdf);

				//

				$this->db->select("count(idGestionPaciente) as cantidad");
				$this->db->from("gestion_paciente_cliente2");
				$this->db->where("resultado", 0);
				$this->db->where("resultado !=3");
				$this->db->where("idGestionPaciente", $this->input->post("codPrincipal"));
			   
				$query = $this->db->get();
				$row_resultadoDisponible = $query->row_array();
			 
				if($row_resultadoDisponible['cantidad'] == 0)
				{
					$parametrosP = array(
						'realizado' => 1,
						'idUsuarioRealizado' => $this->session->userdata('idUsuario'),
						'fechaRealizado' => date("Y-m-d H:m:s")
					);
		 
					//$this->db->where('id', $this->input->post("codPrincipal"));
					//$this->db->update('gestion_paciente', $parametrosP);
				}

				$response['message'] = "Se guardo el resultado correctamente.";
				$response['status'] = true;	
			} else {
				$response['message'] = "No guardo el resultado.";
				$response['status'] = false;	
			}
 



		 
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function saveResultManagement2()
	{
		$this->validarSesion();
 
		if ($this->input->post("idResultado") < 1)
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {
			 
			$parametros = array (
				"idUsuarioModificar" => $this->session->userdata('idUsuario'),
				"fechaModificar" => date('Y-m-d H:i:s'),
				"resultado" => $this->input->post("resultado")
			);
	
			$this->db->trans_start();

			$this->db->where('id', $this->input->post("idResultado"));
			$this->db->where("resultado", 0);
			$this->db->update("gestion_paciente_cliente2", $parametros);
	
			$this->db->trans_complete();
	
			if ($this->db->trans_status())
			{
				$nomberArchivo = md5(time()).".pdf";
 
				$this->patientManagementPdf($this->input->post("idResultado"), $nomberArchivo, $this->input->post("tipoPrueba"));
	
				 
				$this->data['email'] = $this->input->post("email");
				
				if($this->input->post("tipoPrueba") == 1) {

					$this->sendMail($this->data, "RESULTADO PRUEBA DE PCR", "files/antigenos/".$nomberArchivo, "mail/envio_antigeno_medico_pcr");
				} else {

					$this->sendMail($this->data, "RESULTADO PRUEBA DE ANTÍGENO", "files/antigenos/".$nomberArchivo, "mail/envio_antigeno_medico");
				}
				
				$this->data['email'] = $this->input->post("email");

				$parametrosPdf = array (
					"nombre_pdf" => $nomberArchivo
				);

				$this->db->where('id', $this->input->post("idResultado"));
				$this->db->update("gestion_paciente_cliente2", $parametrosPdf);

				$response['message'] = "Se guardo el resultado correctamente.";
				$response['status'] = true;	
			} else {
				$response['message'] = "No guardo el resultado.";
				$response['status'] = false;	
			}
 



		 
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function patientManagementPdf($id, $nomberArchivo, $tipo=0)
    {
		$this->validarSesion();

		$data["infoGestionPaciente"] = $this->Helper->info_gestionPaciente($id);
		
		if($tipo == 2) {
			$html = $this->load->view('pdf_exports/genera_pdf_gestion_pcr', $data, TRUE);
		} else {
			$html = $this->load->view('pdf_exports/genera_pdf_gestion', $data, TRUE);
		}
 
		// Cargamos la librería
		$this->load->library('pdfgenerator');
		// generamos el PDF. Pasemos por encima de la configuración general y definamos otro tipo de papel-Letter
		$this->pdfgenerator->generate($html, $nomberArchivo, false, "A4", "portrait", true, "files/antigenos");
	}
	
	
	public function gestionarExamenes()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		$userId = ($this->input->post("cmbUsuario")) ? $this->input->post("cmbUsuario") :  $this->uri->segment(2);
		
		$usuario = $this->Usuario->datosUsuario($userId);

		if($this->Helper->permiso_usuario("gestionar_solicitar_examenes"))
		{	
			$this->db->select("se.idUsuario, se.fechaExamen, se.numeroPedido, exa.nombre as examen, se.fechaCreacion");
			$this->db->from('solicitarexamen_orion se');
			$this->db->join('examen_orion exa', 'exa.codigo = se.idExamen');
			if ($this->input->post("codigoPedido")){
				$this->db->or_where("se.numeroPedido", $this->input->post("codigoPedido")*1);
			} else {
				$this->db->where("se.idUsuario", $userId);
			}
			$this->db->order_by("se.fechaExamen", "desc");
			
			$query = $this->db->get()->result();
		 
			$resultado  =  [];
 
			if (count($query) >0) {
				foreach($query as $row)
				{

					
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://policlinicobarranco.orion-labs.com/api/v1/ordenes?filtrar[numero_pedido]=$row->numeroPedido",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					'Accept: application/json',
					'Content-Type: application/json',
					'authorization: Bearer Dj2StjHonJhddocwyxDtfO4dO72yWbSJUSuJEb489UmYvKhdax3uNjxjt8j0'
				)
			));
	
			$response = curl_exec($curl);
			$err = curl_error($curl);
	
			curl_close($curl);
	
			if ($err) {
				echo "cURL Error #:" . $err;
			} else {
				$idOrden = 0;
				$estado = 0;

				$respuestaOrden = json_decode($response);

			 
				foreach($respuestaOrden->data as $result) {
					$idOrden =  $result->id;
					$estado =  $result->estado;
				}
			}

					$resultado[]   = array(
					'idOrden'	=> htmlspecialchars($idOrden, ENT_QUOTES),
					'estado'	=> htmlspecialchars($estado, ENT_QUOTES),
					'fechaExamen'	=> htmlspecialchars($row->fechaExamen, ENT_QUOTES),
					'numeroPedido'	=> htmlspecialchars($row->numeroPedido, ENT_QUOTES),
					'examen'	=> htmlspecialchars($row->examen, ENT_QUOTES),
					'idUsuario'	=> htmlspecialchars($row->idUsuario, ENT_QUOTES),
					'fechaCreacion'	=> htmlspecialchars($row->fechaCreacion, ENT_QUOTES)
					);

				}
				
				} 

			 
			$this->data['resultados']  = $resultado;
			$this->data['usuario']  = $usuario;

 

			$this->data['medicos'] = $this->Doctor->all();


			$this->load->view('gestionarExamenes/index', $this->data);
		} else {
			redirect(base_url("inicio"));
		}

	}
	
	
	public function buscarExamen_orion()
	{
		$json = [];
		if(!empty($this->input->post("q")) || !empty($this->input->post("busqueda"))){

			$this->db->select("codigo as id, concat(nombre) as text");
			$this->db->from('examen_orion');
			if($this->input->post("busqueda")){
				$this->db->where_in('codigo',$this->input->post("busqueda"));
			} else {
				$this->db->like('codigo', $this->input->post("q"));
				$this->db->or_like('nombre', $this->input->post("q"));
			}

			$this->db->order_by("nombre", "ASC");
			$this->db->limit(20);
	 
			$json = $this->db->get()->result();
		}
		
		echo json_encode($json);
	}
	

	public function viewResult($codigo)
	{
		if($codigo > 0) {

			$curlPdf = curl_init();

			curl_setopt_array($curlPdf, array(
				CURLOPT_URL => "https://policlinicobarranco.orion-labs.com/api/v1/ordenes/$codigo/pdf",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					'Accept: application/json',
					'Content-Type: application/json',
					'authorization: Bearer Dj2StjHonJhddocwyxDtfO4dO72yWbSJUSuJEb489UmYvKhdax3uNjxjt8j0'
				)
			));

			$responsePdf = curl_exec($curlPdf);
			$errPdf = curl_error($responsePdf);

			curl_close($curlPdf);

			if ($errPdf) {
				echo "cURL Error #:" . $errPdf;
			} else {
				//echo "ok: ".$response;
			
				$nomberArchivo = md5(time()).".pdf";

				header('Content-type: application/pdf');
				header('Content-Disposition: inline; filename="'.$nomberArchivo.'"');
				header('Content-Transfer-Encoding: binary');
				header('Accept-Ranges: bytes');
				echo $responsePdf;
			}
		}
	}	
	
 //caja
	public function administración_caja()
    {
		$this->validarSesion();
		$this->cargarDatosSesion();
		$result = array();

		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			if($this->input->post("cmbmedico") || $this->input->post("cliente") || $this->input->post("fechaInicial"))
			{
				
				$this->db->select("c.idEspecialidad, c.idAvailability, c.idCita, c.idUsuario, c.idAvailability, c.idPago, pg.monto, if(c.idPago, pg.status, IF((select count(id) as cantidad from solicitud_citas_pagos where codigo_asignacion= c.codigo_asignacion and pago= 1) > 0 , 1, 0)) as statusPago, c.tipoCita as idCitaTipo, if(`c`.`tipoCita` ='CV' || `c`.virtual = 1, 'Virtual', if(`c`.`tipoCita` ='CP', 'Presencial','Domiciliario')) as tipoCita, c.fechaCita, c.horaCita, c.motivo, c.status, c.fechaCreacion, concat(d.firstname, ' ', d.lastname) as medico, s.name as especialidad, d.codigoSala,  p.phone, concat( p.firstname, ' ', p.lastname ) AS paciente, c.codigo_asignacion");
			
				$this->db->from('cita c');
				$this->db->join('doctors d', 'd.idDoctor = c.idMedico');
				$this->db->join('specialties s', 's.idSpecialty = c.idEspecialidad');
				$this->db->join('patients p', 'p.idUsuario = c.idUsuario');
				$this->db->join('pago pg', 'pg.idPago = c.idPago', "left");
		
				 if ($this->session->userdata('rol') == 2) {
					$this->db->where('d.idUsuario', $this->session->userdata('idUsuario'));
				} 
				
				if ($this->session->userdata('rol') == 3) {
					$this->db->where('c.idUsuario', $this->session->userdata('idUsuario'));
				}
				
				if($this->input->post("cliente")) {
					$this->db->where('c.idUsuario', $this->input->post("cliente"));
				}

				if(($this->input->post("fechaInicial") and $this->input->post("fechaFinal")) and !$this->input->post("cliente")) {
					$this->db->where("c.fechaCita BETWEEN '".$this->input->post("fechaInicial"). "' and '".$this->input->post("fechaFinal"). "'");
				}

				if($this->input->post("cmbmedico") and !$this->input->post("cliente")) {
					$this->db->where('c.idMedico', $this->input->post("cmbmedico"));
				}
	 
				$this->db->where('c.status !=', 2);
				$this->db->order_by("c.fechaCita", "DESC");
		
				$query = $this->db->get()->result();
			 
				if ($query) {
					foreach($query as $row)
					{
						$result[]   = array(
						'idCita'	=> htmlspecialchars($row->idCita, ENT_QUOTES),
						'idCitaTipo'	=> htmlspecialchars($row->idCitaTipo, ENT_QUOTES),
						'idUsuario'	=> htmlspecialchars($row->idUsuario, ENT_QUOTES),
						'tipoCita'	=> htmlspecialchars($row->tipoCita, ENT_QUOTES),
						'fechaCita'	=> htmlspecialchars($row->fechaCita, ENT_QUOTES),
						'horaCita'	=> htmlspecialchars($row->horaCita, ENT_QUOTES),
						'especialidad'	=> htmlspecialchars($row->especialidad, ENT_QUOTES),
						'medico'	=> htmlspecialchars($row->medico, ENT_QUOTES),
						'status'	=> htmlspecialchars($row->status, ENT_QUOTES),
						'paciente'	=> htmlspecialchars($row->paciente, ENT_QUOTES),
						'idPago'	=> htmlspecialchars($row->idPago, ENT_QUOTES),
						'statusPago'	=> htmlspecialchars($row->statusPago, ENT_QUOTES),
						'idEspecialidad'	=> htmlspecialchars($row->idEspecialidad, ENT_QUOTES),
						'monto'	=> htmlspecialchars($row->monto, ENT_QUOTES),
						'idAvailability'	=> htmlspecialchars($row->idAvailability, ENT_QUOTES),
						'codigo_asignacion'	=> htmlspecialchars($row->codigo_asignacion, ENT_QUOTES),
						'fechaCreacion'	=> htmlspecialchars($row->fechaCreacion, ENT_QUOTES)
						);
					}
				}
			}
		
			$this->data['resultados']  = $result;
			$this->data['permisoCancelarCita']  = $this->Helper->permiso_usuario("cancelar_cita");
			$this->data['permisoVidoLlamada']  = $this->Helper->permiso_usuario("video_llamada");
			$this->data['cerrarCita']  = $this->Helper->permiso_usuario("guardar_historia_clinica");
			$this->data['realizarPago']  = $this->Helper->permiso_usuario("cambiar_status_pago");
			$this->data['consultarCita']  = $this->Helper->permiso_usuario("filtro_busqueda_cita");
			
			$this->data['medicos'] = $this->Doctor->all();

			$this->db->select("idSpecialty, name");
			$this->db->from('specialties');
			$this->db->where('status', 1);
			$this->db->order_by("name", "asc");
	
			$this->data["especialidades"] = $this->db->get()->result();


			$this->load->view('caja/index', $this->data);
		} else {
			
			redirect(base_url("inicio"));
		}
	}
	
	public function print_administración_caja()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$idcita = $this->uri->segment(3);
  
			$this->db->select("ci.idUsuarioCreacion, ci.idUsuario, ci.fechaCita, left(ci.horaCita, 5) as horaCita, concat(pa.firstname, ' ', pa.lastname) as paciente, concat(do.firstname, ' ', do.lastname) as medico, esp.name, pg.monto, pro.descripcion");
			$this->db->from('cita ci');
			$this->db->join('doctors do', 'do.idDoctor = ci.idMedico');
			$this->db->join('patients pa', 'pa.idUsuario = ci.idUsuario');
			$this->db->join('specialties esp', 'esp.idSpecialty = ci.idEspecialidad');
			$this->db->join('pago pg', 'pg.idPago = ci.idPago');
			$this->db->join('procedimientos pro', 'pro.codigo_interno = ci.codigo_procedimiento', "left");
			$this->db->where("ci.idCita", $idcita);
			
			$query = $this->db->get()->result();
				
			if ($query) {
				foreach($query as $row)
				{
					$result[]   = array(
					'fechaCita'	=> htmlspecialchars($row->fechaCita, ENT_QUOTES),
					'horaCita'	=> htmlspecialchars($row->horaCita, ENT_QUOTES),
					'paciente'	=> htmlspecialchars($row->paciente, ENT_QUOTES),
					'medico'	=> htmlspecialchars($row->medico, ENT_QUOTES),
					'monto'	=> htmlspecialchars($row->monto, ENT_QUOTES),
					'descripcion'	=> htmlspecialchars($row->descripcion, ENT_QUOTES),
					'name'	=> htmlspecialchars($row->name, ENT_QUOTES)
					);

					$this->data['usuarioCita'] = $this->Usuario->datosUsuario($row->idUsuario);
					$this->data['cantidad'] = $this->Helper->numeroHistorialClinica($row->idUsuario);
					$this->data['terminalista']  = $this->Usuario->datosUsuario($row->idUsuarioCreacion);
				}
			}
		
			$this->data['resultados']  = $result;

			$this->load->view('caja/print', $this->data);
		} else {
				
			redirect(base_url("inicio"));
		}
	}
	 
	public function agregar_caja_pago22()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$usuario = $this->uri->segment(3);
     
			$sqlResumen = "
				SELECT
					'Procedimientos' AS concepto,
					idUsuario,
					codigo_interno,
					( fechaCreacion ) AS fecha,
					SUM( precio ) AS precio,
					( SELECT monto FROM agregar_pago_procedimiento_descuento_caja WHERE codigo_interno = agregar_pago_procedimiento_caja.codigo_interno AND activo = 1 ) AS descuento 
				FROM
					agregar_pago_procedimiento_caja 
				WHERE
					agregar_pago_procedimiento_caja.idUsuario = $usuario 
					AND agregar_pago_procedimiento_caja.activo = 1 
				GROUP BY
					 3,4 /*UNION
				SELECT
					'Laboratorios' AS concepto,
					idUsuario,
					codigo_interno,
					( fechaCreacion ) AS fecha,
					SUM( precio ) AS precio,
					( SELECT monto FROM agregar_pago_laboratorio_descuento_caja WHERE codigo_interno = agregar_pago_laboratorio_caja.codigo_interno AND activo = 1 ) AS descuento 
				FROM
					agregar_pago_laboratorio_caja 
				WHERE
					idUsuario = $usuario 
					AND activo = 1 
				GROUP BY
					 3,4*/ UNION

					SELECT
					'Farmacia' AS concepto,
					idUsuario,
					codigo_interno,
					( fechaCreacion ) AS fecha,
					SUM( precio ) AS precio,
					'0' AS descuento 
				FROM
					agregar_pago_farmacia_caja 
				WHERE
					idUsuario = $usuario 
					AND activo = 1 
				GROUP BY
					id
				ORDER BY
					1,
					4 DESC
			";

			$queryResumen = $this->db->query($sqlResumen);

		 
		 
			$this->data["resumen"] = $queryResumen;
		 
			$this->load->view('caja/agregar_pago', $this->data);
		} else {
				
			redirect(base_url("inicio"));
		}
	}	
	
	public function agregar_caja_pago001()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$usuario = $this->uri->segment(3);
			$cita = $this->uri->segment(4);
			$resultados = array();
			$sqlResumen = "
				SELECT
					'Procedimientos' AS concepto,
					idCita,
					idUsuario,
					codigo_interno,
					( fechaCreacion ) AS fecha,
					SUM( precio ) AS precio,
					( SELECT monto FROM agregar_pago_procedimiento_descuento_caja WHERE codigo_interno = agregar_pago_procedimiento_caja.codigo_interno AND activo = 1 ) AS descuento 
				FROM
					agregar_pago_procedimiento_caja 
				WHERE
					(agregar_pago_procedimiento_caja.idCita = $cita /* or agregar_pago_procedimiento_caja.idUsuario = $usuario*/)
					AND agregar_pago_procedimiento_caja.activo = 1 
				GROUP BY
					 3,4,5
				order by 5 desc
				 
			";

			$queryResumen = $this->db->query($sqlResumen);
			$this->data["resumen"] = $queryResumen;
			 
		 
			$this->load->view('caja/agregar_pago', $this->data);
		} else {
				
			redirect(base_url("inicio"));
		}
	}

	public function agregar_caja_pago()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$usuario = $this->uri->segment(3);
			$cita = $this->uri->segment(4);

			if($cita > 7769)
			{
				$code = $this->uri->segment(5);

				$resultados = array();
				$sqlResumen = "
					SELECT
					solicitud_citas_pagos.id,
						procedimientos.descripcion,
						( SELECT GROUP_CONCAT(descripcion) FROM procedimientos pro INNER JOIN historial_asignacion_cita ON historial_asignacion_cita.codigo_procedimiento = pro.codigo_interno WHERE historial_asignacion_cita.idCita = $cita and historial_asignacion_cita.code_principal !='') AS procedimientoTwo,
						'Procedimientos' AS concepto,
						solicitud_citas_pagos.idCita,
						solicitud_citas_pagos.marca,
						solicitud_citas_pagos.codigo_asignacion,
						solicitud_citas_pagos.idUsuario,
						solicitud_citas_pagos.codigo_procedimiento,
						solicitud_citas_pagos.codigo_interno,
						( fechaCreacion ) AS fecha,
						if((select gratis from cita where idCita = $cita) =1 , 0, solicitud_citas_pagos.precio) as precio,
						( SELECT monto FROM solicitud_citas_pagos_descuento WHERE idProPagoCaja = solicitud_citas_pagos.id AND activo = 1 ) AS descuento 
					FROM
					solicitud_citas_pagos
						INNER JOIN procedimientos ON procedimientos.codigo_interno = solicitud_citas_pagos.codigo_procedimiento 
					WHERE
						solicitud_citas_pagos.codigo_asignacion = '$code'
						AND solicitud_citas_pagos.activo = 1
					
				";
				
				 
 
				$queryResumen = $this->db->query($sqlResumen);

				$this->db->select("ec.fechaCreacion, ec.idCita, pro.descripcion, concat( do.firstname, ' ', do.lastname ) AS doctor");
				$this->db->from('examenauxiliar_cita ec');
				$this->db->join('procedimientos pro', 'pro.codigo_interno = ec.codigoTipo');
				$this->db->join('cita ci', 'ci.idCita = ec.idCita');
				$this->db->join('doctors do', 'do.idDoctor = ci.idMedico');
				$this->db->where("ec.tipo", "PRO");
				$this->db->where("ci.idCita", $usuario);
				$this->db->order_by("ec.fechaCreacion", "desc");
				
				$resultados = $this->db->get()->result();
			
				$this->data["resumen"] = $queryResumen;
				$this->data["resultados"] = $resultados;

				$this->db->select("concat(pro.codigo_interno, '=', asc.id) as id, concat(pro.descripcion, ' = ', asc.precio) as descripcion");
				$this->db->from('solicitud_citas_pagos asc');
				$this->db->join('procedimientos pro', 'pro.codigo_interno = asc.codigo_procedimiento');
				$this->db->where("asc.activo", 1);
				$this->db->where("asc.idUsuario", $usuario );
				$this->db->group_start();
				$this->db->where("asc.codigo_asignacion is null");
				$this->db->or_where("asc.codigo_asignacion", 0);
				$this->db->group_end();
				$this->db->order_by("pro.descripcion", "desc");
			
				$resultadosProcedimientos = $this->db->get()->result();
				$this->data["resultadosPro"] = $resultadosProcedimientos;
				
				$this->load->view('cajaNew/agregar_pago_add', $this->data);
			} else {
				$sqlResumen = "
					SELECT
						'Procedimientos' AS concepto,
						idCita,
						idUsuario,
						codigo_interno,
						( fechaCreacion ) AS fecha,
						SUM( precio ) AS precio,
						( SELECT monto FROM agregar_pago_procedimiento_descuento_caja WHERE codigo_interno = agregar_pago_procedimiento_caja.codigo_interno AND activo = 1 ) AS descuento 
					FROM
						agregar_pago_procedimiento_caja 
					WHERE
						(agregar_pago_procedimiento_caja.idCita = $cita  /*or agregar_pago_procedimiento_caja.idUsuario = $usuario*/)
						AND agregar_pago_procedimiento_caja.activo = 1 
					GROUP BY
						3,4,5
					order by 5 desc
					
				";

				$queryResumen = $this->db->query($sqlResumen);
				$this->data["resumen"] = $queryResumen;
				
			
				$this->load->view('caja/agregar_pago', $this->data);
			}
	
		} else {
				
			redirect(base_url("inicio"));
		}
	}
	
	public function print_administración_add__caja()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$codigoInterno = $this->uri->segment(3);
			$tipo = $this->uri->segment(4);
			$usuario = $this->uri->segment(5);
			
			$this->data['usuarioCita'] = $this->Usuario->datosUsuario($usuario);

			if($tipo == "Laboratorios")
			{
				$this->db->select("exa.nombre as descripcion, exa.precio, ( SELECT agregar_pago_laboratorio_descuento_caja.monto FROM agregar_pago_laboratorio_descuento_caja WHERE codigo_interno =  aplc.codigo_interno AND activo = 1 ) AS descuento ");
				$this->db->from('agregar_pago_laboratorio_caja aplc');
				$this->db->join('examen exa', 'exa.id = aplc.codigo_laboratorio');
				$this->db->where("aplc.codigo_interno", $codigoInterno);
				$this->db->order_by("exa.nombre", "asc");
				
				$query = $this->db->get()->result();
					
				if ($query) {
					foreach($query as $row)
					{
						$result[]   = array(
							'examen'	=> htmlspecialchars($row->descripcion, ENT_QUOTES),
							'descuento'	=> htmlspecialchars($row->descuento*1, ENT_QUOTES),
							'precio'	=> htmlspecialchars($row->precio, ENT_QUOTES)
						);
					}
				}
			} else if($tipo == "Farmacia"){
				$this->db->select("'Farmacia' as nombre, precio");
				$this->db->from('agregar_pago_farmacia_caja');
				$this->db->where("codigo_interno", $codigoInterno);
				
				$query = $this->db->get()->result();
					
				if ($query) {
					foreach($query as $row)
					{
						$result[]   = array(
						'examen'	=> htmlspecialchars($row->nombre, ENT_QUOTES),
						'descuento'	=> 0,
						'precio'	=> htmlspecialchars($row->precio, ENT_QUOTES)
						);
					}
				}
			} else {
				$this->db->select("pro.descripcion, pro.precio, ( SELECT agregar_pago_procedimiento_descuento_caja.monto FROM agregar_pago_procedimiento_descuento_caja WHERE codigo_interno =  appc.codigo_interno AND activo = 1 ) AS descuento ");
				$this->db->from('agregar_pago_procedimiento_caja appc');
				$this->db->join('procedimientos pro', 'pro.codigo_interno = appc.codigo_procedimiento', 'left');
				$this->db->where("appc.codigo_interno", $codigoInterno);
				$this->db->order_by("pro.descripcion", "asc");

				$query = $this->db->get()->result();
			   
				if ($query) {
					foreach($query as $row)
					{
						$result[]   = array(
						'examen'	=> htmlspecialchars($row->descripcion, ENT_QUOTES),
						'descuento'	=> htmlspecialchars($row->descuento*1, ENT_QUOTES),
						'precio'	=> htmlspecialchars($row->precio, ENT_QUOTES)
						);
					}
				}	
			}
				
				$this->data['resultados']  = $result;
		 
			$this->load->view('caja/print_add', $this->data);
		} else {
				
			redirect(base_url("inicio"));
		}
	}
	
	public function administración_caja_grabar()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$idUsuario = $this->input->post("usuario");
			$codigoInterno = null;
 

			if($this->input->post("procedimiento")) {

				if(count($this->input->post("procedimiento"))) {
					for ($i=0; $i < count(array_filter($this->input->post("procedimiento"))); $i++) { 

						$costo = $this->Helper->consultar_precio($this->input->post("procedimiento")[$i]);

						$procedimientosPro = array(
							'idUsuario' => $idUsuario,
							'codigo_procedimiento' => $this->input->post("procedimiento")[$i],
							'idCita' => $this->input->post("idCita"),
							'precio' => $costo["precio"],
							'idUsuarioCreacion' => $this->session->userdata('idUsuario')
						);

						$this->db->trans_start();
						$this->db->insert("agregar_pago_procedimiento_caja", $procedimientosPro);
						$idCodigo = $this->db->insert_id();
						$this->db->trans_complete();
						

						if(is_null($codigoInterno)) {
							$codigoInterno = strtoupper(uniqid());
						}
 
						$parametros = array (
							"codigo_interno" =>$codigoInterno
						);
	
						$this->db->where('id', $idCodigo);
						$this->db->update('agregar_pago_procedimiento_caja', $parametros);

					}
				}
			}

			if($this->input->post("farmacia") > 0) {

				$procedimientosFar = array(
					'idUsuario' => $idUsuario,
					'precio' => $this->input->post("farmacia"),
					'idUsuarioCreacion' => $this->session->userdata('idUsuario')
				);

				$this->db->trans_start();
				$this->db->insert("agregar_pago_farmacia_caja", $procedimientosFar);
				$idCodigo = $this->db->insert_id();
				$this->db->trans_complete();
				

				if(is_null($codigoInterno)) {
					$codigoInterno = strtoupper(uniqid());
				}

				$parametros = array (
					"codigo_interno" =>$codigoInterno
				);

				$this->db->where('id', $idCodigo);
				$this->db->update('agregar_pago_farmacia_caja', $parametros);
			}

			$response['message'] = "Se registro correctamente.";
			$response['status'] = true;
		 
			
			$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );

		} else {
				
			redirect(base_url("inicio"));
		}
	}
	
	public function administración_caja_grabar22()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$idUsuario = $this->input->post("usuario");
			$codigoInterno = null;

			if($this->input->post("laboratorio")) {

				if(count($this->input->post("laboratorio"))) {
					for ($i=0; $i < count(array_filter($this->input->post("laboratorio"))); $i++) { 

						$costo = $this->Helper->consultar_precio($this->input->post("laboratorio")[$i], true);

						$laboratoriosLab = array(
							'idUsuario' => $idUsuario,
							'codigo_laboratorio' => $this->input->post("laboratorio")[$i],
							'precio' => $costo["precio"],
							'idUsuarioCreacion' => $this->session->userdata('idUsuario')
						);

						$this->db->trans_start();
						$this->db->insert("agregar_pago_laboratorio_caja", $laboratoriosLab);
						$idCodigo = $this->db->insert_id();
						$this->db->trans_complete();
						

						if(is_null($codigoInterno)) {
							$codigoInterno = strtoupper(uniqid());
						}
 
						$parametros = array (
							"codigo_interno" =>$codigoInterno
						);
	
						$this->db->where('id', $idCodigo);
						$this->db->update('agregar_pago_laboratorio_caja', $parametros);

					}
				}
			}

			if($this->input->post("procedimiento")) {

				if(count($this->input->post("procedimiento"))) {
					for ($i=0; $i < count(array_filter($this->input->post("procedimiento"))); $i++) { 

						$costo = $this->Helper->consultar_precio($this->input->post("procedimiento")[$i]);

						$procedimientosPro = array(
							'idUsuario' => $idUsuario,
							'codigo_procedimiento' => $this->input->post("procedimiento")[$i],
							'precio' => $costo["precio"],
							'idUsuarioCreacion' => $this->session->userdata('idUsuario')
						);

						$this->db->trans_start();
						$this->db->insert("agregar_pago_procedimiento_caja", $procedimientosPro);
						$idCodigo = $this->db->insert_id();
						$this->db->trans_complete();
						

						if(is_null($codigoInterno)) {
							$codigoInterno = strtoupper(uniqid());
						}
 
						$parametros = array (
							"codigo_interno" =>$codigoInterno
						);
	
						$this->db->where('id', $idCodigo);
						$this->db->update('agregar_pago_procedimiento_caja', $parametros);

					}
				}
			}

			if($this->input->post("farmacia") > 0) {

				$procedimientosFar = array(
					'idUsuario' => $idUsuario,
					'precio' => $this->input->post("farmacia"),
					'idUsuarioCreacion' => $this->session->userdata('idUsuario')
				);

				$this->db->trans_start();
				$this->db->insert("agregar_pago_farmacia_caja", $procedimientosFar);
				$idCodigo = $this->db->insert_id();
				$this->db->trans_complete();
				

				if(is_null($codigoInterno)) {
					$codigoInterno = strtoupper(uniqid());
				}

				$parametros = array (
					"codigo_interno" =>$codigoInterno
				);

				$this->db->where('id', $idCodigo);
				$this->db->update('agregar_pago_farmacia_caja', $parametros);
			}

			$response['message'] = "Se registro correctamente.";
			$response['status'] = true;
		 
			
			$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );

		} else {
				
			redirect(base_url("inicio"));
		}
	}

	public function administración_caja_grabar_descuento()
	{
		$descuento = $this->input->post("descuento");
		$codigoInterno = $this->input->post("codigoInterno");
		$idCita = $this->input->post("idCita");
		$usuario = $this->input->post("usuario");
		$tipo = $this->input->post("tipo");

		$parametros = array (
			"activo" => 0
		);

		$parametrosInsert = array(
			'codigo_interno' => $codigoInterno,
			'monto' => $descuento,
			'idUsuario' => $this->session->userdata('idUsuario')
		);

		if ($tipo == "Procedimientos")
		{
			$this->db->trans_start();
			$this->db->where('codigo_interno', $codigoInterno);
			$this->db->update('agregar_pago_procedimiento_descuento_caja', $parametros);
			$this->db->trans_complete();
	

	
			$this->db->trans_start();
			$this->db->insert("agregar_pago_procedimiento_descuento_caja", $parametrosInsert);
			$this->db->trans_complete();

		} 

		redirect(base_url("cash-management/addPay/$usuario/$idCita/0"));
	}
	
	public function administración_caja_grabar_descuento22()
	{
		$descuento = $this->input->post("descuento");
		$codigoInterno = $this->input->post("codigoInterno");
		$usuario = $this->input->post("usuario");
		$tipo = $this->input->post("tipo");

		$parametros = array (
			"activo" => 0
		);

		$parametrosInsert = array(
			'codigo_interno' => $codigoInterno,
			'monto' => $descuento,
			'idUsuario' => $this->session->userdata('idUsuario')
		);

		if ($tipo == "Procedimientos")
		{
			$this->db->trans_start();
			$this->db->where('codigo_interno', $codigoInterno);
			$this->db->update('agregar_pago_procedimiento_descuento_caja', $parametros);
			$this->db->trans_complete();
	

	
			$this->db->trans_start();
			$this->db->insert("agregar_pago_procedimiento_descuento_caja", $parametrosInsert);
			$this->db->trans_complete();

		} else if ($tipo == "Laboratorios") {
			$this->db->trans_start();
			$this->db->where('codigo_interno', $codigoInterno);
			$this->db->update('agregar_pago_laboratorio_descuento_caja', $parametros);
			$this->db->trans_complete();
	
			$this->db->trans_start();
			$this->db->insert("agregar_pago_laboratorio_descuento_caja", $parametrosInsert);
			$this->db->trans_complete();
		}


		redirect(base_url("cash-management/addPay/$usuario"));
	}
	
	public function administración_caja_eliminar()
	{
		$codigoInterno = $this->input->post("codigoInterno");
		$idCita = $this->input->post("idCita");
		$usuario = $this->input->post("usuario");
		$tipo = $this->input->post("tipo");

		$parametros = array (
			"activo" => 0
		);

		if($tipo == "Laboratorios")
		{
			$this->db->trans_start();
			$this->db->where('codigo_interno', $codigoInterno);
			$this->db->update('agregar_pago_laboratorio_caja', $parametros);
			$this->db->trans_complete();
		} else if($tipo == "Procedimientos")
		{
			$this->db->trans_start();
			$this->db->where('codigo_interno', $codigoInterno);
			$this->db->update('agregar_pago_procedimiento_caja', $parametros);
			$this->db->trans_complete();
		}  


		redirect(base_url("cash-management/addPay/$usuario/$idCita"));
	}
	
	public function administración_caja_eliminar222()
	{
		$codigoInterno = $this->input->post("codigoInterno");
		$usuario = $this->input->post("usuario");
		$tipo = $this->input->post("tipo");

		$parametros = array (
			"activo" => 0
		);

		if($tipo == "Laboratorios")
		{
			$this->db->trans_start();
			$this->db->where('codigo_interno', $codigoInterno);
			$this->db->update('agregar_pago_laboratorio_caja', $parametros);
			$this->db->trans_complete();
		} else if($tipo == "Procedimientos")
		{
			$this->db->trans_start();
			$this->db->where('codigo_interno', $codigoInterno);
			$this->db->update('agregar_pago_procedimiento_caja', $parametros);
			$this->db->trans_complete();
		} else {
			$this->db->trans_start();
			$this->db->where('codigo_interno', $codigoInterno);
			$this->db->update('agregar_pago_farmacia_caja', $parametros);
			$this->db->trans_complete();
		}


		redirect(base_url("cash-management/addPay/$usuario"));
	}

	public function buscarProcedimientos()
	{
		$json = [];
		if(!empty($this->input->post("q")) || !empty($this->input->post("busqueda"))){

			$this->db->select("codigo_interno as id, concat(descripcion, ' = ', precio, ' => Min. : ', tiempo) as text");
			$this->db->from('procedimientos');
			$this->db->where('status', 1);
			if($this->input->post("busqueda")){
				$this->db->where_in('codigo_interno',$this->input->post("busqueda"));
			} else {
				$this->db->like('descripcion', $this->input->post("q"));
			}

			$this->db->order_by("descripcion", "ASC");
			$this->db->limit(20);
	 
			$json = $this->db->get()->result();
		}
		
		echo json_encode($json);
	}
	

	public function buscaridProcedimientos()
	{
		$json = [];
		if(!empty($this->input->post("q"))){

			$this->db->select("id, descripcion as text");
			$this->db->from('procedimientos');
			$this->db->where('status', 1);
			$this->db->like('descripcion', $this->input->post("q"));

			$this->db->order_by("descripcion", "ASC");
			$this->db->limit(20);
	 
			$json = $this->db->get()->result();
		}
		
		echo json_encode($json);
	}
	
	public function grabar_papanicolau()
	{
		$user = ($this->input->post("idUsuario"))? $this->input->post("idUsuario") : $this->session->userdata('idUsuario');
		
		if($this->input->post("idTipo"))
		{
			$parametroUpdate = array (
				'calidad_muestra' => $this->input->post("calidad_muestra"),
				'flora_doderlein' => $this->input->post("flora_doderlein"),
				'polimorfonucleares' => $this->input->post("polimorfonucleares"),
				'hematies' => $this->input->post("hematies"),
				'filamentos_mucoides' => $this->input->post("filamentos_mucoides"),
				'candida_albicans' => $this->input->post("candida_albicans"),
				'gardnerella_vaginalis' => $this->input->post("gardnerella_vaginalis"),
				'herpes' => $this->input->post("herpes"),
				'resultados' => $this->input->post("resultados"),
				'observaciones' => $this->input->post("observaciones"),
				'idUsuarioActulizar' => $this->session->userdata('idUsuario'),
				'fechaActulizar' => date('Y-m-d H:i:s')
			);

			$this->db->trans_start();
			$this->db->where('id', $this->input->post("idTipo"));
			$this->db->update("papanicolau", $parametroUpdate);
			$this->db->trans_complete();
		} else {
			if($this->input->post("calidad_muestra") || $this->input->post("flora_doderlein") || $this->input->post("polimorfonucleares") || $this->input->post("hematies") || $this->input->post("filamentos_mucoides") || $this->input->post("candida_albicans") || $this->input->post("gardnerella_vaginalis") || $this->input->post("herpes")) {
				$parametros= array(
					'idExamen' => $this->input->post("idExamen"),
					'calidad_muestra' => $this->input->post("calidad_muestra"),
					'flora_doderlein' => $this->input->post("flora_doderlein"),
					'polimorfonucleares' => $this->input->post("polimorfonucleares"),
					'hematies' => $this->input->post("hematies"),
					'filamentos_mucoides' => $this->input->post("filamentos_mucoides"),
					'candida_albicans' => $this->input->post("candida_albicans"),
					'gardnerella_vaginalis' => $this->input->post("gardnerella_vaginalis"),
					'herpes' => $this->input->post("herpes"),
					'resultados' => $this->input->post("resultados"),
					'observaciones' => $this->input->post("observaciones"),
					'idUsuario' => $user,
					'idUsuarioActulizar' => $this->session->userdata('idUsuario')
				);

				$this->db->trans_start();
				$this->db->insert('papanicolau', $parametros);
				$idLab = $this->db->insert_id();
				$this->db->trans_complete();
			}
		}

		if ($this->db->trans_status())
		{
			if(isset($idLab))
			{
				$parametro = array (
					"estado" => 1,
					"idTipo" => $idLab
				);

				$this->db->where('id', $this->input->post("idSolicitud"));
				$this->db->update("solicitarexamen", $parametro);
			}

			$response['message'] = "SE REGISTRO CORRECTAMENTE.";
			$response['status'] = true;
		} else {
			$response['message'] = "NO SE REGISTRO.";
			$response['status'] = false;
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function grabar_biopsia()
	{
		$user = ($this->input->post("idUsuario"))? $this->input->post("idUsuario") : $this->session->userdata('idUsuario');
		
		if($this->input->post("idTipo"))
		{
			$parametroUpdate = array (
				'muestra_remitida' => $this->input->post("muestra_remitida"),
				'conclusion' => $this->input->post("conclusion"),
				'examen_microscopico' => $this->input->post("examen_microscopico"),
				'idUsuarioActulizar' => $this->session->userdata('idUsuario'),
				'fechaActulizar' => date('Y-m-d H:i:s')
			);

			$this->db->trans_start();
			$this->db->where('id', $this->input->post("idTipo"));
			$this->db->update("biopsia_pequena", $parametroUpdate);
			$this->db->trans_complete();
		} else {
			if($this->input->post("muestra_remitida") || $this->input->post("conclusion") || $this->input->post("examen_microscopico")) {
				$parametros= array(
					'idExamen' => $this->input->post("idExamen"),
					'muestra_remitida' => $this->input->post("muestra_remitida"),
					'conclusion' => $this->input->post("conclusion"),
					'examen_microscopico' => $this->input->post("examen_microscopico"),
					'idUsuario' => $user,
					'idUsuarioActulizar' => $this->session->userdata('idUsuario')
				);

				$this->db->trans_start();
				$this->db->insert('biopsia_pequena', $parametros);
				$idLab = $this->db->insert_id();
				$this->db->trans_complete();
			}
		}

		if ($this->db->trans_status())
		{
			if(isset($idLab))
			{
				$parametro = array (
					"estado" => 1,
					"idTipo" => $idLab
				);

				$this->db->where('id', $this->input->post("idSolicitud"));
				$this->db->update("solicitarexamen", $parametro);
			}

			$response['message'] = "SE REGISTRO CORRECTAMENTE.";
			$response['status'] = true;
		} else {
			$response['message'] = "NO SE REGISTRO.";
			$response['status'] = false;
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function grabar_vdrl()
	{
		$user = ($this->input->post("idUsuario"))? $this->input->post("idUsuario") : $this->session->userdata('idUsuario');
		
		if($this->input->post("idTipo"))
		{
			$parametroUpdate = array (
				'dato1' => $this->input->post("dato1"),
				'observacion' => $this->input->post("observacion"),
				'idUsuarioActulizar' => $this->session->userdata('idUsuario'),
				'fechaActulizar' => date('Y-m-d H:i:s')
			);

			$this->db->trans_start();
			$this->db->where('id', $this->input->post("idTipo"));
			$this->db->update("laboratorio", $parametroUpdate);
			$this->db->trans_complete();
		} else {
 
			$parametros= array(
				'idExamen' => $this->input->post("idExamen"),
				'dato1' => $this->input->post("dato1"),
				'observacion' => $this->input->post("observacion"),
				'idUsuario' => $user,
				'idUsuarioActulizar' => $this->session->userdata('idUsuario')
			);

			$this->db->trans_start();
			$this->db->insert('laboratorio', $parametros);
			$idLab = $this->db->insert_id();
			$this->db->trans_complete();
		}

		if ($this->db->trans_status())
		{
			if(isset($idLab))
			{
				$parametro = array (
					"estado" => 1,
					"idTipo" => $idLab
				);

				$this->db->where('id', $this->input->post("idSolicitud"));
				$this->db->update("solicitarexamen", $parametro);
			}

			$response['message'] = "SE REGISTRO CORRECTAMENTE.";
			$response['status'] = true;
		} else {
			$response['message'] = "NO SE REGISTRO.";
			$response['status'] = false;
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}	
	
	public function info_paciente()
	{
		$user = $this->input->post("idUsuario");
		
		$response['message'] = "No se conecto con la bd";
		$response['status'] = false;

		if($user) {

			$this->db->select("document, email, phone, birthdate, concat(firstname, ', ', lastname) as nombres");
			$this->db->from('patients');
			$this->db->where_in('idUsuario', $user);

			$query = $this->db->get();
			$response['info'] = $query->row_array();


			$response['message'] = "SE REGISTRO EL EXAMEN CORRECTAMENTE.";
			$response['status'] = true;
		} 
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );	
	}
	
	public function grabar_SecrecionVaginal()
	{

		$user = ($this->input->post("idUsuario"))? $this->input->post("idUsuario") : $this->session->userdata('idUsuario');
		
		if($this->input->post("idTipo"))
		{
			$parametroUpdate = array (
				'celulas' => $this->input->post("celulas"),
				'leucocitos' => $this->input->post("leucocitos"),
				'trichomonas' => $this->input->post("trichomonas"),
				'levaduras' => $this->input->post("levaduras"),
				'fdoderlein' => $this->input->post("fdoderlein"),
				'germenes' => $this->input->post("germenes"),
				'seaislo' => $this->input->post("seaislo"),
				'observacion' => $this->input->post("observacion"),
				'idUsuarioActulizar' => $this->session->userdata('idUsuario'),
				'fechaActulizar' => date('Y-m-d H:i:s')
			);

			$this->db->trans_start();
			$this->db->where('id', $this->input->post("idTipo"));
			$this->db->update("secrecion_vaginal", $parametroUpdate);
			$this->db->trans_complete();
		} else {

			if($this->input->post("celulas") || $this->input->post("leucocitos")  || $this->input->post("trichomonas")  || $this->input->post("levaduras")  || $this->input->post("fdoderlein") ) {
			 
				$parametros= array(
					'idExamen' => $this->input->post("idExamen"),
					'celulas' => $this->input->post("celulas"),
					'leucocitos' => $this->input->post("leucocitos"),
					'trichomonas' => $this->input->post("trichomonas"),
					'levaduras' => $this->input->post("levaduras"),
					'fdoderlein' => $this->input->post("fdoderlein"),
					'germenes' => $this->input->post("germenes"),
					'seaislo' => $this->input->post("seaislo"),
					'observacion' => $this->input->post("observacion"),
					'idUsuario' => $user,
					'idUsuarioActulizar' => $this->session->userdata('idUsuario')
				);

				$this->db->trans_start();
				$this->db->insert('secrecion_vaginal', $parametros);
				$idLab = $this->db->insert_id();
				$this->db->trans_complete();
			}
		}

		if ($this->db->trans_status())
		{
			if(isset($idLab))
			{
				$parametro = array (
					"estado" => 1,
					"idTipo" => $idLab
				);

				$this->db->where('id', $this->input->post("idSolicitud"));
				$this->db->update("solicitarexamen", $parametro);
			}

			$response['message'] = "SE REGISTRO CORRECTAMENTE.";
			$response['status'] = true;
		} else {
			$response['message'] = "NO SE REGISTRO.";
			$response['status'] = false;
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function grabar_plantillaGeneral()
	{

		$user = ($this->input->post("idUsuario"))? $this->input->post("idUsuario") : $this->session->userdata('idUsuario');
		
		if($this->input->post("idTipo"))
		{
			$parametroUpdate = array (
				'dato1' => $this->input->post("dato1"),
				'dato4' => $this->input->post("dato4"),
				'observacion' => $this->input->post("observacion"),
				'idUsuarioActulizar' => $this->session->userdata('idUsuario'),
				'fechaActulizar' => date('Y-m-d H:i:s')
			);

			$this->db->trans_start();
			$this->db->where('id', $this->input->post("idTipo"));
			$this->db->update("laboratorio", $parametroUpdate);
			$this->db->trans_complete();
		} else {

			if($this->input->post("dato1") || $this->input->post("dato4")) {
			 
				$parametros= array(
					'idExamen' => $this->input->post("idExamen"),
					'dato1' => $this->input->post("dato1"),
					'dato4' => $this->input->post("dato4"),
					'observacion' => $this->input->post("observacion"),
					'idUsuario' => $user,
					'idUsuarioActulizar' => $this->session->userdata('idUsuario')
				);

				$this->db->trans_start();
				$this->db->insert('laboratorio', $parametros);
				$idLab = $this->db->insert_id();
				$this->db->trans_complete();
			}
		}

		if ($this->db->trans_status())
		{
			if(isset($idLab))
			{
				$parametro = array (
					"estado" => 1,
					"idTipo" => $idLab
				);

				$this->db->where('id', $this->input->post("idSolicitud"));
				$this->db->update("solicitarexamen", $parametro);
			}

			$response['message'] = "SE REGISTRO CORRECTAMENTE.";
			$response['status'] = true;
		} else {
			$response['message'] = "NO SE REGISTRO.";
			$response['status'] = false;
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}


	public function citas_programadas()
	{
		 
		$fecha = $this->uri->segment(2);
		$especialiadad = $this->uri->segment(3);

		$this->validarSesion();

		$this->cargarDatosSesion();

		$this->db->select("ci.idCita, ci.fechaCita, ci.horaCita, ci.status, if(ci.status = 0, 'Atendido', if(ci.status = 1, 'Sin Atender', 'Cancelado')) as estadoCita, , pro.descripcion as especialidad, concat(d.firstname, ', ', d.lastname) as medico, concat(pac.document, ' - ', pac.firstname, ' ', pac.lastname) as paciente");

		$this->db->from('cita ci');
		$this->db->join('patients pac', 'pac.idUsuario = ci.idUsuario');
		$this->db->join('doctors d', 'd.idDoctor = ci.idMedico');
		$this->db->join('procedimientos pro', 'pro.codigo_interno = ci.codigo_procedimiento', "left");
		$this->db->where('ci.fechaCita', $fecha);
		$this->db->where('d.idSpecialty', $especialiadad);
		$this->db->order_by("ci.fechaCita", "desc");
		$this->db->order_by("ci.idMedico", "desc");
		$this->db->order_by("ci.horaCita", "desc");

		$this->data["registros"] = $this->db->get();

		$this->load->view('citas_programadas', $this->data);
	}	
	
	//test
	
	
	
		
 //caja
	public function administración_caja2()
    {
		$this->validarSesion();
		$this->cargarDatosSesion();
		$result = array();

		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			if($this->input->post("cmbmedico") || $this->input->post("cliente") || $this->input->post("fechaInicial"))
			{
				
				$this->db->select("c.idEspecialidad, c.idAvailability, c.idCita, c.idUsuario, c.idAvailability, c.idPago, pg.monto, pg.status as statusPago, c.tipoCita as idCitaTipo, if(`c`.`tipoCita` ='CV' || `c`.virtual = 1, 'Virtual', if(`c`.`tipoCita` ='CP', 'Presencial','Domiciliario')) as tipoCita, c.fechaCita, c.horaCita, c.motivo, c.status, c.fechaCreacion, concat(d.firstname, ' ', d.lastname) as medico, s.name as especialidad, d.codigoSala,  p.phone, concat( p.firstname, ' ', p.lastname ) AS paciente");
			
				$this->db->from('cita c');
				$this->db->join('doctors d', 'd.idDoctor = c.idMedico');
				$this->db->join('specialties s', 's.idSpecialty = c.idEspecialidad');
				$this->db->join('patients p', 'p.idUsuario = c.idUsuario');
				$this->db->join('pago pg', 'pg.idPago = c.idPago');
		
				 if ($this->session->userdata('rol') == 2) {
					$this->db->where('d.idUsuario', $this->session->userdata('idUsuario'));
				} 
				
				if ($this->session->userdata('rol') == 3) {
					$this->db->where('c.idUsuario', $this->session->userdata('idUsuario'));
				}
				
				if($this->input->post("cliente")) {
					$this->db->where('c.idUsuario', $this->input->post("cliente"));
				}

				if(($this->input->post("fechaInicial") and $this->input->post("fechaFinal")) and !$this->input->post("cliente")) {
					$this->db->where("c.fechaCita BETWEEN '".$this->input->post("fechaInicial"). "' and '".$this->input->post("fechaFinal"). "'");
				}

				if($this->input->post("cmbmedico") and !$this->input->post("cliente")) {
					$this->db->where('c.idMedico', $this->input->post("cmbmedico"));
				}
	 
				$this->db->where('c.status !=', 2);
				$this->db->order_by("c.fechaCita", "DESC");
		
				$query = $this->db->get()->result();
			 
				if ($query) {
					foreach($query as $row)
					{
						$result[]   = array(
						'idCita'	=> htmlspecialchars($row->idCita, ENT_QUOTES),
						'idCitaTipo'	=> htmlspecialchars($row->idCitaTipo, ENT_QUOTES),
						'idUsuario'	=> htmlspecialchars($row->idUsuario, ENT_QUOTES),
						'tipoCita'	=> htmlspecialchars($row->tipoCita, ENT_QUOTES),
						'fechaCita'	=> htmlspecialchars($row->fechaCita, ENT_QUOTES),
						'horaCita'	=> htmlspecialchars($row->horaCita, ENT_QUOTES),
						'especialidad'	=> htmlspecialchars($row->especialidad, ENT_QUOTES),
						'medico'	=> htmlspecialchars($row->medico, ENT_QUOTES),
						'status'	=> htmlspecialchars($row->status, ENT_QUOTES),
						'paciente'	=> htmlspecialchars($row->paciente, ENT_QUOTES),
						'idPago'	=> htmlspecialchars($row->idPago, ENT_QUOTES),
						'statusPago'	=> htmlspecialchars($row->statusPago, ENT_QUOTES),
						'idEspecialidad'	=> htmlspecialchars($row->idEspecialidad, ENT_QUOTES),
						'monto'	=> htmlspecialchars($row->monto, ENT_QUOTES),
						'idAvailability'	=> htmlspecialchars($row->idAvailability, ENT_QUOTES),
						'fechaCreacion'	=> htmlspecialchars($row->fechaCreacion, ENT_QUOTES)
						);
					}
				}
			}
		
			$this->data['resultados']  = $result;
			$this->data['permisoCancelarCita']  = $this->Helper->permiso_usuario("cancelar_cita");
			$this->data['permisoVidoLlamada']  = $this->Helper->permiso_usuario("video_llamada");
			$this->data['cerrarCita']  = $this->Helper->permiso_usuario("guardar_historia_clinica");
			$this->data['realizarPago']  = $this->Helper->permiso_usuario("cambiar_status_pago");
			$this->data['consultarCita']  = $this->Helper->permiso_usuario("filtro_busqueda_cita");
			
			$this->data['medicos'] = $this->Doctor->all();

			$this->db->select("idSpecialty, name");
			$this->db->from('specialties');
			$this->db->where('status', 1);
			$this->db->order_by("name", "asc");
	
			$this->data["especialidades"] = $this->db->get()->result();


			$this->load->view('caja2/index', $this->data);
		} else {
			
			redirect(base_url("inicio"));
		}
	}
	
	public function print_administración_caja2()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$idcita = $this->uri->segment(3);
  
			$this->db->select("ci.idUsuarioCreacion, ci.idUsuario, ci.fechaCita, ci.horaCita, concat(pa.firstname, ' ', pa.lastname) as paciente, concat(do.firstname, ' ', do.lastname) as medico, esp.name, pg.monto, pro.descripcion");
			$this->db->from('cita ci');
			$this->db->join('doctors do', 'do.idDoctor = ci.idMedico');
			$this->db->join('patients pa', 'pa.idUsuario = ci.idUsuario');
			$this->db->join('specialties esp', 'esp.idSpecialty = ci.idEspecialidad');
			$this->db->join('pago pg', 'pg.idPago = ci.idPago');
			$this->db->join('procedimientos pro', 'pro.codigo_interno = ci.codigo_procedimiento', "left");
			$this->db->where("ci.idCita", $idcita);
			
			$query = $this->db->get()->result();
			 
			 
			if ($query) {
				foreach($query as $row)
				{
					$result[]   = array(
					'fechaCita'	=> htmlspecialchars($row->fechaCita, ENT_QUOTES),
					'horaCita'	=> htmlspecialchars($row->horaCita, ENT_QUOTES),
					'paciente'	=> htmlspecialchars($row->paciente, ENT_QUOTES),
					'medico'	=> htmlspecialchars($row->medico, ENT_QUOTES),
					'monto'	=> htmlspecialchars($row->monto, ENT_QUOTES),
					'descripcion'	=> htmlspecialchars($row->descripcion, ENT_QUOTES),
					'name'	=> htmlspecialchars($row->name, ENT_QUOTES)
					);

					$this->data['usuarioCita'] = $this->Usuario->datosUsuario($row->idUsuario);
					$this->data['cantidad'] = $this->Helper->numeroHistorialClinica($row->idUsuario);

					$this->data['terminalista']  = $this->Usuario->datosUsuario($row->idUsuarioCreacion);
				}
			}
		
			$this->data['resultados']  = $result;
		 


			$this->load->view('caja2/print', $this->data);
		} else {
				
			redirect(base_url("inicio"));
		}
	}
	
	public function agregar_caja_pago2()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$cita = $this->uri->segment(4);
     
			$sqlResumen = "
				SELECT
					'Procedimientos' AS concepto,
					idCita,
					codigo_interno,
					( fechaCreacion ) AS fecha,
					SUM( precio ) AS precio,
					( SELECT monto FROM agregar_pago_procedimiento_descuento_caja WHERE codigo_interno = agregar_pago_procedimiento_caja.codigo_interno AND activo = 1 ) AS descuento 
				FROM
					agregar_pago_procedimiento_caja 
				WHERE
					agregar_pago_procedimiento_caja.idCita =   
					AND agregar_pago_procedimiento_caja.activo = 1 
				GROUP BY
					 3,4 UNION
			/* 	SELECT
					'Laboratorios' AS concepto,
					idUsuario,
					codigo_interno,
					( fechaCreacion ) AS fecha,
					SUM( precio ) AS precio,
					( SELECT monto FROM agregar_pago_laboratorio_descuento_caja WHERE codigo_interno = agregar_pago_laboratorio_caja.codigo_interno AND activo = 1 ) AS descuento 
				FROM
					agregar_pago_laboratorio_caja 
				WHERE
					idUsuario =  
					AND activo = 1 
				GROUP BY
					 3,4 
					  
					 UNION*/

					SELECT
					'Farmacia' AS concepto,
					idCita,
					codigo_interno,
					( fechaCreacion ) AS fecha,
					SUM( precio ) AS precio,
					'0' AS descuento 
				FROM
					agregar_pago_farmacia_caja 
				WHERE
					idCita =  
					AND activo = 1 
				GROUP BY
					id
				ORDER BY
					1,
					4 DESC
			";

			$sqlResumen = "
					SELECT
					'Procedimientos' AS concepto,
					procedimientos.descripcion,
					agregar_pago_procedimiento_caja.id,
					agregar_pago_procedimiento_caja.pago,
					agregar_pago_procedimiento_caja.codigo_interno,
					agregar_pago_procedimiento_caja.idCita,
					agregar_pago_procedimiento_caja.codigo_interno,
					( agregar_pago_procedimiento_caja.fechaCreacion ) AS fecha,
					agregar_pago_procedimiento_caja.precio,
					( SELECT monto FROM agregar_pago_procedimiento_descuento_caja WHERE idProPagoCaja = agregar_pago_procedimiento_caja.id AND activo = 1 ) AS descuento 
				FROM
					agregar_pago_procedimiento_caja
					INNER JOIN procedimientos ON procedimientos.codigo_interno = agregar_pago_procedimiento_caja.codigo_procedimiento 
				WHERE
					agregar_pago_procedimiento_caja.idCita = $cita 
					AND agregar_pago_procedimiento_caja.activo = 1
					ORDER BY agregar_pago_procedimiento_caja.fechaCreacion desc
			";

			$queryResumen = $this->db->query($sqlResumen);

		 
		 
			$this->data["resumen"] = $queryResumen;
		 
			$this->load->view('caja2/agregar_pago', $this->data);
		} else {
				
			redirect(base_url("inicio"));
		}
	}	
	
	public function print_administración_add__caja2()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$result = array();

			$codigoInterno = $this->uri->segment(6);
			$tipo = $this->uri->segment(5);
			$usuario = $this->uri->segment(3);
			$cita = $this->uri->segment(4);
			
			$this->data['usuarioCita'] = $this->Usuario->datosUsuario($usuario);
			$this->data['cita'] = $cita;

			if($tipo == "Laboratorios")
			{
				$this->db->select("exa.nombre as descripcion, exa.precio, ( SELECT agregar_pago_laboratorio_descuento_caja.monto FROM agregar_pago_laboratorio_descuento_caja WHERE codigo_interno =  aplc.codigo_interno AND activo = 1 ) AS descuento ");
				$this->db->from('agregar_pago_laboratorio_caja aplc');
				$this->db->join('examen exa', 'exa.id = aplc.codigo_laboratorio');
				$this->db->where("aplc.codigo_interno", $codigoInterno);
				$this->db->order_by("exa.nombre", "asc");
				
				$query = $this->db->get()->result();
					
				if ($query) {
					foreach($query as $row)
					{
						$result[]   = array(
							'examen'	=> htmlspecialchars($row->descripcion, ENT_QUOTES),
							'descuento'	=> htmlspecialchars($row->descuento*1, ENT_QUOTES),
							'precio'	=> htmlspecialchars($row->precio, ENT_QUOTES)
						);
					}
				}
			} else if($tipo == "Farmacia"){
				$this->db->select("'Farmacia' as nombre, precio");
				$this->db->from('agregar_pago_farmacia_caja');
				$this->db->where("codigo_interno", $codigoInterno);
				
				$query = $this->db->get()->result();
					
				if ($query) {
					foreach($query as $row)
					{
						$result[]   = array(
						'examen'	=> htmlspecialchars($row->nombre, ENT_QUOTES),
						'descuento'	=> 0,
						'precio'	=> htmlspecialchars($row->precio, ENT_QUOTES)
						);
					}
				}
			} else {
				$this->db->select("pro.descripcion, pro.precio, ( SELECT agregar_pago_procedimiento_descuento_caja.monto FROM agregar_pago_procedimiento_descuento_caja WHERE idProPagoCaja =  appc.id AND activo = 1 and appc.activo = 1 ) AS descuento ");
				$this->db->from('agregar_pago_procedimiento_caja appc');
				$this->db->join('procedimientos pro', 'pro.codigo_interno = appc.codigo_procedimiento');
				$this->db->where("appc.idCita", $cita);
				if($codigoInterno > 0)	$this->db->where("appc.id", $codigoInterno);
				$this->db->where("appc.pago", 1);
				$this->db->where("appc.activo", 1);
				$this->db->order_by("pro.descripcion", "asc");

				$query = $this->db->get()->result();

				if ($query) {
					foreach($query as $row)
					{
						$result[]   = array(
						'examen'	=> htmlspecialchars($row->descripcion, ENT_QUOTES),
						'descuento'	=> htmlspecialchars($row->descuento*1, ENT_QUOTES),
						'precio'	=> htmlspecialchars($row->precio, ENT_QUOTES)
						);
					}
				}	
			}
				
				$this->data['resultados']  = $result;
		 
			$this->load->view('caja2/print_add', $this->data);
		} else {
				
			redirect(base_url("inicio"));
		}
	}
	
	public function administración_caja_grabar2()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$idUsuario = $this->input->post("usuario");
			$cita = $this->input->post("cita");
			$codigoInterno = null;

			if($this->input->post("procedimiento")) {

				if(count($this->input->post("procedimiento"))) {
					for ($i=0; $i < count(array_filter($this->input->post("procedimiento"))); $i++) { 

						$costo = $this->Helper->consultar_precio($this->input->post("procedimiento")[$i]);

						$procedimientosPro = array(
							'idCita' => $cita,
							'idUsuario' => $idUsuario,
							'codigo_procedimiento' => $this->input->post("procedimiento")[$i],
							'precio' => $costo["precio"],
							'idUsuarioCreacion' => $this->session->userdata('idUsuario')
						);

						$this->db->trans_start();
						$this->db->insert("agregar_pago_procedimiento_caja", $procedimientosPro);
						$idCodigo = $this->db->insert_id();
						$this->db->trans_complete();
						

						if(is_null($codigoInterno)) {
							$codigoInterno = strtoupper(uniqid());
						}
 
						$parametros = array (
							"codigo_interno" =>$codigoInterno
						);
	
						$this->db->where('id', $idCodigo);
						$this->db->update('agregar_pago_procedimiento_caja', $parametros);

					}
				}

				$sql_pagoStatus = "UPDATE pago
				INNER JOIN cita ON cita.idPago = pago.idPago 
				SET 
					pago.status = 0,
					pago.monto = ( SELECT sum( precio ) FROM agregar_pago_procedimiento_caja  WHERE idCita = $cita AND activo = 1)
				WHERE
					cita.idCita = $cita 
					AND ( SELECT count( * ) FROM agregar_pago_procedimiento_caja WHERE idCita = $cita AND activo = 1 ) > 0;";
				
				$this->db->query($sql_pagoStatus);

				$this->validarPagoMonto($cita);

			}

			if($this->input->post("farmacia") > 0) {

				$procedimientosFar = array(
					'idUsuario' => $idUsuario,
					'precio' => $this->input->post("farmacia"),
					'idUsuarioCreacion' => $this->session->userdata('idUsuario')
				);

				$this->db->trans_start();
				$this->db->insert("agregar_pago_farmacia_caja", $procedimientosFar);
				$idCodigo = $this->db->insert_id();
				$this->db->trans_complete();
				

				if(is_null($codigoInterno)) {
					$codigoInterno = strtoupper(uniqid());
				}

				$parametros = array (
					"codigo_interno" =>$codigoInterno
				);

				$this->db->where('id', $idCodigo);
				$this->db->update('agregar_pago_farmacia_caja', $parametros);
			}

			$response['message'] = "Se registro correctamente.";
			$response['status'] = true;
		 
			
			$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );

		} else {
				
			redirect(base_url("inicio"));
		}
	}

	public function administración_caja_grabar_descuento2()
	{
		$descuento = $this->input->post("descuento");
		$codigoInterno = $this->input->post("codigoInterno");
		$usuario = $this->input->post("usuario");
		$idCita = $this->input->post("idCita");
		$tipo = $this->input->post("tipo");
		$procediemientoCaja = $this->input->post("idAgregarProCaja");

		$parametros = array (
			"activo" => 0
		);

		$parametrosInsert = array(
			'codigo_interno' => $codigoInterno,
			'monto' => $descuento,
			'idUsuario' => $this->session->userdata('idUsuario')
		);

		if ($tipo == "Procedimientos")
		{
			$parametrosInsert = array(
				'idProPagoCaja' => $procediemientoCaja,
				'codigo_interno' => $codigoInterno,
				'monto' => $descuento,
				'idUsuario' => $this->session->userdata('idUsuario')
			);

			// $this->db->trans_start();
			// $this->db->where('codigo_interno', $codigoInterno);
			// $this->db->update('agregar_pago_procedimiento_descuento_caja', $parametros);
			// $this->db->trans_complete();

			if($descuento >= 0)
			{
				$parametrosDescuento = array(
					'activo' => 0
				);

				$this->db->where('idProPagoCaja', $procediemientoCaja);
				$this->db->update('agregar_pago_procedimiento_descuento_caja', $parametrosDescuento);

				$this->db->trans_start();
				$this->db->insert("agregar_pago_procedimiento_descuento_caja", $parametrosInsert);
				$this->db->trans_complete();

				$sql_pagoStatusMonto = "UPDATE pago
							INNER JOIN cita ON cita.idPago = pago.idPago 
							SET 
								pago.monto = ( SELECT sum( precio ) FROM agregar_pago_procedimiento_caja  WHERE idCita = $idCita AND activo = 1 AND pago = 0 )
							WHERE
								cita.idCita = $idCita 
								AND ( SELECT count( * ) FROM agregar_pago_procedimiento_caja WHERE idCita = $idCita AND activo = 1 and pago = 0) > 0;";

				$this->db->query($sql_pagoStatusMonto);

				$this->validarPagoMonto($idCita);
				
			}


		} else if ($tipo == "Laboratorios") {
			$this->db->trans_start();
			$this->db->where('codigo_interno', $codigoInterno);
			$this->db->update('agregar_pago_laboratorio_descuento_caja', $parametros);
			$this->db->trans_complete();
	
			$this->db->trans_start();
			$this->db->insert("agregar_pago_laboratorio_descuento_caja", $parametrosInsert);
			$this->db->trans_complete();
		}


		redirect(base_url("cash-management2/addPay/$usuario/$idCita"));
	}
	
	public function administración_caja_eliminar2()
	{
		$codigoInterno = $this->input->post("codigoInterno");
		$procedimiento = $this->input->post("procedimiento");
		$usuario = $this->input->post("usuario");
		$tipo = $this->input->post("tipo");
		$idCita = $this->input->post("idCita");

		$parametros = array (
			"activo" => 0,
			"pago" => 0
		);

		if($tipo == "Laboratorios")
		{
			$this->db->trans_start();
			$this->db->where('codigo_interno', $codigoInterno);
			$this->db->update('agregar_pago_laboratorio_caja', $parametros);
			$this->db->trans_complete();
		} else if($tipo == "Procedimientos")
		{
			$this->db->trans_start();
			$this->db->where('id', $procedimiento);
			$this->db->update('agregar_pago_procedimiento_caja', $parametros);
			$this->db->trans_complete();

			$this->db->where('idProPagoCaja', $procedimiento);
			$this->db->update('agregar_pago_procedimiento_descuento_caja', array (
				"activo" => 0
			));



			$sql_pagoStatusMonto = "UPDATE pago
						INNER JOIN cita ON cita.idPago = pago.idPago 
						SET 
							pago.monto = ( SELECT sum( precio ) FROM agregar_pago_procedimiento_caja  WHERE idCita = $idCita AND activo = 1 AND pago = 0 )
						WHERE
							cita.idCita = $idCita 
							AND ( SELECT count( * ) FROM agregar_pago_procedimiento_caja WHERE idCita = $idCita AND activo = 1 and pago = 0) > 0;";

			$this->db->query($sql_pagoStatusMonto);

			$sql_pagoStatus = "UPDATE pago
						INNER JOIN cita ON cita.idPago = pago.idPago 
						SET 
							pago.status = 1,
							pago.monto = ( SELECT sum( precio ) FROM agregar_pago_procedimiento_caja  WHERE idCita = $idCita AND activo = 1 AND pago = 1 )
						WHERE
							cita.idCita = $idCita 
							AND ( SELECT count( * ) FROM agregar_pago_procedimiento_caja WHERE idCita = $idCita AND activo = 1 and pago = 1) > 0;";

			$this->db->query($sql_pagoStatus);

			$this->validarPagoMonto($idCita);
		} 


		redirect(base_url("cash-management2/addPay/$usuario/$idCita"));
	}

	public function validarPagoMonto($idCita)
	{
		$sql = "SELECT
		(
		IF
			(
				sum( precio ) IS NULL,
				0,
						sum( precio )) - (
					SELECT
					IF
						(
							sum( agregar_pago_procedimiento_descuento_caja.monto ) IS NULL,
							0,
						sum( agregar_pago_procedimiento_descuento_caja.monto )) AS total 
					FROM
						agregar_pago_procedimiento_descuento_caja
						INNER JOIN agregar_pago_procedimiento_caja pro2 ON pro2.id = agregar_pago_procedimiento_descuento_caja.idProPagoCaja 
					WHERE
						pro2.idCita = $idCita 
						AND agregar_pago_procedimiento_descuento_caja.activo = 1 
					)) AS resultado 
			FROM
				agregar_pago_procedimiento_caja 
			WHERE
				agregar_pago_procedimiento_caja.idCita = $idCita 
				AND agregar_pago_procedimiento_caja.activo =1";

		$query = $this->db->query($sql);	 
		 
		$row_resultado = $query->row_array();

 
		$resultado = $row_resultado['resultado'];

		$sql_pagoMonto = "UPDATE pago
			INNER JOIN cita ON cita.idPago = pago.idPago 
			SET 
				pago.monto =  $resultado
			WHERE
				cita.idCita = $idCita";

		$this->db->query($sql_pagoMonto);
 
		 
	}
	
	public function gpagoStatusPro()
	{

		$this->validarSesion();

		$cita = $this->input->post("cita");
		$status = ($this->input->post("status") == "true")? "1" : "0";

		$parametros = array (
			"idUsuarioPago" => $this->session->userdata('idUsuario'),
			"fechapago" => date('Y-m-d H:i:s'),
			"pago" => $status
		);

		$this->db->trans_start();

		$this->db->where('id', $this->input->post("idProcedimiento"));
		$this->db->where("pago != $status");
		$this->db->update("agregar_pago_procedimiento_caja", $parametros);

		$this->db->trans_complete();

		if ($this->db->trans_status())
		{
			$sql_pago = "UPDATE pago
							INNER JOIN cita ON cita.idPago = pago.idPago 
							SET pago.monto = ( SELECT sum( precio ) FROM agregar_pago_procedimiento_caja  WHERE idCita = $cita AND activo = 1 AND pago = 1 ) 
						WHERE
							cita.idCita = $cita";
			$this->db->query($sql_pago);

			$sql_pagoStatusActivo = "UPDATE pago
								INNER JOIN cita ON cita.idPago = pago.idPago 
								SET 
									pago.status = 1 , 
									pago.idUsuarioModificar = ".$this->session->userdata('idUsuario')." ,
									pago.fechaModificar = '".date('Y-m-d H:i:s')."'
								WHERE
									cita.idCita = $cita 
									AND ( SELECT count( * ) FROM agregar_pago_procedimiento_caja WHERE idCita = $cita AND activo = 1 AND pago = 0 ) = 0;";
			$this->db->query($sql_pagoStatusActivo);

			$sql_pagoStatusInactivo = "UPDATE pago
								INNER JOIN cita ON cita.idPago = pago.idPago 
								SET 
									pago.status = 0,
									pago.monto = ( SELECT sum( precio ) FROM agregar_pago_procedimiento_caja  WHERE idCita = $cita AND activo = 1 ) 
								WHERE
									cita.idCita = $cita 
									AND ( SELECT count( * ) FROM agregar_pago_procedimiento_caja WHERE idCita = $cita AND activo = 1 AND pago = 0 ) > 0;";
			$this->db->query($sql_pagoStatusInactivo);
			
			$this->validarPagoMonto($cita);

			 
			echo true;
		}

		echo false;
	}
	
	public function gpagoStatus_procedimiento()
	{
		$this->validarSesion();

		$status = ($this->input->post("status") == "true")? "1" : "0";

		$parametros = array (
			"idUsuarioModificar" => $this->session->userdata('idUsuario'),
			"fechaModificar" => date('Y-m-d H:i:s'),
			"status" => $status
		);

		$this->db->trans_start();

		$this->db->where('idPago', $this->input->post("idPago"));
		$this->db->where("status != $status");
		$this->db->update("pago", $parametros);

		$this->db->trans_complete();

		if ($this->db->trans_status())
		{
			$parametrosPro = array (
				"pago" => $status,
				"idUsuarioPago" => $this->session->userdata('idUsuario'),
				"fechapago" => date('Y-m-d H:i:s')
			);
	
			$this->db->where('idCita', $this->input->post("idCita"));
			$this->db->where("activo = 1");
			$this->db->update("agregar_pago_procedimiento_caja", $parametrosPro);

			echo true;
		}

		echo false;
	}

	public function my_quotes_personalized()
    {
		$this->validarSesion();
		$this->cargarDatosSesion();
		$result = array();

		if($this->Helper->permiso_usuario("busqueda_cita_personalizado"))
		{
			$this->db->select("c.virtual, c.idCita, c.idUsuario, c.idAvailability, pg.status as statusPago, c.tipoCita as idCitaTipo, if(`c`.`tipoCita` ='CV' || `c`.virtual = 1, 'Virtual', if(`c`.`tipoCita` ='CP', 'Presencial', if(`c`.`tipoCita` ='PR', 'Procedimiento', 'Domiciliario'))) as tipoCita, c.fechaCita, c.horaCita, c.motivo, c.status, c.fechaCreacion, concat(d.firstname, ' ', d.lastname) as medico, s.name as especialidad, 
			concat( p.firstname, ' ', p.lastname ) AS paciente, pro.descripcion, mc.tipo as motivoTipoCita");
		
			$this->db->from('cita c');
			$this->db->join('doctors d', 'd.idDoctor = c.idMedico');
			$this->db->join('specialties s', 's.idSpecialty = c.idEspecialidad');
			$this->db->join('patients p', 'p.idUsuario = c.idUsuario');
			$this->db->join('procedimientos pro', 'pro.codigo_interno = c.codigo_procedimiento', "left");
			$this->db->join('motivo_cita mc', 'mc.id = c.idMotivoCita', "LEFT");
			$this->db->join('pago pg', 'pg.idPago = c.idPago');
 
			if(!$this->input->post("fecha")) {
				$this->db->where("c.fechaCita = date(NOW())");
			}
			
			if($this->input->post("cmbmedico") and (!$this->input->post("cliente"))) {
				$this->db->where('c.idMedico', $this->input->post("cmbmedico"));
			}

			if($this->input->post("cliente") and !$this->input->post("cmbmedico")) {
				$this->db->where('c.idUsuario', $this->input->post("cliente"));
			}

			if($this->input->post("fecha") and !$this->input->post("cmbmedico") and !$this->input->post("cliente")) {
				$this->db->where('c.fechaCita', date("Y-m-d", strtotime($this->input->post("fecha"))));
			}


			
			$this->db->where('c.status', 1);
			$this->db->order_by("c.fechaCita", "desc");
			$this->db->order_by("c.fechaCreacion", "desc");
			
			$query = $this->db->get()->result();
			 
			if ($query) {
				foreach($query as $row)
				{
					$result[]   = array(
					'idCita'	=> htmlspecialchars($row->idCita, ENT_QUOTES),
					'idCitaTipo'	=> htmlspecialchars($row->idCitaTipo, ENT_QUOTES),
					'idUsuario'	=> htmlspecialchars($row->idUsuario, ENT_QUOTES),
					'tipoCita'	=> htmlspecialchars($row->tipoCita, ENT_QUOTES),
					'fechaCita'	=> htmlspecialchars($row->fechaCita, ENT_QUOTES),
					'horaCita'	=> htmlspecialchars($row->horaCita, ENT_QUOTES),
					'especialidad'	=> htmlspecialchars($row->especialidad, ENT_QUOTES),
					'medico'	=> htmlspecialchars($row->medico, ENT_QUOTES),
					'motivo'	=> htmlspecialchars($row->motivo, ENT_QUOTES),
					'status'	=> htmlspecialchars($row->status, ENT_QUOTES),
					'paciente'	=> htmlspecialchars($row->paciente, ENT_QUOTES),
					'statusPago'	=> htmlspecialchars($row->statusPago, ENT_QUOTES),
					'virtual'	=> htmlspecialchars($row->virtual, ENT_QUOTES),
					'descripcion'	=> htmlspecialchars($row->descripcion, ENT_QUOTES),
					'motivoTipoCita'	=> htmlspecialchars($row->motivoTipoCita, ENT_QUOTES),
					'fechaCreacion'	=> htmlspecialchars($row->fechaCreacion, ENT_QUOTES)
					);
				}
				}
		
			$this->data['resultados']  = $result;
			
			$this->data['medicos'] = $this->Doctor->all();

	 
			$this->load->view('cita/mis_citas_personalizado', $this->data);
		} else {
			
			redirect(base_url("inicio"));
		}
	}
	
	public function nueva_cita($cita, $usuario) {
		$this->validarSesion();
		$this->cargarDatosSesion();

		$this->db->select("count(idCita) as cantidad");
		$this->db->from("cita");
		$this->db->where("status", 1);
		$this->db->where("idCita", $cita);
	 
		$query = $this->db->get();
		$row_resultado = $query->row_array();

		$disponible = $row_resultado['cantidad'];

		if($this->Helper->permiso_usuario("guardar_historia_clinica"))
		{
			if($disponible == 1)
			{
				$this->data['usuarioCita'] = $this->Usuario->datosUsuario($usuario);

				$this->data['guardarHclinica']  = $this->Helper->permiso_usuario("guardar_historia_clinica");
				
			} else {
				die("Cita no disponible");
			}
			
			/*
			$this->db->select("ci.idCita, ci.fechaCita, concat(do.title, ' ', do.firstname, do.lastname) as medico, pro.descripcion as nombreProcedimiento, sp.name as especialidad");
			$this->db->from('cita ci');
			$this->db->join('doctors do', 'do.idDoctor = ci.idMedico');
			$this->db->join('specialties sp', 'sp.idSpecialty = do.idSpecialty');
			$this->db->join('procedimientos pro', 'pro.codigo_interno = ci.codigo_procedimiento', "left");
			$this->db->where("ci.idUsuario", $usuario);
			$this->db->where("ci.status", 0);
			$this->db->order_by("ci.fechaCita", "desc");
			
			$resultados = $this->db->get()->result();*/
			
			$sql = "
				SELECT
				'LAB' AS tipo,
				soli.idUsuario,
				soli.estado,
				soli.codigo_interno AS idCita,
				soli.fechaExamen AS fechaCita,
				'' AS medico,
				(
				SELECT
					GROUP_CONCAT( nombre ) 
				FROM
					examen
					INNER JOIN solicitarexamen ON solicitarexamen.idExamen = examen.id 
				WHERE
					solicitarexamen.codigo_interno = soli.codigo_interno 
				GROUP BY
					solicitarexamen.codigo_interno 
				) AS nombreProcedimiento,
				'' AS `especialidad` 
				FROM
					solicitarexamen soli 
				WHERE
					soli.idUsuario = '$usuario'
				GROUP BY
					soli.codigo_interno UNION
				SELECT
					'PRO' AS tipo,
					'' as idUsuario,
					'' as estado,
					`ci`.`idCita`,
					`ci`.`fechaCita`,
					concat( do.title, ' ', `do`.`firstname`, do.lastname ) AS medico,
					`pro`.`descripcion` AS `nombreProcedimiento`,
					`sp`.`name` AS `especialidad` 
				FROM
					`cita` `ci`
					JOIN `doctors` `do` ON `do`.`idDoctor` = `ci`.`idMedico`
					JOIN `specialties` `sp` ON `sp`.`idSpecialty` = `do`.`idSpecialty`
					LEFT JOIN `procedimientos` `pro` ON `pro`.`codigo_interno` = `ci`.`codigo_procedimiento` 
				WHERE
					`ci`.`idUsuario` = '$usuario' 
					AND `ci`.`status` = 0
			";
	 
			$resultados = $this->db->query($sql)->result();
	 
			$this->data["miHistoria"] = $resultados;

			if($cita > 7769)
			{
				$this->db->distinct();
				$this->db->select("GROUP_CONCAT(pro.descripcion) as procedimiento, ci.idEspecialidad");
				$this->db->from("historial_asignacion_cita hac");
				$this->db->join('procedimientos pro', 'pro.codigo_interno = hac.codigo_procedimiento');
				$this->db->join('cita ci', 'ci.idCita = hac.idCita');
				$this->db->where("ci.idCita", $cita);
			 
				$query = $this->db->get();
				$row_resultado = $query->row_array();
				 
				$this->data["procedimiento"] = $row_resultado['procedimiento'];
				$this->data["codigo_especialidad"] = $row_resultado['idEspecialidad'];
			} else {
				$this->db->select("pro.descripcion as procedimiento, ci.idEspecialidad");
				$this->db->from("cita ci");
				$this->db->join('procedimientos pro', 'pro.codigo_interno = ci.codigo_procedimiento', "left");
				$this->db->where("ci.idCita", $cita);
			 
				$query = $this->db->get();
				$row_resultado = $query->row_array();
				 
				$this->data["procedimiento"] = $row_resultado['procedimiento'];
				$this->data["codigo_especialidad"] = $row_resultado['idEspecialidad'];

			}
			
			$this->db->select("idSpecialty, name");
			$this->db->from('specialties');
			$this->db->where("status", 1);
			$this->db->order_by("name", "desc");
			
			$interconsultas = $this->db->get()->result();
	 
			$this->data["interconsultas"] = $interconsultas;
			
			$this->load->view('cita/historial_new', $this->data);
		} else {
			die("Acceso no disponlible");
		}
	}

	
	public function nueva_cita21($cita, $usuario) {
		$this->validarSesion();
		$this->cargarDatosSesion();

		$this->db->select("count(idCita) as cantidad");
		$this->db->from("cita");
		$this->db->where("status", 1);
		$this->db->where("idCita", $cita);
	 
		$query = $this->db->get();
		$row_resultado = $query->row_array();

		$disponible = $row_resultado['cantidad'];

		if($this->Helper->permiso_usuario("guardar_historia_clinica"))
		{
			if($disponible == 1)
			{
				$this->data['usuarioCita'] = $this->Usuario->datosUsuario($usuario);

				$this->data['guardarHclinica']  = $this->Helper->permiso_usuario("guardar_historia_clinica");
				
			} else {
				die("Cita no disponlible");
			}
			
			$this->db->select("ci.idCita, ci.fechaCita, concat(do.title, ' ', do.firstname, do.lastname) as medico, pro.descripcion as nombreProcedimiento, sp.name as especialidad");
			$this->db->from('cita ci');
			$this->db->join('doctors do', 'do.idDoctor = ci.idMedico');
			$this->db->join('specialties sp', 'sp.idSpecialty = do.idSpecialty');
			$this->db->join('procedimientos pro', 'pro.codigo_interno = ci.codigo_procedimiento', "left");
			$this->db->where("ci.idUsuario", $usuario);
			$this->db->where("ci.status", 0);
			$this->db->order_by("ci.fechaCita", "desc");
			
			$resultados = $this->db->get()->result();
	 
			$this->data["miHistoria"] = $resultados;

			$this->db->select("pro.descripcion as procedimiento");
			$this->db->from("cita ci");
			$this->db->join('procedimientos pro', 'pro.codigo_interno = ci.codigo_procedimiento', "left");
			$this->db->where("ci.idCita", $cita);
		 
			$query = $this->db->get();
			$row_resultado = $query->row_array();
	
			$this->data["procedimiento"] = $row_resultado['procedimiento'];
			
			$this->load->view('cita/historial_new2', $this->data);
		} else {
			die("Acceso no disponlible");
		}
	}
	
	public function actualizar_cita($cita) {
		$this->validarSesion();
		$this->cargarDatosSesion();

		$this->db->select("count(idCita) as cantidad");
		$this->db->from("cita");
		$this->db->where("status", 0);
		$this->db->where("idCita", $cita);
	 
		$query = $this->db->get();
		$row_resultado = $query->row_array();

		$disponible = $row_resultado['cantidad'];

		if($this->Helper->permiso_usuario("actualizar_historial_clinica"))
		{
			if($disponible == 1)
			{
				$this->data["historialMedico"] = $this->Helper->historialMedico($cita);
				$this->data["examenFisicoCita"] = $this->Helper->examenFisicoCita($cita);
				$this->data["citaDiagnostico"] = $this->Helper->citaDiagnosticoPdf($cita, false);
				$this->data["ptratamiento"] = $this->Helper->planTratamiento($cita, false);
				$this->data["dataReceta"] = $this->Helper->citaRecetaPdf($cita, false);
				$this->data["dataExamenM"] = $this->Helper->citaExamenMPdf2($cita, false);
				$this->data["descansoMedico"] = $this->Helper->descansoMedico($cita);
				
				$this->db->select("pro.descripcion as procedimiento, ci.idEspecialidad");
				$this->db->from("cita ci");
				$this->db->join('procedimientos pro', 'pro.codigo_interno = ci.codigo_procedimiento', "left");
				$this->db->where("ci.idCita", $cita);
			 
				$query = $this->db->get();
				$row_resultado = $query->row_array();
		
				$this->data["procedimiento"] = $row_resultado['procedimiento'];
				$this->data["codigo_especialidad"] = $row_resultado['idEspecialidad'];
				
				$this->data['actualizarHClinica']  = $this->Helper->permiso_usuario("actualizar_historial_clinica");
			} else {
				die("Cita no disponlible");
			}

			
			$this->load->view('cita/historial', $this->data);
		} else {
			die("Acceso no disponlible");
		}
	}


	public function buscar_procedimiento_lab()
	{
		$json = [];
		if(!empty($this->input->post("q")) || !empty($this->input->post("busqueda"))){


			$sql = "select concat('PRO|', codigo_interno) as id, concat('PRO: ', descripcion) as text from procedimientos
				 where status=1 and descripcion like '%".$this->input->post("q")."%'
			union
			select concat('LAB|', id) as id , concat('LAB: ', nombre) as text from examen
				where status=1 and nombre like '%".$this->input->post("q")."%'
			ORDER BY 2 limit 20";
		 
			$json = $this->db->query($sql)->result();
		}
		
		echo json_encode($json);
	}

	public function eliminar_registro_resultado()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();
 
		if ($this->input->post("codigo") < 1)
		{
            $response['status'] = FALSE;
			$response['message'] = 'Validación Erronea';
        } else {
			 
			$parametros = array (
				"activo" => 0,
				"idUsuarioElimina" => $this->session->userdata('idUsuario'),
				"fechaEliminacion" => date('Y-m-d H:i:s')
			);
	
			$this->db->trans_start();

			$this->db->where('activo', 1);
			$this->db->where("id", $this->input->post("codigo"));
			$this->db->update("resultado_paciente", $parametros);
	
			$this->db->trans_complete();
	
			if ($this->db->trans_status())
			{
				$response['message'] = "Se elimino el registro correctamente.";
				$response['status'] = true;	
			} else {
				$response['message'] = "No se pudo realizar la operación.";
				$response['status'] = false;	
			}
 
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function consultarDni() 
	{
		$curl = curl_init();
		$dni = $this->input->post("dni");
		
		$this->db->select("count(document) as cantidad");
		$this->db->from("patients");
		$this->db->where("document", $dni);
	 
		$query = $this->db->get();
		$row_resultado = $query->row_array();

		if ($row_resultado['cantidad'] == 0)
		{

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.apis.net.pe/v1/dni?numero=$dni",
				//CURLOPT_URL => "https://dniruc.apisperu.com/api/v1/dni/$dni?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImxhY2FsZGVyb25jODBAZ21haWwuY29tIn0.kqD9hT8w3XI-AW74V1-Bw_0rk4TlqsITrhxd9guxyBc",
				//CURLOPT_URL => "https://apiperu.dev/api/dni/$dni?api_token=62f556647c4ba82a2933f63feaf0e4f40baaa66cf246028642301fbc42ae9876",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json',
					'authorization: Bearer apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N'
				)
				
			));
			$responseData = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				$response['usuario'] =  "cURL Error #:" .$err;
				$response['status'] = false;
			} else {

				$obj = json_decode($responseData);
	
				$response['usuario'] =  $obj;
				$response['status'] = true;		
			}
		} else {
			$response['usuario'] =  "El paciente(DNI) ya esta registrado. No se le puede registrar de nuevo.";
			$response['status'] = false;
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function gestion_citas()
	{
		$this->validarSesion();

		$this->cargarDatosSesion();

		$this->data["registros"] = array();
		$this->data["registrosLab"] = array();
		$usuario = null;

		$this->data['especialidades'] = $this->Especialidad->listAll();

		if($this->input->post("paciente"))	$usuario = $this->input->post("paciente"); else $usuario = $this->session->flashdata('data_paciente');

	 
		$this->data["paciente"] = $this->Usuario->datosUsuario($usuario);

		$this->db->select("(select count(id) from historial_asignacion_cita where historial_asignacion_cita.codigo_asignacion = asci.codigo_asignacion) as cantidad, asci.codigo_asignacion, asci.tipo_asignacion, asci.idUsuario, asci.id, asci.marca_cita, DATE_ADD(asci.fechaCreacion, INTERVAL 2 HOUR) as  fechaCreacion, right(asci.codigo_asignacion, 4) as codeAsignacion, if(asci.pago =1 , 'SI', 'NO') as pago, pro.descripcion, sp.name as especialidad, pro.idEspecialidad, right(asci.norden, 4) as norden");

		$this->db->from('solicitud_citas_pagos asci');
		$this->db->join('procedimientos pro', 'pro.codigo_interno = asci.codigo_procedimiento');
		$this->db->join('specialties sp', 'sp.idSpecialty = pro.idEspecialidad');
		$this->db->where('asci.activo', 1);
		$this->db->where('asci.tipo_solicitud', "PRO");
		$this->db->where('asci.idUsuario', $usuario);
		$this->db->order_by("asci.fechaCreacion", "desc");

		$this->data["registros"] = $this->db->get()->result();

		$this->db->select("asci.idUsuario, if(asci.pago =1 , 'SI', 'NO') as pago, asci.fechaCreacion, right(asci.norden, 4) as norden, exa.id,  exa.id, concat(exa.nombre, ' = ', exa.precio) as text , if(asci.realizado = 1, 'SI', 'NO') as realizado, asci.codigo_lab");
		$this->db->from('solicitud_citas_pagos asci');
		$this->db->join('examen exa', 'exa.id = asci.codigo_procedimiento');
		$this->db->where('exa.status', 1);
		$this->db->where('asci.tipo_solicitud', "LAB");
		$this->db->where('asci.idUsuario', $usuario);

		$this->data["registrosLab"] = $this->db->get();
	
		$this->db->select("concat(asci.id, '=', exa.id) as id, concat(exa.nombre, ' = ', exa.precio) as text , if(asci.realizado = 1, 'SI', 'NO') as realizado ");
		$this->db->from('solicitud_citas_pagos asci');
		$this->db->join('examen exa', 'exa.id = asci.codigo_procedimiento');
		$this->db->where('exa.status', 1);
		$this->db->where('asci.activo', 1);
		$this->db->where('asci.marca_cita', 2);
		$this->db->where('asci.tipo_solicitud', "LAB");
		$this->db->where('asci.idUsuario', $usuario);

		$this->data["laboratorios"] = $this->db->get()->result();

		$this->load->view('gestionCitas/index', $this->data);
	}

	public function consultarPaciente()
	{
		if($this->input->post("cliente"))
		{
			$response['paciente'] = $this->Usuario->datosUsuario($this->input->post("cliente"));

			$response['status'] = true;
		} else {
			$response['message'] = "!Paciente no encontrado¡";
			$response['status'] = false;
		}

		$this->output->set_content_type( 'application/json' )->set_output(json_encode($response));

	}
	
	
public function fechas_disponibles()
{
  $this->validarSesion();
  $this->cargarDatosSesion();

  $this->data['especialidades'] = $this->Especialidad->listAll();
  $this->data['profesionales'] = $this->Doctor->all_especialidad($this->input->post("especialidades"));

  $this->data["registros"] = array();

  if($this->input->post("especialidades") || $this->uri->segment(2) > 0)
  {
    //$fecha = ($this->input->post("fechaBusqueda")) ? " = '".$this->input->post("fechaBusqueda")."'" : " >= '".date('Y-m-d', time())."'";
    $doctor = ($this->uri->segment(2) > 0) ? $this->uri->segment(2) : $this->input->post("profesionales");

    $this->db->select("av.date, left(min( av.start_time ), 5) as inicio,	left(max( av.end_time ), 5) as final, if(av.turno =1, 'Mañana', 'Tarde') as turno, concat(do.firstname, ' ', do.lastname) as profesional, esp.name as especialidad, if(av.area = 1, 'CE-COVID', if(av.area = 2, 'RETEN', if(av.area = 3, 'PRESENCIAL', if(av.area = 5, 'Teleconsulta', 'Procedimientos')))) as area");
    $this->db->from('availabilities av');
    $this->db->join('doctors do', 'do.idDoctor = av.idDoctor');
    $this->db->join('specialties esp', 'esp.idSpecialty = do.idSpecialty');
    if($this->input->post("especialidades"))	$this->db->where('do.idSpecialty', $this->input->post("especialidades"));
    if($doctor)	$this->db->where('av.idDoctor', $doctor);
    $this->db->where('av.date >= date(NOW())');

    //$this->db->where("av.date $fecha");
    $this->db->where('av.disponible in(0, 1)');
    $this->db->group_by(array("av.date", "av.idDoctor", "av.turno", "av.area" ));
    //$this->db->order_by("av.date, av.turn, av.idDoctor asc");
    $this->db->order_by("av.date   asc");
  
    $this->data["registros"] = $this->db->get()->result();

    $this->data["medico"] = $doctor;
  }

 
  $this->load->view('gestionCitas/form_fechaDisponibles', $this->data);
}

public function fechas_disponibles2()
{
  $this->validarSesion();
  $this->cargarDatosSesion();

  $this->data['especialidades'] = $this->Especialidad->listAll();
  $this->data['profesionales'] = $this->Doctor->all_especialidad($this->input->post("especialidades"));

  $this->data["registros"] = array();

  if($this->input->post("especialidades") || $this->uri->segment(2) > 0)
  {
    //$fecha = ($this->input->post("fechaBusqueda")) ? " = '".$this->input->post("fechaBusqueda")."'" : " >= '".date('Y-m-d', time())."'";
    $doctor = ($this->uri->segment(2) > 0) ? $this->uri->segment(2) : $this->input->post("profesionales");

    $this->db->select("av.date, min( av.start_time ) as inicio,	max( av.end_time ) as final, if(av.turn =1, 'Mañana', 'Tarde') as turno, concat(do.firstname, ' ', do.lastname) as profesional, esp.name as especialidad");
    $this->db->from('availabilities av');
    $this->db->join('doctors do', 'do.idDoctor = av.idDoctor');
    $this->db->join('specialties esp', 'esp.idSpecialty = do.idSpecialty');
    if($this->input->post("especialidades"))	$this->db->where('do.idSpecialty', $this->input->post("especialidades"));
    if($doctor)	$this->db->where('av.idDoctor', $doctor);
    $this->db->where('av.date >= date(NOW())');
    //$this->db->where("av.date $fecha");
    $this->db->where('av.disponible in(0, 1)');
    $this->db->group_by(array("av.date", "av.idDoctor", "av.turn"));
    //$this->db->order_by("av.date, av.turn, av.idDoctor asc");
    $this->db->order_by("av.idDoctor, av.date, av.turn asc");
  
    $this->data["registros"] = $this->db->get()->result();

    $this->data["medico"] = $doctor;
  }

 
  $this->load->view('gestionCitas/form_fechaDisponibles', $this->data);
}


	public function listar_EventosMedicos()
	{
		$medico = $this->uri->segment(2);
 
		$sql = "SELECT min(start_time) as start_time, max(end_time) as end_time, `date` FROM `availabilities` WHERE `idDoctor` = '$medico' AND `disponible` = 1 AND `date` >= date(now()) GROUP BY `date`";
	 
		$query = $this->db->query($sql);
		$disponibilidades = $query->result();
		$result = array();

		foreach( $disponibilidades as $item){

			$newobj = new stdClass();//create a new
			//$newobj->title = $item->name;
			$newobj->start = $item->date  ;
			$newobj->end = $item->date  ;
			$newobj->rendering = 'background';
			//$newobj->allDay = true;
			$newobj->classNames = "['alert-info', 'fc-today']";
			$newobj->color = '#ff0000'; //Info (aqua)
			$newobj->backgroundColor = '#00ff00'; //Info (aqua)
			//$newobj->borderColor     = $item->border; //Info (aqua)
			//$newobj->url = base_url('cita/confirmar/'.$item->idAvailability);
			$aux = array();

			$aux['date'] = $item->date;
			$aux['start_time'] = $item->start_time;
			$aux['end_time'] = $item->end_time;
			$newobj->description = json_encode($aux);
			$result[] = $newobj;
		}
		
		
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $result ) );
	}

	public function grabar_solicitudes()
	{
		$this->validarSesion();

		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$idUsuario = $this->input->post("paciente");

			if(isset($idUsuario))
			{
				if(count($this->input->post("procedimiento"))) {
					for ($i=0; $i < count(array_filter($this->input->post("procedimiento"))); $i++) { 
						
						$costo = $this->Helper->consultar_precio($this->input->post("procedimiento")[$i]);

						if($this->input->post("cantidad_sesiones") > 0)
						{
							for ($ii=0; $ii < $this->input->post("cantidad_sesiones"); $ii++) { 
								
								$procedimientosPro = array(
									'idUsuario' => $idUsuario,
									'codigo_procedimiento' => $this->input->post("procedimiento")[$i],
									'marca_cita' => 2,
									'tipo_solicitud' => "PRO",
									'precio' => $costo["precio"],
									'idUsuarioCreacion' => $this->session->userdata('idUsuario')
								);
		
								$this->db->insert("solicitud_citas_pagos", $procedimientosPro);
								
		 
							}
						} else { 

						$marca = 0;
						if(count($this->input->post("procedimiento")) > 1)	$marca = 1; else $marca = 0;


						$procedimientosPro = array(
							'idUsuario' => $idUsuario,
							'codigo_procedimiento' => $this->input->post("procedimiento")[$i],
							'marca_cita' => 2,
							'tipo_solicitud' => "PRO",
							'precio' => $costo["precio"],
							'idUsuarioCreacion' => $this->session->userdata('idUsuario')
						);

						$this->db->insert("solicitud_citas_pagos", $procedimientosPro);

					}
				}
			}

				$this->session->set_flashdata('data_paciente', $idUsuario);

				redirect(base_url('appointment-management'));
			} else {
				redirect(base_url('inicio'));
			}
		} else {
			redirect(base_url('inicio'));
		}
	}

	public function consultar_registros_solicitudes()
	{
		$this->validarSesion();
 
		$this->cargarDatosSesion();

		$this->data['especialidades'] = $this->Especialidad->listAll();

		$this->data["registros"] = array();
		$this->data["registrosLab"] = array();
		
		if($this->input->post("usuarioBusqueda"))
		{
			//$this->db->update('solicitud_citas_pagos',array("marca_cita" => 2));
			
			$this->db->select("if(asci.pago =1 , 'SI', 'NO') as pago, right(asci.norden, 4) as norden, asci.codigo_asignacion, asci.tipo_asignacion, asci.idUsuario, asci.id, DATE_ADD(asci.fechaCreacion, INTERVAL 2 HOUR) as  fechaCreacion, right(asci.codigo_asignacion, 4) as codeAsignacion, concat(pro.descripcion, ' => ', asci.precio ) as descripcion, asci.marca_cita, pro.tipo as especialidad, 	(
				SELECT
					count( DISTINCT historial_asignacion_cita.idCita ) 
				FROM
					historial_asignacion_cita
					INNER JOIN solicitud_citas_pagos ON solicitud_citas_pagos.codigo_asignacion = historial_asignacion_cita.codigo_asignacion  
				WHERE
					historial_asignacion_cita.codigo_asignacion = asci.codigo_asignacion
					and solicitud_citas_pagos.activo=1
				) AS cantidad, pro.idEspecialidad");
 
			$this->db->from('solicitud_citas_pagos asci');
			$this->db->join('procedimientos pro', 'pro.codigo_interno = asci.codigo_procedimiento');
			$this->db->join('specialties sp', 'sp.idSpecialty = pro.idEspecialidad');
			$this->db->where('asci.activo', 1);
			$this->db->where('asci.tipo_solicitud', "PRO");
			$this->db->where('asci.idUsuario', $this->input->post("usuarioBusqueda"));
			if($this->input->post("fechaBusqueda"))	$this->db->where('date(asci.fechaCreacion)', $this->input->post("fechaBusqueda"));
			if($this->input->post("cmbEspecialista"))	$this->db->where('pro.idEspecialidad', $this->input->post("cmbEspecialista"));
			$this->db->order_by("asci.fechaCreacion", "desc");
	
			$this->data["registros"] = $this->db->get()->result();


			$this->db->select("asci.idUsuario, if(asci.pago =1 , 'SI', 'NO') as pago, asci.fechaCreacion, right(asci.norden, 4) as norden, exa.id,  exa.id, concat(exa.nombre, ' = ', asci.precio) as text , if(asci.realizado = 1, 'SI', 'NO') as realizado, asci.codigo_lab");
			$this->db->from('solicitud_citas_pagos asci');
			$this->db->join('examen exa', 'exa.id = asci.codigo_procedimiento');
			$this->db->where('exa.status', 1);
			$this->db->where('asci.tipo_solicitud', "LAB");
			$this->db->where('asci.idUsuario', $this->input->post("usuarioBusqueda"));
			$this->db->order_by("asci.fechaCreacion", "desc");

			$this->data["registrosLab"] = $this->db->get();
		 
			$this->db->select("concat(asci.id, '=', exa.id) as id, concat(exa.nombre, ' = ', exa.precio) as text , if(asci.realizado = 1, 'SI', 'NO') as realizado ");
			$this->db->from('solicitud_citas_pagos asci');
			$this->db->join('examen exa', 'exa.id = asci.codigo_procedimiento');
			$this->db->where('exa.status', 1);
			$this->db->where('asci.activo', 1);
			$this->db->where('asci.realizado', 0);
			$this->db->where('asci.marca_cita', 2);
			$this->db->where('asci.tipo_solicitud', "LAB");
			$this->db->where('asci.idUsuario', $this->input->post("usuarioBusqueda"));
			$this->db->order_by("asci.codigo_lab", "desc");
	
			$this->data["laboratorios"] = $this->db->get()->result();
			
			$this->data["paciente"] = $this->Usuario->datosUsuario($this->input->post("usuarioBusqueda"));
 
		}
	 

		$this->load->view('gestionCitas/index', $this->data);
	}

	public function buscar_horarios($tipoCita)
	{
		$this->cargarDatosSesion();
		$this->validarSesion();

		if($this->Helper->permiso_usuario("realizar_reservas_citas"))
		{
			$today = date("Y-m-d");
			
			$this->data['especialidades'] = $this->Especialidad->listAll();	
			$this->data['profesionales'] = $this->Doctor->all_especialidad($this->input->get("service"));	
			
			$this->data['tipoCita'] = $tipoCita; 
			
			$this->db->select("u.idUser, concat(p.firstname, ' ', p.lastname, ' - ', p.document) as nombreUsuario");
			$this->db->from('users u');
			$this->db->join('patients p', 'p.idUsuario = u.idUser');
			$this->db->where('u.status', 1);
			$this->db->where("u.idRol", 3);
			$this->db->order_by("p.lastname", "asc");

			$this->data["usuarios"] = $this->db->get()->result();

			$this->db->select("GROUP_CONCAT(asc.id ) as ids, GROUP_CONCAT(distinct 'Min: ',  p.tiempo, ' => ', p.descripcion ) as concepto, GROUP_CONCAT(p.codigo_interno) as codigoProcedimiento");
			$this->db->from("procedimientos p");
			$this->db->join('solicitud_citas_pagos asc', 'asc.codigo_procedimiento = p.codigo_interno');
			$this->db->where("p.status", 1);
			$this->db->where("asc.marca_cita", 1);
			$this->db->where("asc.idUsuario", $this->input->get("user"));
			//$this->db->where("asc.codigo_interno", $this->input->get('code'));
			//if($this->input->get('tipo') == "SESION")	$this->db->where("asc.id", $this->input->get('unique'));
		 
			$query = $this->db->get();
		 
			$row_resultado = $query->row_array();
			 
			$this->data['procedimientos'] = $row_resultado['concepto'];
			$this->data['codigoProcedimiento'] = $row_resultado['codigoProcedimiento'];
			$this->data['ids'] = $row_resultado['ids'];
			
			$this->db->select("id, nombre");
			$this->db->from("motivo_cita");
			$this->db->where("activo", 1);
			$this->db->order_by("nombre", 'ASC');
			$this->data["motivoCitas"] = $this->db->get()->result();
	
			$this->load->view("gestionCitas/cita_paso_1", $this->data);

		} else {
			redirect(base_url("inicio"));
		}
	}
	
	public function guardar_informacion_paciente()
	{
		$response['message'] = "Error interno.";
		$response['status'] = false;

		if($this->input->post("nroDocumento") != $this->input->post("nDocumentoOld"))
		{
			$this->db->select("count(document) as cantidad");
			$this->db->from("patients");
			$this->db->where("document", $this->input->post("nroDocumento"));
			
			$query = $this->db->get();
			$row_resultado = $query->row_array();
	
			if ($row_resultado['cantidad'] == 0)
			{


				$this->db->trans_start();
				$this->db->where('username', $this->input->post("nDocumentoOld"));
				$this->db->update('users', array("username" => $this->input->post("nroDocumento")));
				$this->db->trans_complete();
	
				if ($this->db->trans_status())
				{
					$this->db->where('document', $this->input->post("nDocumentoOld"));
					$this->db->update('patients', array("document" => $this->input->post("nroDocumento")));
				}


				$parametros = array (
					"email" => $this->input->post("email"),
					"phone" => $this->input->post("telefono"),
					"firstname" => $this->input->post("nombres"),
					"lastname" => $this->input->post("apellidos"),
					"birthdate" => $this->input->post("fechaNacimiento")
				);
				
				$this->db->where('idUsuario', $this->input->post("userPaciente"));
				$this->db->update('patients', $parametros);

				$response['message'] = "Se actualizo correctamente los datos del paciente.";
				$response['status'] = true;

			} else {
				$response['message'] =  "El Nro Documento (paciente) ya esta registrado. No se le puede cambiar.";
				$response['status'] = false;
			}
		} else {
			$parametros = array (
				"email" => $this->input->post("email"),
				"phone" => $this->input->post("telefono"),
				"firstname" => $this->input->post("nombres"),
				"lastname" => $this->input->post("apellidos"),
				"birthdate" => $this->input->post("fechaNacimiento")
			);
			
			$this->db->where('idUsuario', $this->input->post("userPaciente"));
			$this->db->update('patients', $parametros);

			$response['message'] = "Se actualizo correctamente los datos del paciente.";
			$response['status'] = true;
		}
 
	 
		
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function guardar_informacion_paciente2()
	{
		$response['message'] = "Error interno.";
		$response['status'] = false;

		$parametros = array (
			"email" => $this->input->post("email"),
			"phone" => $this->input->post("telefono"),
			"firstname" => $this->input->post("nombres"),
			"lastname" => $this->input->post("apellidos"),
			"birthdate" => $this->input->post("fechaNacimiento")
		);
		
		$this->db->trans_start();
		$this->db->where('idUsuario', $this->input->post("userPaciente"));
		$this->db->update('patients', $parametros);
		$this->db->trans_complete();
		
		if ($this->db->trans_status())
		{
			$response['message'] = "SE ACTUALIZO CORRECTAMENTE.";
			$response['status'] = true;
		}
	 
		
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function actualizar_marca()
	{ 
		$parametros = array (
			"marca_cita" => $this->input->post("marca")
		);

		$this->db->where('id', $this->input->post("id"));
		$this->db->update('solicitud_citas_pagos', $parametros);
	}

	public function buscarDisponibilidad_horas()
	{
		$this->load->model('Helper');

		$medico = $this->input->post('cmbMedico');

		$fecha = str_replace("/", "-", $this->input->post('fecha'));
		$fecha =  date("Y-m-d", strtotime($fecha));

		 
		$this->data['promedio'] = $this->Helper->promedioCalificacion($medico);

		$result = $this->Disponibilidad->listarPorMedicoFecha($medico, $fecha, "CP");		
		$this->procesarDisponibilidad($result);
		$this->load->view("gestionCitas/list", $this->data);
	}

	public function detalle_citas_asignadas()
	{
		$this->cargarDatosSesion();
		$this->validarSesion();

		$code = $this->uri->segment(2);

		if($this->Helper->permiso_usuario("realizar_reservas_citas"))
		{
			$this->db->select("hac.idCita, ci.fechaCita, ci.horaCita, if(ci.status = 0, 'Atendido', if(ci.status = 1, 'Sin Atender', 'Cancelado')) as estadoCita, pro.descripcion, concat(do.firstname, ' ', do.lastname) as profesional, esp.name as especialidad, (select descripcion from procedimientos where codigo_interno = hac.code_principal) as proPrincipal");
			$this->db->from('historial_asignacion_cita hac');
			$this->db->join('solicitud_citas_pagos asc', 'asc.codigo_asignacion = hac.codigo_asignacion');
			$this->db->join('cita ci', 'ci.idCita = hac.idCita');
			$this->db->join('doctors do', 'do.idDoctor = ci.idMedico');
			$this->db->join('specialties esp', 'esp.idSpecialty = do.idSpecialty');
			$this->db->join('procedimientos pro', 'pro.codigo_interno = hac.codigo_procedimiento');
			$this->db->where('hac.codigo_asignacion', "$code");
			$this->db->where('asc.activo', 1);
			$this->db->where('hac.activo', 1);
			$this->db->group_by(array("1", "5"));
			$this->db->order_by("hac.idCita", "desc");

			$this->data["registros"] = $this->db->get();

			$this->load->view("gestionCitas/historial_citas_asignadas", $this->data);

		} else {
			die(503);
		}
	}

	public function print_administración_caja_solicitud()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$idcita = $this->uri->segment(3);
  
			$this->db->select("ci.idUsuarioCreacion, ci.idUsuario, ci.fechaCita, left(ci.horaCita, 5) as horaCita, concat(pa.firstname, ' ', pa.lastname) as paciente, concat(do.firstname, ' ', do.lastname) as medico, esp.name, ci.codigo_procedimiento, ci.codigo_asignacion, ci.gratis");
			$this->db->from('cita ci');
			$this->db->join('doctors do', 'do.idDoctor = ci.idMedico');
			$this->db->join('patients pa', 'pa.idUsuario = ci.idUsuario');
			$this->db->join('pago pg', 'pg.idPago = ci.idPago', "left");
			$this->db->join('specialties esp', 'esp.idSpecialty = ci.idEspecialidad');
			$this->db->where("ci.idCita", $idcita);
			
			$query = $this->db->get()->result();

			if ($query) {
				foreach($query as $row)
				{
					$this->db->select("GROUP_CONCAT(distinct pro.descripcion ) as concepto");
					$this->db->from("historial_asignacion_cita hac");
					$this->db->join('procedimientos pro', 'pro.codigo_interno = hac.codigo_procedimiento');
					$this->db->join('cita ci', 'ci.idCita = hac.idCita');
					$this->db->where("hac.activo", 1);
					$this->db->where("ci.status", 1);
					$this->db->where("ci.idCita", $idcita);
					$this->db->where("hac.codigo_asignacion", $row->codigo_asignacion);
				 
					$query = $this->db->get();
					$row_resultado = $query->row_array();
					 
					$this->data['procedimientos'] = $row_resultado['concepto'];

					$codigo = ($this->input->get("code")) ? $this->input->get("code") : $row->codigo_asignacion;
					$this->db->select("sum( precio ) - 
					if( (SELECT sum( monto ) FROM solicitud_citas_pagos_descuento WHERE solicitud_citas_pagos_descuento.codigo_asignacion = solicitud_citas_pagos.codigo_asignacion AND solicitud_citas_pagos_descuento.activo = 1 ) is null, 0, (SELECT sum( monto ) FROM solicitud_citas_pagos_descuento WHERE solicitud_citas_pagos_descuento.codigo_asignacion = solicitud_citas_pagos.codigo_asignacion AND solicitud_citas_pagos_descuento.activo = 1 ) ) AS monto");
					$this->db->from("solicitud_citas_pagos");
					$this->db->where("activo", 1);
					$this->db->where("codigo_asignacion", $codigo);
				 
					$query = $this->db->get();

					$row_resultadoMonto = $query->row_array();


					$this->db->select("code_principal");
					$this->db->from("historial_asignacion_cita");
					$this->db->where("codigo_asignacion", $row->codigo_asignacion);
					$this->db->where("activo", 1);
					$this->db->where("idCita", $idcita);
					$this->db->where("code_principal is not null");
				 
					$query = $this->db->get();
					$row_resultadoCodigo = $query->row_array();
					$row_resultadoCodigo['code_principal'];

					$this->db->select("descripcion as titulo");
					$this->db->from("procedimientos");
					$this->db->where("codigo_interno", $row_resultadoCodigo['code_principal']);
				 
					$query = $this->db->get();
					$row_resultadoTitulo = $query->row_array();
					 
					$result[]   = array(
					'fechaCita'	=> htmlspecialchars($row->fechaCita, ENT_QUOTES),
					'horaCita'	=> htmlspecialchars($row->horaCita, ENT_QUOTES),
					'paciente'	=> htmlspecialchars($row->paciente, ENT_QUOTES),
					'medico'	=> htmlspecialchars($row->medico, ENT_QUOTES),
					'monto'	=> htmlspecialchars($row->gratis? 0: $row_resultadoMonto['monto'], ENT_QUOTES),
					'descripcion'	=> htmlspecialchars($this->data['procedimientos'], ENT_QUOTES),
					'titulo'	=> htmlspecialchars($row_resultadoTitulo['titulo'], ENT_QUOTES),
					'name'	=> htmlspecialchars($row->name, ENT_QUOTES)
					);

					$this->data['usuarioCita'] = $this->Usuario->datosUsuario($row->idUsuario);
					$this->data['cantidad'] = $this->Helper->numeroHistorialClinica($row->idUsuario);
					$this->data['terminalista']  = $this->Usuario->datosUsuario($row->idUsuarioCreacion);
				}
			}
		
			$this->data['resultados']  = $result;

			$this->load->view('gestionCitas/print', $this->data);
		} else {
				
			redirect(base_url("inicio"));
		}
	}

	public function administración_caja_general()
    {
		 
		$this->validarSesion();
		$this->cargarDatosSesion();
		$result = array();

		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$query = array();

			if($this->input->post("cliente") || $this->input->post("fechaInicial"))
			{
				$sql = "";

				if($this->input->post("cliente")) {
					$sql = "
						SELECT
							`apc`.`id`,
							`apc`.`tipo_solicitud` as tipo,
							`pro`.`descripcion`,
							`apc`.`precio`,
							RIGHT ( apc.codigo_asignacion, 4 ) AS codigo,
							CONCAT( pa.firstname, ' ', pa.lastname ) AS paciente,
							`apc`.`pago` AS `pagoStatus`,
							`apc`.`idUsuario`,
							date_format( apc.fechaCreacion, '%d/%m/%Y %H:%i' ) AS fecha,
							`apc`.`codigo_asignacion`,
							( SELECT sum( monto ) FROM solicitud_citas_pagos_descuento WHERE idProPagoCaja = apc.id AND activo = 1 ) AS descuento,
							`apc`.`codigo_procedimiento`
						FROM
							`solicitud_citas_pagos` `apc`
							JOIN `procedimientos` `pro` ON `pro`.`codigo_interno` = `apc`.`codigo_procedimiento`
							JOIN `patients` `pa` ON `pa`.`idUsuario` = `apc`.`idUsuario` 
						WHERE
							`apc`.`activo` = 1 
							AND `apc`.`tipo_solicitud` = 'PRO' 
							AND `apc`.`idUsuario` = ".$this->input->post("cliente")."
							AND `apc`.`tipo_solicitud` like '".$this->input->post("tipo")."%'
							
							union 
						SELECT
							`apc2`.`id`,
							`apc2`.`tipo_solicitud` as tipo,
							`exa`.`nombre` as descripcion,
							`apc2`.`precio`,
							`apc2`.`codigo_lab` as codigo,
							CONCAT( pa.firstname, ' ', pa.lastname ) AS paciente,
							`apc2`.`pago` AS `pagoStatus`,
							`apc2`.`idUsuario`,
							date_format( apc2.fechaCreacion, '%d/%m/%Y %H:%i' ) AS fecha,
							`apc2`.`codigo_asignacion`,
							`apc2`.`descuento`,
							`apc2`.`codigo_procedimiento`
						FROM
							`solicitud_citas_pagos` `apc2`
							JOIN `examen` `exa` ON `exa`.`id` = `apc2`.`codigo_procedimiento`
							JOIN `patients` `pa` ON `pa`.`idUsuario` = `apc2`.`idUsuario` 
						WHERE
							`apc2`.`activo` = 1 
							AND `apc2`.`tipo_solicitud` = 'LAB' 
							AND `apc2`.`idUsuario` = ".$this->input->post("cliente")."
							AND `apc2`.`tipo_solicitud` like '".$this->input->post("tipo")."%'
						ORDER BY 1 desc
					";

				$query = $this->db->query($sql)->result();
				} 
		
			}

			$this->data['resultados']  = $query;
			$this->data['realizarPago']  = $this->Helper->permiso_usuario("cambiar_status_pago");
			$this->data['consultarCita']  = $this->Helper->permiso_usuario("filtro_busqueda_cita");
			
			$this->db->select("idSpecialty, name");
			$this->db->from('specialties');
			$this->db->where('status', 1);
			$this->db->order_by("name", "asc");

			$this->load->view('gestionCitas/caja', $this->data);
		} else {
			
			redirect(base_url("inicio"));
		}
	}

	public function administración_caja_grabar_new()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$idUsuario = $this->input->post("usuario");
			$codigoInterno = null;
 

			if($this->input->post("procedimiento")) {

				if(count($this->input->post("procedimiento"))) {
					for ($i=0; $i < count(array_filter($this->input->post("procedimiento"))); $i++) { 

						$porciones = explode("=", $this->input->post("procedimiento")[$i]);
						
						if(is_null($codigoInterno)) {
							$codigoInterno = strtoupper(uniqid());
						}
 

						$procedimientosPro = array(
							'idCita' => $this->input->post("idCita"),
							'codigo_procedimiento' => $porciones[0],
							'codigo_asignacion' => $this->input->post("code"),
							'idUsuario' => $this->session->userdata('idUsuario')
						);

						$this->db->trans_start();
						$this->db->insert("historial_asignacion_cita", $procedimientosPro);
						$this->db->trans_complete();

						if ($this->db->trans_status()) 
						{
							$parametros = array (
								"idCita" => $this->input->post("idCita"),
								"codigo_asignacion" => $this->input->post("code"),
								"codigo_interno" =>$codigoInterno
							);
		
							$this->db->where('id', $porciones[1]);
							$this->db->update('solicitud_citas_pagos', $parametros);

						}

					}
				}
			}

			if($this->input->post("farmacia") > 0) {

				$procedimientosFar = array(
					'idUsuario' => $idUsuario,
					'precio' => $this->input->post("farmacia"),
					'idUsuarioCreacion' => $this->session->userdata('idUsuario')
				);

				$this->db->trans_start();
				$this->db->insert("agregar_pago_farmacia_caja", $procedimientosFar);
				$idCodigo = $this->db->insert_id();
				$this->db->trans_complete();
				

				if(is_null($codigoInterno)) {
					$codigoInterno = strtoupper(uniqid());
				}

				$parametros = array (
					"codigo_interno" =>$codigoInterno
				);

				$this->db->where('id', $idCodigo);
				$this->db->update('agregar_pago_farmacia_caja', $parametros);
			}

			$response['message'] = "Se registro correctamente.";
			$response['status'] = true;
		 
			
			$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );

		} else {
				
			redirect(base_url("inicio"));
		}
	}

	public function print_administración_add_caja_new()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$tipo = $this->uri->segment(3);
			$usuario = $this->uri->segment(4);
			$cita = $this->uri->segment(5);
			//$code = $this->uri->segment(6);
			$result = array();
			$code = $this->input->get("code");
			
			$this->data['usuarioCita'] = $this->Usuario->datosUsuario($usuario);

			if($tipo == "Laboratorios")
			{
				$this->db->select("exa.nombre as descripcion, exa.precio, ( SELECT agregar_pago_laboratorio_descuento_caja.monto FROM agregar_pago_laboratorio_descuento_caja WHERE codigo_interno =  aplc.codigo_interno AND activo = 1 ) AS descuento ");
				$this->db->from('agregar_pago_laboratorio_caja aplc');
				$this->db->join('examen exa', 'exa.id = aplc.codigo_laboratorio');
				$this->db->where("aplc.codigo_interno", $cita);
				$this->db->order_by("exa.nombre", "asc");
				
				$query = $this->db->get()->result();
					
				if ($query) {
					foreach($query as $row)
					{
						$result[]   = array(
							'examen'	=> htmlspecialchars($row->descripcion, ENT_QUOTES),
							'descuento'	=> htmlspecialchars($row->descuento*1, ENT_QUOTES),
							'precio'	=> htmlspecialchars($row->precio, ENT_QUOTES)
						);
					}
				}
	 
			} else {
				$this->db->select("pro.descripcion, appc.precio, ( SELECT sum(solicitud_citas_pagos_descuento.monto) FROM solicitud_citas_pagos_descuento WHERE codigo_asignacion =  appc.codigo_asignacion AND activo = 1 and marca = 1 and idProPagoCaja = appc.id ) AS descuento,  appc.codigo_asignacion");
				$this->db->from('solicitud_citas_pagos appc');
				$this->db->join('procedimientos pro', 'pro.codigo_interno = appc.codigo_procedimiento');
				$this->db->where("appc.idCita", $cita);
				$this->db->where("appc.activo", 1);
				//$this->db->where("appc.id", $code);
				$this->db->where("appc.marca",1);
				$this->db->order_by("pro.descripcion", "asc");

				$query = $this->db->get()->result();

				if ($query) {
					foreach($query as $row)
					{
						$result[]   = array(
						'examen'	=> htmlspecialchars($row->descripcion, ENT_QUOTES),
						'descuento'	=> htmlspecialchars($row->descuento*1, ENT_QUOTES),
						'precio'	=> htmlspecialchars($row->precio, ENT_QUOTES)
						);

						$code = $row->codigo_asignacion;
					}
				}

				$this->db->where('idCita', $cita);
				$this->db->update('solicitud_citas_pagos', array("marca" => 1));
			}
				
				$this->data['resultados']  = $result;
				$this->data['code']  = $code;
		 
			$this->load->view('cajaNew/print_add', $this->data);
		} else {
				
			redirect(base_url("inicio"));
		}
	}

	public function actualizar_marcaPrint()
	{ 
		$parametros = array (
			"marca" => $this->input->post("marca")
		);

		$this->db->where('id', $this->input->post("id"));
		$this->db->update('solicitud_citas_pagos', $parametros);
	}

	public function administración_caja_grabar_descuento_new()
	{
		$descuento = $this->input->post("descuento");
		$codigoInterno = $this->input->post("codigoInterno");
		$idCita = $this->input->post("idCita");
		$usuario = $this->input->post("usuario");
		$tipo = $this->input->post("tipo");
		$code = $this->input->post("code");
		$codePrincipal = $this->input->post("codeid");

		$parametros = array (
			"activo" => 0
		);

		$parametrosInsert = array(
			'codigo_interno' => $codigoInterno,
			'idProPagoCaja' => $codePrincipal,
			'codigo_asignacion' => $code,
			'monto' => $descuento,
			'idUsuario' => $this->session->userdata('idUsuario')
		);

		if ($tipo == "Procedimientos")
		{
			$this->db->where('idProPagoCaja', $codePrincipal);
			//$this->db->where('codigo_interno', $codigoInterno);
			$this->db->update('solicitud_citas_pagos_descuento', $parametros);
	
			$this->db->insert("solicitud_citas_pagos_descuento", $parametrosInsert);
			
				
			$this->db->where('id', $codePrincipal);
			$this->db->where('codigo_asignacion', $code);
			$this->db->update('solicitud_citas_pagos', array("descuento" => $descuento));

		} 

		redirect(base_url("cash-management/addPay/$usuario/$idCita/$code"));
	}

	public function administración_caja_eliminar_new()
	{
		$codigoInterno = $this->input->post("codigoInterno");
		$idCita = $this->input->post("idCita");
		$usuario = $this->input->post("usuario");
		$tipo = $this->input->post("tipo");
		$code = $this->input->post("code");
		$codeid = $this->input->post("codeid");
		$procedimiento = $this->input->post("procedimiento");

		$parametros = array (
			"codigo_asignacion" => null
		);

		if($tipo == "Laboratorios")
		{
			$this->db->trans_start();
			$this->db->where('codigo_interno', $codeid);
			$this->db->update('agregar_pago_laboratorio_caja', $parametros);
			$this->db->trans_complete();
		} else if($tipo == "Procedimientos")
		{
			$this->db->trans_start();
			$this->db->where('id', $codeid);
			$this->db->update('solicitud_citas_pagos', $parametros);
			$this->db->trans_complete();

			if ($this->db->trans_status()) 
			{
/* 				$parametros = array (
					"codigo_asignacion" => null
				);

				$this->db->where('codigo_asignacion', $code);
				$this->db->where('idUsuario', $usuario);
				$this->db->where('codigo_procedimiento', $procedimiento);
				$this->db->update('solicitud_citas_pagos_descuento', $parametros);
*/
				$parametrosCajaDescuento = array (
					"activo" => 0,
					"idUsuario" => $this->session->userdata('idUsuario'),
					"fechaCreacion" => date("Y-m-d H:m:s")
				); 

				$this->db->where('idProPagoCaja', $codeid);
				$this->db->update('solicitud_citas_pagos_descuento', $parametrosCajaDescuento);

				$parametrosHistorial = array (
					"activo" => 0
				);

				$this->db->where('codigo_asignacion', $code);
				$this->db->where('idCita', $idCita);
				$this->db->where('codigo_procedimiento', $procedimiento);
				$this->db->update('historial_asignacion_cita', $parametrosHistorial);
			}
		}  


		redirect(base_url("cash-management/addPay/$usuario/$idCita/$code"));
	}

	public function ventana_citas()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		$code = $this->uri->segment(3);
		$codigoId = $this->uri->segment(4);

		$this->data['paciente'] = null;
		$this->data['resultados'] = array();

		$this->db->select("pro.descripcion, scp.precio, scp.id");
		$this->db->from('solicitud_citas_pagos scp');
		$this->db->join('procedimientos pro', 'pro.codigo_interno = scp.codigo_procedimiento');
		$this->db->where("scp.id", $codigoId);
		
		$this->data['registroEditar'] = $this->db->get()->result();
		
		$paciente = null;
		$result = array();
		
		if($code)
		{
			$this->db->select("ci.idCita, ci.idUsuario, date_format(ci.fechaCita, '%d/%m/%Y') as fechaCita, ci.horaCita, concat(pa.firstname, ' ', pa.lastname) as paciente, concat(do.firstname, ' ', do.lastname) as medico, esp.name, ci.codigo_procedimiento, if(ci.status = 0, 'Atendido', if(ci.status = 1, 'Sin Atender', 'Cancelado')) as estadoCita");
			$this->db->from('cita ci');
			$this->db->join('doctors do', 'do.idDoctor = ci.idMedico');
			$this->db->join('patients pa', 'pa.idUsuario = ci.idUsuario');
			$this->db->join('specialties esp', 'esp.idSpecialty = ci.idEspecialidad');
			//$this->db->where("ci.status in(0, 1)");
			$this->db->where("ci.codigo_asignacion", $code);
			
			$query = $this->db->get()->result();

			foreach($query as $row)
			{
				$this->db->select("GROUP_CONCAT(pro.descripcion, '=>', pro.precio ) as concepto");
				$this->db->from("historial_asignacion_cita hac");
				$this->db->join('procedimientos pro', 'pro.codigo_interno = hac.codigo_procedimiento');
				$this->db->join('cita ci', 'ci.idCita = hac.idCita');
				$this->db->where("hac.activo", 1);
				$this->db->where("ci.idCita", $row->idCita);
				$this->db->where("hac.codigo_asignacion", $code);
			
				$query = $this->db->get();
				$row_resultado = $query->row_array();

				$result[]   = array(
				'idCita'	=> $row->idCita,
				'fechaCita'	=> $row->fechaCita,
				'horaCita'	=> $row->horaCita,
				'medico'	=> $row->medico,
				'especialidad'	=> $row->name,
				'procedimiento'	=> $row_resultado['concepto'],
				'estadoCita'	=> $row->estadoCita
			
				);

				$paciente = $row->paciente;
			}

			$this->data['paciente'] = $paciente;

			$this->data['resultados']  = $result;
			$this->data['code']  = $code;
		}

		$this->load->view('gestionCitas/citas', $this->data);
	}

	public function administración_caja_print()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$code = $this->uri->segment(3);
  
			$this->db->select("concat(pa.firstname, ' ', pa.lastname) as paciente, pro.descripcion as procedimiento, scp.precio, sum( scp.precio ) - 
			if( (SELECT sum( monto ) FROM solicitud_citas_pagos_descuento WHERE solicitud_citas_pagos_descuento.idProPagoCaja = scp.id AND solicitud_citas_pagos_descuento.activo = 1 ) is null, 0, (SELECT sum( monto ) FROM solicitud_citas_pagos_descuento WHERE solicitud_citas_pagos_descuento.idProPagoCaja = scp.id AND solicitud_citas_pagos_descuento.activo = 1 ) ) AS monto");
			$this->db->from('solicitud_citas_pagos scp');
			$this->db->join('patients pa', 'pa.idUsuario = scp.idUsuario');
			$this->db->join('procedimientos pro', 'pro.codigo_interno = scp.codigo_procedimiento');
			$this->db->where("scp.id", $code);
			
			$query = $this->db->get()->result();

			if ($query) {
				foreach($query as $row)
				{
			 

					$result[]   = array(
					'paciente'	=> htmlspecialchars($row->paciente, ENT_QUOTES),
					'precio'	=> htmlspecialchars($row->precio, ENT_QUOTES),
					'monto'	=> htmlspecialchars($row->monto, ENT_QUOTES),
					'procedimiento'	=> htmlspecialchars($row->procedimiento, ENT_QUOTES)
					);

				}
			}
		
			$this->data['resultados']  = $result;

			$this->load->view('gestionCitas/print_caja', $this->data);
		} else {
				
			redirect(base_url("inicio"));
		}
	}

	public function modificar_precio()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();
		$idReg = $this->input->post("idRegistro");
		$code = $this->input->post("code");

		$parametros = array (
			"precio" => $this->input->post("monto")
		);

		$this->db->trans_start();
		$this->db->where('id', $idReg);
		$this->db->update('solicitud_citas_pagos', $parametros);
		$this->db->trans_complete();
	 

		if ($this->db->trans_status())
		{
			$parametroInsert = array(
				'precio' => $this->input->post("montoOrigen"),
				'codigo_solicitudCPago' => $idReg,
				'idUsuario' => $this->session->userdata('idUsuario')
			);
	
			$this->db->insert('solicitud_citas_pagos_modficar', $parametroInsert);
		}

		redirect(base_url("cash-management/window-print/$code/$idReg"));
	}

	
	public function buscarRecetas()
	{
		$json = [];
		if(!empty($this->input->post("q")) || !empty($this->input->post("busqueda"))){

			$this->db->select("id, concat(descripcion, ' => ', presentacion) as text");
			$this->db->from('recetas');
			$this->db->like('descripcion', $this->input->post("q"));
			$this->db->where("activo", 1);
			$this->db->order_by("descripcion", "ASC");
			$this->db->limit(20);
	 
			$json = $this->db->get()->result();
		}
		
		echo json_encode($json);
	}
	
	
	public function print_administración_antigenos()
	{
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$code = $this->uri->segment(3);
  
			$this->db->select("if(gp.sede = 'SBC', 'SBCMedic', 'Domicilio') as sede, gp.id, (gp.id - 816) as numerox, gp.quienSolicito, gp.email, gp.telefono, gp.hora, gp.fecha, gp.idUsuario, (gp.costo_cantidadPrueba + gp.costo_cantidadPrueba_psr + gp.costo_transporte-gp.porcentajeDescuento) as precio, gp.cantidadPrueba,  gp.cantidadPrueba_psr,( gp.costo_cantidadPrueba) as precioAnti, (gp.costo_cantidadPrueba_psr) as precioPcr, gp.costo_transporte, gp.fechaCreacion, (
				gp.id - (
				SELECT
					max( id ) 
				FROM
					gestion_paciente 
				WHERE
					date( fechaCreacion ) <= date( date_add( now( ), INTERVAL - 1 DAY ) ) 
				) 
			) as numero, IF
			( gp.tipo_banco = 'BCP', 'Banco de Crédito', IF ( gp.tipo_banco = 'BBVA', 'BBVA Continental', IF ( gp.tipo_banco = 'SCOTBANK', 'Scotiabank', IF ( gp.tipo_banco = 'INTERB', 'Interbank', IF ( gp.tipo_banco = 'GRA', 'Gratis', IF ( gp.tipo_banco = 'EFE', 'Efectivo', IF ( gp.tipo_banco = 'TAR', 'Tarjeta', IF ( gp.tipo_banco = 'TRA', 'Transferencia', 'Efectivo' ) ) ) ) ) ) ) ) AS   tipoPago");
			$this->db->from('gestion_paciente gp');
			$this->db->where("id", $code);
 
			$query = $this->db->get()->result();
			$codigoInterno = null;


			$this->db->select("id, concat(nombre, ', ', apellido) as nombre, concat(dni, ' ', pasaporte) as documento, tipo_prueba");
			$this->db->from('gestion_paciente_cliente2');
			$this->db->where("idGestionPaciente", $code);

			$pacientes = $this->db->get();

			if ($query) {
				foreach($query as $row)
				{
					$result[]   = array(
					'quienSolicito'	=> htmlspecialchars($row->quienSolicito, ENT_QUOTES),
					'telefono'	=> htmlspecialchars($row->telefono, ENT_QUOTES),
					'nro'	=> htmlspecialchars($row->numero, ENT_QUOTES),
					'hora'	=> htmlspecialchars($row->hora, ENT_QUOTES),
					'sede'	=> htmlspecialchars($row->sede, ENT_QUOTES),
					'cantidadPrueba'	=> htmlspecialchars($row->cantidadPrueba, ENT_QUOTES),
					'precioAnti'	=> htmlspecialchars($row->precioAnti, ENT_QUOTES),
					'cantidadPrueba_psr'	=> htmlspecialchars($row->cantidadPrueba_psr, ENT_QUOTES),
					'precioPcr'	=> htmlspecialchars($row->precioPcr, ENT_QUOTES),
					'costo_transporte'	=> htmlspecialchars($row->costo_transporte, ENT_QUOTES),
					'precio'	=> htmlspecialchars($row->precio, ENT_QUOTES),
					'fechaCreacion'	=> htmlspecialchars($row->fechaCreacion, ENT_QUOTES),
					'tipoPago'	=> htmlspecialchars($row->tipoPago, ENT_QUOTES),
					'fecha'	=> htmlspecialchars($row->fecha, ENT_QUOTES)
					);

		 

					$this->data['terminalista'] = $this->Usuario->datosUsuario($row->idUsuario);
					 
 
				}
			}
		
			$this->data['resultados']  = $result;
			$this->data['pacientes']  = $pacientes;

			$this->load->view('gestionPaciente/print', $this->data);
		} else {
				
			redirect(base_url("inicio"));
		}
	}
	
		public function patientSummaryManagementPay()
	{
		$parametros = array(
			'pago' => $this->input->post("valor"),
			'idUsuarioPago' => $this->session->userdata('idUsuario'),
			'fechaPago' => date("Y-m-d H:m:s")
		);

		$this->db->trans_start();
		//$this->db->where("realizado = 0");
		$this->db->where('id', $this->input->post("codigo"));
		$this->db->update('gestion_paciente', $parametros);
		$this->db->trans_complete();
	 
		if ($this->db->trans_status())
		{
			 
			$this->db->where('tipo_solicitud', "ANT");
			$this->db->where('codigo_procedimiento', $this->input->post("codigo"));
			$this->db->update('solicitud_citas_pagos', $parametros);
			 

			$response['status'] = true;
		} else {
			$response['status'] = false;
		}
	

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}
	
	public function patientSummaryManagementReadyNew()
	{
		$parametros = array(
			'idDoctor' => $this->input->post("profesionales"),
			'realizado' => $this->input->post("resultado"),
			'idUsuarioRealizado' => $this->session->userdata('idUsuario'),
			'fechaRealizado' => date("Y-m-d H:m:s")
		);

		$this->db->trans_start();
		//$this->db->where("realizado = 0");
		$this->db->where('id', $this->input->post("codigo"));
		$this->db->update('gestion_paciente', $parametros);
		$this->db->trans_complete();
	 
		if ($this->db->trans_status())
		{
			$response['status'] = true;
		} else {
			$response['status'] = false;
		}
	

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}



	public function gestion_pago()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			//$usuario = $this->input->post("paciente");
			$usuario = $this->uri->segment(2);
 
			$queryResumen = array();
			
			if($usuario)
			{
				$sqlResumen = "
					SELECT
						'PRO' as tipo,
						concat('PRO-',cita.codigo_asignacion) as codigo_asignacion,
						cita.codigo_asignacion as code,
						GROUP_CONCAT( cita.idCita) as codigo,
						(select GROUP_CONCAT(descripcion) from procedimientos where codigo_interno in(select codigo_procedimiento from solicitud_citas_pagos scp where scp.codigo_asignacion = solicitud_citas_pagos.codigo_asignacion and scp.activo = 1) ) as descripcion,
						
						
						@precio := (select sum(scp.precio) from solicitud_citas_pagos scp where scp.codigo_asignacion = solicitud_citas_pagos.codigo_asignacion and scp.activo = 1 ) as precio, 
						 
						@descuento_porcentaje := (select descuento_porcentaje/100  from solicitud_citas_pagos_descuento where codigo_asignacion = cita.codigo_asignacion and solicitud_citas_pagos_descuento.activo = 1) as descuento_porcentaje,

						@precio - @precio*(if(@descuento_porcentaje is null, 0, @descuento_porcentaje)) as total

					FROM
						cita
						INNER JOIN solicitud_citas_pagos ON solicitud_citas_pagos.codigo_asignacion = cita.codigo_asignacion
						INNER JOIN procedimientos ON procedimientos.codigo_interno = solicitud_citas_pagos.codigo_procedimiento 
					WHERE
						cita.codigo_asignacion IS NOT NULL 
						AND cita.idUsuario = $usuario 
						AND cita.`status` = 1 
						AND cita.fechaCita >= date(now())
						and solicitud_citas_pagos.pago = 0
					GROUP BY
					cita.codigo_asignacion

					union

					SELECT
					'LAB' AS tipo,
					concat( 'LAB-', solicitarexamen.codigo_interno ) AS codigo_asignacion,
					solicitarexamen.codigo_interno as code,
					solicitarexamen.codigo_interno AS codigo,
					GROUP_CONCAT( examen.nombre ) AS descripcion,
 
					@precio := (select precio from solicitarexamen_detalle where codigo_interno= solicitarexamen.codigo_interno)
					as precio ,
					@descuento_porcentaje := IF
					((
						SELECT
							count(*) 
						FROM
							solicitarexamen_descuento_transporte 
						WHERE
							codigo_interno = solicitarexamen.codigo_interno 
							AND solicitarexamen_descuento_transporte.activo = 1
							and  solicitarexamen_descuento_transporte.tipo ='DES' 
							) = 1,
						( SELECT descuento_porcentaje FROM solicitarexamen_descuento_transporte WHERE codigo_interno = solicitarexamen.codigo_interno AND solicitarexamen_descuento_transporte.activo = 1 and  solicitarexamen_descuento_transporte.tipo ='DES'),
						0 
					) as descuento_porcentaje,
					if((select count(*) from solicitarexamen_detalle where codigo_interno= solicitarexamen.codigo_interno) =1, (@precio - @precio*(select descuento/100 from solicitarexamen_detalle where codigo_interno= solicitarexamen.codigo_interno)), sum(solicitarexamen.precio) - sum(solicitarexamen.precio)*@descuento_porcentaje/100)  
				AS total  
				FROM
					solicitarexamen
					INNER JOIN examen ON examen.id = solicitarexamen.idExamen 
				WHERE
					solicitarexamen.idUsuario = $usuario 
					AND solicitarexamen.status_pago = 0 
					AND ( solicitarexamen.idPerfil IS NULL OR solicitarexamen.idPerfil = 0 )
					AND solicitarexamen.idCita is null 
					AND solicitarexamen.codigo_asignacion is null
				GROUP BY
					2
				";

				$queryResumen = $this->db->query($sqlResumen);
			}

			$this->data["resumen"] = $queryResumen;

			$this->data["paciente"] = $this->Usuario->datosUsuario($usuario);
			
			$this->load->view('cajaNew/gestion_pago', $this->data);

		} else {
				
			redirect(base_url("inicio"));
		}
	}


	public function gestion_pago_detalle()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		$codePro = "";
		$codeLab = "";
 
		foreach ($this->input->post("code") as $value) {

			$trozo = explode("-", $value);

			if($trozo[0] == "PRO") {
				$codePro = $codePro."'".$trozo[1]."',";
			}

			if($trozo[0] == "LAB") {
				$codeLab = $codeLab."'".$trozo[1]."',";
			}
			
		}



		$codePro = substr($codePro, 0, -1);
		$codeLab = substr($codeLab, 0, -1);


 
		if(!empty($codePro))	$codePro = $codePro; else $codePro = "''";
		if(!empty($codeLab))	$codeLab = $codeLab; else $codeLab = "''";
 
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$usuario = $this->uri->segment(3);
 
			
			$sqlResumen = "
			SELECT
			'PRO' as tipo,
			cita.codigo_asignacion,
			
			'' as codigo_interno,
			(select GROUP_CONCAT(descripcion) from procedimientos where codigo_interno in(select codigo_procedimiento from solicitud_citas_pagos scp where scp.codigo_asignacion = solicitud_citas_pagos.codigo_asignacion and scp.activo = 1) ) as descripcion,

			@precio := (select sum(scp.precio) from solicitud_citas_pagos scp where scp.codigo_asignacion = solicitud_citas_pagos.codigo_asignacion and scp.activo = 1 ) as precio, 
			
			@descuento_porcentaje := solicitud_citas_pagos.descuento_porcentaje as descuento_porcentaje,
			
			  
			

			solicitud_citas_pagos.id,
			cita.idUsuario,
			'' as fechaExamen
		FROM
			cita
			INNER JOIN solicitud_citas_pagos ON solicitud_citas_pagos.codigo_asignacion = cita.codigo_asignacion
			INNER JOIN procedimientos ON procedimientos.codigo_interno = solicitud_citas_pagos.codigo_procedimiento 
		WHERE
			cita.codigo_asignacion IS NOT NULL 
			AND cita.`status` = 1 
			AND cita.fechaCita >= date(now())
			and cita.codigo_asignacion in ($codePro)
			AND solicitud_citas_pagos.activo = 1
			and solicitud_citas_pagos.pago = 0
			GROUP BY
			cita.codigo_asignacion

			union

			SELECT
				'LAB' as tipo,
				solicitarexamen.codigo_interno AS codigo_asignacion,
				solicitarexamen.codigo_interno,
				concat(GROUP_CONCAT( examen.nombre), ' => ', solicitarexamen.codigo_interno) AS descripcion,

				 

				  @precio := if((select count(*) from solicitarexamen_detalle where codigo_interno= solicitarexamen.codigo_interno) =1, (select precio from solicitarexamen_detalle where codigo_interno= solicitarexamen.codigo_interno), sum(solicitarexamen.precio)) as precio,
				 
				  @descuento_porcentaje := IF
				  ((
					  SELECT
						  count(*) 
					  FROM
						  solicitarexamen_descuento_transporte 
					  WHERE
						  codigo_interno = solicitarexamen.codigo_interno 
						  AND solicitarexamen_descuento_transporte.activo = 1
						  and  solicitarexamen_descuento_transporte.tipo ='DES' 
						  ) = 1,
					  ( SELECT descuento_porcentaje FROM solicitarexamen_descuento_transporte WHERE codigo_interno = solicitarexamen.codigo_interno AND solicitarexamen_descuento_transporte.activo = 1 and  solicitarexamen_descuento_transporte.tipo ='DES'),
					  0 
				  ) as descuento_porcentaje,

				'' as id,
				solicitarexamen.idUsuario,
				solicitarexamen.fechaExamen
				
			FROM
				solicitarexamen
				INNER JOIN examen ON examen.id = solicitarexamen.idExamen 
			WHERE
				solicitarexamen.codigo_interno in($codeLab)  
				AND solicitarexamen.status_pago = 0 
				AND ( solicitarexamen.idPerfil IS NULL OR solicitarexamen.idPerfil = 0 )
				AND solicitarexamen.idCita is null 
				AND solicitarexamen.codigo_asignacion is null
			GROUP BY
				3

			";
 
			$queryResumen = $this->db->query($sqlResumen);
			if($queryResumen->num_rows() < 1 ){
				die("No permitido");
			}

			$this->data["resumen"] = $queryResumen;
			
			$this->db->select("id, descripcion");
			$this->db->from('catalogo_tipo_pago');
			$this->db->where("activo", 1);
			$this->db->order_by("orden", "desc");
			
			$resultadosTipoPago = $this->db->get()->result();
			
			$this->data["resultadosTipoPago"] = $resultadosTipoPago;

			
			$caracter = ",";

			if($codePro == "''")
			{
				$caracter = "";
				$codePro = "";
			}

			if($codeLab == "''")
			{
				$caracter = "";
				$codeLab = "";
			}

			$codeGeneral = $codePro.$caracter.$codeLab;
			$this->data["code"] = $codeGeneral;
			$this->data["user"] = $this->input->post("user");
 
				
				$this->load->view('cajaNew/gestion_pago_grabar', $this->data);
	
		} else {
				
			redirect(base_url("inicio"));
		}
	}




	public function modificar_monto()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();
		$tipo = $this->input->post("tipo");

		$response['message'] = "NO SE GRABO.";
		$response['status'] = false;

		if($tipo == "PRO")
		{

			$idReg = $this->input->post("idRegistro");

			$parametros = array (
				"precio" => $this->input->post("monto")
			);

			$this->db->trans_start();
			$this->db->where('id', $idReg);
			$this->db->update('solicitud_citas_pagos', $parametros);
			$this->db->trans_complete();
					


			if ($this->db->trans_status())
			{
				$this->db->where('codigo_solicitudCPago', $idReg);
				$this->db->update('solicitud_citas_pagos_modficar', array("activo" => 0));
	
				$parametroInsert = array(
					'precio' => $this->input->post("montoOrigen"),
					'codigo_solicitudCPago' => $idReg,
					'idUsuario' => $this->session->userdata('idUsuario')
				);
		
				$this->db->insert('solicitud_citas_pagos_modficar', $parametroInsert);
	
				
				$response['message'] = "SE MODIFICO EL PRECIO.";
				$response['status'] = true;
			}
		} else if($tipo == "LAB") {
			

			//new

			$this->db->select("count(id) as cantidad");
			$this->db->from("solicitarexamen_detalle");
			$this->db->where("codigo_interno", $this->input->post("code"));
		   
			$query = $this->db->get();
			$row_resultadoDisponible = $query->row_array();
	 
			if($row_resultadoDisponible['cantidad'] == 0)
			{
				$cajaLabdescuento = array(
					'idUsuario' => $this->input->post("idUsuario"),
					'codigo_interno' => $this->input->post("code"),
					'fechaExamen' => $this->input->post("fechaExamen"),
					'precio' => $this->input->post("monto"),
					'idUsuarioCreacion' => $this->session->userdata('idUsuario')
				);
	
				$this->db->insert("solicitarexamen_detalle", $cajaLabdescuento);
			}

			$this->db->where('codigo_interno', $this->input->post("code"));
			$this->db->update('solicitarexamen_detalle', array("precio" => $this->input->post("monto")));


			//descuento

			$this->db->select("descuento");
			$this->db->from("solicitarexamen_detalle");
			$this->db->where("codigo_interno", $this->input->post("code"));
		 
			$query = $this->db->get();
			$row_resultadoDesc = $query->row_array();
			$descuento = $this->input->post("monto")*($row_resultadoDesc["descuento"]/100);



			$this->db->select("sum(precio) as precio, count(id) as cantidad, descuento");
			$this->db->from("solicitarexamen");
			$this->db->where("codigo_interno", $this->input->post("code"));
			$this->db->where("idPerfil", 0);
		 
			$query = $this->db->get();
			$row_resultado = $query->row_array();





			$monto = ($this->input->post("monto") - $row_resultado["precio"])/$row_resultado["cantidad"];

			$this->db->select("id");
			$this->db->from("solicitarexamen");
			$this->db->where("codigo_interno", $this->input->post("code"));
			$this->db->where("idPerfil", 0);
						
			$queryLab = $this->db->get()->result();
		 
			foreach($queryLab as $row)
			{
				$this->db->set("descuento", $descuento, FALSE);
				$this->db->set("precio", "precio + $monto", FALSE);
				$this->db->where('id', $row->id);
				$this->db->update('solicitarexamen');

				 

			}

			$this->db->select("id");
			$this->db->from("solicitud_citas_pagos");
			$this->db->where("codigo_lab", $this->input->post("code"));
						
			$querySoli = $this->db->get()->result();
		 
			foreach($querySoli as $reg)
			{
				$this->db->set("descuento", $descuento, FALSE);
				$this->db->set("precio", "precio + $monto", FALSE);
				$this->db->where('id', $reg->id);
				$this->db->update('solicitud_citas_pagos');
			}
 


			$response['message'] = "SE MODIFICO EL PRECIO.";
			$response['status'] = true;
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );

	}



	public function guardar_pago_descuento()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();
		
		$monto = $this->input->post("monto")*($this->input->post("descuento")/100);
	 
		$tipo = $this->input->post("tipo");
		$code = $this->input->post("code");
		$codePrincipal = $this->input->post("codeid");

		$response['message'] = "NO SE REGISTRO.";
		$response['status'] = false;

		$parametros = array (
			"activo" => 0
		);

		$parametrosInsert = array(
			'idProPagoCaja' => $codePrincipal,
			'codigo_asignacion' => $code,
			'monto' => $monto,
			'descuento_porcentaje' => $this->input->post("descuento"),
			'idUsuario' => $this->session->userdata('idUsuario')
		);

		if ($tipo == "PRO")
		{
			$this->db->where('idProPagoCaja', $codePrincipal);
			$this->db->update('solicitud_citas_pagos_descuento', $parametros);

			$this->db->insert("solicitud_citas_pagos_descuento", $parametrosInsert);
	
			$this->db->where('id', $codePrincipal);
			$this->db->where('codigo_asignacion', $code);
			$this->db->update('solicitud_citas_pagos', array("descuento" => $monto, "descuento_porcentaje" => $this->input->post("descuento")));

			$response['message'] = "SE APLICO EL DESCUENTO.";
			$response['status'] = true;
		} else if ($tipo == "LAB") {

			$this->db->where('codigo_interno', $code);
			$this->db->where('tipo', "DES");
			$this->db->update('solicitarexamen_descuento_transporte', $parametros);

			$cajaLabdescuento = array(
				'tipo' => "DES",
				'codigo_interno' => $code,
				'monto' => $monto,
				'descuento_porcentaje' => $this->input->post("descuento"),
				'idUsuario' => $this->session->userdata('idUsuario')
			);

			$this->db->insert("solicitarexamen_descuento_transporte", $cajaLabdescuento);

			$this->db->where('codigo_interno', $code);
			$this->db->update('solicitarexamen', array("descuento" => $monto));

			//new

			$this->db->where('codigo_interno', $code);
			$this->db->update('solicitarexamen_detalle', array("descuento" => $this->input->post("descuento")));

			$this->db->where('codigo_lab', $code);
			$this->db->update('solicitud_citas_pagos', array("descuento" => $monto));


			$response['message'] = "SE APLICO EL DESCUENTO.";
			$response['status'] = true;
		}

 
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}




	public function guardar_pago()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$code = $this->input->post("code");
			$codigoInterno = strtoupper(uniqid());

				for ($i=0; $i < count(array_filter($this->input->post("tipoPago"))); $i++) { 

					$pago = array(
						'codigo_interno' => $codigoInterno,
						'idTipoPago' => $this->input->post("tipoPago")[$i],
						'monto_pago' => empty($this->input->post("montoTipoPago")[$i]) ? 0 : $this->input->post("montoTipoPago")[$i],
						'comprobante' => $this->input->post("tipoComprobante"),
						'nro_comprobante' => $this->input->post("numeroComprobante"),
						'observacion' => $this->input->post("observacion"),
						'idUsuario' => $this->input->post("user"),
						'idUsuarioCreacion' => $this->session->userdata('idUsuario')
					);

					$this->db->trans_start();
					$this->db->insert("gestion_pago", $pago);
					$this->db->trans_complete();
				}

	
				$response['message'] = "No se puede guarda el registro.";
				$response['status'] = false;

				if ($this->db->trans_status())
				{

					$sql = "
						SELECT
						'PRO' as tipo,
						cita.codigo_asignacion,
						 
						@precio := (select sum(scp.precio) from solicitud_citas_pagos scp where scp.codigo_asignacion = solicitud_citas_pagos.codigo_asignacion and scp.activo = 1 ) as precio, 

					  
					  solicitud_citas_pagos.descuento_porcentaje
					FROM
						cita
						INNER JOIN solicitud_citas_pagos ON solicitud_citas_pagos.codigo_asignacion = cita.codigo_asignacion
						INNER JOIN procedimientos ON procedimientos.codigo_interno = solicitud_citas_pagos.codigo_procedimiento 
					WHERE
						cita.codigo_asignacion IS NOT NULL 
						AND cita.`status` = 1 
						AND cita.codigo_asignacion in ($code)
						AND cita.fechaCita >= date(now())
						AND solicitud_citas_pagos.activo = 1
						and solicitud_citas_pagos.pago = 0
						GROUP BY
						cita.codigo_asignacion

						union

						SELECT
							'LAB' as tipo,
							solicitarexamen.codigo_interno AS codigo_asignacion,
							
							
							
							if((select count(*) from solicitarexamen_detalle where codigo_interno= solicitarexamen.codigo_interno) =1, (select precio from solicitarexamen_detalle where codigo_interno= solicitarexamen.codigo_interno), sum(solicitarexamen.precio)) as precio ,
				
			 

			   IF
			   ((
				   SELECT
					   count(*) 
				   FROM
					   solicitarexamen_descuento_transporte 
				   WHERE
					   codigo_interno = solicitarexamen.codigo_interno 
					   AND solicitarexamen_descuento_transporte.activo = 1
					   and  solicitarexamen_descuento_transporte.tipo ='DES' 
					   ) = 1,
				   ( SELECT descuento_porcentaje FROM solicitarexamen_descuento_transporte WHERE codigo_interno = solicitarexamen.codigo_interno AND solicitarexamen_descuento_transporte.activo = 1 and  solicitarexamen_descuento_transporte.tipo ='DES'),
				   0 
			   ) as descuento_porcentaje
			
							
						FROM
							solicitarexamen
							INNER JOIN examen ON examen.id = solicitarexamen.idExamen 
						WHERE
							solicitarexamen.codigo_interno in($code) and
							solicitarexamen.status_pago = 0
							AND (solicitarexamen.idCita is null or solicitarexamen.codigo_asignacion is null)
						GROUP BY
							2
					";

					$query = $this->db->query($sql)->result();

					foreach ($query as $row) {
				
						$pagoDetalle = array(
							'codigo_interno' => $codigoInterno,
							'codigo_asignacion' => $row->codigo_asignacion,
							'tipo' => $row->tipo,
							'monto' => $row->precio,
							'descuento' => $row->descuento_porcentaje,
							'idUsuario' => $this->session->userdata('idUsuario')
						);
			
						$this->db->insert("gestion_pago_detalle", $pagoDetalle);

						$this->gpagoStatusNew($row->tipo, $row->codigo_asignacion);

						$response['message'] = "Se guardo correctamente.";
						$response['status'] = true;

					}
				}

				$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );

		} else {
				
			redirect(base_url("inicio"));
		}
	}

	public function gpagoStatusNew($tipo, $code)
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		if($tipo == "PRO")
		{
			$parametros = array (
				"idUsuarioPago" => $this->session->userdata('idUsuario'),
				"fechaPago" => date('Y-m-d H:i:s'),
				"pago" => 1
			);
		
			$this->db->where('codigo_asignacion', $code);
			$this->db->update("solicitud_citas_pagos", $parametros);

			$parametrosLab = array (
				"idUsuarioModificar" => $this->session->userdata('idUsuario'),
				"fechaModificar" => date('Y-m-d H:i:s'),
				"status_pago" => 1
			);
		
			$this->db->where('codigo_asignacion', $code);
			$this->db->update("solicitarexamen", $parametrosLab);


		} else if($tipo == "LAB") {
			$parametros = array (
				"idUsuarioModificar" => $this->session->userdata('idUsuario'),
				"fechaModificar" => date('Y-m-d H:i:s'),
				"status_pago" => 1
			);
		
			$this->db->where('codigo_interno', $code);
			$this->db->update("solicitarexamen", $parametros);
		}

	}



	public function gestion_pago_resumen()
	{

		$this->validarSesion();
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$this->db->select("gp.monto_pago, if(gp.comprobante ='', 'Sin-Comprobante', if(gp.comprobante ='BOL', 'BOLETA', 'FACTURA')) as comprobante, gp.nro_comprobante, ctp.descripcion as tipoPago, gp.fechaCreacion, gp.codigo_interno, (SELECT
			GROUP_CONCAT( cita.idCita ) 
		FROM
			gestion_pago_detalle
			INNER JOIN cita ON cita.codigo_asignacion = gestion_pago_detalle.codigo_asignacion 
		WHERE
			gestion_pago_detalle.codigo_interno = gp.codigo_interno and gestion_pago_detalle.tipo='PRO') as citas, (SELECT
			GROUP_CONCAT( codigo_asignacion ) 
		FROM
			gestion_pago_detalle
		WHERE
			gestion_pago_detalle.codigo_interno = gp.codigo_interno and gestion_pago_detalle.tipo='LAB') as labs,
			(select if(tipo = 'EXT', 'EXTERNO', if(tipo = 'EDE', 'Error de descripción', if(tipo = 'ANU', 'ANULACIÓN', ''))) from gestion_pago_ncredito where codigo_interno = gp.codigo_interno) as extorno,
			(select concat(firstname, ' ' , lastname) from patients where idUsuario = gp.idUsuarioCreacion) as usuarioPago,
			(select concat(firstname, ' ' , lastname) from patients where idUsuario = gp.idUsuario) as paciente
			");
		
			$this->db->from("gestion_pago gp");
			$this->db->join('catalogo_tipo_pago ctp', 'ctp.id = gp.idTipoPago');

			if($this->input->post("paciente") and !$this->input->post("fechaInicial")){
				$this->db->where("gp.idUsuario", $this->input->post("paciente"));
			}

			if($this->input->post("fechaInicial")){
				$this->db->where("date(gp.fechaCreacion)", $this->input->post("fechaInicial"));
			}

			if($this->input->post("comprobante")){
				$this->db->where("gp.nro_comprobante", $this->input->post("comprobante"));
			}
			
			
			$this->db->order_by("gp.fechaCreacion", "desc");
			
			$query = $this->db->get()->result();

			$this->data["resumen"] = $query;
			$this->data["paciente"] = $this->Usuario->datosUsuario($this->input->post("paciente"));

			if($this->input->post("fechaInicial")){
				$this->data["paciente"] = null;
			}
			

			$this->load->view('cajaNew/gestion_pago_resumen', $this->data);
			

		} else {
				
			redirect(base_url("inicio"));
		}
	}


	public function gestion_pago_resumen_detalle()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$code = $this->uri->segment(3);
			
			$sqlResumen = "
			SELECT
			if(gestion_pago_detalle.tipo ='LAB', gestion_pago_detalle.codigo_asignacion, (select GROUP_CONCAT( idCita )  from cita where codigo_asignacion = gestion_pago_detalle.codigo_asignacion) ) as codigo_asignacion,
			(
				IF
					(
						gestion_pago_detalle.tipo = 'LAB',
						(
						SELECT
							GROUP_CONCAT( examen.nombre ) 
						FROM
							solicitarexamen
							INNER JOIN examen ON examen.id = solicitarexamen.idExamen 
						WHERE
							solicitarexamen.codigo_interno = gestion_pago_detalle.codigo_asignacion 
						),
						(
						SELECT
							GROUP_CONCAT( procedimientos.descripcion ) 
						FROM
							procedimientos
							INNER JOIN solicitud_citas_pagos ON solicitud_citas_pagos.codigo_procedimiento = procedimientos.codigo_interno 
						WHERE
							solicitud_citas_pagos.codigo_asignacion = gestion_pago_detalle.codigo_asignacion 
						) 
					) 
				) AS concepto,
				gestion_pago_detalle.monto,
				gestion_pago_detalle.descuento,
				gestion_pago_detalle.fechaCreacion 
			FROM
				gestion_pago_detalle
				INNER JOIN gestion_pago ON gestion_pago.codigo_interno = gestion_pago_detalle.codigo_interno 
			WHERE
				gestion_pago.codigo_interno = '$code'

				group by gestion_pago.codigo_interno, gestion_pago_detalle.codigo_asignacion

			";

			$queryResumen = $this->db->query($sqlResumen)->result();
 
				$this->data['resultados']  = $queryResumen;
				
		 
			$this->load->view('cajaNew/gestion_pago_resumen_detalle', $this->data);
		} else {
				
			redirect(base_url("inicio"));
		}
	}



	public function gestion_pago_detalle_editar()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		$this->db->select("GROUP_CONCAT('\'', codigo_asignacion, '\'') as codigo");
		$this->db->from("gestion_pago_detalle");
		$this->db->where("codigo_interno", $this->uri->segment(3));
	   
		$query = $this->db->get();
		$row_resultadoDisponible = $query->row_array();
 		$code = $row_resultadoDisponible['codigo'];
		 
		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			
 
			
			$sqlResumen = "
			SELECT
			'PRO' as tipo,
			cita.codigo_asignacion,
			
			'' as codigo_interno,
			(select GROUP_CONCAT(descripcion) from procedimientos where codigo_interno in(select codigo_procedimiento from solicitud_citas_pagos scp where scp.codigo_asignacion = solicitud_citas_pagos.codigo_asignacion and scp.activo = 1) ) as descripcion,

			@precio := (select sum(scp.precio) from solicitud_citas_pagos scp where scp.codigo_asignacion = solicitud_citas_pagos.codigo_asignacion and scp.activo = 1 ) as precio, 
			
			@descuento_porcentaje := solicitud_citas_pagos.descuento_porcentaje as descuento_porcentaje,
			
			  
			

			solicitud_citas_pagos.id,
			solicitud_citas_pagos.activo_nc,
			'' as idUsuario,
			'' as fechaExamen
		FROM
			cita
			INNER JOIN solicitud_citas_pagos ON solicitud_citas_pagos.codigo_asignacion = cita.codigo_asignacion
			INNER JOIN procedimientos ON procedimientos.codigo_interno = solicitud_citas_pagos.codigo_procedimiento 
		WHERE
			cita.codigo_asignacion IS NOT NULL 
			and cita.codigo_asignacion in ($code)
			AND solicitud_citas_pagos.activo = 1
			GROUP BY
			cita.codigo_asignacion

			union

			SELECT
				'LAB' as tipo,
				solicitarexamen.codigo_interno AS codigo_asignacion,
				solicitarexamen.codigo_interno,
				concat(GROUP_CONCAT( examen.nombre), ' => ', solicitarexamen.codigo_interno) AS descripcion,

				 

				  @precio := if((select count(*) from solicitarexamen_detalle where codigo_interno= solicitarexamen.codigo_interno) =1, (select precio from solicitarexamen_detalle where codigo_interno= solicitarexamen.codigo_interno), sum(solicitarexamen.precio)) as precio,
				 
				  @descuento_porcentaje := IF
				  ((
					  SELECT
						  count(*) 
					  FROM
						  solicitarexamen_descuento_transporte 
					  WHERE
						  codigo_interno = solicitarexamen.codigo_interno 
						  AND solicitarexamen_descuento_transporte.activo = 1
						  and  solicitarexamen_descuento_transporte.tipo ='DES' 
						  ) = 1,
					  ( SELECT descuento_porcentaje FROM solicitarexamen_descuento_transporte WHERE codigo_interno = solicitarexamen.codigo_interno AND solicitarexamen_descuento_transporte.activo = 1 and  solicitarexamen_descuento_transporte.tipo ='DES'),
					  0 
				  ) as descuento_porcentaje,
				 
				'' as id,
				solicitarexamen.activo_nc,
				solicitarexamen.idUsuario,
				solicitarexamen.fechaExamen
				
			FROM
				solicitarexamen
				INNER JOIN examen ON examen.id = solicitarexamen.idExamen 
			WHERE
			solicitarexamen.codigo_interno  
	
			in(  $code )    
				AND ( solicitarexamen.idPerfil IS NULL OR solicitarexamen.idPerfil = 0 )
				AND solicitarexamen.idCita is null 
				AND solicitarexamen.codigo_asignacion is null
			GROUP BY
				3

			";
 
			$queryResumen = $this->db->query($sqlResumen);
		 

			$this->data["resumen"] = $queryResumen;
			
			$this->db->select("id, descripcion");
			$this->db->from('catalogo_tipo_pago');
			$this->db->where("activo", 1);
			$this->db->order_by("orden", "desc");
			
			$resultadosTipoPago = $this->db->get()->result();
			
			$this->data["resultadosTipoPago"] = $resultadosTipoPago;

	 
			$this->data["user"] = $this->input->post("user");

			$this->db->select("idTipoPago, monto_pago, comprobante, nro_comprobante, observacion");
			$this->db->from('gestion_pago');
			$this->db->where("codigo_interno", $this->uri->segment(3));
			
			$resultadosGpago = $this->db->get()->result();
			
			$this->data["resultadosGpago"] = $resultadosGpago;

			$this->db->select("id, numero, tipo, codigo");
			$this->db->from('gestion_pago_ncredito');
			$this->db->where("codigo_interno", $this->uri->segment(3));
			
			$query = $this->db->get();
			$row_resultadoNCredito = $query->row_array();
			 
			
			$this->data["resultadosNcredito"] = $row_resultadoNCredito;
 
			$this->data["code"] = 1;
			$this->load->view('cajaNew/gestion_pago_grabar_editar', $this->data);
	
		} else {
				
			die("No permitido");
			redirect(base_url("inicio"));
		}
	}



	public function gestion_pago_grabar_nc()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		$code = $this->input->post("codeInterno");

		$response['message'] = "NO SE GUARDO.";
		$response['status'] = false;


			$this->db->select("count(ci.idCita) as cantidad");
			$this->db->from("gestion_pago_detalle gpd");
			$this->db->join('cita ci', 'ci.codigo_asignacion = gpd.codigo_asignacion');
			$this->db->where("gpd.codigo_interno", $code);
			$this->db->where("gpd.tipo", "PRO");
			$this->db->where("ci.status", 0);
		
			$query = $this->db->get();
			$row_resultadoDisponible = $query->row_array();

			$this->db->select("count(soli.id) as cantidad");
			$this->db->from("solicitarexamen soli");
			$this->db->where("soli.codigo_interno in(select codigo_asignacion from gestion_pago_detalle where codigo_interno = '$code' and tipo= 'LAB') ");
			$this->db->where("soli.estado in(1,2)");
		
			$query = $this->db->get();
			$row_resultadoDisponibleLab = $query->row_array();
 
	
			if($row_resultadoDisponible['cantidad'] == 0 and $row_resultadoDisponibleLab ['cantidad'] == 0)
			{
				if($this->input->post("codigo") == "1234")
				{

				if($this->input->post("idNcredito"))
				{
					$parametrosUpdate = array(
						'numero' => $this->input->post("nroNcredito"),
						'tipo' => $this->input->post("tipoEA"),
						'codigo' => $this->input->post("codigo")
					);
					
					$this->db->trans_start();
					$this->db->where('codigo_interno', $code);
					$this->db->update("gestion_pago_ncredito", $parametrosUpdate);
					$this->db->trans_complete();
			
					if ($this->db->trans_status())
					{
						$parametrosUpdate["accion"] = "ENC";
						$parametrosUpdate["codigo_interno"] = $code;
						$this->db->insert("gestion_pago_ncredito_log", $parametrosUpdate);


						//if($this->input->post("tipoEA") == "ANU") {
							$this->db->where('activo_nc', 1);
							$this->db->where("codigo_asignacion in(select codigo_asignacion from gestion_pago_detalle where codigo_interno = '$code')");
							$this->db->update("solicitud_citas_pagos", array("pago" => 0));

							$this->db->where('activo_nc', 1);
							$this->db->where("codigo_interno in(select codigo_asignacion from gestion_pago_detalle where codigo_interno = '$code')");
							$this->db->update("solicitarexamen", array("status_pago" => 0));

						//}

						$response['message'] = "SE GUARDO CORRECTAMENTE.";
						$response['status'] = true;
					}
				}
				} else {
					$response['message'] = "CÓDIGO ES INCORRECTO.";
					$response['status'] = false;
				}
		
				if($this->input->post("nroNcredito") and !$this->input->post("idNcredito"))
				{
					$parametrosInsert = array(
						'codigo_interno' => $code,
						'numero' => $this->input->post("nroNcredito"),
						'idUsuario' => $this->session->userdata('idUsuario')
					);
					
					$this->db->trans_start();
					$this->db->insert("gestion_pago_ncredito", $parametrosInsert);
					$this->db->trans_complete();
			
					if ($this->db->trans_status())
					{
						$response['message'] = "SE GUARDO CORRECTAMENTE.";
						$response['status'] = true;

						$parametrosInsert["accion"] = "NNC";
						$this->db->insert("gestion_pago_ncredito_log", $parametrosInsert);
					}
				}
			} else {
				$response['message'] = "NO SE PUEDE GUARDAR, EXISTE REGISTROS ATENDIDOS.";
				$response['status'] = false;
			}



 
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}


	public function gestion_pago_actualizarProducto()
	{
		 
		$valor = ($this->input->post("valor") == "true") ? 1 : 0;

		$this->db->where('codigo_interno', $this->input->post("codeInterno"));
		$this->db->where('codigo_asignacion', $this->input->post("codeAsignacion"));
		$this->db->update('gestion_pago_detalle', array("activo" => $valor));


		$this->db->where('codigo_asignacion', $this->input->post("codeAsignacion"));
		$this->db->update('solicitud_citas_pagos', array("activo_nc" => $valor));

		$this->db->where('codigo_lab', $this->input->post("codeAsignacion"));
		$this->db->update('solicitud_citas_pagos', array("activo_nc" => $valor));

		$this->db->where('codigo_interno', $this->input->post("codeAsignacion"));
		$this->db->update('solicitarexamen', array("activo_nc" => $valor));
	 
		$response['message'] = "SE GUARDO CORRECTAMENTE.";
		$response['status'] = true;


		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	}

	public function detalle_solicitud()
	{
		$this->validarSesion();
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("administracion_caja"))
		{
			$code = $this->uri->segment(3);
			$result = array();
			$tipo = "";
			
			//$this->data['usuarioCita'] = $this->Usuario->datosUsuario($usuario);

			if($tipo == "Laboratorios")
			{
				 
	 
			} else {

				$this->db->select("ci.idCita, date_format(ci.fechaCita, '%d/%m/%Y') as fechaCita, ci.horaCita, concat(pa.firstname, ' ', pa.lastname) as paciente, esp.name, ci.codigo_procedimiento, if(ci.status = 0, 'Atendido', if(ci.status = 1, 'Sin-Atender', 'Cancelado')) as estadoCita, pro.descripcion");
				$this->db->from('cita ci');
				$this->db->join('patients pa', 'pa.idUsuario = ci.idUsuario');
				$this->db->join('historial_asignacion_cita hac', 'hac.codigo_asignacion = ci.codigo_asignacion');
				$this->db->join('procedimientos pro', 'pro.codigo_interno = hac.codigo_procedimiento');
				$this->db->join('specialties esp', 'esp.idSpecialty = pro.idEspecialidad');
				$this->db->where("ci.codigo_asignacion", $code);
				$this->db->where("ci.status", 1);
				$this->db->group_by("ci.idCita");
				
				$result = $this->db->get()->result();
			}
				$this->data['resultados']  = $result;
		 
			$this->load->view('cajaNew/detalle_solicitud', $this->data);
		} else {
				
			redirect(base_url("inicio"));
		}
	}

}
