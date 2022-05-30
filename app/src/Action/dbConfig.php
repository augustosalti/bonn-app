<?php

namespace Src\config;
use Dotenv;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '../../../');
$dotenv->load();

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

use PDO;

class ConectarDB
{

    private static $instancia;
    public $con;
    public $idAplicacion = 1;


    function __construct()
    {
        $USER = $_ENV['DB_USER'];
        $PASS = $_ENV['DB_PASSWD'];
        $DBNAME = $_ENV['DB_NAME'];
        $HOST = $_ENV['DB_HOST'];

        try {
            $mysqlConnect = "mysql:host=$HOST;dbname=$DBNAME;charset=utf8mb4";
            $this->con = new PDO($mysqlConnect, $USER, $PASS);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->con;
        } catch (PDOException $e) {
            echo "No se ha podido establecer la conexión: " . $e->getMessage();
            die();
        }

    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public static function singleton()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function __wakeup()
    {
        throw new Exception('Clase  ' . __CLASS__ . 'no se puede serializar');
    }

    public function __sleep()
    {
        throw new Exception('Clase  ' . __CLASS__ . 'no se puede serializar');
    }

    public function __clone()
    {
        throw new Exception('Clase  ' . __CLASS__ . 'no puede ser clonada');
    }

    // Metodos imprescindibles
    public function escape_base64($data)
    {
        if (is_numeric($data)) return $data;  // SI ES NUMERICO DEVUELVE LA DATA

        if (!isset($data) or empty($data)) return ''; // SI NO ESTA SETEADA DEVUELVE UN STRING VACIO         

        $non_displayables = array(
            '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/',             // url encoded 16-31
            '/[\x00-\x08]/',            // 00-08
            '/\x0b/',                   // 11
            '/\x0c/',                   // 12
            '/[\x0e-\x1f]/'             // 14-31
        );
        foreach ($non_displayables as $regex)
            $data = preg_replace($regex, '', $data);
        $data = str_replace("'", "''", $data);;
        return $data;
    }
    //DEVUELVE LA DATA 
    public function escape_bd($data)
    {
        if (is_numeric($data)) return $data;  // SI ES NUMERICO DEVUELVE LA DATA

        if (!isset($data) or empty($data)) return ''; // SI NO ESTA SETEADA DEVUELVE UN STRING VACIO         

        $non_displayables = array(
            '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/',             // url encoded 16-31
            '/[\x00-\x08]/',            // 00-08
            '/\x0b/',                   // 11
            '/\x0c/',                   // 12
            '/[\x0e-\x1f]/'             // 14-31
        );
        foreach ($non_displayables as $regex)
            $data = preg_replace($regex, '', $data);
        $data = str_replace("'", "''", $data);

        return $data;
    }

    public function datos_bd_select($select ,$mensaje, $accion)
    {
        $stm = $this->con->prepare($select); 
        $valores =[];
      
      
        if (array_key_exists('join',$mensaje) ) {
          foreach ($mensaje["join"] as $join) {
                    if (array_key_exists('where',$join) )  {
                    foreach ($join["where"] as $where) {
                        $lccondicion =  $where['condicion'];
                        if ($lccondicion == 'like') {
                            continue;
                        }
                        if ($lccondicion == 'in') {
                            continue;
                        }
                        $valor =  $where['valor'];
                        $valor = htmlentities( $valor);

                        array_push($valores,$valor);
                    }
                }   
          } 
        }

        if (array_key_exists('update',$mensaje) ) {
            foreach ($mensaje["update"] as $update) {
              
                $valor =  $update['valor'];
                $valor = htmlentities( $valor);

                array_push($valores,$valor);
            }
        }

        if (array_key_exists('insert',$mensaje) ) {
            foreach ($mensaje["insert"] as $insert) {
              
                $valor =  $insert['valor'];
                $valor = htmlentities( $valor);
                array_push($valores,$valor);
            }
        }

        if (array_key_exists('where',$mensaje) ) {
            foreach ($mensaje["where"] as $where) {
                $lccondicion =  $where['condicion'];
                if ($lccondicion == 'like') {
                    continue;
                }
                if ($lccondicion == 'in') {
                    continue;
                }
                $valor =  $where['valor'];
                array_push($valores,$valor);
            }
        }

        if (array_key_exists('whereExt',$mensaje) ) {
     
            foreach ($mensaje["whereExt"] as $whereExt) {
                $accion1ux =  $whereExt['accion'];
                
                if ($accion1ux  == 'valor') {
                   $valor =  $whereExt['valor'];
                   $valor = htmlentities( $valor);
                   array_push($valores,$valor);
                }
            }
         }        


        if (array_key_exists('parametros',$mensaje) ) {
            foreach ($mensaje["parametros"] as $where) {
                $valor =  $where['valor'];
                $valor = htmlentities( $valor);                
                $valor =  $valor;   
                array_push($valores,$valor);
            }
        }
        try {
            //code...
            $ok = $stm->execute($valores);
        } catch (\PDOException $th) {
            //throw $th;
            return [
                "cod" => 1,
                "msj" => "ERROR: ". $th.message,
                "datos" => 0 ];
        }
        if ($accion === 'select') {
            $v_datos = array();
            $i = 0;
            while ($fila = $stm->fetch(PDO::FETCH_ASSOC)) {
                $v_datos[$i] = $fila;
                $i++;
            }
            return $v_datos;    
        }  
        if ($accion === 'delete') {
            if (!$ok) {
                return [
                    "cod" => 1,
                    "msj" => "ERROR: No se ha podido borrar el registro.",
                    "datos" => 0 ];
            } else {
                return [
                    "cod" => 0,
                    "msj" => "Registros actualizados",
                    "datos" => $stm->rowCount() ];
            }  
        }  
        if ($accion === 'update') {
            if (!$ok) {
                return [
                    "cod" => 1,
                    "msj" => "ERROR: No se ha podido modificar el registro.",
                    "datos" => 0 ];
            } else {
                return [
                    "cod" => 0,
                    "msj" => "Registros actualizados",
                    "datos" => $stm->rowCount() ];
            }  
        }  
        if ($accion === 'insert') {
            if (!$ok) {
                
                return [
                    "cod" => 1,
                    "msj" => $ok,
                    "datos" => 0 ];
            } else {
                return [
                    "cod" => 0,
                    "msj" => "Registro insertado",
                    "datos" => $this->con->lastInsertId() ];
            }  
        }  
    }



    function miniatura($archivo, $local, $ancho, $alto){
        $arrNombre = explode(".", $local);
        $nombre = $arrNombre[0];
        $extension = $arrNombre[1];

        if($extension=="jpg" || $extension=="jpeg") $nuevo = imagecreatefromjpeg($archivo);
        if($extension=="png") $nuevo = imagecreatefrompng($archivo);
        if($extension=="bmp") $nuevo = imagecreatefrombmp($archivo);
        $thumb = imagecreatetruecolor($ancho, $alto);
        $ancho_original = imagesx($nuevo);
        $alto_original = imagesy($nuevo);
        imagecopyresampled($thumb,$nuevo,0,0,0,0,$ancho,$alto,$ancho_original,$alto_original);
        $thumb_name = "$nombre.$extension";
        // 90 es la calidad de compresión, Máx 100
        if($extension=="jpg" || $extension=="jpeg") imagejpeg($thumb, $thumb_name,90);
        if($extension=="png") imagepng($thumb, $thumb_name);
        if($extension=="gif") imagegif($thumb, $thumb_name);
    }

    public function generarRandom($length = 8){
        $key = "";
        $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
        $max = strlen($pattern)-1;
        for($i = 0; $i < $length; $i++){
            $key .= substr($pattern, mt_rand(0,$max), 1);
        }
        return $key;
    }    

    public function guardarDocumentos($id_cliente, $id_proveedor, $nombre_doc, $base64){
        if (isset($base64)){
            $micarpeta = "files/" . $id_cliente . "/" . $id_proveedor . "/";
            if (!file_exists($micarpeta)) {
                mkdir($micarpeta, 0777, true);
            }
            $archivo = explode('.', $nombre_doc);
            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
            $micarpetaMin = $micarpeta;
            $nombreArchivo = $nombre_doc;
            $nombreArchivoMin = $archivo[0] . "_min." . $archivo[count($archivo) - 1];
            $micarpeta .= $nombreArchivo;
            $micarpetaMin .= $nombreArchivoMin;
            if (file_exists($micarpeta)) {
                $i = 1;
                $nuevaCarpeta = "";
                do {
                    $archivo = explode('.', $micarpeta);
                    $archivo[0] .= "(" . $i . ")";
                    $nuevaCarpeta = $archivo[0] . "." . $archivo[1];
                    $micarpetaMin = $archivo[0] . "_min." . $archivo[1];
                    $i++;
                } while (file_exists($nuevaCarpeta) == true);
                if ($nuevaCarpeta != "") {
                    $micarpeta = $nuevaCarpeta;
                }
            }
            file_put_contents($micarpeta, $data . PHP_EOL, FILE_APPEND);
            $this->image_resize($micarpeta, $micarpetaMin, 300, 300);
            
            return $micarpeta;       
        }
        return false;
    }

    function image_resize($src, $dst, $width, $height, $crop = 1){
        if (!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";
        $type = strtolower(substr(strrchr($src, "."), 1));
        if ($type == 'jpeg') $type = 'jpg';
        switch ($type) {
            case 'bmp':
                $img = imagecreatefromwbmp($src);
                break;
            case 'gif':
                $img = imagecreatefromgif($src);
                break;
            case 'jpg':
                $img = imagecreatefromjpeg($src);
                break;
            case 'png':
                $img = imagecreatefrompng($src);
                break;
            default:
                return "Unsupported picture type!";
        }

        // resize
        if ($crop) {
            if ($w < $width or $h < $height) return false;
            $ratio = max($width / $w, $height / $h);
            $h = $height / $ratio;
            $x = ($w - $width / $ratio) / 2;
            $w = $width / $ratio;
        } else {
            if ($w < $width and $h < $height) return false;
            $ratio = min($width / $w, $height / $h);
            $width = $w * $ratio;
            $height = $h * $ratio;
            $x = 0;
        }

        $new = imagecreatetruecolor($width, $height);

        imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

        switch ($type) {
            case 'bmp':
                imagewbmp($new, $dst);
                break;
            case 'jpg':
                imagejpeg($new, $dst);
                break;
            case 'png':
                imagepng($new, $dst);
                break;
        }
        return true;
    }

    public function datos_bd($tabla, $v_claves){
        $sql = "SELECT * FROM " . $tabla . " WHERE 1=1 ";
        if (is_array($v_claves)) {
            $nexo = " AND ";
            foreach ($v_claves as $campo => $valor) {
                $sql .= $nexo . $campo . " = '" . $valor . "' ";
            }
        } else if ($v_claves != "") {
            $sql .= " AND " . $v_claves;
        }
        try {
            $stm = $this->con->prepare($sql);
            $res = $stm->execute();
        } catch (Exception $e) {
            echo $sql;
            echo $e;
        }
        $v_datos = array();
        $i = 0;
        while ($fila = $stm->fetch(PDO::FETCH_ASSOC)) {
            $v_datos[$i] = $fila;
            $i++;
        }
        return $v_datos;
    }

    public function getTokenJWTAdm($password, $usuario){
        function base64url_encode($data)
        {
            return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
        }
        $jwt_secret = '46128648670214272';
        $ahora = time();
        $exp = strtotime('+1 year', $ahora);

        $data = $this->datos_bd('cliente', ["usuario" => $usuario, "pass" => $password]);
        
        if (!empty($data)) {
            $header = base64url_encode('{ "alg": "HS256", "typ": "JWT"}');
            $payload = base64url_encode('{
                "id_cliente" : "' . $data[0]['id'] .  '",
                "iat": ' . $ahora . ',
                "exp": ' . $exp . '
                }');

            $firma =  base64url_encode(hash_hmac('sha256', $header . '.' . $payload, $jwt_secret, true));
            $token = $header . '.' . $payload . '.' . $firma;

            $data = [
                "cod" => 0,
                "token" => "Bearer $token"
            ];
            return $data;
        } else {
            $data = [
                "cod" => 1,
                "usuario" => $usuario,
                "mensaje" => "Error, usuario o contraseña incorrectos"
            ];
        }
        return $data;
    }

    public function checkJWTAuth($token){
        return true;
        if ($token == null || $token == "") {
            return false;
        } else {
            $jwt_secret = '46128648670214272';
            $jwt_secciones = explode('.', $token);
            $payload = json_decode(base64_decode($jwt_secciones[1]));
            $ahora = time();
            $fecha_fin =  $payload && $payload->{'exp'} ? $payload->{'exp'} : 0;
            $fecha_valida = (($fecha_fin - $ahora) > 0);
            $firma = $jwt_secciones[2];
            $Header_Payload = $jwt_secciones[0] . '.' . $jwt_secciones[1];
            $firmaC = base64_encode(hash_hmac('sha256', $Header_Payload, $jwt_secret, true));
            $firmaC = rtrim(strtr(base64_encode(hash_hmac('sha256', $Header_Payload, $jwt_secret, true)), '+/', '-_'), '=');
            return ($firmaC == $firma && $fecha_valida);
        }
    }

}
