<?php
session_start();

if (isset($_POST['email'], $_POST['password'])) {

    $correoUsuario = $_POST['email'];
    $contrasenaUsuario = $_POST['password'];

    // establecer la conexión con la base de datos
    $pdo = new PDO('mysql:host=localhost;dbname=fct', 'david', 'qwe123QWE123*');

    $stmt = $pdo->prepare("SELECT usu_nivell FROM USUARI WHERE usu_mail = :correoUsuario");
    $stmt->execute(array('correoUsuario' => $correoUsuario));
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        if (password_verify($contrasenaUsuario, $usuario['usu_contra'])) {

            $_SESSION['token'] = generarToken();
            $nivelUsuario = $usuario['usu_nivell'];

            if ($nivelUsuario === 'admin') {
                header('Location: ../../api/ADMIN.php');
                exit();
            } elseif ($nivelUsuario === 'user') {
                header('Location: ../../api/USER.php');
                exit();
            } else {
                echo "Error: Nivel de usuario desconocido";
                exit();
            }
        } else {
            header('Location: ../login.php?error=1');
            exit();
        }
    } else {
        header('Location: ../login.php?error=2');
        exit();
    }
} else {

    echo "Error: Datos de formulario no enviados";
    exit();
}
?>
