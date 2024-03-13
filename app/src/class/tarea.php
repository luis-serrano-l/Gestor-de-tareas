<?php
    class Tarea {
        private $id;
        private $descripcion;
        private $completada;

        private $usuario_id;

        public function __construct($descripcion, $usuario_id = null) {
            $this->descripcion = $descripcion;
            $this->usuario_id = $usuario_id;
            $this->completada = 0;
        }

        public function getDescripcion() {
            return $this->descripcion;
        }

        public function getUsuario_id() {
            return $this->usuario_id;
        }

        public function getCompletada() {
            return $this->completada;
        }
    }