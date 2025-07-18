<?php

class Conexion {

    /**
     * Establece la conexión con la base de datos y la devuelve.
     * * @return mysqli|false El objeto de conexión si tiene éxito, o false si falla.
     */
    public static function conectar() {
        // --- Parámetros de conexión ---
        // Modifica estos valores si tu configuración es diferente.
        $servidor = "localhost"; // O "localhost"
        $usuario = "root";       // Usuario estándar en entornos locales como XAMPP
        $password = "";          // Contraseña estándar en entornos locales como XAMPP
        $base_de_datos = "proyecto1"; // Nombre de la BD de tu archivo .sql

        // Crear la conexión utilizando MySQLi
        $conexion = new mysqli($servidor, $usuario, $password, $base_de_datos);

        // --- Verificación de la conexión ---
        // Si hay un error, el script se detiene y muestra el problema.
        if ($conexion->connect_error) {
            // En un entorno de producción, es mejor registrar este error en un archivo
            // en lugar de mostrarlo en pantalla por seguridad.
            die("Error de conexión: " . $conexion->connect_error);
        }

        // --- Establecer el juego de caracteres a UTF-8 ---
        // Esto es crucial para evitar problemas con tildes y caracteres especiales.
        if (!$conexion->set_charset("utf8")) {
            // Manejar el error si no se puede establecer el charset
            error_log("Error al establecer el juego de caracteres UTF-8: " . $conexion->error);
        }

        // Devolver el objeto de conexión para ser utilizado en otras partes del sistema
        return $conexion;
    }
}
?>