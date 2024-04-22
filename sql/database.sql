CREATE DATABASE IF NOT EXISTS cinemapp;

USE cinemapp;

CREATE TABLE IF NOT EXISTS Usuario (
                Nombre VARCHAR(32) PRIMARY KEY,
                Contrasena VARCHAR(32));

CREATE TABLE IF NOT EXISTS Review (
                Fecha DATETIME PRIMARY KEY,
                Usuario VARCHAR(32),
                Nombre VARCHAR(255),
                Imagen TEXT,
                Ano INTEGER,
                Puntuacion INTEGER,
                Resena TEXT,
                FOREIGN KEY (Usuario) REFERENCES Usuario(Nombre)
                );



INSERT INTO IGNORE Usuario (Nombre, Contrasena) VALUES ('admin', 'admin');
