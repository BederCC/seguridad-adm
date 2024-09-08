<?php
$host = "localhost";
$dbname = "seguridaddb";
$user = "postgres";
$pass = "root";

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

    $conn = pg_connect("host=$host dbname=$dbname user=$user password=$pass");
    if (!$conn) {
        die("Error en la conexión");
    }

    $sql = "INSERT INTO usuarios (DNI, nombre, email, tarjetaCredito, fechaVencimiento, cvc) 
            VALUES ($1, $2, $3, $4, $5, $6)";
    $result = pg_query_params($conn, $sql, array($dni, $nombre, $email, $tarjetaCreditoEncriptada, $fechaVencimiento, $cvcEncriptado));

    if ($result) {
        echo "Registro exitoso";
    } else {
        echo "Error: " . pg_last_error($conn);
    }

    pg_close($conn);
}
?>


