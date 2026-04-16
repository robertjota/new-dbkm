<?php

/**
 * Helper para respuestas JSON estándar de la API
 *
 * @category    Helpers
 * @package     Helpers
 */

class DwResponse
{
    /**
     * Respuesta exitosa
     *
     * @param string $message
     * @param mixed $data
     * @param string $url
     * @return array
     */
    public static function success($message = 'Operación exitosa', $data = null, $url = null)
    {
        return [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'url' => $url
        ];
    }

    /**
     * Respuesta de error
     *
     * @param string $message
     * @param mixed $errors
     * @return array
     */
    public static function error($message = 'Ha ocurrido un error', $errors = null)
    {
        return [
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ];
    }

    /**
     * Respuesta de validación
     *
     * @param array $errors
     * @return array
     */
    public static function validation($errors)
    {
        return [
            'status' => 'validation',
            'message' => 'Por favor verifique los datos ingresados',
            'errors' => $errors
        ];
    }

    /**
     * Respuesta de información
     *
     * @param string $message
     * @return array
     */
    public static function info($message)
    {
        return [
            'status' => 'info',
            'message' => $message
        ];
    }

    /**
     * Envía la respuesta JSON y termina la ejecución
     *
     * @param array $response
     */
    public static function send($response)
    {
        // Limpiar cualquier output previo
        while (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        View::select(NULL);
        exit;
    }

    /**
     * Envía respuesta exitosa y termina
     */
    public static function sendSuccess($message = 'Operación exitosa', $data = null, $url = null)
    {
        self::send(self::success($message, $data, $url));
    }

    /**
     * Envía respuesta de error y termina
     */
    public static function sendError($message = 'Ha ocurrido un error', $errors = null)
    {
        self::send(self::error($message, $errors));
    }

    /**
     * Envía respuesta de validación y termina
     */
    public static function sendValidation($errors)
    {
        self::send(self::validation($errors));
    }
}
