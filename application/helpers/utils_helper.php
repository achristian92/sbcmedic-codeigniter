<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('setActive')) {
    function setActive($controller){
        $CI = & get_instance();
        //$class2 = $CI->router->fetch_class();
        $class = $CI->router->fetch_method();

        return ($class == $controller) ? 'active' : '';
    }
}

if (!function_exists('urls_amigables')) {
    //creamos la funcion y no explico mas sobre que es cada linea por que eso ya es otro tema.
    function urls_amigables($string) {
        $string = trim($string);
    
        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A'),
            $string
        );
    
        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E'),
            $string
        );
    
        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I'),
            $string
        );
    
        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O'),
            $string
        );
    
        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U'),
            $string
        );
    
        $string = str_replace(
            array('ç', 'Ç'),
            array('c', 'C',),
            $string
        );
    
        $string = str_replace(
            array('>', '<'),
            array('&gt;', '&lt;',),
            $string
        );
    
        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
            array("\\", "º", "~",
                 "#"),
            '',
            $string
        );
        
    return $string;

    }
}