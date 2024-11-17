UsuariosController: <?php
// Asegúrate de que estás incluyendo el autoloader correctamente y de que `firebase/php-jwt` está instalado.
// require '../vendor/autoload.php';
// use Firebase\JWT\JWT;

// define('JWT_SECRET', 'aitageo2hbc45og'); // Clave secreta para el token JWT

require_once "../model/database.php";

class UsuariosController {
    private $conexion;

    public function __construct() {
        $database = new Database();
        $this->conexion = $database->getConexion();
    }

    public function login() {
        header('Content-Type: application/json');

        // Obtiene los datos del frontend
        $data = json_decode(file_get_contents('php://input'), true);
        $correo = $data['correo'];
        $password = $data['password'];
        
        $sql = "SELECT correo, password FROM administradores WHERE correo = :correo";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $usuario['password'])) {
                echo json_encode(["success" => "Logueo exitoso"]);
                http_response_code(200); //exitoso
               
            } else {
                echo json_encode(["error" => "Usuario o contraseña incorrectos"]);
                http_response_code(401); // prohibido
                return;
            }
        } else {
            echo json_encode(["error" => "Usuario no encontrado"]);
            http_response_code(404); // No encontrado
            return;
        }
    }
}
