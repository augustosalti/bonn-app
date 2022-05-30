<?php

Namespace App\functions;
/* 
 funcion generica para obtener la select de cualquier tabla 
 operadores soportados  ["=","<>",">","<","like"];
 operaciones en where  ["and","or"];
 tipos soportadors =["n","s"];
*/
use src\config\ConectarDB;
use App\Helper\JSONHelper;

////////////////////////////////////////////////////////////////////////////////////////////
// conguracion , añadir aqui las tablas , los campos , where ... que va a soportar
////////////////////////////////////////////////////////////////////////////////////////////
function configuracion_tabla($tabla,&$tablasenum,&$selectenum,&$camposwhere,&$orderenum,&$groupbyenum,
                            &$selectJoinTabla,&$selectenumJoin,&$tablaalias,&$camposOnJoin,&$camposwhereJoin,&$aliascamposJoin ){

    $tablasenum = ["cliente", "color", "modelo", "productos", "proveedor", "tipo", "expo"];

    if ($tabla === 'cliente') {
        $selectenum  = ["*","id","nombre","usuario","telefono","cp","direccion","logo"];
        $camposwhere = ["id","nombre","usuario"];
    };

    if ($tabla === 'color') {
        $selectenum  = ["*","id","id_cliente","id_proveedor","id_tipo","nombre"];
        $camposwhere = ["id","id_cliente","id_proveedor","id_tipo","nombre"];
    };

    if ($tabla === 'modelo') {
        $selectenum  = ["*","id","id_cliente","id_proveedor","id_tipo","nombre","descripcion"];
        $camposwhere = ["id","id_cliente","id_proveedor","id_tipo","nombre","descripcion"];
    };

    if ($tabla === 'productos') {
        $selectenum  = ["*","id","id_cliente","id_proveedor","id_modelo","id_tipo","imagen","imagen2","nombre","descripcion","precio","precio_anterior","costo","alto","ancho","profundo","id_color","stock","activo","codigo"];
        $camposwhere = ["id","id_cliente","id_proveedor","id_modelo","id_tipo","nombre","descripcion","precio","costo","alto","ancho","profundo","id_color","stock"];
    };

    if ($tabla === 'proveedor') {
        $selectenum  = ["*","id","id_cliente","nombre","direccion","cp","telefono","email"];
        $camposwhere = ["id","id_cliente","nombre"];
    };

    if ($tabla === 'tipo') {
        $selectenum  = ["*","id","id_cliente","nombre","descripcion"];
        $camposwhere = ["id","id_cliente","nombre"];
    };

    if ($tabla === 'expo') {
        $selectenum  = ["*","id","id_cliente","link","shortlink"];
        $camposwhere = ["id","id_cliente"];
    };
    
 
 } 

function configuracion_update($tabla,&$tablasenum ,&$camposupdate,&$camposwhere){
   
    $tablasenum = ["cliente", "color", "modelo", "productos", "proveedor", "tipo"];

    if ($tabla === 'cliente') {
        $camposupdate  = ["*","id","nombre","usuario","telefono","cp","direccion","logo"];
        $camposwhere = ["id","nombre","usuario"];
    };

    if ($tabla === 'color') {
        $camposupdate  = ["*","id","id_cliente","id_proveedor","id_tipo","nombre"];
        $camposwhere = ["id","id_cliente","id_proveedor","id_tipo","nombre"];
    };

    if ($tabla === 'modelo') {
        $camposupdate  = ["*","id","id_cliente","id_proveedor","id_tipo","nombre","descripcion"];
        $camposwhere = ["id","id_cliente","id_proveedor","id_tipo","nombre","descripcion"];
    };

    if ($tabla === 'productos') {
        $camposupdate  = ["*","id","id_cliente","id_proveedor","id_modelo","id_tipo","imagen","imagen2","nombre","descripcion","precio","precio_anterior","costo","alto","ancho","profundo","id_color","stock","codigo"];
        $camposwhere = ["id","id_cliente","id_proveedor","id_modelo","id_tipo","nombre","descripcion","precio","costo","alto","ancho","profundo","id_color","stock","codigo"];
    };

    if ($tabla === 'proveedor') {
        $camposupdate  = ["*","id","id_cliente","nombre","direccion","cp","telefono","email"];
        $camposwhere = ["id","id_cliente","nombre"];
    };

    if ($tabla === 'tipo') {
        $camposupdate  = ["*","id","id_cliente","nombre","descripcion"];
        $camposwhere = ["id","id_cliente","nombre"];
    };

    if ($tabla === 'expo') {
        $camposupdate  = ["*","id","id_cliente","link","shortlink"];
        $camposwhere = ["id","id_cliente"];
    };
 }

function configuracion_insert($tabla,&$tablasenum ,&$camposinsert){

    $tablasenum = ["cliente", "color", "modelo", "productos", "proveedor", "tipo"];

    if ($tabla === 'cliente') {
        $camposinsert  = ["id","nombre","usuario","telefono","cp","direccion","logo"];
    };

    if ($tabla === 'color') {
        $camposinsert  = ["id","id_cliente","id_proveedor","id_tipo","nombre"];
    };

    if ($tabla === 'modelo') {
        $camposinsert  = ["id","id_cliente","id_proveedor","id_tipo","nombre","descripcion"];
    };

    if ($tabla === 'productos') {
        $camposinsert  = ["id","id_cliente","id_proveedor","id_modelo","id_tipo","imagen","imagen2","nombre","descripcion","precio","precio_anterior","costo","alto","ancho","profundo","id_color","stock","activo","codigo"];
    };

    if ($tabla === 'proveedor') {
        $camposinsert  = ["id","id_cliente","nombre","direccion","cp","telefono","email"];
    };

    if ($tabla === 'tipo') {
        $camposinsert  = ["id","id_cliente","nombre","descripcion"];
    };

    if ($tabla === 'expo') {
        $camposinsert  = ["id","id_cliente","link","shortlink"];
    };
 }

 
function configuracion_delete($tabla,&$tablasenum , &$camposwhere){
   
    $tablasenum = ["cliente", "color", "modelo", "productos", "proveedor", "tipo"];

    if ($tabla === 'cliente') {
        $camposwhere = ["id"];
    };

    if ($tabla === 'color') {
        $camposwhere = ["id"];
    };

    if ($tabla === 'modelo') {
        $camposwhere = ["id"];
    };

    if ($tabla === 'productos') {
        $camposwhere = ["id"];
    };

    if ($tabla === 'proveedor') {
        $camposwhere = ["id"];
    };

    if ($tabla === 'tipo') {
        $camposwhere = ["id"];
    };

    if ($tabla === 'expo') {
        $camposwhere = ["id"];
    };
    
 }

class TABLAS {
    
    public static function dameProcedimiento($mensaje) {
 
       // preparamos los parametros
         $lcparametros =  '' ;
         if (array_key_exists('parametros',$mensaje) ) {
            
             foreach ($mensaje["parametros"] as $parametro) {

                 $valor = '?'; 
                 $lccampo = $parametro['campo'];
                 $lccampo = htmlentities( $lccampo);
                 $lccampo = '@'. $lccampo;

                 if ( $lcparametros != '' ) {
                    $lcparametros =  $lcparametros .',';
                 } 
                 $lcparametros = $lcparametros . ' ' . $lccampo . ' := ' .  $valor ;
             }
         }
 
 
        return  ' CALL '. $mensaje["procedimiento"] . '('  . $lcparametros . ')';
 
    }
    public static function dameSelect($mensaje,$selectenum)   {
        
       // preparamos la select           
        $lcselect =  '' ;
        $lctablaalias ='' ;
        if (array_key_exists('tablaalias', $mensaje)) {
            $lctablaalias = $mensaje["tablaalias"] ;
            $lctablaalias= htmlentities($lctablaalias);
        } ;

        if (array_key_exists('select', $mensaje)) {
            $lbtodos = false;
            foreach ($mensaje["select"] as $campo) {
                $campo = htmlentities( $campo);
                if ($campo === '*') {
                    $lbtodos = true;
                }
                if ($lctablaalias != '') {
                    $campo =  $lctablaalias .'.' . $campo;
                }

                if ($lcselect) {
                    $lcselect = $lcselect . ',';
                };
                $lcselect = $lcselect . $campo;
            }

            if ($lbtodos)  {
                $lcselect =  '' ;
                foreach ($selectenum as $campo) {
                    if ($lcselect) {
                        $lcselect = $lcselect . ',';
                    };
                    if ($campo !== '*') {
                        if ($lctablaalias != '') {
                            $campo =  $lctablaalias .'.' . $campo;
                        }
                        $lcselect = $lcselect . $campo;    
                    }
                }

            }
        } 

      // preparamos la where      
        $lcwhere =  '' ;
        if (array_key_exists('where',$mensaje) ) {
           
            foreach ($mensaje["where"] as $where) {
                $operador = 'and';  
                  if (array_key_exists('operador', $where)) {
                       $operador = $where['operador'];
                  } else {
                       $operador = ' and ';
                  };
                if (!$lcwhere) {
                    $operador = ' ';
                }; 

                $valor =  $where['valor'];
                $valor = htmlentities( $valor);
                $tipo =  $where['tipo'];
                $tipo = htmlentities( $tipo);                
                $lccampo = $where['campo'];
                $lccampo = htmlentities( $lccampo); 
                if ($lctablaalias != '') {
                    $lccampo =  $lctablaalias .'.' . $lccampo;
                }

   

                $lccondicion =  $where['condicion'];
                $valor2 = '?';
                if ($lccondicion == 'like') {
                    $separador = "'";
                    $valor2 = 'UPPER(' .  $separador . '%'.$valor.'%'.  $separador .')';
                    $separador = "";
                    $lccampo = 'UPPER(' . $lccampo .')';
                } 
                if ($lccondicion == 'in') {
                    $valor2 = ' (' . $valor  .')';
                } 
     

                $lcwhere = $lcwhere .' ' . $operador . ' ' . $lccampo . ' ' . $where['condicion']  . ' ' . $valor2 ;
            }
        }
      // preparamos el order      
       $lcorder = '' ;          
       if (array_key_exists('order', $mensaje)) {
         
            foreach ($mensaje["order"] as $campoaux1) {
                $campo = $campoaux1["campo"];
                $campo = htmlentities( $campo); 
                if ($lctablaalias != '') {
                    $campo =  $lctablaalias .'.' . $campo;
                }  
                $sentido = '';
                if (array_key_exists('sentido', $campoaux1)) {
                    $sentido = ' ' . $campoaux1['sentido'];
               } 
                if ($lcorder) {
                    $lcorder = $lcorder . ',';
                };
                $lcorder = $lcorder . $campo . $sentido;
                }
        }
        // preparamos el paginado
        $lclimit =  '' ;
        if (array_key_exists('paginado', $mensaje)) {        
            if ($mensaje["paginado"]) {
                $lclimit = ' limit ' . $mensaje["paginado"]["registros_pagina"] . ' offset  '. ($mensaje["paginado"]["registros_pagina"] * $mensaje["paginado"]["numero_pagina"]);
                    }
         } 
        
      //////////////////////////////

       
        if ($lcwhere) {
            $lcwhere = ' where ' . $lcwhere;
        }

        if ($lcorder) {
            $lcorder = ' order by ' . $lcorder;
        }            
      ////////////////// preparamos los on
      $camposjoin = ''; 
      $sentejoin = '';

      if (array_key_exists('join', $mensaje)) {
       
        foreach ($mensaje["join"] as $join) {
            $joinaux=' '; 
            $lcwhereJointabla = $join["tabla"];
            $lcwhereJointipoJoin = $join["tipoJoin"];
            $lcwhereJointablaalias = '';
            if (array_key_exists('tablaalias', $join)) {
                $lcwhereJointablaalias= $join["tablaalias"];
            }
            if ($joinaux!=' ') {
                $joinaux = $joinaux . ',' ;
            }
            
            $joinaux =  $joinaux . $lcwhereJointipoJoin . ' ' . $lcwhereJointabla . ' ' . $lcwhereJointablaalias;
            
            if (array_key_exists('campos', $join))  {
                foreach ($join["campos"] as $campos) {
                    $campoaux =  $campos['campo'];
                    if (array_key_exists('alias', $campos)) {
                        $campoaux = $campoaux . ' as ' . $campos['alias'];
                    }
                    if ($camposjoin) {
                        $camposjoin = $camposjoin . ',';
                    };
                    if ( $lcwhereJointablaalias){
                        $campoaux = $lcwhereJointablaalias . '.' . $campoaux ;
                    }
                    $camposjoin = $camposjoin . $campoaux;
                }    
            }
            if (array_key_exists('agregados', $join))  {
                foreach ($join["agregados"] as $campos) {
                    $operacion=  $campos['operacion'];
                    ////
                    if ($operacion  === 'count') {
                            $campoaux = '*' ;
                        $campoaux = 'count(' .$campoaux.  ')';
                    };
                    ///
                    if (array_key_exists('alias', $campos)) {
                        $campoaux = $campoaux . ' as ' . $campos['alias'];
                    }
                    if ($camposjoin) {
                        $camposjoin = $camposjoin . ',';
                    };
                    $camposjoin = $camposjoin . $campoaux;
                }    
            }

            $parteon = ' ';
            if (array_key_exists('on', $join))  {
                foreach ($join["on"] as $on) {
                    $campo1 =  $on['campo1'];
                    if ($lctablaalias !== '' )  {
                        $campo1 = $lctablaalias .'.'. $campo1;
                    }
                    $campo2 =  $on['campo2'];      
                    if ($lcwhereJointablaalias !== '' )  {
                        $campo2 = $lcwhereJointablaalias .'.'. $campo2;
                    }
                    $condicion =  $on['condicion'];                                        
                    $operador = 'and';
                    if (array_key_exists('operador', $on)) {
                        $operador =  $on['operador'];  
                    }

                    if ( $parteon==' ') {
                        $operador = ''; 
                    }
                    $parteon = $parteon . ' ' . $operador . ' '  . $campo1 .  $condicion .  $campo2; 
                }    
            } 

            ///////////////////////////
            $lcwherejoin =  ' ' ;
            if (array_key_exists('where',$join) ) {
           
            foreach ($join["where"] as $wherejo) {
                $operador1 = 'and';  
                  if (array_key_exists('operador', $wherejo)) {
                       $operador1 = $wherejo['operador'];
                  } else {
                       $operador1 = ' and ';
                  };
                if ($lcwherejoin == ' ') {
                    $operador1 = ' ';
                }; 

                $valor1 =  $wherejo['valor'];
                $valor1 = htmlentities( $valor1);
                $lccampo1 = $wherejo['campo'];
                $lccampo1 = htmlentities( $lccampo1); 
                if ($lcwhereJointablaalias != '') {
                    $lccampo1 =  $lcwhereJointablaalias .'.' . $lccampo1;
                }

                $lccondicion1 =  $wherejo['condicion'];
                $valor2 = '?';
                if ($lccondicion1 == 'like') {
                    $separador1 = "'";
                    $valor2 = 'UPPER(' .  $separador1 . '%'.$valor1.'%'.  $separador1 .')';
                    $separador1 = "";
                    $lccampo = 'UPPER(' . $lccampo1 .')';
                } 
                if ($lccondicion1 == 'in') {
                    $valor2 = ' (' . $valor1  .')';
                } 

                $lcwherejoin = $lcwherejoin .' ' . $operador1 . ' ' . $lccampo1 . ' ' . $wherejo['condicion']  . ' ' . $valor2 ;
            }
        }
            //////////////////////////
         if ($sentejoin !== ''){
             $sentejoin = $sentejoin . ' ';
          } else {
          }
          if ( ($parteon !== ' ') && ( $lcwherejoin != ' ') ){
            $lcwherejoin = ' and '. $lcwherejoin;
          }
          $sentejoin = $sentejoin . $joinaux .' on ' .$parteon . $lcwherejoin;

        } 
     }
     $lcdistin ='';
     if (array_key_exists('distinctSelect', $mensaje)) {
         $lcdistin = 'distinct ';
     }
     if ($lcselect) {
             if ($camposjoin) {
                $camposjoin = ',' . $camposjoin;
             }
            $lcselect = 'select ' . $lcdistin . $lcselect . $camposjoin ;
        } else  {
            $lcselect = 'select ' .$lcdistin . $camposjoin ;
        }

  // whereExt la where      
  $lcwhereExt =  '' ;
  if (array_key_exists('whereExt',$mensaje) ) {
     
      foreach ($mensaje["whereExt"] as $whereExt) {
          $accion =  $whereExt['accion'];
          
          if ($accion  == 'campo') {
             $campo =  $whereExt['campo'];
             $campo = htmlentities( $campo);
             if ($lctablaalias != '') {
                $campo =  $lctablaalias .'.' . $campo;
            }
             $lcwhereExt= $lcwhereExt .$campo;
             continue;
             
          }
          if ($accion  == 'valor') {
             $valor =  $whereExt['valor'];
             $lcwhereExt= $lcwhereExt .'?';
             continue;
          }
          $lcwhereExt = $lcwhereExt .  ' ' . $accion . ' ';
      }
   }        
  
        $selectconst[] =  $lcselect;
        $selectconst[] =  ' from ' . $mensaje["tabla"] . ' ' . $lctablaalias;
        $selectconst[] =  $sentejoin;
        $selectconst[] =  $lcwhere; 
        $selectconst[] =  $lcwhereExt;         
        $selectconst[] = $lcorder . $lclimit;
         return $selectconst;          

    }
    public static function dameUpdate($mensaje) {
         // preparamos el set
         $lcset  ='';
         if (array_key_exists('update',$mensaje) ) {
            
            foreach ($mensaje["update"] as $where) {

                //$valor =  $where['valor'];
                //$valor = htmlentities( $valor);
                $valor = '?';
                $tipo =  $where['tipo'];
                $tipo = htmlentities( $tipo);                
                $lccampo = $where['campo'];
                $lccampo = htmlentities( $lccampo); 

                if($lcset) {
                    $lcset = $lcset .',';
                }
                $lcset = $lcset .' ' . $lccampo . ' = ' . $valor ;
            }
        }

       // preparamos la where      
         $lcwhere =  '' ;
         if (array_key_exists('where',$mensaje) ) {
            
             foreach ($mensaje["where"] as $where) {
                 $operador = 'and';  
                   if (array_key_exists('operador', $where)) {
                        $operador = $where['operador'];
                   } else {
                        $operador = ' and ';
                   };
                 if (!$lcwhere) {
                     $operador = ' ';
                 }; 
 
                 $valor =  $where['valor'];
                 $valor = htmlentities( $valor);
                 $tipo =  $where['tipo'];
                 $tipo = htmlentities( $tipo);                
                 $lccampo = $where['campo'];
                 $lccampo = htmlentities( $lccampo); 
 
   
                 $lccondicion =  $where['condicion'];
                 if ($lccondicion == 'like') {
                     $separador = "'";
                     $valor = 'UPPER(' .  $separador . '%'.$valor.'%'.  $separador .')';
                     $separador = "";
                     $lccampo = 'UPPER(' . $lccampo .')';
                 } else {
                     $valor = '?';
                 }
 
          
 
                 $lcwhere = $lcwhere .' ' . $operador . ' ' . $lccampo . ' ' . $where['condicion']  . ' ' . $valor ;
             }
         }
       
       //////////////////////////////
  
       
      
        return 'UPDATE ' . $mensaje["tabla"] 
           . ' SET ' . $lcset
           . ' WHERE ' . $lcwhere;
 
    }  
    public static function dameDelete($mensaje) {
        // preparamos el set
        $lcset  ='';

      // preparamos la where      
        $lcwhere =  '' ;
        if (array_key_exists('where',$mensaje) ) {
           
            foreach ($mensaje["where"] as $where) {
                $operador = 'and';  
                  if (array_key_exists('operador', $where)) {
                       $operador = $where['operador'];
                  } else {
                       $operador = ' and ';
                  };
                if (!$lcwhere) {
                    $operador = ' ';
                }; 

                $valor =  $where['valor'];
                $valor = htmlentities( $valor);
                $tipo =  $where['tipo'];
                $tipo = htmlentities( $tipo);                
                $lccampo = $where['campo'];
                $lccampo = htmlentities( $lccampo); 

 

                $lccondicion =  $where['condicion'];
                if ($lccondicion == 'like') {
                    $separador = "'";
                    $valor = 'UPPER(' .  $separador . '%'.$valor.'%'.  $separador .')';
                    $separador = "";
                    $lccampo = 'UPPER(' . $lccampo .')';
                } else {
                    $valor = '?';
                }

         

                $lcwhere = $lcwhere .' ' . $operador . ' ' . $lccampo . ' ' . $where['condicion']  . ' ' . $valor ;
            }
        }
     
      //////////////////////////////
 
      
     
       return 'DELETE FROM ' . $mensaje["tabla"] . ' WHERE ' . $lcwhere;
   } 
    public static function dameInsert($mensaje) {

      // preparamos la where      
        $lcinsert =  '' ;
        $lcvalores = '';
        if (array_key_exists('insert',$mensaje) ) {
           
            foreach ($mensaje["insert"] as $where) {

                $tipo =  $where['tipo'];
                $tipo = htmlentities( $tipo);                
                $lccampo = $where['campo'];
                $lccampo = htmlentities( $lccampo); 

                if ($lcinsert) {
                    $lcinsert =  $lcinsert . ',';
                    $lcvalores = $lcvalores . ',';
                };
                $lcinsert  = $lcinsert  .  $lccampo ;
          
                $lcvalores = $lcvalores . '?'  ;
            }
        }
      
      //////////////////////////////
 
      
     
       return 'INSERT INTO ' . $mensaje["tabla"] 
          . '(' . $lcinsert .')'
          . ' VALUES  (' .$lcvalores . ')';

    }  

    public static function switchAccion($mensaje){

        $bd = ConectarDB::singleton();

        $v_resp["cod"] = "1";
        $v_resp["msj"] = "Error no definido";
 

        //////////////////////////////////////
        switch($mensaje["accion"]) {
            case "get":  
                $tablasenum ="";
                $condicionenum =  ["=","<>",">","<","like","in"];
                $operadorenum =  ["and","or"];
                $tiposwhereenum =["n","s","d"];
                $selectenum = ["*"];
                $camposwhere = [""];
                $orderenum =[""];
                $groupbyenum = [""];

                $selectJoinTabla = [""];
                $aliascamposJoin =[""];  
                $selectenumJoin= [""];
                $tipoJoin = ["INNER JOIN","LEFT JOIN","RIGHT JOIN","OUTER JOIN"];
                $tablaalias= [""];
                $camposOnJoin= [""];
                $camposwhereJoin=[""];
                $agregados=["count"];   
                $accioenum =["and","or","(","campo","=","<>",">","<","valor",")"];        
        
                configuracion_tabla($mensaje["tabla"],$tablasenum ,$selectenum,$camposwhere,$orderenum,$groupbyenum,
                                   $selectJoinTabla,$selectenumJoin, $tablaalias,$camposOnJoin, $camposwhereJoin,$aliascamposJoin   );

                $schema = [
                    "type"=>"object",
                    "properties"=>[
                        "id_administrador"=>[
                            "description"=>"Id del administrador",
                            "type"=>"integer"
                         ],
                         "tabla"=>[
                            "type" => "string",
                            "description"=>"Id de la aplicacion",
                            "enum" => $tablasenum
                        ],
                        "paginado"=> [
                            "type" => "object",
                            "properties"=> [
                               "registros_pagina" => [
                                    "type" => "number"
                               ],
                            "numero_pagina"=> [
                                    "type" => "number"
                             ]              
                            ],
                            "required"=>["registros_pagina","numero_pagina"]                
                        ],
                        "tablaalias" => [
                            "type" => "string",
                            "enum" => $tablaalias
                        ],                        
                        "select" => [
                                "type" => "array",
                                "items" => [
                                    "type" => "string",
                                    "enum" => $selectenum
                                ],
                        ],
                        "where" => [
                          "type"=>"array",
                          "items" => [
                            "type" => "object",
                            "properties" => [
                                    "operador"=>[
                                        "description"=>"operacion del where",
                                        "type"=>"string",
                                        "enum" => $operadorenum
                                    ],
                                    "campo"=>[
                                        "description"=>"campo del where",
                                        "type"=>"string",
                                        "enum" => $camposwhere
                                    ],
                                    "tipo"=>[
                                        "description"=>"tipo campo",
                                        "type"=>"string",
                                        "enum" => $tiposwhereenum
                                    ],
                                    "condicion"=>[
                                        "description"=>"operacion a realizar",
                                        "type"=>"string",
                                        "enum" => $condicionenum
                                    ],
                                    "valor"=>[
                                        "description"=>"valor del campo where",
                                        "type"=>"string"
                                    ],
                                ], 
                          "required"=>["campo","condicion","valor"]                                   
                         ],
                        ],
                        "whereExt" => [
                            "type"=>"array",
                            "items" => [
                              "type" => "object",
                              "properties" => [
                                      "accion"=>[
                                          "description"=>"operacion del where",
                                          "type"=>"string",
                                          "enum" => $accioenum
                                      ],
                                      "valor"=>[
                                          "description"=>"valor del where",
                                          "type"=>"string",
                                      ],
                                      "campo"=>[
                                        "description"=>"campo del where",
                                        "type"=>"string",
                                        "enum" => $camposwhere
                                      ],
                                  ], 
                            "required"=>["accion"]                                   
                           ],
                          ],                        
                        "join" => [
                            "type" => "array",
                            "items" => [
                                "type" => "object",
                                "properties" => [
                                        "tabla"=>[
                                            "description"=>".",
                                            "type"=>"string",
                                            "enum" => $selectJoinTabla
                                        ],
                                        "tablaalias"=>[
                                            "description"=>".",
                                            "type"=>"string",
                                            "enum" => $tablaalias
                                        ],
                                        "tipoJoin"=>[
                                            "description"=>".",
                                            "type"=>"string",
                                            "enum" => $tipoJoin
                                        ],
                                        "campos" => [
                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "campo"=>[
                                                        "description"=>".",
                                                        "type"=>"string",
                                                        "enum" => $selectenumJoin
                                                    ], 
                                                    "alias"=>[
                                                        "description"=>".",
                                                        "type"=>"string",
                                                        "enum" => $aliascamposJoin
                                                    ],
                                               
                                                ], 
                                               "required"=>["campo"]       
                                            ],
                                        ], 
                                        "agregados"  =>[

                                            "type" => "array",
                                            "items" => [
                                                "type" => "object",
                                                "properties" => [
                                                    "campo"=>[
                                                        "description"=>".",
                                                        "type"=>"string",
                                                        "enum" => $selectenumJoin
                                                    ], 
                                                    "operacion"=>[
                                                        "description"=>".",
                                                        "type"=>"string",
                                                        "enum" => $agregados
                                                    ], 
                                                    "alias"=>[
                                                        "description"=>".",
                                                        "type"=>"string",
                                                        "enum" => $aliascamposJoin
                                                    ],
                                               
                                                ], 
                                               "required"=>["operacion"]       
                                            ],
                                        ],
                                        "on" => [
                                            "type"=>"array",
                                            "items" => [
                                              "type" => "object",
                                              "properties" => [
                                                      "operador"=>[
                                                          "description"=>"operacion del where",
                                                          "type"=>"string",
                                                          "enum" => $operadorenum
                                                      ],
                                                      "campo1"=>[
                                                        "description"=>"campo del where",
                                                        "type"=>"string",
                                                        "enum" => $camposwhere
                                                      ],
                                                      "condicion"=>[
                                                          "description"=>"operacion a realizar",
                                                          "type"=>"string",
                                                          "enum" => $condicionenum
                                                      ],
                                                      "campo2"=>[
                                                        "description"=>"campo del where",
                                                        "type"=>"string",
                                                        "enum" => $camposOnJoin
                                                      ],
                                                  ], 
                                             "required"=>["campo1","condicion","campo2"]                                   
                                           ],
                                          ],

                                        "where" => [
                                            "type"=>"array",
                                            "items" => [
                                              "type" => "object",
                                              "properties" => [
                                                      "operador"=>[
                                                          "description"=>"operacion del where",
                                                          "type"=>"string",
                                                          "enum" => $operadorenum
                                                      ],
                                                      "campo"=>[
                                                          "description"=>"campo del where",
                                                          "type"=>"string",
                                                          "enum" => $camposwhereJoin
                                                      ],
                                                      "condicion"=>[
                                                          "description"=>"operacion a realizar",
                                                          "type"=>"string",
                                                          "enum" => $condicionenum
                                                      ],
                                                      "valor"=>[
                                                          "description"=>"valor del campo where",
                                                          "type"=>"string"
                                                      ],
                                                  ], 
                                               "required"=>["campo","condicion","valor"]                                   
                                           ],
                                          ],
                                    ],
                                 "required"=>["tabla","tipoJoin","on"]       
                          ],
                        ],
                        "order" =>[
                            "type" => "array",
                            "items" => [
                                "type" => "object",
                                "properties" => [
                                    "campo"=>[
                                        "description"=>".",
                                        "type"=>"string",
                                        "enum" => $orderenum
                                    ], 
                                    "sentido"=>[    
                                        "description"=>".",
                                        "type"=>"string",
                                        "enum" => ['ASC','DESC']
                                    ],
                               
                                ], 
                               "required"=>["campo"]       
                            ],
                        ],
                        "groupby" => [
                            "type" => "array",
                            "items" => [
                                "type" => "string",
                                "enum" => $groupbyenum
                            ],
                        ],

                    ],
                "required"=>["tabla"]                       
                ];           
                
                list($ok, $msg, $data) = JSONHelper::validate($mensaje, $schema);
                if (!$ok){
                    $resp = ["cod"=>1,"statusCode"=>5, "status"=>"error", "message"=>$msg, "data"=>$data]; 
                    return $resp;
                } else {
                    $select =  TABLAS::dameSelect($mensaje,$selectenum);

                    $data = $bd->datos_bd_select(implode(" ", $select),$mensaje,'select');
                    $data = [
                        "cod" => 0,
                        "msj" => "OK",
                        "datos" => $data
                        ];
                }
                return $data;
            break;
            case "getProcedure":  
                $tiposwhereenum =["n","s","d"];
                configuracion_procedimiento($mensaje["procedimiento"],$procedurenum,$parametrospro) ;
                $schema = [
                    "type"=>"object",
                    "properties"=>[
                        "id_administrador"=>[
                            "description"=>"Id del administrador",
                            "type"=>"integer"
                         ],
                         "idAplicacion"=>[
                            "description"=>"Id de aplicacion",
                            "type"=>"integer"
                         ],                         
                         "procedimiento"=>[
                            "type" => "string",
                            "description"=>"Id de la aplicacion",
                            "enum" => $procedurenum
                        ],
                        "parametros" => [
                          "type"=>"array",
                          "items" => [
                            "type" => "object",
                            "properties" => [
                                    "campo"=>[
                                        "description"=>"campo",
                                        "type"=>"string",
                                        "enum" => $parametrospro
                                    ],
                                    "tipo"=>[
                                        "description"=>"tipos",
                                        "type"=>"string",
                                        "enum" => $tiposwhereenum
                                    ],
                                    "valor"=>[
                                        "description"=>"valor del campo where",
                                        "type"=>"string"
                                    ],
                                ], 
                          "required"=>["campo","tipo","valor"]                                   
                         ],
                        ],
                    ],
                "required"=>["procedimiento"]                       
                ];           
                
                list($ok, $msg, $data) = JSONHelper::validate($mensaje, $schema);
                if (!$ok){
                    $resp = ["cod"=>1,"statusCode"=>5, "status"=>"error", "message"=>$msg, "data"=>$data]; 
                    return $resp;
                } else {
                    $select =  TABLAS::dameProcedimiento($mensaje,$parametrospro);

                    $data = $bd->datos_bd_select($select,$mensaje,'select');
                    $data = [
                        "cod" => 0,
                        "msj" => "OK",
                        "datos" => $data
                        ];
                }
                return $data;
            break;
            case "update":  
                $tablasenum ="";
                $condicionenum =  ["=","<>",">","<","like"];
                $operadorenum =  ["and","or"];
                $tiposwhereenum =["n","s","d"];
                $camposupdate = [""];
                $camposwhere = [""];
        
                configuracion_update($mensaje["tabla"],$tablasenum ,$camposupdate,$camposwhere);

                $schema = [
                    "type"=>"object",
                    "properties"=>[
                        "id_administrador"=>[
                            "description"=>"Id del administrador",
                            "type"=>"integer"
                         ],
                         "tabla"=>[
                            "type" => "string",
                            "description"=>"Id de la aplicacion",
                            "enum" => $tablasenum
                        ],
                        "update" => [
                            "type"=>"array",
                            "items" => [
                              "type" => "object",
                              "properties" => [
                                      "campo"=>[
                                          "description"=>"campo del updates",
                                          "type"=>"string",
                                          "enum" => $camposupdate
                                      ],
                                      "tipo"=>[
                                          "description"=>"tipo campo",
                                          "type"=>"string",
                                          "enum" => $tiposwhereenum
                                      ],
                                      "valor"=>[
                                          "description"=>"valor del campo where",
                                          "type"=>"string"
                                      ],
                                  ], 
                            "required"=>["campo","valor"]                                   
                           ],
                          ],                        
                        "where" => [
                          "type"=>"array",
                          "items" => [
                            "type" => "object",
                            "properties" => [
                                    "operador"=>[
                                        "description"=>"operacion del where",
                                        "type"=>"string",
                                        "enum" => $operadorenum
                                    ],
                                    "campo"=>[
                                        "description"=>"campo del where",
                                        "type"=>"string",
                                        "enum" => $camposwhere
                                    ],
                                    "tipo"=>[
                                        "description"=>"tipo campo",
                                        "type"=>"string",
                                        "enum" => $tiposwhereenum
                                    ],
                                    "condicion"=>[
                                        "description"=>"operacion a realizar",
                                        "type"=>"string",
                                        "enum" => $condicionenum
                                    ],
                                    "valor"=>[
                                        "description"=>"valor del campo where",
                                        "type"=>"string"
                                    ],
                                ], 
                          "required"=>["campo","condicion","valor"]                                   
                         ],
                        ],
                    ],
                "required"=>["tabla","update","where"]                       
                ];           
                
                list($ok, $msg, $data) = JSONHelper::validate($mensaje, $schema);
                if (!$ok){
                    $resp = ["cod"=>1,"statusCode"=>5, "status"=>"error", "message"=>$msg, "data"=>$data]; 
                    return $resp;
                } else {
                    $select =  TABLAS::dameUpdate($mensaje);

                    $data = $bd->datos_bd_select($select,$mensaje,'update');
                     }
                return $data;
            break;
            case "insert":  
                $tablasenum ="";
                $tiposwhereenum =["n","s","d"];
                $camposinsert = [""];
        
                configuracion_insert($mensaje["tabla"],$tablasenum ,$camposinsert);
                $schema = [
                    "type"=>"object",
                    "properties"=>[
                        "id_administrador"=>[
                            "description"=>"Id del administrador",
                            "type"=>"integer"
                         ],
                         "tabla"=>[
                            "type" => "string",
                            "description"=>"tabla a realizar el insert",
                            "enum" => $tablasenum
                        ],
                        "insert" => [
                            "type"=>"array",
                            "items" => [
                              "type" => "object",
                              "properties" => [
                                      "campo"=>[
                                          "description"=>"campo del insert",
                                          "type"=>"string",
                                          "enum" => $camposinsert
                                      ],
                                      "tipo"=>[
                                          "description"=>"tipo campo",
                                          "type"=>"string",
                                          "enum" => $tiposwhereenum
                                      ],
                                      "valor"=>[
                                          "description"=>"valor del campo insert",
                                          "type"=>"string"
                                      ],
                                  ], 
                            "required"=>["campo","valor"]                                   
                           ],   
                          ],                        
                    ],
                "required"=>["tabla","insert"]                       
                ];           
                
                list($ok, $msg, $data) = JSONHelper::validate($mensaje, $schema);
                if (!$ok){
                    $resp = ["cod"=>1,"statusCode"=>5, "status"=>"error", "message"=>$msg, "data"=>$data]; 
                    return $resp;
                } else {
                    $select =  TABLAS::dameInsert($mensaje);
                    $data = $bd->datos_bd_select($select,$mensaje,'insert');
                }
                return $data;
            break;
            case "delete":  
                $tablasenum ="";
                $condicionenum =  ["=","<>",">","<","like"];
                $operadorenum =  ["and","or"];
                $tiposwhereenum =["n","s","d"];
                $camposwhere = [""];
        
                configuracion_delete($mensaje["tabla"],$tablasenum ,$camposwhere);

                $schema = [
                    "type"=>"object",
                    "properties"=>[
                        "id_administrador"=>[
                            "description"=>"Id del administrador",
                            "type"=>"integer"
                         ],
                         "tabla"=>[
                            "type" => "string",
                            "description"=>"Id de la aplicacion",
                            "enum" => $tablasenum
                        ],
                        "where" => [
                          "type"=>"array",
                          "items" => [
                            "type" => "object",
                            "properties" => [
                                    "operador"=>[
                                        "description"=>"operacion del where",
                                        "type"=>"string",
                                        "enum" => $operadorenum
                                    ],
                                    "campo"=>[
                                        "description"=>"campo del where",
                                        "type"=>"string",
                                        "enum" => $camposwhere
                                    ],
                                    "tipo"=>[
                                        "description"=>"tipo campo",
                                        "type"=>"string",
                                        "enum" => $tiposwhereenum
                                    ],
                                    "condicion"=>[
                                        "description"=>"operacion a realizar",
                                        "type"=>"string",
                                        "enum" => $condicionenum
                                    ],
                                    "valor"=>[
                                        "description"=>"valor del campo where",
                                        "type"=>"string"
                                    ],
                                ], 
                          "required"=>["campo","condicion","valor"]                                   
                         ],
                        ],
                    ],
                "required"=>["tabla","where"]                       
                ];           
                
                list($ok, $msg, $data) = JSONHelper::validate($mensaje, $schema);
                if (!$ok){
                    $resp = ["cod"=>1,"statusCode"=>5, "status"=>"error", "message"=>$msg, "data"=>$data]; 
                    return $resp;
                } else {
                    $select =  TABLAS::dameDelete($mensaje);

                    $data = $bd->datos_bd_select($select,$mensaje,'delete');
                     }
                return $data;
            break;
            default:
                $v_resp=[
                    "cod" => 1,
                    "msj" => "Acción no definida"
                ];
                return $v_resp;
            break;
        }
    }
}
?>