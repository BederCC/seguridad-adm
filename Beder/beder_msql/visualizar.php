<?php
// Configuración de la conexión a la base de datos
$host = "localhost";
$dbname = "seguridadadb";  // Cambia esto por el nombre de tu base de datos
$username = "root";
$password = "";  // Cambia esto por tu contraseña de MySQL

// Clave y vector de inicialización (IV) usados para encriptar/desencriptar
$encryption_key = 'estaEsUnaClaveSegura1234567890';  // La misma clave usada para encriptar
$iv = '1234567891011121';  // El IV utilizado para encriptar

// Función para desencriptar los datos
function desencriptar($data, $encryption_key, $iv) {
    return openssl_decrypt(base64_decode($data), 'AES-256-CBC', $encryption_key, 0, $iv);
}

// Conectar a la base de datos
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

// Consulta para obtener los datos de la tabla usuarios
$query = "SELECT DNI, nombre, email, tarjetaCredito, fechaVencimiento, cvc FROM usuarios";
$stmt = $conn->prepare($query);
$stmt->execute();

// Estilos para la tabla
echo '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualización de Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            padding: 50px;
            text-align: center;
        }
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        table th, table td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
        }
        table th {
            background-color: #6c7ae0;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table tr:hover {
            background-color: #d9e2f1;
        }
    </style>
</head>
<body>
    <h2>Datos de Usuarios Registrados</h2>
    <table>
        <tr>
            <th>DNI</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Tarjeta de Crédito</th>
            <th>Fecha de Vencimiento</th>
            <th>CVC</th>
        </tr>';

// Recorrer los resultados de la consulta
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Desencriptar los campos de tarjeta de crédito y CVC
    $tarjetaCredito = desencriptar($row['tarjetaCredito'], $encryption_key, $iv);
    $cvc = desencriptar($row['cvc'], $encryption_key, $iv);

    // Mostrar los datos en la tabla
    echo '<tr>
        <td>' . htmlspecialchars($row['DNI']) . '</td>
        <td>' . htmlspecialchars($row['nombre']) . '</td>
        <td>' . htmlspecialchars($row['email']) . '</td>
        <td>' . htmlspecialchars($tarjetaCredito) . '</td>
        <td>' . htmlspecialchars($row['fechaVencimiento']) . '</td>
        <td>' . htmlspecialchars($cvc) . '</td>
    </tr>';
}

echo '
    </table>
</body>
</html>';
?>
