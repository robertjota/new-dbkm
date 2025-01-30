<?php

Load::models("sistema/usuario", "sistema/perfil");
class LoginController extends Controller
{
    public function index()
    {
        // especificamos el template a usar
        View::template("login");
        // Solo si se ha enviado el formulario
        if (Input::hasPost('login')) {
            // recuperamos los datos del formulario para validar acceso
            $login = Input::post('login');

            if (empty($login["usuario"]) && empty($login["password"])) {
                Flash::error("Por favor, introduce tu usuario y tu contraseña");
            } elseif (empty($login["usuario"])) {
                Flash::error("Por favor, introduce tu usuario");
            } elseif (empty($login["password"])) {
                Flash::error("Por favor, introduce tu contraseña");
            } else {

                $usuario = $login["usuario"];
                // en la base de datos el password esta encriptado con md5, desde php se realiza la conversion
                $pwd = md5($login["password"]);

                // seteamos las variables de sesion
                Session::set("usuario", $usuario);
                Session::set("password", $pwd);
                // seteamos las variables de sesion

                // iniciamos el Auth, el modelo se llama Usuario, asi como la tabla
                $auth = new Auth("model", "class: Usuario", "usuario: " . $usuario, "password: " . $pwd);
                if ($auth->authenticate()) {
                    $usuario_data = (new Usuario())->find_first("usuario = '$usuario' AND password = '$pwd'");
                    $perfil = (new Perfil())->find_first("id = '$usuario_data->perfil_id'");

                    // seteamos las variables de sesion
                    Session::set("id", $usuario_data->id);
                    Session::set("nombre", $usuario_data->nombre);
                    Session::set("email", $usuario_data->email);
                    Session::set("perfil_id", $usuario_data->perfil_id);
                    Session::set("perfil", $perfil->perfil);
                    Session::set("imagen", $usuario_data->img_usuario);
                    // Si el usuario es valido, lo mandamos al index
                    // de la aplicacion ya logueado
                    Redirect::to("/admin");
                    return false;
                } else {
                    Flash::error("Credenciales inválidas");
                }
            }
        }
    }

    public function logout()
    {
        View::select(null);
        Auth::destroy_identity();
        Redirect::to("login");
    }
}
