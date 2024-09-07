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
