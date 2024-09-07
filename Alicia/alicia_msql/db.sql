CREATE DATABASE seguridadDB;
USE seguridadDB;

CREATE TABLE usuarios (
    DNI VARCHAR(15) PRIMARY KEY,
    nombre VARCHAR(50),
    email VARCHAR(100),
    tarjetaCredito VARBINARY(255),  -- Campo encriptado
    fechaVencimiento DATE,
    cvc VARBINARY(255)  -- Campo encriptado
);
