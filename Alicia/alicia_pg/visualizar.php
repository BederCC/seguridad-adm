<?php
$host = "localhost";
$dbname = "seguridaddb";
$user = "postgres";
$pass = "root";

$key = "mi_clave_secreta"; // Mismo valor usado en la encriptación

function decryptData($data, $key) {
    $data = base64_decode($data);
    $iv = substr($data, 0, openssl_cipher_iv_length('aes-128-cbc'));
    $encrypted = substr($data, openssl_cipher_iv_length('aes-128-cbc'));
    return openssl_decrypt($encrypted, 'aes-128-cbc', $key, 0, $iv);
}

$conn = pg_connect("host=$host dbname=$dbname user=$user password=$pass");
if (!$conn) {
    die("Error en la conexión");
}

$sql = "SELECT DNI, nombre, email, tarjetaCredito, fechaVencimiento, cvc FROM usuarios";
$result = pg_query($conn, $sql);

while ($row = pg_fetch_assoc($result)) {
    echo "DNI: " . $row["dni"] . "<br>";
    echo "Nombre: " . $row["nombre"] . "<br>";
    echo "Email: " . $row["email"] . "<br>";
    echo "Tarjeta de Crédito: " . decryptData($row["tarjetaCredito"], $key) . "<br>";
    echo "Fecha de Vencimiento: " . $row["fechaVencimiento"] . "<br>";
    echo "CVC: " . decryptData($row["cvc"], $key) . "<br><br>";
}

pg_close($conn);
?>


