<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Contactanos extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("inicio/correo");
    }

    function contacto()
    {
        $datos_correo = array(
            "nombre" => $this->input->post('nombre'),
            "correo" => $this->input->post('correo'),
            "empresa" => $this->input->post('empresa'),
            "ciudad" => $this->input->post('ciudad'),
            "telefono" => $this->input->post('telefono'),
            "comentarios" => $this->input->post('comentarios'),
        );
        if (
            empty($datos_correo["correo"]) || empty($datos_correo["nombre"]) || empty($datos_correo["ciudad"])
            || empty($datos_correo["empresa"] || empty($datos_correo["telefono"])|| $datos_correo["comentarios"]=="")
        ) {
            $correo = false;
            $respuesta_correo = "Existe un problema en el envio, revise que los campos no esten vacios";
        } else {
            $correo = $this->correo->sendEmail($datos_correo);
            if ($correo) {
                $respuesta_correo = "Su correo ha sido enviado con éxito";
            } else {
                $respuesta_correo = "Existe un problema en el envio, revise que los campos no esten vacios";
            }
        }

        $respuesta = array("error" => !$correo, "respuesta" => $respuesta_correo);
        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
}
