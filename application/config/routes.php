<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'welcome';
$route['login'] = 'welcome/index';
$route['logout'] = 'welcome/logout';
$route['provincias/(:any)'] = 'welcome/listarProvincias/$1';
$route['distritos/(:any)'] = 'welcome/listarDistritos/$1';
$route['doctores/(:any)'] = 'welcome/listarDoctores/$1';
$route['listarEventos'] = 'welcome/listarDisponibilidad';
$route['buscarEventos/(:any)/(:any)'] = 'welcome/buscarDisponibilidad/$1/$2';
$route['registro'] = 'welcome/registrar';
$route['guardar'] = 'welcome/store';
$route['validar'] = 'welcome/validarUsuario';
$route['cargar/(:any)'] = 'welcome/cargar/$1';
$route['cita'] = 'welcome/cita';
$route['confirmacion'] = 'welcome/confirmacion';
$route['bienvenida'] = 'welcome/bienvenida';
$route['cita/buscar/(:any)'] = 'welcome/buscar/$1';
$route['cita/confirmar/(:any)'] = 'welcome/confirmar/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['inicio'] = 'welcome/inicio';

$route['recuperar'] = 'welcome/recuperarPassword';
$route['validaremail'] = 'welcome/validarEmail';
$route['reset/(:any)'] = 'welcome/cambiarPassword/$1';
$route['confirmarpassword'] = 'welcome/confirmarCambioPassword';

$route['miperfil'] = 'welcome/perfil';
$route['actualiZarDatos'] = 'welcome/actualiZar_datos';

$route['listarEventosMedicos/(:any)/(:any)/(:any)/(:any)'] = 'welcome/listarEventosMedicos/$1/$2/$3/$4';

$route['procesarPago'] = 'welcome/procesarPagoUnico';

$route['miscitas'] = 'welcome/misCitas';
$route['mis-citas-personalizado'] = 'welcome/my_quotes_personalized';

$route['mihistorial'] = 'welcome/miHistorial';
$route['gCalificacion'] = 'welcome/grabarCalificacion';
$route['calificacionMedico/(:num)'] = 'welcome/promedioCalificacionView/$1';

$route['gCita'] = 'welcome/cerrarCita';
$route['uCita'] = 'welcome/updateCita';
//$route['update-appointment/(:num)'] = 'welcome/actualizar_cita/$1';
$route['new-appointment/(:num)/(:num)'] = 'welcome/nueva_cita/$1/$2';

$route['update-appointment/(:num)/(:num)'] = 'welcome/actualizar_cita/$1/$2';

$route['downloadPdf/(:num)/(:any)/(:num)'] = 'welcome/descargarPdf/$1/$2/$3';
$route['gCancelarCita'] = 'welcome/gCancelarCita';
$route['gCloseHistory'] = 'welcome/gCerrarHistorial';

$route['downloadPdfHistorial/(:num)/(:num)'] = 'welcome/descargarPdfHistorial/$1/$2';

$route['examenesMedicos'] = 'welcome/examenesMedicos';
$route['resultados'] = 'welcome/resultados';
$route['subirResultado'] = "welcome/uploadResult";

$route['farmacia'] = 'welcome/farmacia';
$route['gpagoStatus'] = 'welcome/gpagoStatus';
$route['gBolFacStatus'] = 'welcome/gBolFacStatus';

$route['searchClient'] = 'welcome/buscarCliente';
$route['searchCie10'] = 'welcome/buscarCie';
$route['search-recipes'] = 'welcome/buscarRecetas';
$route['search-procedimiento-lab'] = 'welcome/buscar_procedimiento_lab';

$route['editarHistorial'] = "welcome/editarHistory";

$route['gestionBuscarPaciente'] = "welcome/patientSearchManagement";
$route['gestionResumenPaciente'] = "welcome/patientSummaryManagement";
$route['gestionResumenRealizado'] = "welcome/patientSummaryManagementReady";

$route['gestionBuscarPaciente-new'] = "welcome/patientSearchManagementNew";

$route['gestionPaciente'] = "welcome/patientManagement";
$route['editarGestionPaciente/(:num)'] = "welcome/editPatientManagement/$1";
$route['actualizarGestionPaciente'] = "welcome/updatePatientManagement";
$route['gPaciente'] = "welcome/saveManagement";
$route['gestionPacientePdf/(:num)'] = 'welcome/patientManagementPdf/$1';
$route['gResultadoPaciente'] = 'welcome/saveResultManagement';
$route['gCancelarResultado'] = 'welcome/cancelResult';
$route['gestionAntigeno'] = 'welcome/gestionAntigeno';
$route['view-resultPdf/(:any)'] = "welcome/verResultadoPdf";

$route['cash-management-antigeno/print/(:num)'] = 'welcome/print_administración_antigenos/$1';
$route['gestionResumenRealizado-pay'] = "welcome/patientSummaryManagementPay";
$route['gestionResumenRealizado-new'] = "welcome/patientSummaryManagementReadyNew";
 
//informes
$route['gNuevoExamen'] = 'welcome/gNewExam';

$route['gNuevoMicrologia'] = 'welcome/gNewMicrologia';
$route['editarMicrologia/(:num)'] = 'welcome/editarMicrologia/$1';

$route['pdfinforme/(:any)/(:any)/(:num)'] = 'welcome/pdf_informe/$1/$2/$3';

$route['importar_excel'] = 'admin/admin/importar_excel';

$route['informe/index'] = 'welcome/gestion_informe';
$route['informe/formulario/(:num)/(:num)'] = 'welcome/frm_tipo/$1/$2';
$route['informe/formularioView/(:any)/(:num)'] = 'welcome/frm_examen/$1/$2';
$route['informe/gNuevoLaboratorio'] = 'welcome/gNewLaboratorio';
$route['informe/gNuevoLaboratorioParasotologico'] = 'welcome/gNewLaboratorioParasotologico';
$route['informe/gNuevoHemograma'] = 'welcome/gNewHemograma';
$route['informe/gNuevoLaboratorioOrinaCompleto'] = 'welcome/gNewOrinaCompleto';
$route['informe/gNuevoAglutinacion'] = 'welcome/gNewAglutinacion';
$route['informe/gNewPapanicolau'] = 'welcome/grabar_papanicolau';
$route['informe/gNewBiopsia'] = 'welcome/grabar_biopsia';
$route['informe/gNuevoVdrl'] = 'welcome/grabar_vdrl';
$route['informe/gNuevoSecrecionVaginal'] = 'welcome/grabar_SecrecionVaginal';
$route['informe/gNewPlantillaGeneral'] = 'welcome/grabar_plantillaGeneral';

$route['informe/enviarInforme'] = 'welcome/enviar_pdf_informe';

$route['searchExamen'] = 'welcome/buscarExamen';

$route['informe/resumen'] = 'welcome/resumenInforme';
$route['informe/resumentest'] = 'welcome/resumenInformetest';
$route['informe/statusPago_examen'] = 'welcome/statusPago_examen';
$route['informe/imprimirExamen/(:any)'] = 'welcome/printExamen/$id';
  
//admin
$route["admin/schedule"] = 'admin/admin/schedule';
$route["admin/permisos"] = 'admin/admin/permisos';

$route["admin/gHorario"] = 'admin/admin/grabarHorario';
$route["admin/bloquearHorario"] = 'admin/admin/bloquearHorario';
$route["admin/gbloqueoHorario"] = 'admin/admin/guardarBloquearHorario';
$route["admin/dAllHorarios"] = 'admin/admin/eliminarAllHorarios';

$route["admin/deleteHorario/(:num)"] = 'admin/admin/eliminarHorario/$1';
$route["admin/usuarioRol/(:num)"] = 'admin/admin/usuarioRol/$1';
$route["admin/gUsuarioRol"] = "admin/admin/gUsuarioRol";
$route["admin/rolPermiso/(:num)"] = 'admin/admin/rolPermiso/$1';
$route["admin/gRolPermiso"] = 'admin/admin/gRolPermiso';
$route["admin/medicoUsuario/(:num)"] = 'admin/admin/medicoUsuario/$1';
$route["admin/gMedicoUsuario"] = 'admin/admin/gMedicoUsuario';

$route['admin/reporteExcel'] = 'admin/admin/reporteExcel';
$route['admin/downloadExcelLaboratorio'] = 'admin/admin/exportar2excelLab';
$route['admin/downloadExcelProcedimientos'] = 'admin/admin/exportar2excelProced';
$route['admin/downloadProcedimientos'] = 'admin/admin/exportar2_excelProced';
$route['admin/downloadExcel'] = 'admin/admin/exportar2excel';
$route['admin/import'] = 'admin/admin/import';

$route['admin/recordatorioEmail'] = 'admin/admin/recordatorioEmailCitas';
$route['admin/recordatorioEmailDia'] = 'admin/admin/recordatorioEmailCitasDia';

$route['admin/examenes-solicitados'] = 'admin/admin/exams_requested';
//excel

$route['admin/reporteExcelCitas'] = 'admin/admin/exportarCitas';
$route['admin/reporteExcelPagos'] = 'admin/admin/exportarPagos';
$route['admin/reporteExcelLab'] = 'admin/admin/exportar_examenesLab';
$route['admin/reporteExcelSolicita'] = 'admin/admin/exportar_solicitud';
$route['admin/reporteExcelOrden'] = 'admin/admin/exportar_solicitud_orden';
$route['admin/reporteExcelCovid'] = 'admin/admin/exportar_covid';
//gestion examenes
$route['gestionarExamenes/(:any)'] = 'welcome/gestionarExamenes/$1';
$route['verResultado/(:num)'] = "welcome/viewResult/$1";
$route['searchExamenOrion'] = 'welcome/buscarExamen_orion';
$route['gNuevoExamenOrion'] = 'welcome/gNewExam_orion';

//admin caja
$route['cash-management'] = 'welcome/administración_caja';
$route['cash-management/print/(:any)'] = 'welcome/print_administración_caja/$id';

//$route['cash-management/addPay/(:any)'] = 'welcome/agregar_caja_pago/$id';
//$route['cash-management/print_add/(:any)/(:any)/(:num)'] = 'welcome/print_administración_add__caja/$1/$2';
//$route['cash-management/addPay/(:any)/(:any)'] = 'welcome/agregar_caja_pago/$1/$2';
$route['cash-management/addPay/(:any)/(:any)/(:any)'] = 'welcome/agregar_caja_pago/$1/$2/$3';
$route['cash-management/print_add/(:any)/(:any)/(:num)/(:num)'] = 'welcome/print_administración_add__caja/$1/$2/$3/$4';

$route['cash-management-save'] = 'welcome/administración_caja_grabar';
$route['cash-management-save-discount'] = 'welcome/administración_caja_grabar_descuento';
$route['cash-management-delete'] = 'welcome/administración_caja_eliminar';

$route['searchProcedimientos'] = 'welcome/buscarProcedimientos';
$route['search2Procedimientos'] = 'welcome/buscaridProcedimientos';
//info
$route['infoPaciente'] = 'welcome/info_paciente';

$route['scheduled_appointments/(:any)/(:num)'] = 'welcome/citas_programadas';



//admin caja
$route['cash-management2'] = 'welcome/administración_caja2';
$route['cash-management2/print/(:any)'] = 'welcome/print_administración_caja2/$id';

$route['cash-management2/addPay/(:any)/(:any)'] = 'welcome/agregar_caja_pago2/$1/$2';
$route['cash-management2/print_add/(:num)/(:num)/(:any)/(:any)'] = 'welcome/print_administración_add__caja2/$1/$2/$3/$4';
$route['cash-management2-save'] = 'welcome/administración_caja_grabar2';
$route['cash-management2-save-discount'] = 'welcome/administración_caja_grabar_descuento2';
$route['cash-management2-delete'] = 'welcome/administración_caja_eliminar2';

$route['searchProcedimientos'] = 'welcome/buscarProcedimientos';
$route['search2Procedimientos'] = 'welcome/buscaridProcedimientos';

$route['gpago-procedimiento'] = 'welcome/gpagoStatusPro';
$route['gpagoStatusProcedimiento'] = 'welcome/gpagoStatus_procedimiento';

// resultados
$route['delete-record-result'] = 'welcome/eliminar_registro_resultado';
$route['consult-dni'] = 'welcome/consultarDni';


//programar citas

$route['appointment-management'] = 'welcome/gestion_citas';
$route['consult-patient'] = 'welcome/consultarPaciente';
$route['available-dates/(:any)'] = 'welcome/fechas_disponibles/$1';
$route['listarEventos-medicos/(:any)'] = 'welcome/listar_EventosMedicos/$1';
$route['record-requests'] = 'welcome/grabar_solicitudes';
$route['check-request-records'] = 'welcome/consultar_registros_solicitudes';
$route['cita/search/(:any)'] = 'welcome/buscar_horarios/$1';
$route['update-option'] = 'welcome/actualizar_marca';
$route['buscarEventosHoras'] = 'welcome/buscarDisponibilidad_horas';
$route['detail-appointments-assigned/(:any)'] = 'welcome/detalle_citas_asignadas/$1';
//$route['cancel-request'] = 'welcome/cancelar_solicitud';
$route['cash-management-records/print/(:num)'] = 'welcome/print_administración_caja_solicitud/$1';
$route['cash-management-general'] = 'welcome/administración_caja_general';
$route['cash-management/window-print/(:any)/(:any)'] = 'welcome/ventana_citas/$1/$2';

$route['cita/search/(:any)'] = 'welcome/buscar_horarios/$1';
$route['modify-price'] = 'welcome/modificar_precio';
$route['save-patient-information'] = 'welcome/guardar_informacion_paciente';

//admin caja new
$route['cash-management-new'] = 'welcome/administración_caja_new';
$route['cash-management-save-new'] = 'welcome/administración_caja_grabar_new';
$route['cash-management/print_add-new/(:any)/(:num)/(:num)/(:any)'] = 'welcome/print_administración_add_caja_new/$1/$2/$3/$4';
$route['update-option-print'] = 'welcome/actualizar_marcaPrint';
$route['cash-management-save-discount-new'] = 'welcome/administración_caja_grabar_descuento_new';
$route['cash-management-delete-new'] = 'welcome/administración_caja_eliminar_new';
$route['management-box/print/(:any)'] = 'welcome/administración_caja_print/$id';

$route['cash-management/addPay-new/(:any)/(:any)/(:any)'] = 'welcome/agregar_caja_pago_new/$1/$2/$3';

//modulo ocupacional
$route['ocupacional/inicio'] = 'mocupacional/ocupacional/index';
$route['ocupacional/registroEmpresa'] = 'mocupacional/ocupacional/medical_company_new';
$route['ocupacional/registroAfiliado'] = 'mocupacional/ocupacional/medical_affiliate_registration';
$route['ocupacional/editarAfiliado/(:num)'] = 'mocupacional/ocupacional/medical_affiliate_edit/$1';
$route['ocupacional/saveAfiliado'] = 'mocupacional/ocupacional/updateAfiliado';
$route['ocupacional/registroDatos'] = 'mocupacional/ocupacional/registrar_dato';
$route['ocupacional/editarDatos/(:num)/(:num)'] = 'mocupacional/ocupacional/edit_dato/$1/$2';
$route['ocupacional/actualizarHAfiliado'] = 'mocupacional/ocupacional/update_datoHistoria';
$route['ocupacional/registroAxiliarLabo'] = 'mocupacional/ocupacional/medical_affiliate_AuxiLab';
$route['ocupacional/buscar'] = 'mocupacional/ocupacional/search';

$route['ocupacional/guardarHClinicaAfiliado'] = 'mocupacional/ocupacional/medical_history_affiliate__save';
$route['ocupacional/editarEmpresa/(:num)'] = 'mocupacional/ocupacional/medical_company_edit/$1';
$route['ocupacional/editarSaveEmpresa'] = 'mocupacional/ocupacional/medical_company__editSave';

$route['ocupacional/guardarAfiliadoHClinica'] = 'mocupacional/ocupacional/medical_history_record_save';

$route['ocupacional/guardarEmpresa'] = 'mocupacional/ocupacional/medical_company__save';
$route['ocupacional/searchAffiliate'] = 'mocupacional/ocupacional/buscarAfiliado';


$route['ocupacional/anexo2/(:num)/(:num)'] = 'mocupacional/ocupacional/anexo2/$1/$2';
$route['ocupacional/cAptitud/(:num)/(:num)'] = 'mocupacional/ocupacional/certificado_aptitud/$1/$2';
$route['ocupacional/informeMedico/(:num)/(:num)'] = 'mocupacional/ocupacional/examen_informeMedico/$1/$2';

//test
$route['downloadPdftest'] = 'welcome/descargarPdftest';
