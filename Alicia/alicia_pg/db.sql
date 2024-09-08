CREATE DATABASE seguridadDB;
\c seguridadDB;

CREATE TABLE usuarios (
    DNI VARCHAR(15) PRIMARY KEY,
    nombre VARCHAR(50),
    email VARCHAR(100),
    tarjetaCredito BYTEA,  -- Campo encriptado
    fechaVencimiento DATE,
    cvc BYTEA  -- Campo encriptado
);


SET GLOBAL validate_password_policy = 'MEDIUM';
SET GLOBAL validate_password_length = 12;

SET GLOBAL general_log = 'ON';


logging_collector = on
log_statement = 'all'


$stmt = $conn->prepare("INSERT INTO usuarios (DNI, nombre, email) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $dni, $nombre, $email);
$stmt->execute();


pg_prepare($conn, "insert_user", "INSERT INTO usuarios (DNI, nombre, email) VALUES ($1, $2, $3)");
pg_execute($conn, "insert_user", array($dni, $nombre, $email));
