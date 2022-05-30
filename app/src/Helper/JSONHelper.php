<?php
Namespace App\Helper;
use JsonSchema\Validator;
use JsonSchema\Constraints\Constraint;


class JSONHelper {

    public static function validate($data, $schema){
        
        $validator = new Validator();

        if (is_string($schema)){
            $schema = file_get_contents($schema);    
            $schema = json_decode($schema);

        } 
        try {
            $data = json_decode(json_encode($data));
            $validator->validate($data,  ($schema) , Constraint::CHECK_MODE_VALIDATE_SCHEMA);
         } catch (\Exception $e) {                                  
            return array(false, 'Error en la validación del esquema', [$e->getMessage()]);
         }
         $errores = [];
         if ($validator->isValid()) {
            return array(true, 'Ok', $errores);
         } else {
            foreach ($validator->getErrors() as $error) {
               $errores[]  = array("error" => $error['property'], "mensaje" => $error['message']);
            }
            $error = array(false, 'Error en la validación de los datos', $errores);
            return $error;
         }
        
    }

}




?>


