<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ocupacional extends CI_Controller {

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
 
	public function index()
	{
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("descargar_historia_clinica"))
		{
			$this->load->view("mocupacional/index", $this->data);

		} else {
			redirect(base_url("inicio"));
		}

	}

	public function search()
	{
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("descargar_historia_clinica"))
		{
			$this->db->select("id, razonSocial");
			$this->db->from('historial_empresa_ocupacional');
			$this->db->where('status', 1);
			$this->db->order_by("razonSocial", "asc");

			$this->data["empresas"] = $this->db->get()->result();
			$this->data["registroBusquedas"] = array();

			$nombres = $this->input->post("nombres");
			$apellidos = $this->input->post("apellidos");
			$email = $this->input->post("email");
			$documento = $this->input->post("documento");
			$telefono = $this->input->post("telefono");
			$cmbEmpresa = $this->input->post("cmbEmpresa");

			if($nombres || $apellidos || $email || $documento || $telefono || $cmbEmpresa) {
				$this->db->select("hco.id, hcao.nombre, hcao.apellido, hcao.documento, hcao.email, hcao.telefono, heo.razonSocial, hcoh.id as idHistorial, hcao.id as idAfiliado");
				$this->db->from('historial_clinico_ocupacional hco');
				$this->db->join('historial_clinico_afiliado_ocupacional hcao', 'hcao.id = hco.idAfiliado');
				$this->db->join('historial_empresa_ocupacional heo', 'heo.id = hco.idEmpresa');
				$this->db->join('historial_conclusion_ocupacional hcoh', 'hcoh.idAfiliado = hco.idAfiliado');
				if($nombres)	$this->db->where("hcao.nombre like '%$nombres%'");
				if($apellidos)	$this->db->where("hcao.apellido like '%$apellidos%'");
				if($email)	$this->db->where("hcao.email like '%$email%'");
				if($documento)	$this->db->where("hcao.documento like '%$documento%'");
				if($telefono)	$this->db->where("hcao.telefono like '%$telefono%'");
				if($cmbEmpresa)	$this->db->where("heo.id", $cmbEmpresa);
				$this->db->order_by("hcoh.id", "DESC");

				$this->data["registroBusquedas"] = $this->db->get()->result();
			}
			
			$this->load->view("mocupacional/buscar", $this->data);

		} else {
			redirect(base_url("inicio"));
		}

	}
 
	public function registrar_dato()
	{
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("descargar_historia_clinica"))
		{
			$this->db->select("id, razonSocial");
			$this->db->from('historial_empresa_ocupacional');
			$this->db->where('status', 1);
			$this->db->order_by("razonSocial", "asc");

			$this->data["empresas"] = $this->db->get()->result();

			$this->db->select("id, descipcion");
			$this->db->from('catalogo_puesto_ocupacional');
			$this->db->where('status', 1);
			$this->db->order_by("descipcion", "asc");

			$this->data["puestos"] = $this->db->get()->result();

			$this->db->select("id, descipcion");
			$this->db->from('catalogo_antecedentepatologico_personal');
			$this->db->where('status', 1);
			$this->db->order_by("descipcion", "asc");

			$this->data["aPatologicosPersonales"] = $this->db->get()->result();

			$this->db->select("id, descipcion");
			$this->db->from('catalogo_osistema_emedica');
			$this->db->where('status', 1);
			$this->db->order_by("descipcion", "asc");

			$this->data["osistemaEmedicas"] = $this->db->get()->result();

			$this->db->select("id, descipcion");
			$this->db->from('catalogo_conclusionaudiometria');
			$this->db->where('status', 1);
			$this->db->order_by("descipcion", "asc");
			$this->data["conclusionaudiometrias"] = $this->db->get()->result();

			$this->load->view("mocupacional/registrarDato", $this->data);

		} else {
			redirect(base_url("inicio"));
		}

	}
 
	public function edit_dato()
	{
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("descargar_historia_clinica"))
		{
			$this->db->select("id, razonSocial");
			$this->db->from('historial_empresa_ocupacional');
			$this->db->where('status', 1);
			$this->db->order_by("razonSocial", "asc");

			$this->data["empresas"] = $this->db->get()->result();

			$this->db->select("id, descipcion");
			$this->db->from('catalogo_puesto_ocupacional');
			$this->db->where('status', 1);
			$this->db->order_by("descipcion", "asc");

			$this->data["puestos"] = $this->db->get()->result();

			$this->db->select("id, descipcion");
			$this->db->from('catalogo_antecedentepatologico_personal');
			$this->db->where('status', 1);
			$this->db->order_by("descipcion", "asc");

			$this->data["aPatologicosPersonales"] = $this->db->get()->result();

			$this->db->select("id, descipcion");
			$this->db->from('catalogo_osistema_emedica');
			$this->db->where('status', 1);
			$this->db->order_by("descipcion", "asc");

			$this->data["osistemaEmedicas"] = $this->db->get()->result();

			$this->db->select("id, descipcion");
			$this->db->from('catalogo_conclusionaudiometria');
			$this->db->where('status', 1);
			$this->db->order_by("descipcion", "asc");
			$this->data["conclusionaudiometrias"] = $this->db->get()->result();
			
			$this->load->model('HistoriaClinica');
			
			$this->data["aAntecedenteOcupacionales"] = $this->HistoriaClinica->afiliadoAntecedenteOcupacional(1, 1);
			$this->data["hapatologicop_ocupacionales"] = $this->HistoriaClinica->catalogo_antecedentepatologico_personal_edit(1, 1);
			$this->data["apatologicop_hnocivos"] = $this->HistoriaClinica->apatologicop_hnocivo(1, 1);
			$this->data["apatologicof_ocupacional"] = $this->HistoriaClinica->apatologicof_ocupacional(1, 1);
			$this->data["apatologicof_eaccidentes"] = $this->HistoriaClinica->apatologicof_eaccidente(1, 1);
			$this->data["emedica_ocupacional"] = $this->HistoriaClinica->emedica_ocupacional(1, 1);
			$this->data["conclusion_ocupacionales"] = $this->HistoriaClinica->conclusion_ocupacional(1, 1);
			$this->data["emedica_efisico_ocupacionales"] = $this->HistoriaClinica->emedica_efisico_ocupacional_edit(1, 1);
			$this->data["conclusionaudiometria_ocupacionales"] = $this->HistoriaClinica->conclusionaudiometria_ocupacional_edit(1, 1);
			$this->data["conclusionespirometria_ocupacionales"] = $this->HistoriaClinica->conclusionespirometria_ocupacional(1, 1);
			$this->data["dmedico_ocupacionales"] = $this->HistoriaClinica->dmedico_ocupacional(1, 1); 

			$this->load->view("mocupacional/registrarDato_edit", $this->data);

		} else {
			redirect(base_url("inicio"));
		}

	}

	public function buscarAfiliado()
	{
		$json = [];
		if(!empty($this->input->post("q")) || !empty($this->input->post("busqueda")) ){

			$this->db->select("id, concat(nombre, ' - ',  apellido) as text");
	
			$this->db->from('historial_clinico_afiliado_ocupacional ');

			if($this->input->post("busqueda")){
				$this->db->where_in('id',$this->input->post("busqueda"));
			} else {
			
			$this->db->where("nombre like '%". $this->input->post("q")."%'");
			 $this->db->or_where("apellido like '%". $this->input->post("q")."%'");
			$this->db->or_where("documento like '%". $this->input->post("q")."%'");
		}
			$this->db->order_by("apellido", "ASC");
			$this->db->limit(10);
	 
			$json = $this->db->get()->result();
		}
		
		echo json_encode($json);
	}
 
	public function medical_affiliate_registration()
	{
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("descargar_historia_clinica"))
		{
				
			$this->db->select("id, concat(razonSocial, ' / ' , actividadEconomica) as razonSocial");
			$this->db->from('historial_empresa_ocupacional');
			$this->db->where('status', 1);
			$this->db->order_by("razonSocial", "asc");

			$this->data["empresas"] = $this->db->get()->result();
			
			$this->db->select("id, descipcion");
			$this->db->from('catalogo_tipoevaluacion_ocupacional');
			$this->db->where('status', 1);
			$this->db->order_by("descipcion", "asc");

			$this->data["tipoEvaluaciones"] = $this->db->get()->result();

			$this->db->select("id, descipcion");
			$this->db->from('catalogo_puesto_ocupacional');
			$this->db->where('status', 1);
			$this->db->order_by("descipcion", "asc");

			$this->data["puestos"] = $this->db->get()->result();

			$this->load->model('Departamento');	
			$this->data['departamentos'] = $this->Departamento->listAll();

			$this->load->view("mocupacional/registrarAfiliado", $this->data);
		} else {
			redirect(base_url("inicio"));
		}

	}
 
	public function medical_company_new()
	{
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("descargar_historia_clinica"))
		{
			$this->load->model('Departamento');	
			$this->data['departamentos'] = $this->Departamento->listAll();

			$this->db->select("id, razonSocial, actividadEconomica, lugarTrabajo, idUbicacion");
			$this->db->from('historial_empresa_ocupacional');
			$this->db->where("status", 1);
			 
			$this->db->order_by("razonSocial", "DESC");
			
			$this->data["empresas"] = $this->db->get()->result();

			$this->data['departamentos'] = $this->Departamento->listAll();


			$this->load->view("mocupacional/registrarEmpresa", $this->data);
		} else {
			redirect(base_url("inicio"));
		}

	}

	public function medical_affiliate_edit($idOcupacional)
	{
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("descargar_historia_clinica"))
		{
			$this->db->select("id, concat(razonSocial, ' / ' , actividadEconomica) as razonSocial");
			$this->db->from('historial_empresa_ocupacional');
			$this->db->where('status', 1);
			$this->db->order_by("razonSocial", "asc");

			$this->data["empresas"] = $this->db->get()->result();
			
			$this->db->select("id, descipcion");
			$this->db->from('catalogo_tipoevaluacion_ocupacional');
			$this->db->where('status', 1);
			$this->db->order_by("descipcion", "asc");

			$this->data["tipoEvaluaciones"] = $this->db->get()->result();

			$this->db->select("id, descipcion");
			$this->db->from('catalogo_puesto_ocupacional');
			$this->db->where('status', 1);
			$this->db->order_by("descipcion", "asc");

			$this->data["puestos"] = $this->db->get()->result();

			$this->load->model('Departamento');	
			$this->data['departamentos'] = $this->Departamento->listAll();

			$this->load->model('HistoriaClinica');
			$this->load->model('Provincia');
			$this->load->model('Distrito');

			$this->data["historialEmpresa"] = $this->HistoriaClinica->historiale_empresa($idOcupacional);
			$this->data["afiliado"] = $this->HistoriaClinica->afiliadoDato($this->data["historialEmpresa"]["idAfiliado"]);
 
			$this->data['miUbigeoEmpresa'] = $this->Usuario->ubigeo($this->data["historialEmpresa"]["ubigeo"]);

			$this->data['provinciasEmp'] = $this->Provincia->listaTodos($this->data['miUbigeoEmpresa']["department_id"]);
			$this->data['distritosEmp'] = $this->Distrito->listaTodos($this->data['miUbigeoEmpresa']["department_id"], $this->data['miUbigeoEmpresa']["province_id"]);

			$this->data['miUbigeo'] = $this->Usuario->ubigeo($this->data["afiliado"]["ubigeoId"]);
			$this->data['provincias'] = $this->Provincia->listaTodos($this->data['miUbigeo']["department_id"]);
			$this->data['distritos'] = $this->Distrito->listaTodos($this->data['miUbigeo']["department_id"], $this->data['miUbigeo']["province_id"]);

			$this->load->view("mocupacional/editarAfiliado", $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}

	public function medical_company_edit($id)
	{
		$this->db->select("id, razonSocial, actividadEconomica, lugarTrabajo, idUbicacion");
		$this->db->from('historial_empresa_ocupacional');
		$this->db->where("id", $id);

		$query = $this->db->get();
 
		if ($query->num_rows() == 1)
		{
			foreach ($query->result() as $row)
			{
				$response['idEmpresa'] =  $row->id;
				$response['razonSocial'] =  $row->razonSocial;
				$response['actividadEconomica'] =  $row->actividadEconomica;
				$response['lugarTrabajo'] =  $row->lugarTrabajo;
				$response['idDistrict'] =  $row->idUbicacion;
		 
			}
		}

		$response["ubicacion"] = $this->Usuario->ubigeo($response['idDistrict']);

		 
		$this->load->model('Provincia');
		$this->load->model('Distrito');
		 ;
		$response["provincias"] = $this->Provincia->listByDep($response["ubicacion"]["department_id"]);
		$response["distritos"] = $this->Distrito->listByProv($response["ubicacion"]["province_id"]);

		$this->output->set_content_type( 'application/json' )->set_output(json_encode($response));
	}
 
	public function medical_company__save()
	{
		$this->cargarDatosSesion();

		$afiliado = array(
			'razonSocial' => $this->input->post("razonSocial"),
			'actividadEconomica' => $this->input->post("actividadEconomica"),
			'lugarTrabajo' => $this->input->post('lugarTrabajo'),
			'idUbicacion' => $this->input->post('cmbDistrito'),
			'idUsuario' => $this->session->userdata('idUsuario')
		);

		$this->db->trans_start();
		$this->db->insert("historial_empresa_ocupacional", $afiliado);
		$this->db->trans_complete();

		if ($this->db->trans_status())
		{
			$response['status'] = true;
			$response['message'] = 'Se registro la empresa correctamente.';
		} else {
			$response['status'] = FALSE;
			$response['message'] = 'No se registro la empresa.';
		}

		
	 
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	
	}
 
	public function medical_company__editSave()
	{
		$this->cargarDatosSesion();
 
		if($this->Helper->permiso_usuario("descargar_historia_clinica"))
		{
			$parametros = array(
				'razonSocial' => $this->input->post("razonSocialEdit"),
				'actividadEconomica' => $this->input->post("aEconomicaEdit"),
				'lugarTrabajo' => $this->input->post('lTrabajoEdit'),
				'idUbicacion' => $this->input->post('cmbDistritoEdit')
			);

			$this->db->trans_start();
			$this->db->where('id', $this->input->post("idEmpresa"));
			$this->db->update("historial_empresa_ocupacional", $parametros);
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
				$response['status'] = true;
				$response['message'] = 'Se actualizo la empresa correctamente.';
			} else {
				$response['status'] = FALSE;
				$response['message'] = 'No se actualizo la empresa.';
			}

		
	
		} else {
			redirect(base_url("inicio"));
		}
	
		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
	
	}

	public function medical_history_record_save()
	{
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("descargar_historia_clinica"))
		{
			
			move_uploaded_file($_FILES["archivo"]["tmp_name"], 'img/ocupacional/'.$_FILES['archivo']["name"]);

			$afiliado = array(
				'nombre' => $this->input->post("nombres"),
				'apellido' => $this->input->post("apellidos"),
				'fechaNacimiento' => $this->input->post("fechaNacimiento"),
				'documento' => $this->input->post('documento'),
				'domicilioFiscal' => $this->input->post('dFiscal'),
				'ubigeo' => $this->input->post('cmbDistritoAfi'),
				'email' => $this->input->post('email'),
				'telefono' => $this->input->post('telefono'),
				'estadoCivil' => $this->input->post('cmbEcivil'),
				'gradoInstruccion' => $this->input->post('cmbGinstruccion'),
				'nThijovivo' => $this->input->post('nTHVivos'),
				'nDependiente' => $this->input->post('nDependiente'),
				'genero' => $this->input->post('genero'),
				'foto' => $_FILES['archivo']['name'],
				'idUsuario' => $this->session->userdata('idUsuario')
			);

			$this->db->trans_start();
			$this->db->insert("historial_clinico_afiliado_ocupacional", $afiliado);
			$idAfiliado = $this->db->insert_id();
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
				$hclinicaAfiliado = array(
					'idEmpresa' => $this->input->post("cmbEmpresa"),
					'idAfiliado' => $idAfiliado,
					'fecha' => date("Y-m-d"),
					'idTipoEvaluacion' => $this->input->post("cmbTipoE"),
					'ubigeo' => $this->input->post('cmbDistrito'),
					'idPuesto' => $this->input->post('cmbPuesto'),
					'idUsuario' => $this->session->userdata('idUsuario')
				);
	
				$this->db->insert("historial_clinico_ocupacional", $hclinicaAfiliado);

				$response['status'] = true;
				$response['message'] = "Se guardo al afiliado correctamente.";

			} else {
				$response['status'] = FALSE;
				$response['message'] = 'No se puedo registrar al afiliado.';
			}

		} else {
			redirect(base_url("inicio"));
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
		
	}

	public function updateAfiliado()
	{
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("descargar_historia_clinica"))
		{
	 
			if(!empty($_FILES["archivo"]["tmp_name"]))	move_uploaded_file($_FILES["archivo"]["tmp_name"], 'img/ocupacional/'.$_FILES['archivo']["name"]);

			$afiliado = array(
				'nombre' => $this->input->post("nombres"),
				'apellido' => $this->input->post("apellidos"),
				'fechaNacimiento' => $this->input->post("fechaNacimiento"),
				'documento' => $this->input->post('documento'),
				'domicilioFiscal' => $this->input->post('dFiscal'),
				'ubigeo' => $this->input->post('cmbDistritoAfi'),
				'email' => $this->input->post('email'),
				'telefono' => $this->input->post('telefono'),
				'estadoCivil' => $this->input->post('cmbEcivil'),
				'gradoInstruccion' => $this->input->post('cmbGinstruccion'),
				'nThijovivo' => $this->input->post('nTHVivos'),
				'nDependiente' => $this->input->post('nDependiente'),
				'genero' => $this->input->post('genero')
			);
			
			if(!empty($_FILES["archivo"]["name"]))	$afiliado["foto"] = $_FILES['archivo']['name'];
 
			$this->db->trans_start();
			$this->db->where('id', $this->input->post("idAfiliado"));
			$this->db->update("historial_clinico_afiliado_ocupacional", $afiliado);
			$idAfiliado = $this->db->insert_id();
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
				$hclinicaAfiliado = array(
					'idEmpresa' => $this->input->post("cmbEmpresa"),
					'fecha' => date("Y-m-d"),
					'idTipoEvaluacion' => $this->input->post("cmbTipoE"),
					'ubigeo' => $this->input->post('cmbDistrito'),
					'idPuesto' => $this->input->post('cmbPuesto')
				);
				
				$this->db->where('id', $this->input->post("idAfiliado_ocupacional"));
				$this->db->update("historial_clinico_ocupacional", $hclinicaAfiliado);

				$response['status'] = true;
				$response['message'] = "Se actualizo al afiliado correctamente.";

			} else {
				$response['status'] = FALSE;
				$response['message'] = 'No se puedo actualizo al afiliado.';
			}

		} else {
			redirect(base_url("inicio"));
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
		
	}

	public function medical_history_affiliate__save()
	{
		$this->cargarDatosSesion();

		$idAfiliado = $this->input->post("afiliado");

		if($this->Helper->permiso_usuario("descargar_historia_clinica"))
		{

			$parametrosConclusionOcupa = array(
				'idAfiliado' => $idAfiliado,
				'evaluacionPsicologica' => $this->input->post("conclusinEPsicologica"),
				'radiografia' => $this->input->post("conclusionRadiografia"),
				'otros' => $this->input->post("otros"),
				'recomedacion' => $this->input->post("recomendacion"),
				'idUsuario' => $this->session->userdata('idUsuario')
			);
			$this->db->trans_start();
			$this->db->insert("historial_conclusion_ocupacional", $parametrosConclusionOcupa);
			$idHistorial = $this->db->insert_id();
			$this->db->trans_complete();

	 
			
			if ($idAfiliado and $this->db->trans_status())
			{
				if(count(array_filter($this->input->post("cmbEmpresa"))) > 0) {
					for ($i=0; $i < count(array_filter($this->input->post("cmbEmpresa"))); $i++) { 
				 
							$parametrosEmpresa = array(
								'idHistorial' => $idHistorial,
								'idAfiliado' => $idAfiliado,
								'idEmpresa' => $this->input->post("cmbEmpresa")[$i],
								'areaTrabajo' => $this->input->post("aTrabajo")[$i],
								'idOcupacion' => $this->input->post("puesto")[$i],
								'fecha' => $this->input->post("fecha")[$i],
								'tiempo' => $this->input->post("tiempo")[$i],
								'eOcupacional' => $this->input->post("exposicionOcu")[$i],
								'epp' => $this->input->post("epp")[$i],
								'idUsuario' => $this->session->userdata('idUsuario')
							);

							$this->db->insert("historial_antecedente_ocupacional", $parametrosEmpresa);
						}
				}



				if(count(array_filter($this->input->post("aPatologicosPersonal"))) > 0) {
					for ($i=0; $i < count(array_filter($this->input->post("aPatologicosPersonal"))); $i++) { 

						$parametrosApPersonales = array(
							'idAfiliado' => $idAfiliado,
							'idHistorial' => $idHistorial,
							'idAntecedentePatologico' => $this->input->post("aPatologicosPersonal")[$i],
							'observacionAntecedentePatologico' => $this->input->post("observacionApPerso")[$i],
							'niega' => $this->input->post('niegaPatologicosPersonal'),
							'idUsuario' => $this->session->userdata('idUsuario')
						);

						$this->db->insert("historial_apatologicop_ocupacional", $parametrosApPersonales);
					}

				}


				if(count(array_filter($this->input->post("habitoNocivo"))) > 0) {
					for ($i=0; $i < count(array_filter($this->input->post("habitoNocivo"))); $i++) { 

						$parametrosHNocivos = array(
							'idAfiliado' => $idAfiliado,
							'idHistorial' => $idHistorial,
							'habitoNocivo' => $this->input->post("habitoNocivo")[$i],
							'tipo' => $this->input->post("tipo")[$i],
							'cantidad' => $this->input->post("cantidad")[$i],
							'frecuencia' => $this->input->post("frecuencia")[$i],
							'medicamento' => $this->input->post("medicamento"),
							'idUsuario' => $this->session->userdata('idUsuario')
						);

						$this->db->insert("historial_apatologicop_hnocivo_ocupacional", $parametrosHNocivos);
					}

				}


				
				$parametrosApatologicofOcupa = array(
					'idHistorial' => $idHistorial,
					'idAfiliado' => $idAfiliado,
					'padre' => $this->input->post("padre"),
					'madre' => $this->input->post("madre"),
					'hermanos' => $this->input->post("hermanos"),
					'hijoVivo' => $this->input->post("hijoVivo"),
					'nHijoVivo' => $this->input->post("numeroHv"),
					'hijoFallecido' => $this->input->post("hijoFallecido"),
					'nHijoFallecido' => $this->input->post("numeroHf"),
					'idUsuario' => $this->session->userdata('idUsuario')
				);

				$this->db->insert("historial_apatologicof_ocupacional", $parametrosApatologicofOcupa);

				 


				if(count(array_filter($this->input->post("accidente"))) > 0) {
					for ($i=0; $i < count(array_filter($this->input->post("accidente"))); $i++) { 

						$parametrosAeaccidente = array(
							'idHistorial' => $idHistorial,
							'idAfiliado' => $idAfiliado,
							'enfermedadAccidente' => $this->input->post("accidente")[$i],
							'comentario' => $this->input->post("observacionAccidente")[$i],
							'asociacionTrabajo' => $this->input->post("cbkOpciones")[$i],
							'anio' => $this->input->post("anio")[$i],
							'diaDescanso' => $this->input->post("diasDescanso")[$i],
							'idUsuario' => $this->session->userdata('idUsuario')
						);

						$this->db->insert("historial_apatologicof_eaccidente_ocupacional", $parametrosAeaccidente);

					}

				}
								
				$parametrosEmedicaOcupa = array(
					'idHistorial' => $idHistorial,
					'idAfiliado' => $idAfiliado,
					'anamnesis' => $this->input->post("anamnesis"),
					'exa_cli_talla' => $this->input->post("talla_ec"),
					'exa_cli_peso' => $this->input->post("peso_ec"),
					'exa_cli_imc' => $this->input->post("imc_ec"),
					'exa_cli_perimetro' => $this->input->post("pabdominal_ec"),
					'exa_cli_resp' => $this->input->post("fresp_ec"),
					'exa_cli_card' => $this->input->post("fcard_ec"),
					'exa_cli_pa' => $this->input->post("pa_ec"),
					'exa_cli_temperatura' => $this->input->post("temperatura_ec"),
					'exa_cli_otros' => $this->input->post("otros_ec"),
					'ectoscopia' => $this->input->post("ectoscopia"),
					'estadoMental' => $this->input->post("eMental"),
					'idUsuario' => $this->session->userdata('idUsuario')
				);

				$this->db->insert("historial_emedica_ocupacional", $parametrosEmedicaOcupa);

				if(count(array_filter($this->input->post("osistemaEmedicas"))) > 0) {
					for ($i=0; $i < count(array_filter($this->input->post("osistemaEmedicas"))); $i++) { 

						$parametrosEfisico_ocupacional = array(
							'idHistorial' => $idHistorial,
							'idAfiliado' => $idAfiliado,
							'idOrganoSistema' => $this->input->post("osistemaEmedicas")[$i],

							'av_od' => $this->input->post("av_od")[$i],
							'av_oi' => $this->input->post("av_oi")[$i],
							'cc_od' => $this->input->post("cc_od")[$i],
							'cc_oi' => $this->input->post("cc_oi")[$i],
							'fo_od' => $this->input->post("fo_od")[$i],
							'fo_oi' => $this->input->post("fo_oi")[$i],
							'vc_od' => $this->input->post("vc_od")[$i],
							'vc_oi' => $this->input->post("vc_oi")[$i],
							'testMosca' => $this->input->post("testMosca")[$i],
							'circulos' => $this->input->post("circulos")[$i],
							'animales' => $this->input->post("animales")[$i],
							
							'sinHallazgo' => $this->input->post("cbkOpcOS")[$i],
							'hallazgo' => $this->input->post("hallazgo")[$i],
							'idUsuario' => $this->session->userdata('idUsuario')
						);

						$this->db->insert("historial_emedica_efisico_ocupacional", $parametrosEfisico_ocupacional);
					}

				}





				 
				if(count(array_filter($this->input->post("conceptoAudio"))) > 0) {
					for ($i=0; $i < count(array_filter($this->input->post("conceptoAudio"))); $i++) { 

						$parametrosConclusionaudiometria = array(
							'idHistorial' => $idHistorial,
							'idAfiliado' => $idAfiliado,
							'idAudiometria' => $this->input->post("conceptoAudio")[$i],
							'observaciones' => $this->input->post("contenidoAudio")[$i],
							'idUsuario' => $this->session->userdata('idUsuario')
						);

						$this->db->insert("historial_conclusionaudiometria_ocupacional", $parametrosConclusionaudiometria);
					}

				}

				$parametrosConclusionEspirometria = array(
					'idHistorial' => $idHistorial,
					'idAfiliado' => $idAfiliado,
					'noAplica' => $this->input->post("noAplicaCEsperi"),
					'resultado' => $this->input->post("resultadoEsperi"),
					'fev' => $this->input->post("fev"),
					'fvc' => $this->input->post("fvc"),
					'idUsuario' => $this->session->userdata('idUsuario')
				);

				$this->db->insert("historial_conclusionespirometria_ocupacional", $parametrosConclusionEspirometria);

				if($this->input->post("dMedicoOcupCie")) {
					if(count($this->input->post("dMedicoOcupCie"))) {
						for ($i=0; $i < count(array_filter($this->input->post("dMedicoOcupCie"))); $i++) { 

							$parametrosConclusionaudiometria = array(
								'idHistorial' => $idHistorial,
								'idAfiliado' => $idAfiliado,
								'idCie' => $this->input->post("dMedicoOcupCie")[$i],
								'descipcionCie' => $this->input->post("descipcionCie")[$i],
								'conceptoCie' => $this->input->post("conceptoCie")[$i],
								'apto' => $this->input->post("aptoCieMocupa"),
								'aptoc_restriccion' => $this->input->post("aptocRestriccionCieMocupa"),
								'no_apto' => $this->input->post("noAptoCieMocupa"),
								'idUsuario' => $this->session->userdata('idUsuario')
							);

							$this->db->insert("historial_dmedico_ocupacional", $parametrosConclusionaudiometria);
						}
					}
				}

				

				if(count(array_filter($this->input->post("examenesAuxi"))) > 0) {
					for ($i=0; $i < count(array_filter($this->input->post("examenesAuxi"))); $i++) { 

						$parametrosExamenAuxi = array(
							'idAfiliado' => $idAfiliado,
							'idHistorial' => $idHistorial,
							'codigo_examen' => $this->input->post("examenesAuxi")[$i],
							'comentario' => $this->input->post("contenidoAxi")[$i],
							'idUsuario' => $this->session->userdata('idUsuario')
						);

						$this->db->insert("historial_emedica_examen_auxiliar", $parametrosExamenAuxi);
					}

				}
				

				if(count(array_filter($this->input->post("examenesLab"))) > 0) {
					for ($i=0; $i < count(array_filter($this->input->post("examenesLab"))); $i++) { 

						$parametrosExamenLab = array(
							'idAfiliado' => $idAfiliado,
							'idHistorial' => $idHistorial,
							'idExamen' => $this->input->post("examenesLab")[$i],
							'comentario' => $this->input->post("comentarioLab")[$i],
							'idUsuario' => $this->session->userdata('idUsuario')
						);

						$this->db->insert("historial_emedica_examen_lab", $parametrosExamenLab);
					}

				}


				$response['status'] = true;
				$response['message'] = 'Se ingreso la información del afiliado correctamente.';

			}  else {

				$response['status'] = FALSE;
				$response['message'] = 'No se puedo ingresar al afiliado.';
			}


		} else {
			redirect(base_url("inicio"));
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
		
	}

	public function medical_affiliate_AuxiLab()
	{
		$this->cargarDatosSesion();

		if($this->Helper->permiso_usuario("descargar_historia_clinica"))
		{
			$this->load->view("mocupacional/registrarAfiliado_examenLab", $this->data);
		} else {
			redirect(base_url("inicio"));
		}
	}

	public function update_datoHistoria()
	{
		$this->cargarDatosSesion();

		$idAfiliado = $this->input->post("idAfiliado");
		$idHistorial = $this->input->post("idHistorial");

		if($this->Helper->permiso_usuario("descargar_historia_clinica"))
		{

			if ($idAfiliado and $idHistorial)
			{
				if(count(array_filter($this->input->post("cmbEmpresa"))) > 0) {

					
					$this->db->trans_start();
					$this->db->delete('historial_antecedente_ocupacional', array('idHistorial' => $idHistorial, 'idAfiliado' => $idAfiliado)); 
					$this->db->trans_complete();

					if ($this->db->trans_status()) {
						for ($i=0; $i < count(array_filter($this->input->post("cmbEmpresa"))); $i++) { 
					
							$parametrosEmpresa = array(
								'idHistorial' => $idHistorial,
								'idAfiliado' => $idAfiliado,
								'idEmpresa' => $this->input->post("cmbEmpresa")[$i],
								'areaTrabajo' => $this->input->post("aTrabajo")[$i],
								'idOcupacion' => $this->input->post("puesto")[$i],
								'fecha' => $this->input->post("fecha")[$i],
								'tiempo' => $this->input->post("tiempo")[$i],
								'eOcupacional' => $this->input->post("eOcupacional")[$i],
								'epp' => $this->input->post("epp")[$i],
								'idUsuario' => $this->session->userdata('idUsuario')
							);

							$this->db->insert("historial_antecedente_ocupacional", $parametrosEmpresa);
						}
					}
				}



				if(count(array_filter($this->input->post("aPatologicosPersonal"))) > 0) {
					$this->db->trans_start();
					$this->db->delete('historial_apatologicop_ocupacional', array('idHistorial' => $idHistorial, 'idAfiliado' => $idAfiliado)); 
					$this->db->trans_complete();

					if ($this->db->trans_status()) {

						for ($i=0; $i < count(array_filter($this->input->post("aPatologicosPersonal"))); $i++) { 

							$parametrosApPersonales = array(
								'idAfiliado' => $idAfiliado,
								'idHistorial' => $idHistorial,
								'idAntecedentePatologico' => $this->input->post("aPatologicosPersonal")[$i],
								'observacionAntecedentePatologico' => $this->input->post("observacionApPerso")[$i],
								'niega' => $this->input->post('niegaPatologicosPersonal'),
								'idUsuario' => $this->session->userdata('idUsuario')
							);

							$this->db->insert("historial_apatologicop_ocupacional", $parametrosApPersonales);
						}
					}

				}


				if(count(array_filter($this->input->post("habitoNocivo"))) > 0) {
					$this->db->trans_start();
					$this->db->delete('historial_apatologicop_hnocivo_ocupacional', array('idHistorial' => $idHistorial, 'idAfiliado' => $idAfiliado)); 
					$this->db->trans_complete();

					if ($this->db->trans_status()) {

						for ($i=0; $i < count(array_filter($this->input->post("habitoNocivo"))); $i++) { 

							$parametrosHNocivos = array(
								'idAfiliado' => $idAfiliado,
								'idHistorial' => $idHistorial,
								'habitoNocivo' => $this->input->post("habitoNocivo")[$i],
								'tipo' => $this->input->post("tipo")[$i],
								'cantidad' => $this->input->post("cantidad")[$i],
								'frecuencia' => $this->input->post("frecuencia")[$i],
								'medicamento' => $this->input->post("medicamento"),
								'idUsuario' => $this->session->userdata('idUsuario')
							);

							$this->db->insert("historial_apatologicop_hnocivo_ocupacional", $parametrosHNocivos);
						}
					}

				}

				$this->db->trans_start();
				$this->db->delete('historial_apatologicof_ocupacional', array('idHistorial' => $idHistorial, 'idAfiliado' => $idAfiliado)); 
				$this->db->trans_complete();

				if ($this->db->trans_status()) {

					$parametrosApatologicofOcupa = array(
						'idHistorial' => $idHistorial,
						'idAfiliado' => $idAfiliado,
						'padre' => $this->input->post("padre"),
						'madre' => $this->input->post("madre"),
						'hermanos' => $this->input->post("hermanos"),
						'hijoVivo' => $this->input->post("hijoVivo"),
						'nHijoVivo' => $this->input->post("numeroHv"),
						'hijoFallecido' => $this->input->post("hijoFallecido"),
						'nHijoFallecido' => $this->input->post("numeroHf"),
						'idUsuario' => $this->session->userdata('idUsuario')
					);

					$this->db->insert("historial_apatologicof_ocupacional", $parametrosApatologicofOcupa);

				}

 
				if(count(array_filter($this->input->post("accidente"))) > 0) {
					$this->db->trans_start();
					$this->db->delete('historial_apatologicof_eaccidente_ocupacional', array('idHistorial' => $idHistorial, 'idAfiliado' => $idAfiliado)); 
					$this->db->trans_complete();
	
					if ($this->db->trans_status()) {
						for ($i=0; $i < count(array_filter($this->input->post("accidente"))); $i++) { 

							$parametrosAeaccidente = array(
								'idHistorial' => $idHistorial,
								'idAfiliado' => $idAfiliado,
								'enfermedadAccidente' => $this->input->post("accidente")[$i],
								'comentario' => $this->input->post("observacionAccidente")[$i],
								'asociacionTrabajo' => $this->input->post("cbkOpciones")[$i],
								'anio' => $this->input->post("anio")[$i],
								'diaDescanso' => $this->input->post("diasDescanso")[$i],
								'idUsuario' => $this->session->userdata('idUsuario')
							);

							$this->db->insert("historial_apatologicof_eaccidente_ocupacional", $parametrosAeaccidente);

						}
					}

				}
				
				$this->db->trans_start();
				$this->db->delete('historial_emedica_ocupacional', array('idHistorial' => $idHistorial, 'idAfiliado' => $idAfiliado)); 
				$this->db->trans_complete();

				if ($this->db->trans_status()) {
					$parametrosEmedicaOcupa = array(
						'idHistorial' => $idHistorial,
						'idAfiliado' => $idAfiliado,
						'anamnesis' => $this->input->post("anamnesis"),
						'exa_cli_talla' => $this->input->post("talla_ec"),
						'exa_cli_peso' => $this->input->post("peso_ec"),
						'exa_cli_imc' => $this->input->post("imc_ec"),
						'exa_cli_perimetro' => $this->input->post("pabdominal_ec"),
						'exa_cli_resp' => $this->input->post("fresp_ec"),
						'exa_cli_card' => $this->input->post("fcard_ec"),
						'exa_cli_pa' => $this->input->post("pa_ec"),
						'exa_cli_temperatura' => $this->input->post("temperatura_ec"),
						'exa_cli_otros' => $this->input->post("otros_ec"),
						'ectoscopia' => $this->input->post("ectoscopia"),
						'estadoMental' => $this->input->post("eMental"),
						'idUsuario' => $this->session->userdata('idUsuario')
					);

					$this->db->insert("historial_emedica_ocupacional", $parametrosEmedicaOcupa);
				}

 
				if(count(array_filter($this->input->post("osistemaEmedicas"))) > 0) {

 
					$this->db->trans_start();
					$this->db->delete('historial_emedica_efisico_ocupacional', array('idHistorial' => $idHistorial, 'idAfiliado' => $idAfiliado)); 
					$this->db->trans_complete();
	
					if ($this->db->trans_status()) {
						for ($i=0; $i < count(array_filter($this->input->post("osistemaEmedicas"))); $i++) { 

							$parametrosEfisico_ocupacional = array(
								'idHistorial' => $idHistorial,
								'idAfiliado' => $idAfiliado,
								'idOrganoSistema' => $this->input->post("osistemaEmedicas")[$i],

								'av_od' => $this->input->post("av_od")[0],
								'av_oi' => $this->input->post("av_oi")[0],
								'cc_od' => $this->input->post("cc_od")[0],
								'cc_oi' => $this->input->post("cc_oi")[0],
								'fo_od' => $this->input->post("fo_od")[0],
								'fo_oi' => $this->input->post("fo_oi")[0],
								'vc_od' => $this->input->post("vc_od")[0],
								'vc_oi' => $this->input->post("vc_oi")[0],
								'testMosca' => $this->input->post("testMosca")[0],
								'circulos' => $this->input->post("circulos")[0],
								'animales' => $this->input->post("animales")[0],
								
								'sinHallazgo' => $this->input->post("cbkOpcOS")[$i],
								'hallazgo' => $this->input->post("hallazgo")[$i],
								'idUsuario' => $this->session->userdata('idUsuario')
							);

							$this->db->insert("historial_emedica_efisico_ocupacional", $parametrosEfisico_ocupacional);
						}
					}

				}

				if($idHistorial and $idAfiliado ){
					$parametrosConclusionOcupa = array(
						'evaluacionPsicologica' => $this->input->post("conclusinEPsicologica"),
						'radiografia' => $this->input->post("conclusionRadiografia"),
						'otros' => $this->input->post("otros"),
						'recomedacion' => $this->input->post("recomendacion"),
						'idUsuario' => $this->session->userdata('idUsuario')
					);

					$this->db->where('id', $idHistorial);
					$this->db->where('idAfiliado', $idAfiliado);
					$this->db->update("historial_conclusion_ocupacional", $parametrosConclusionOcupa);
				}

				 
				if(count(array_filter($this->input->post("conceptoAudio"))) > 0) {
					$this->db->trans_start();
					$this->db->delete('historial_conclusionaudiometria_ocupacional', array('idHistorial' => $idHistorial, 'idAfiliado' => $idAfiliado)); 
					$this->db->trans_complete();
	
					if ($this->db->trans_status()) {

						for ($i=0; $i < count(array_filter($this->input->post("conceptoAudio"))); $i++) { 

							$parametrosConclusionaudiometria = array(
								'idHistorial' => $idHistorial,
								'idAfiliado' => $idAfiliado,
								'idAudiometria' => $this->input->post("conceptoAudio")[$i],
								'observaciones' => $this->input->post("contenidoAudio")[$i],
								'idUsuario' => $this->session->userdata('idUsuario')
							);

							$this->db->insert("historial_conclusionaudiometria_ocupacional", $parametrosConclusionaudiometria);
						}
					}

				}

				$this->db->trans_start();
				$this->db->delete('historial_conclusionespirometria_ocupacional', array('idHistorial' => $idHistorial, 'idAfiliado' => $idAfiliado)); 
				$this->db->trans_complete();

				if ($this->db->trans_status()) {

					$parametrosConclusionEspirometria = array(
						'idHistorial' => $idHistorial,
						'idAfiliado' => $idAfiliado,
						'noAplica' => $this->input->post("noAplicaCEsperi"),
						'resultado' => $this->input->post("resultadoEsperi"),
						'fev' => $this->input->post("fev"),
						'fvc' => $this->input->post("fvc"),
						'idUsuario' => $this->session->userdata('idUsuario')
					);

					$this->db->insert("historial_conclusionespirometria_ocupacional", $parametrosConclusionEspirometria);
				}

				if($this->input->post("dMedicoOcupCie")) {
					if(count($this->input->post("dMedicoOcupCie"))) {
 
						$this->db->trans_start();
						$this->db->delete('historial_dmedico_ocupacional', array('idHistorial' => $idHistorial, 'idAfiliado' => $idAfiliado)); 
						$this->db->trans_complete();
		
						if ($this->db->trans_status()) {
							for ($i=0; $i < count(array_filter($this->input->post("dMedicoOcupCie"))); $i++) { 
								$parametrosConclusionaudiometria = array(
									'idHistorial' => $idHistorial,
									'idAfiliado' => $idAfiliado,
									'idCie' => $this->input->post("dMedicoOcupCie")[$i],
									'descipcionCie' => $this->input->post("descipcionCie")[$i],
									'conceptoCie' => $this->input->post("conceptoCie")[$i],
									'apto' => $this->input->post("aptoCieMocupa"),
									'aptoc_restriccion' => $this->input->post("aptocRestriccionCieMocupa"),
									'no_apto' => $this->input->post("noAptoCieMocupa"),
									'idUsuario' => $this->session->userdata('idUsuario')
								);

								$this->db->insert("historial_dmedico_ocupacional", $parametrosConclusionaudiometria);
							}
						}
					}
				}


				$response['status'] = true;
				$response['message'] = 'Se actualizo los datos del afiliado correctamente.';

			}  else {

				$response['status'] = FALSE;
				$response['message'] = 'No se puedo actualizar los datos del afiliado.';
			}


		} else {
			redirect(base_url("inicio"));
		}

		$this->output->set_content_type( 'application/json' )->set_output( json_encode( $response ) );
		
	}

	public function anexo2($idAfiliado, $idHistorial)
    {
		$this->validarSesion();
  
		$this->load->model('HistoriaClinica');
  
		$data["historialEmpresa"] = $this->HistoriaClinica->historiale_empresa($idAfiliado);
		$data["afiliado"] = $this->HistoriaClinica->afiliadoDato($idAfiliado);
		
		$data["aAntecedenteOcupacionales"] = $this->HistoriaClinica->afiliadoAntecedenteOcupacional($idAfiliado, $idHistorial);
		$data["hapatologicop_ocupacionales"] = $this->HistoriaClinica->catalogo_antecedentepatologico_personal($idAfiliado, $idHistorial);
		$data["apatologicop_hnocivos"] = $this->HistoriaClinica->apatologicop_hnocivo($idAfiliado, $idHistorial);
		$data["apatologicof_ocupacional"] = $this->HistoriaClinica->apatologicof_ocupacional($idAfiliado, $idHistorial);
		$data["apatologicof_eaccidentes"] = $this->HistoriaClinica->apatologicof_eaccidente($idAfiliado, $idHistorial);
		$data["emedica_ocupacional"] = $this->HistoriaClinica->emedica_ocupacional($idAfiliado, $idHistorial);
		$data["emedica_efisico_ocupacionales"] = $this->HistoriaClinica->emedica_efisico_ocupacional($idAfiliado, $idHistorial);
		$data["conclusion_ocupacionales"] = $this->HistoriaClinica->conclusion_ocupacional($idAfiliado, $idHistorial);
		$data["conclusionaudiometria_ocupacionales"] = $this->HistoriaClinica->conclusionaudiometria_ocupacional($idAfiliado, $idHistorial);
		$data["conclusionespirometria_ocupacionales"] = $this->HistoriaClinica->conclusionespirometria_ocupacional($idAfiliado, $idHistorial);
		$data["dmedico_ocupacionales"] = $this->HistoriaClinica->dmedico_ocupacional($idAfiliado, $idHistorial);
 
		$html = $this->load->view('pdf_exports/ocupacional/anexo2', $data, TRUE);

		// Cargamos la librería
		$this->load->library('pdfgenerator');
		// generamos el PDF. Pasemos por encima de la configuración general y definamos otro tipo de papel-Letter
		$this->pdfgenerator->generate($html, "filename", false, "A4", "portrait");
	}

	public function certificado_aptitud($idAfiliado, $idHistorial)
	{
		$this->validarSesion();
  
		$this->load->model('HistoriaClinica');

		$data["afiliado"] = $this->HistoriaClinica->afiliadoDato($idAfiliado);
		$data["dmedico_ocupacionales"] = $this->HistoriaClinica->dmedico_ocupacional($idAfiliado, $idHistorial);
		$data["historialEmpresa"] = $this->HistoriaClinica->historiale_empresa($idAfiliado);
		$data["conclusion_ocupacionales"] = $this->HistoriaClinica->conclusion_ocupacional($idAfiliado, $idHistorial);
		

		$html = $this->load->view('pdf_exports/ocupacional/certificaAptitud', $data, TRUE);

		// Cargamos la librería
		$this->load->library('pdfgenerator');
		// generamos el PDF. Pasemos por encima de la configuración general y definamos otro tipo de papel-Letter
		$this->pdfgenerator->generate($html, "filename", false, "A4", "portrait");
	}

	public function examen_informeMedico($idAfiliado, $idHistorial)
	{
		$this->validarSesion();
  
		$this->load->model('HistoriaClinica');

		$data["afiliado"] = $this->HistoriaClinica->afiliadoDato($idAfiliado);
		$data["dmedico_ocupacionales"] = $this->HistoriaClinica->dmedico_ocupacional($idAfiliado, $idHistorial);
		$data["historialEmpresa"] = $this->HistoriaClinica->historiale_empresa($idAfiliado);
		$data["emedica_ocupacional"] = $this->HistoriaClinica->emedica_ocupacional($idAfiliado, $idHistorial);
		$data["dmedico_ocupacionales"] = $this->HistoriaClinica->dmedico_ocupacional($idAfiliado, $idHistorial);
		$data["conclusion_ocupacionales"] = $this->HistoriaClinica->conclusion_ocupacional($idAfiliado, $idHistorial);
		$data["conclusion_examenAuxiliares"] = $this->HistoriaClinica->ocupacional_examenAuxiliares($idAfiliado, $idHistorial);
		$data["conclusion_examenLaboratorios"] = $this->HistoriaClinica->ocupacional_examenLaboratorio($idAfiliado, $idHistorial);
		

		$html = $this->load->view('pdf_exports/ocupacional/informeMedico', $data, TRUE);

		// Cargamos la librería
		$this->load->library('pdfgenerator');
		// generamos el PDF. Pasemos por encima de la configuración general y definamos otro tipo de papel-Letter
		$this->pdfgenerator->generate($html, "filename", false, "A4", "portrait");
	}

}
