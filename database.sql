DROP DATABASE IF EXISTS apuntes;
CREATE DATABASE apuntes CHARACTER SET utf8mb4;
USE apuntes;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(60) NOT NULL,
  apellido VARCHAR(60) NOT NULL,
  curso VARCHAR(20) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  clave VARCHAR(255) NOT NULL,
  apuntes_subidos INT NOT NULL DEFAULT 0,
  votos_recibidos INT NOT NULL DEFAULT 0,
  puntos INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE materias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE apuntes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  materia_id INT NOT NULL,
  titulo VARCHAR(150) NOT NULL,
  descripcion VARCHAR(300) NOT NULL,
  anio INT NOT NULL,
  archivo VARCHAR(100),
  enlace VARCHAR(300),
  votos INT NOT NULL DEFAULT 0,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (materia_id) REFERENCES materias(id)
) ENGINE=InnoDB;

CREATE TABLE votos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  apunte_id INT NOT NULL,
  usuario_id INT NOT NULL,
  UNIQUE (apunte_id, usuario_id),
  FOREIGN KEY (apunte_id) REFERENCES apuntes(id) ON DELETE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE grupos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  creador_id INT NOT NULL,
  materia_id INT NOT NULL,
  tema VARCHAR(150) NOT NULL,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  lugar VARCHAR(100) NOT NULL,
  cupo INT NOT NULL,
  lugares INT NOT NULL,
  FOREIGN KEY (creador_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (materia_id) REFERENCES materias(id)
) ENGINE=InnoDB;

CREATE TABLE inscripciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  grupo_id INT NOT NULL,
  usuario_id INT NOT NULL,
  UNIQUE (grupo_id, usuario_id),
  FOREIGN KEY (grupo_id) REFERENCES grupos(id) ON DELETE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO materias (nombre) VALUES
('Analisis Matematico'),('Fisica'),('Redes'),('Programacion'),('Base de Datos'),('Electronica');
