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

                $loginUser = $login["usuario"];
                $usuario = new Usuario();
                if ($usuario->find_first("login = '$loginUser' AND estado = 'A'")) {
                    $hash = $usuario->password;
                    $valid = false;
                    
                    // Check if it's bcrypt (starts with $2a$, $2b$, $2y$ or $2x$) - 60 chars
                    if (strlen($hash) === 60 && strpos($hash, '$2') === 0) {
                        $valid = password_verify($login["password"], $hash);
                    } 
                    // Check if it's SHA1 (40 hex chars) - legacy
                    elseif (strlen($hash) === 40 && ctype_xdigit($hash)) {
                        if (sha1($login["password"]) === $hash) {
                            $usuario->password = password_hash($login["password"], PASSWORD_BCRYPT);
                            $usuario->update();
                            $valid = true;
                        }
                    }
                    // Check for argon2 or other modern hashes
                    elseif (strpos($hash, '$argon') !== false) {
                        $valid = password_verify($login["password"], $hash);
                    }
                    
                    if ($valid) {
                        $perfil = new Perfil();
                        $perfil->find_first($usuario->perfil_id);

                        Session::set("id", $usuario->id);
                        Session::set("nombre", $usuario->nombre);
                        Session::set("email", $usuario->email);
                        Session::set("perfil_id", $usuario->perfil_id);
                        Session::set("perfil", $perfil->perfil);
                        Session::set("imagen", $usuario->fotografia);
                        Redirect::to("/admin");
                        return false;
                    }
                }
                Flash::error("Credenciales inválidas");
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
