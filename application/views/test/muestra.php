<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  $data=  base_url('img/logo_sbcmedic.png');
 
  $imagenBase64 = "data:image/png;base64," . base64_encode("logo_sbcmedic.png");
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
<title>Recetas-Exámenes-Médico</title>
   
</head>
<body>

<?php echo base_url('img/logo_sbcmedic.png');?>

<?php echo base_url("img/$imagenBase64");?>
  <div id="header">
    <img src="<?php echo base_url("img/$imagenBase64");?>" width="36%">
	
<img width="40" src="<?php echo $imagenBase64;?>" alt=""/>
<img width="40" src="https://cdn.pixabay.com/photo/2021/10/14/15/11/cathedral-6709412_960_720.jpg" alt=""/>

    <table style="width:100%; border-collapse: collapse;">
      <tr>
        <td><h3>Paciente:</h3></td>
       
      </tr>
    </table>
    <br>
  </div>
  
  </body>
  </html>
  