<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "seguridadADB";

$key = "mi_clave_secreta"; // Debe tener exactamente 16 caracteres para AES-128

function encryptData($data, $key) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-128-cbc', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $tarjetaCredito = $_POST['tarjetaCredito'];
    $fechaVencimiento = $_POST['fechaVencimiento'];
    $cvc = $_POST['cvc'];

    $tarjetaCreditoEncriptada = encryptData($tarjetaCredito, $key);
    $cvcEncriptado = encryptData($cvc, $key);

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }

    $sql = "INSERT INTO usuarios (DNI, nombre, email, tarjetaCredito, fechaVencimiento, cvc) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $dni, $nombre, $email, $tarjetaCreditoEncriptada, $fechaVencimiento, $cvcEncriptado);

    if ($stmt->execute()) {
        echo "Registro exitoso";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
