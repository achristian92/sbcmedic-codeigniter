<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Al requerir el autoload, cargamos todo lo necesario para trabajar
require_once APPPATH."third_party/dompdf/autoload.php";
use Dompdf\Dompdf;
class Pdfgenerator {
// por defecto, usaremos papel A4 en vertical, salvo que digamos otra cosa al momento de generar un PDF
public function generate($html, $filename='', $stream=TRUE, $paper = 'A4', $orientation = "portrait", $guardar=false, $ruta="")
  {
    $dompdf = new DOMPDF();
    $dompdf->loadHtml($html);
    $dompdf->set_option('isRemoteEnabled', TRUE);
    $dompdf->setPaper($paper, $orientation);
    $dompdf->render();

    if ($stream) {
            // "Attachment" => 1 hará que por defecto los PDF se descarguen en lugar de presentarse en pantalla.
            
            $dompdf->stream($filename.".pdf", array("Attachment" => 1));
    } else {
          if($guardar) {
            file_put_contents($ruta."/$filename", $dompdf->output());
            return $dompdf->output();
          }

          //return $dompdf->output();
          return $dompdf->stream("showTemp.pdf", array("Attachment"=>0));
    }
  }
}
?>