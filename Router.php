<?php

namespace MVC;

class Router {

    public $rutasGET = [];
    public $rutasPOST = [];

    public function get($url, $fn) {
        $this->rutasGET[$url] = $fn;
        // debuguear($fn);
    }

    public function post($url, $fn) {
        $this->rutasPOST[$url] = $fn;
        // debuguear($fn);
    }

    public function comprobarRutas() {

        session_start();

        $auth = $_SESSION['login'] ?? null;

        // Arreglo de rutas protegidas
        $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', '/vendedores/crear','/vendedores/actualizar', '/vendedores/eliminar'];

 
        $urlActual = ($_SERVER['REQUEST_URI'] === '') ? '/' :  $_SERVER['REQUEST_URI'];
        $metodo = $_SERVER['REQUEST_METHOD'];

        echo "Inutil";
        
        if($metodo === 'GET') {
            $urlActual = explode('?',$urlActual)[0];
            $fn = $this->rutasGET[$urlActual] ?? null;
        }  else {
            $urlActual = explode('?',$urlActual)[0];
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }

        // Proteger las rutas
        if(in_array($urlActual, $rutas_protegidas) && !$auth) {
            header('Location: /');
        }


        if($fn) {
            // La URL existe y hay una funcion asociada
            call_user_func($fn, $this);
        }
        else {
            echo "No se encontro la URL";
        }
    }

    // Muestra una vista 
    public function render($view, $datos = []) {

        foreach($datos as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once __DIR__ . "/views/$view.php";

        $contenido = ob_get_clean();

        include_once __DIR__ . "/views/layout.php";
    }
}