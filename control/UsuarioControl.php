<?php
require_once 'model/dao/UsuarioDAO.php';

class UsuarioControl {
    private $model;

    public function __construct() {
        $this->model = new UsuarioDAO();
    }

    //funciones del controlador

    public function index() { // read
        //llamar al modelo
        $results = $this->model->listar();
        //llamar a la vista
        require_once 'visual/modulos/usuarioPerez/usuario.list.php'; //tiene que cambiar "nuevo"por "listar"
    }



    public function nuevo() {   // create
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Capture parameters
            $username     = htmlentities($_POST['username']);
            $email        = isset($_POST['email']) ? htmlentities($_POST['email']) : '';
            $birthday    = date('Y-m-d', strtotime($_POST['birthday']));
            $nationality  = htmlentities($_POST['nationality']);
            $password     = htmlentities($_POST['password1']);

            // Call model insert function
            $success = $this->model->insertar($username, $email, $birthday, $nationality, $password);
            $messageString = 'Contacto guardado exitosamente';
            $color = 'primary';

            // Validation if unsuccessful
            if (!$success) {
                $messageString = "No se pudo guardado los datos.";
                $color = "danger";
            }
            if (!isset($_SESSION)) {
                session_start();
            };
            $_SESSION['mensaje'] = $messageString;
            $_SESSION['color'] = $color;

            // Redirect to
            header('Location:index.php?c=usuario&f=index');
        } else {
            require_once 'model/dao/UsuarioDAO.php';
            $mod = new UsuarioDAO();
            $motivo = $mod->listar();

            // Show form new
            require_once 'visual/modulos/usuarioPerez/usuario.nuevo.php';
        }
    }

    public function editar() { // Update
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Capture values
            $id           = htmlentities($_POST['id_user']);
            $username     = htmlentities($_POST['username']);
            $email        = isset($_POST['email']) ? htmlentities($_POST['email']) : '';
            $birthday    = date('Y-m-d', strtotime($_POST['birthday']));
            $nationality  = htmlentities($_POST['nationality']);
            $password     = htmlentities($_POST['password1']);

            // Call model update function
            $success = $this->model->actualizar($id, $username, $email, $birthday, $nationality, $password);
            $messageString = 'Datos actualizados exitosamente';
            $color = 'primary';

            // Validation if unsuccessful
            if (!$success) {
                $messageString = "No se pudo actualizar los datos.";
                $color = "danger";
            }
            if (!isset($_SESSION)) {
                session_start();
            };
            $_SESSION['mensaje'] = $messageString;
            $_SESSION['color'] = $color;

            // Redirect to
            header('Location:index.php?c=usuario&f=index');
        } else {
            // show form with data
            require_once 'model/dao/UsuarioDAO.php';
            $mod = new UsuarioDAO();
            $motivo = $mod->listar();

            // Read data
            $id = $_REQUEST['id'];

            // Comunicate with model
            $row = $this->model->searchId($id);

            // shows form new
            require_once 'visual/modulos/usuarioPerez/usuario.editar.php';
        }
    }

    public function eliminar() {    // Delete
        // Assign values
        $id = $_REQUEST['id'];

        // Connection to model
        $success = $this->model->eliminar($id);
        $messageString = 'Datos eliminado exitosamente';
        $color = 'primary';

        // Validation if unsuccessful
        if (!$success) {
            $messageString = "No se pudo eliminar los datos.";
            $color = "danger";
        }
        if (!isset($_SESSION)) {
            session_start();
        };
        $_SESSION['mensaje'] = $messageString;
        $_SESSION['color'] = $color;

        // Redirect to
        header('Location:index.php?c=usuario&f=index');
    }
}