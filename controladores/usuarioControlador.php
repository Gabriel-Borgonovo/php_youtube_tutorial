<?php

    if($peticionAjax){
        require_once "../modelos/usuarioModelo.php";
    }else{
        require_once "./modelos/usuarioModelo.php";
    }

    class usuarioControlador extends usuarioModelo{
        /**------Controlador agregar usuario-------------- */
        public function agregar_usuario_controlador(){
            $dni=mainModel::limpiar_cadena($_POST['usuario_dni_reg']);
            $nombre=mainModel::limpiar_cadena($_POST['usuario_nombre_reg']);
            $apellido=mainModel::limpiar_cadena($_POST['usuario_apellido_reg']);
            $telefono=mainModel::limpiar_cadena($_POST['usuario_telefono_reg']);
            $direccion=mainModel::limpiar_cadena($_POST['usuario_direccion_reg']);

            $usuario=mainModel::limpiar_cadena($_POST['usuario_usuario_reg']);
            $email=mainModel::limpiar_cadena($_POST['usuario_email_reg']);
            $clave1=mainModel::limpiar_cadena($_POST['usuario_clave_1_reg']);
            $clave2=mainModel::limpiar_cadena($_POST['usuario_clave_2_reg']);
            
            $privilegio=mainModel::limpiar_cadena($_POST['usuario_privilegio_reg']);

            //--------Comprobar campos vacios-------------
            

            if($dni=="" || $nombre=="" || $apellido=="" || $usuario=="" || $clave1=="" || $clave2==""){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No has llenado todos los campos que son obligatorios",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            //--------Verificando integridad de los datos-------------
            
            if(mainModel::verificar_datos("[0-9-]{8,20}",$dni)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El DNI no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}",$nombre)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El NOMBRE no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}",$apellido)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El APELLIDO no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if($telefono!=""){
                if(mainModel::verificar_datos("[0-9()+]{8,20}",$telefono)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El TELEFONO no coincide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            if($direccion!=""){
                if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$direccion)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"La DIRECCIÓN no coincide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El NOMBRE DE USUARIO no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave1) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave2) ){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"Las CLAVES no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            //---Comprobando DNI------
            $check_dni=mainModel::ejecutar_consulta_simple("SELECT usuario_dni FROM usuario WHERE usuario_dni='$dni'");

            if($check_dni->rowCount()>0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El DNI ingresado ya se encuentra registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            //---Comprobando usuario------
            $check_user=mainModel::ejecutar_consulta_simple("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");

            if($check_user->rowCount()>0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El NOMBRE DE USUARIO ingresado ya se encuentra registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            //---Comprobando email------

            if($email!=""){
                if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                    $check_email=mainModel::ejecutar_consulta_simple("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");

                    if($check_email->rowCount()>0){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El EMAIL ingresado ya se encuentra registrado en el sistema",
                            "Tipo"=>"error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                }else{
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"Ha ingresado un correo no valido",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }


            //---Comprobando que las dos passwords sean iguales

        
        }//---Fin controlador
    }

?>