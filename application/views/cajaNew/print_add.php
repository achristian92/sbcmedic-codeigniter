<?php  defined('BASEPATH') or exit('No direct script access allowed'); 
    $tipo = $this->uri->segment(3);
    $usuario = $this->uri->segment(4);
    $cita = $this->uri->segment(5);
?>
<!DOCTYPE html>
    <html><head> 
        <base href="consulta">  
        <meta charset="utf-8"> 
         <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
          <link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico'); ?>" />  <title>SBCMedic | TICKET PRINT</title>  <!-- Tell the browser to be responsive to screen width -->  <meta name="viewport" content="width=device-width, initial-scale=1"> 
          <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

           <style>   
             table.recibo {      border-collapse: collapse;      font-size: 10pt;      font-family: 'Times New Roman', Times, serif;    }    table.recibo td,    th {      border: 1px solid #999;      padding: 0.5rem;      text-align: left;      font-size: 10pt;      font-family: 'Times New Roman', Times, serif;    } 
            </style>
        </head>
        <body>

        <div class="container">
         <div class="row">
            <div class="col-sm">
            <a class="btn btn-primary" href="<?php echo base_url('cash-management/addPay/').$usuario."/$cita"."/". $code; ?>" role="button">Volver</a> | <a href="javascript:void(0)" id="imprime"><i class="fas fa-print"></i> Imprimir</a>
            <br><br>
            
            <div id="myPrintArea"> 
                <table   style="width:60%" class="table table-bordered">    
                <tr>
                    <td colspan="3" style="text-align: center;"><img src="<?php echo base_url('img/logo_sbcmedic.png');?>" alt="logo" width="50%"></td>
                </tr>
                <tr>
                    <td><strong>N°Cita:</strong> <?php echo str_pad($cita, 6, '0', STR_PAD_LEFT); ?></td>
                    <td><strong>Paciente:</strong> <?php echo $usuarioCita["firstname"] . " ". $usuarioCita["lastname"]; ?></td>
                    <td><strong>Nro Documento:</strong> <?php echo $usuarioCita["document"]; ?></td>
                </tr>
                <tr>
                    <th colspan="3" style="text-align: center;">Ignacio Mariategui 154, Barranco.</th>
                </tr>
                <tr>
                    <th colspan="3" style="text-align: center;"><?php echo strtoupper($tipo); ?></th>
                </tr>
                
                <tr>       
                    <th colspan="2">CONCEPTO</th>       
                    <th style="text-align: center;">COSTO (S/.)&nbsp;&nbsp;</th>     
                </tr>        
                    <?php          
                         $total = 0; $descuento = 0;          
                         foreach ($resultados as $row) {           
                            $total = $total + $row["precio"];        
                            $descuento = $descuento + $row["descuento"];
                    ?>          
                    <tr>            
                        <td style="width: 40%;" colspan="2"><?php echo $row["examen"];?></td>            
                        <td style="width: 40%;" align="right"><?php echo number_format($row["precio"], 2);?>&nbsp;&nbsp;</td>          
                    </tr>         
                    <?php } ?>    
                      
                    </table>  
                    <table style="width: 60%;  font-size: 10pt; font-family: 'Times New Roman', Times, serif;" CELLPADDING="5" CELLSPACING="5" border="0">
                        <?php if($descuento > 0) { ?> 
                        <tr>      
                            <td style="text-align: right; font-size: 16px; width: 70%;">Sub Total</td>
                            <td align="right" style="font-size: 18px;"><strong>S./ <?php echo number_format($total, 2);?>&nbsp;&nbsp;</strong></td> 
                        </tr> 

                        <tr>      
                            <td style="text-align: right; font-size: 16px; width: 70%;">Descuento</td>
                            <td align="right" style="font-size: 18px;"><strong>S./ <?php echo number_format($descuento, 2);?>&nbsp;&nbsp;</strong></td> 
                        </tr>
                        <?php } ?>   
                        <tr>      
                            <td style="text-align: right; font-size: 16px; width: 70%;"><strong>TOTAL</strong></td>
                            <td align="right" style="font-size: 18px; border-color:#666666; border-style:dashed; border-width:2px;"><strong>S./ <?php echo number_format($total - $descuento, 2);?>&nbsp;&nbsp;</strong></td> 
                        </tr>   
                        <tr>      
                            <td colspan="2" style="text-align: center;"><strong><u>GRACIAS POR SU ATENCIÓN</u>.</strong></td>  
                         </tr>
                        <tr>      
                            <td>FECHA: <strong><?php echo date("d/m/Y");?></strong></td>
                            <td align="right">HORA:  <strong><?php echo date("H:m");?></strong></td>  
                        </tr>
                    </table>
                </div> 
    </div>
 
  </div>
</div>
   
    </body>
    <?php $this->load->view("scripts"); ?>
    <!-- PrintArea -->
    <script src="<?php echo base_url('js/jquery.PrintArea.js'); ?>"></script>
    <script>    
        $("#imprime").click(function() {      $("div#myPrintArea").printArea();    });
        $("div#myPrintArea").printArea();      window.onafterprint = function() {  } 
       
    </script>
</body>
</html>