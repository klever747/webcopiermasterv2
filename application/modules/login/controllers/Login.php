
<?php

use Firebase\JWT\JWT;

defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("login/service_login_user");
    }

    /*     * ******************* LOGIN********************* */

    public function loginUser()
    {
        $data = array();
        $routesArray = explode("/", $_SERVER['REQUEST_URI']);
        $data['respuesta_registro'] = false;
        $data['actualizacionUser'] = false;

        if (!empty(array_filter($routesArray)[2])) {
            $urlParams = explode("&", array_filter($routesArray)[2]);
            $data['urlParams'] = $urlParams;
        }
        /*--=====================================
        Validar veracidad del correo electrónico
       ======================================*/
        if (isset($data['urlParams'][2])) {
            $verify = base64_decode($data['urlParams'][2]);
            $verificarUsuario = $this->service_login_user->verificarUsuario($verify);
            if (!empty($verificarUsuario)) {
                /*=============================================
                   Actualizar el campo de verificación
                    =============================================*/
                $datosActualizar = array("id" => $verificarUsuario->id, "verificacion_user" => 1);
                $actualizarUsuario = $this->service_login_user->actualizarVerificacionUsuario($datosActualizar);
                if ($actualizarUsuario) {

                    $data['actualizacionUser'] =  '<div class="alert alert-success text-center">Your account has been verified successfully, you can now login</div>';
                }
            } else {
                $data['actualizacionUser'] = '<div class="alert alert-danger text-center">Failed to verify account, email does not exist</div>';
            }
        }
        $data['login'] = $this->load->view('login_user.php', $data, true);
        $data['enrollment'] = $this->load->view('enrollment_user.php', $data, true);
        $categorias_det = $this->mostrarVista2('account.php', $data, true);
    }
    static function postRegistro()
    {
        $ci = &get_instance();
        $email = $ci->input->post('regEmail');
        $data['respuesta_registro'] = array();
        if (isset($email)) {

            /*=============================================
			Validamos la sintaxis de los campos
			=============================================*/
            $primerNombre = $ci->input->post('regFirstName');
            $segundoNombre = $ci->input->post('regLastName');
            $password_user = $ci->input->post('regPassword');
            if (
                preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $primerNombre) &&
                preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $segundoNombre) &&
                preg_match('/^[^0-9][.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $email) &&
                preg_match('/^[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}$/', $password_user)
            ) {

                $primerNombre = $ci->input->post('regFirstName');
                $segundoNombre = $ci->input->post('regLastName');
                $displayName = $primerNombre . " " . $segundoNombre;
                $email = $ci->input->post('regEmail');
                $username = strtolower($email);
                $username = explode("@", $username)[0];
                $password_user = $ci->input->post('regPassword');
                $data = array(
                    "rol_user" => "default",
                    "imagen_user" => "",
                    "displayname_user" => $displayName,
                    "usuario_user" => $username,
                    "password_user" => "",
                    "email_user" => strtolower($email),
                    "ciudad_user" => "",
                    "pais_user" => "",
                    "telefono_user" => "",
                    "direccion_user" => "",
                    "token_user" => "",
                    "metodo_user" => "direct",
                    "verificacion_user" => "",
                    "wishlist_user" => "",
                    "date_create_user" => date("Y-m-d")
                );
                if (isset($password_user) && $password_user != null) {
                    $cryp =  crypt($password_user, '$2a$07$azybxcags23425sdg23sdfhsd$');
                    $data["password_user"] = $cryp;
                    $registroUsuario = $ci->service_login_user->crearUsuario($data);
                }
                $verificarRespuesta = Login::verificarRespuesta($registroUsuario, $displayName, $email);
                if ($verificarRespuesta) {
                    return $verificarRespuesta;
                }
            } else {

                $data['respuesta_registro'] = '<div class="alert alert-danger">Error in the syntax of the fields</div>

				<script>

					fncFormatInputs()

				</script>

				';
            }
        }
        return $data['respuesta_registro'];
    }
    static function verificarRespuesta($registroUsuario, $displayName, $email)
    {
        if ($registroUsuario) {

            $name = $displayName;
            $subject = "Verify your account";
            $email = $email;
            $message = "We must verify your account so that you can enter our Marketplace";
            $url = base_url() . "account&login&" . base64_encode($email);

            $sendEmail = MY_Controller::sendEmail($name, $subject, $email, $message, $url);

            if ($sendEmail == "ok") {

                $data['respuesta_registro'] = '<div class="alert alert-success">Registered user successfully, confirm your account in your email (check spam)</div>

            <script>

                fncFormatInputs()

            </script>

            ';
                return $data['respuesta_registro'];
            } else {

                $data['respuesta_registro'] = '<div class="alert alert-danger">' . $sendEmail . '</div>

            <script>

                fncFormatInputs()

            </script>

            ';
                return $data['respuesta_registro'];
            }
        }
        return $data['respuesta_registro'] = false;
    }
    static function postLogin()
    {
        $ci = &get_instance();
        if (isset($_POST["loginEmail"])) {

            /*=============================================
			Validamos la sintaxis de los campos
			=============================================*/

            if (
                preg_match('/^[^0-9][.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["loginEmail"]) &&
                preg_match('/^[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}$/', $_POST["loginPassword"])
            ) {

                echo '<script>

					fncSweetAlert("loading", "", "");

				</script>';
                $fields = array(

                    "email_user" => $_POST["loginEmail"],
                    "password_user" => $_POST["loginPassword"],

                );
                /*=============================================
			    Buscar Usuario por el correo y traer los datos
			    =============================================*/
                $buscarUsuario =  $ci->service_login_user->buscarUsuarioLogin($fields);

                /*=============================================
			    Creacion del Token del usuario
			    =============================================*/
                $generarToken = Login::generarTokenUser($buscarUsuario, $fields);
                if ($buscarUsuario) {

                    if ($login->results[0]->verification_user == 1) {

                        $_SESSION["user"] = $login->results[0];

                        echo '<script>

								fncFormatInputs();

								window.location = "' . TemplateController::path() . 'account&wishlist";

							</script>
						';
                    } else {

                        echo '<div class="alert alert-danger">Your account has not been verified yet, please check your email inbox.</div>


							<script>

								fncSweetAlert("close", "", "");
								fncFormatInputs()

							</script>
						';
                    }
                } else {

                    echo '<div class="alert alert-danger">' . $login->results . '</div>

						<script>

							fncSweetAlert("close", "", "");
							fncFormatInputs()

						</script>

					';
                }
            } else {

                echo '<div class="alert alert-danger">Error in the syntax of the fields</div>

					<script>

						fncSweetAlert("close", "", "");
						fncFormatInputs()

					</script>
				';
            }
        }
    }
    /*-------------VALIDAR EL CORREO ELECTRONICO-------- */
    public function validarCorreoElectronico()
    {
        $correo = $this->input->post('correo');
        $data['usuario'] = $this->service_login_user->verificarUsuario($correo);

        $respuesta = array(
            "error" => (!$data['usuario'] ? true : false), "respuesta" => $data['usuario']
        );
        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
    static function generarTokenUser($buscarUsuario, $data){
        $ci = &get_instance();
        if(!empty($buscarUsuario)){	

			/*=============================================
			Encriptamos la contraseña
			=============================================*/

			$crypt = crypt($data["password_user"], '$2a$07$azybxcags23425sdg23sdfhsd$');

			if($buscarUsuario->password_user == $crypt){

			 	/*=============================================
				Creación de JWT
				=============================================*/

				$time = time();
				$key = "azscdvfbgnhmjkl1q2w3e4r5t6y7u8i9o";

				$token = array(

					"iat" => $time,  // Tiempo que inició el token
					"exp" => $time + (60*60*24), // Tiempo que expirará el token (+1 dia)
					'data' => [
						"id" =>  $buscarUsuario->id,
						"email" =>  $buscarUsuario->email_user
					]
				);

				$jwt = JWT::encode($token, $key, 'HS256');
				/*=============================================
				Actualizamos la base de datos con el Token del usuario
				=============================================*/

				$data = array(
					"token_user" => $jwt,
					"token_exp_user" => $token["exp"],
                    "id" =>$buscarUsuario->id
				);
                $update = $ci->service_login_user->actualizarTokenUser($data);

				if($update){

					$response[0]->token_user = $jwt;
					$response[0]->token_exp_user = $token["exp"];

					$return = new PostController();
					$return -> fncResponse($response, "postLogin",  null);

				}
	

			}else{

				$response = null;
				$return = new PostController();
				$return -> fncResponse($response, "postLogin",  "Wrong password");

			}

		}else{

			$response = null;
			$return = new PostController();
			$return -> fncResponse($response, "postLogin",  "Wrong email");

		}
    }
    public function enrollmentUser()
    {
        $data = array();
        $categorias_det = $this->mostrarVista2('enrollment_user.php', $data, true);
    }
}
