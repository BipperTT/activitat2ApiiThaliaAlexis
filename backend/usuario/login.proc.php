<?php
session_start();

if (isset($_POST['email'], $_POST['password'])) {
    $correoUsuario = $_POST['email'];
    $contrasenaUsuario = $_POST['password'];

    $url = "api/USUARI.php";
    $data = array(
        'usu_nom' => $correoUsuario,
        'usu_contra' => $contrasenaUsuario
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        $json_response = json_decode($response, true);

        if (isset($json_response['token'])) {
            $_SESSION['token'] = $json_response['token'];
            $nivelUsuario = $json_response['nivel'];

            if ($nivelUsuario === 'admin') {
                header('Location: ADMIN.php');
                exit();
            } elseif ($nivelUsuario === 'user') {
                header('Location: USER.php');
                exit();
            } else {
                echo "Error: Nivel de usuario desconocido";
                exit();
            }
        } else {
            header('Location: ../login.php?error=1');
            exit();
        }
    }
} else {
    echo "Error: Datos de formulario no enviados";
    exit();
}
?>
