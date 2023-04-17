<?php
    $idExamen = $this->uri->segment(3);
    $idSolicitud = $this->uri->segment(4);
    $user = $this->input->get("usuario");
    $idTipo = $this->input->get("idTipo");
	$idPerfil = $this->input->get("idPerfil");
	$nuevo = $this->input->get("nuevo");

    if($nuevo == 1) {
        header("Location: ../../../informe/formularioView/plantilla_general/$idSolicitud?idExamen=$idExamen&user=$user&idTipo=$idTipo&nuevo=$nuevo");
  
    } else {
	
		if($idExamen == 23) {
			header("Location: ../../../informe/formularioView/orina_completo/$idSolicitud?idExamen=$idExamen&user=$user&idTipo=$idTipo");
		} else  if($idExamen == 25) {
			header("Location: ../../../informe/formularioView/hemograma_completo/$idSolicitud?idExamen=$idExamen&user=$user&idTipo=$idTipo");
		} else  if($idExamen == 31 || $idExamen == 32 || $idExamen == 34 || $idExamen == 37 || $idExamen == 38) {
			header("Location: ../../../informe/formularioView/parasitologico/$idSolicitud?idExamen=$idExamen&user=$user&idTipo=$idTipo");
		} else  if($idExamen == 35 || $idExamen == 36) {
			header("Location: ../../../informe/formularioView/test_reumatico_hongos/$idSolicitud?idExamen=$idExamen&user=$user&idTipo=$idTipo");
		} else  if($idExamen == 40) {
			header("Location: ../../../informe/formularioView/aglutinacion/$idSolicitud?idExamen=$idExamen&user=$user&idTipo=$idTipo");
		} else  if($idExamen == 58 || $idExamen == 59 || $idExamen == 61 || $idExamen == 19) {
			header("Location: ../../../informe/formularioView/anticuerpos/$idSolicitud?idExamen=$idExamen&user=$user&idTipo=$idTipo");    
		} else  if($idExamen == 64 || $idExamen == 352) { 
			header("Location: ../../../informe/formularioView/papanicolau/$idSolicitud?idExamen=$idExamen&user=$user&idTipo=$idTipo");
		} else  if($idExamen == 65 || $idExamen == 68) {
			header("Location: ../../../informe/formularioView/biopsiaPequena_colegrama/$idSolicitud?idExamen=$idExamen&user=$user&idTipo=$idTipo");
		} else  if($idExamen == 72) {
			header("Location: ../../../informe/formularioView/vdrl_rpr/$idSolicitud?idExamen=$idExamen&user=$user&idTipo=$idTipo");  	
		} else  if($idExamen == 75) {
			header("Location: ../../../informe/formularioView/secrecion_vaginal/$idSolicitud?idExamen=$idExamen&user=$user&idTipo=$idTipo");  		
		} else {
			header("Location: ../../../informe/formularioView/laboratorio/$idSolicitud?idExamen=$idExamen&user=$user&idTipo=$idTipo&idPerfil=$idPerfil");
		}
	
	}

?>