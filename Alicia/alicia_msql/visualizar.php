<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "seguridadDB";

$key = "mi_clave_secreta"; // Mismo valor usado en la encriptación

function decryptData($data, $key) {
    $data = base64_decode($data);
    $iv = substr($data, 0, openssl_cipher_iv_length('aes-128-cbc'));
    $encrypted = substr($data, openssl_cipher_iv_length('aes-128-cbc'));
    return openssl_decrypt($encrypted, 'aes-128-cbc', $key, 0, $iv);
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

$sql = "SELECT DNI, nombre, email, tarjetaCredito, fechaVencimiento, cvc FROM usuarios";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "DNI: " . $row["DNI"] . "<br>";
        echo "Nombre: " . $row["nombre"] . "<br>";
        echo "Email: " . $row["email"] . "<br>";
        echo "Tarjeta de Crédito: " . decryptData($row["tarjetaCredito"], $key) . "<br>";
        echo "Fecha de Vencimiento: " . $row["fechaVencimiento"] . "<br>";
        echo "CVC: " . decryptData($row["cvc"], $key) . "<br><br>";
    }
} else {
    echo "No hay registros.";
}

$conn->close();
?>
