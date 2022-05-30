<?php


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Helper\JSONHelper;
use App\functions\TABLAS;
use src\config\ConectarDB;


// Routes


$app->get('/', App\Action\HomeAction::class)
    ->setName('homepage');

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, responseType ')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->group('/v1', function () {
    
    // crud
    $this->POST('/api/tablas', function(Request $request, Response $response){

        $token = $request->getHeader("HTTP_AUTHORIZATION");
            $token = str_replace("Bearer ", "", $token);
            $token = implode($token);
            $bd = ConectarDB::singleton();
            if ( $bd->checkJWTAuth($token) == true ){
                $params = $request->getParsedBody();
                $params = ($params==null)?json_decode($request->getBody(), true):$params;
                $schema = [
                    "type"=>"object",
                    "properties"=>[
                        "accion"=>[
                            "description"=>"accion",
                            "type"=>"string"
                        ],
                        "idAplicacion"=>[
                            "description"=>"Id de la aplicacion",
                            "type"=>"integer"
                        ],
                        "tabla"=>[
                            "type" => "string",
                            "description"=>"Id de la aplicacion",
                    ],
    
                    ],
                    "required"=>["tabla"]
                ];
                list($ok, $msg, $data) = JSONHelper::validate($params, $schema);
                if (!$ok){
                    $resp = ["statusCode"=>5, "status"=>"error", "message"=>$msg, "data"=>$data]; 
                    $response->getBody()->write(json_encode($resp));
                        return $response                
                            ->withHeader('Content-Type', 'application/json');
                } else {
                    if($params['tabla'] === 'productos' && isset($params['insert'])){
                        $bd = ConectarDB::singleton();
                        $index = 0;
                        foreach($params['insert'] as $valor){
                            if($valor['campo'] == 'id_cliente'){
                                $id_cliente = $params['insert'][$index]['valor'];
                            }
                            if($valor['campo'] == 'id_proveedor'){
                                $id_proveedor = $params['insert'][$index]['valor'];
                            }
                            $index ++;
                        };
                        for($i=0;$i<count($params['insert']);$i++){
                            if($params['insert'][$i]['campo'] === 'imagen' || $params['insert'][$i]['campo'] === 'imagen2'){
                                $tipo = '';
                                if(str_contains($params['insert'][$i]['valor'], '/png') || str_contains($params['insert'][$i]['valor'], '/PNG')){
                                    $tipo  = 'png';
                                } else if(str_contains($params['insert'][$i]['valor'], '/jpg') || str_contains($params['insert'][$i]['valor'], '/JPG') || str_contains($params['insert'][$i]['valor'], '/jpeg') || str_contains($params['insert'][$i]['valor'], '/JPEG')){
                                    $tipo  = 'JPG';
                                } else if(str_contains($params['insert'][$i]['valor'], '/bmp') || str_contains($params['insert'][$i]['valor'], '/BMP')){
                                    $tipo  = 'bmp';
                                } else if(str_contains($params['insert'][$i]['valor'], '/gif') || str_contains($params['insert'][$i]['valor'], '/GIF')){
                                    $tipo  = 'gif';
                                }
                                if(isset($tipo)){
                                    $nombre_documento = $bd->generarRandom() . '.' . $tipo;
                                    $nombre_documento = $bd->guardarDocumentos($id_cliente, $id_proveedor, $nombre_documento, $params['insert'][$i]['valor']);
                                    $params['insert'][$i]['valor'] = $nombre_documento;
                                }
                            }
                        };
                    }
    
                    if($params['tabla'] === 'productos' && isset($params['update'])){
                        $bd = ConectarDB::singleton();
                        $index = 0;
                        foreach($params['update'] as $valor){
                            if($valor['campo'] == 'id_cliente'){
                                $id_cliente = $params['update'][$index]['valor'];
                            }
                            if($valor['campo'] == 'id_proveedor'){
                                $id_proveedor = $params['update'][$index]['valor'];
                            }
                            $index ++;
                        };
                        for($i=0;$i<count($params['update']);$i++){
                            if($params['update'][$i]['campo'] === 'imagen' || $params['update'][$i]['campo'] === 'imagen2'){
                                $tipo = '';
                                if(str_contains($params['update'][$i]['valor'], '/png') || str_contains($params['update'][$i]['valor'], '/PNG')){
                                    $tipo  = 'png';
                                } else if(str_contains($params['update'][$i]['valor'], '/jpg') || str_contains($params['update'][$i]['valor'], '/JPG') || str_contains($params['update'][$i]['valor'], '/jpeg') || str_contains($params['update'][$i]['valor'], '/JPEG')){
                                    $tipo  = 'jpg';
                                } else if(str_contains($params['update'][$i]['valor'], '/bmp') || str_contains($params['update'][$i]['valor'], '/BMP')){
                                    $tipo  = 'bmp';
                                } else if(str_contains($params['update'][$i]['valor'], '/gif') || str_contains($params['update'][$i]['valor'], '/GIF')){
                                    $tipo  = 'gif';
                                }
                                if(isset($tipo)){
                                    $nombre_documento = $bd->generarRandom() . '.' . $tipo;
                                    $nombre_documento = $bd->guardarDocumentos($id_cliente, $id_proveedor, $nombre_documento, $params['update'][$i]['valor']);
                                    $params['update'][$i]['valor'] = $nombre_documento;
                                }
                            }
                        };
                    }
    
                    $resp = TABLAS::switchAccion($params);
                    
                    if($resp['cod'] === 0){
                        if($params['tabla'] === 'productos' && isset($params['select'])){
                            $bd = ConectarDB::singleton();
                            if(count($resp['datos']) > 1 && count($resp['datos']) < 10){
                                for($i=0;$i<count($resp['datos']);$i++){
                                    $id_producto = $resp['datos'][$i]['id'];
                                    $datos = $bd->datos_bd('productos', ['id'=>$id_producto]);
                                    $imagen = $datos[0]['imagen'];
                                    $imagen2 = $datos[0]['imagen2'];
                                    $arr_imagen = explode(".", $imagen);
                                    if(isset($imagen)){
                                        $imagen = $arr_imagen[0] . "_min." . $arr_imagen[1];
                                        if (file_exists($imagen)){
                                            $binario = file_get_contents($imagen);
                                            $b64 = base64_encode($binario);
                                            $fileb64 = utf8_encode($b64);
                                            $imagen = $fileb64;
                                            switch($arr_imagen[1]){
                                                case "jpg":
                                                    $imagen = 'data:image/jpg;base64,' . $imagen;
                                                break;
                                                case "png":
                                                    $imagen = 'data:image/png;base64,' . $imagen;
                                                break;
                                                case "bmp":
                                                    $imagen = 'data:image/bmp;base64,' . $imagen;
                                                break;
                                                case "gif":
                                                    $imagen = 'data:image/gif;base64,' . $imagen;
                                                break;
                                            }
                                        }else{
                                            $imagen = null;
                                        }
                                    }else{
                                        $imagen = null;
                                    }
                                    $arr_imagen2 = explode(".", $imagen2);
                                    if(isset($imagen2)){
                                        $imagen2 = $arr_imagen2[0] . "_min." . $arr_imagen2[1];
                                        if (file_exists($imagen2)){
                                            $binario = file_get_contents($imagen2);
                                            $b64 = base64_encode($binario);
                                            $fileb64 = utf8_encode($b64);
                                            $imagen2 = $fileb64;
                                            switch($arr_imagen2[1]){
                                                case "jpg":
                                                    $imagen2 = 'data:image/jpg;base64,' . $imagen2;
                                                break;
                                                case "png":
                                                    $imagen2 = 'data:image/png;base64,' . $imagen2;
                                                break;
                                                case "bmp":
                                                    $imagen2 = 'data:image/bmp;base64,' . $imagen2;
                                                break;
                                                case "gif":
                                                    $imagen2 = 'data:image/gif;base64,' . $imagen2;
                                                break;
                                            }
                                        }else{
                                            $imagen2 = null;
                                        }
                                    }else{
                                        $imagen2 = null;
                                    }
    
                                    $resp['datos'][$i]['imagen'] = $imagen;
                                    $resp['datos'][$i]['imagen2'] = $imagen2;
                                }
                            }else if(count($resp['datos']) == 1){
                                $id_producto = $resp['datos'][0]['id'];
                                $datos = $bd->datos_bd('productos', ['id'=>$id_producto]);
                                $imagen = $datos[0]['imagen'];
                                $imagen2 = $datos[0]['imagen2'];
                                $arr_imagen = explode(".", $imagen);
                                if(isset($imagen)){
                                    if (file_exists($imagen)){
                                        $binario = file_get_contents($imagen);
                                        $b64 = base64_encode($binario);
                                        $fileb64 = utf8_encode($b64);
                                        $imagen = $fileb64;
                                        switch($arr_imagen[1]){
                                            case "jpg":
                                                $imagen = 'data:image/jpg;base64,' . $imagen;
                                            break;
                                            case "png":
                                                $imagen = 'data:image/png;base64,' . $imagen;
                                            break;
                                            case "bmp":
                                                $imagen = 'data:image/bmp;base64,' . $imagen;
                                            break;
                                            case "gif":
                                                $imagen = 'data:image/gif;base64,' . $imagen;
                                            break;
                                        }
                                    }else{
                                        $imagen = null;
                                    }
                                }else{
                                    $imagen = null;
                                }
                                $arr_imagen2 = explode(".", $imagen2);
                                if(isset($imagen2)){
                                    if (file_exists($imagen2)){
                                        $binario = file_get_contents($imagen2);
                                        $b64 = base64_encode($binario);
                                        $fileb64 = utf8_encode($b64);
                                        $imagen2 = $fileb64;
                                        switch($arr_imagen2[1]){
                                            case "jpg":
                                                $imagen2 = 'data:image/jpg;base64,' . $imagen2;
                                            break;
                                            case "png":
                                                $imagen2 = 'data:image/png;base64,' . $imagen2;
                                            break;
                                            case "bmp":
                                                $imagen2 = 'data:image/bmp;base64,' . $imagen2;
                                            break;
                                            case "gif":
                                                $imagen2 = 'data:image/gif;base64,' . $imagen2;
                                            break;
                                        }
                                    }else{
                                        $imagen2 = null;
                                    }
                                }else{
                                    $imagen2 = null;
                                }
                                
                                
                                $resp['datos'][0]['imagen'] = $imagen;
                                $resp['datos'][0]['imagen2'] = $imagen2;
                            }else{
                                for($i=0;$i<count($resp['datos']);$i++){
                                    $id_producto = $resp['datos'][$i]['id'];
                                    $datos = $bd->datos_bd('productos', ['id'=>$id_producto]);
                                    $imagen = $datos[0]['imagen'];
                                    $imagen2 = $datos[0]['imagen2'];
                                    if(isset($imagen)){
                                        $imagen = true;
                                    }else{
                                        $imagen = null;
                                    }
                                    if(isset($imagen2)){
                                        $imagen2 = true;
                                    }else{
                                        $imagen2 = null;
                                    }
                                    $resp['datos'][$i]['imagen'] = $imagen;
                                    $resp['datos'][$i]['imagen2'] = $imagen2;
                                }
                            }
                        }
                    }
                }
            }else{
                $resp = [
                    "cod" => 1,
                    "msj" => 'Token incorrecto'
                ];
            }
        $response->getBody()->write(json_encode($resp));
        return $response                
            ->withHeader('Content-Type', 'application/json');
    });


    //login

    $this->POST('/api/login', function(Request $request, Response $response){
        $params = $request->getParsedBody();
        $params = ($params==null)?json_decode($request->getBody(), true):$params;
        $schema = [
            "type"=>"object",
            "properties"=>[
                "usuario"=>[
                    "description"=>"usuario",
                    "type"=>"string"
                ],
                "password"=>[
                    "description"=>"password",
                    "type"=>"string"
                ]
            ],
            "required"=>["usuario", "password"]
        ];
        list($ok, $msg, $data) = JSONHelper::validate($params, $schema);
        if (!$ok){
            $resp = ["statusCode"=>5, "status"=>"error", "message"=>$msg, "data"=>$data]; 

        } else {
            $password = base64_encode($params["password"]);
            $usuario = $params["usuario"];
            $bd = ConectarDB::singleton();
            $resp = $bd->getTokenJWTAdm($password, $usuario);
        }
        $response->getBody()->write(json_encode($resp));
                return $response                
                    ->withHeader('Content-Type', 'application/json');
    });




});
