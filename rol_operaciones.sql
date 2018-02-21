-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-02-2018 a las 01:25:11
-- Versión del servidor: 10.1.29-MariaDB
-- Versión de PHP: 7.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reserva`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_operaciones`
--

CREATE TABLE `rol_operaciones` (
  `id_rol_operaciones` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_lista_vista` int(11) NOT NULL,
  `admin_rol_operaciones` int(1) DEFAULT '0',
  `registrar` int(11) NOT NULL DEFAULT '1',
  `general` int(11) NOT NULL DEFAULT '1',
  `detallada` int(1) NOT NULL DEFAULT '1',
  `actualizar` int(11) NOT NULL DEFAULT '1',
  `eliminar` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rol_operaciones`
--

INSERT INTO `rol_operaciones` (`id_rol_operaciones`, `id_rol`, `id_lista_vista`, `admin_rol_operaciones`, `registrar`, `general`, `detallada`, `actualizar`, `eliminar`) VALUES
(1, 1, 1, 0, 0, 0, 0, 0, 0),
(2, 1, 2, 0, 0, 0, 0, 0, 0),
(3, 1, 3, 0, 0, 0, 0, 0, 0),
(4, 1, 4, 0, 0, 0, 0, 0, 0),
(8, 1, 9, 0, 0, 0, 0, 0, 0),
(9, 1, 10, 0, 0, 0, 0, 0, 0),
(10, 1, 11, 0, 0, 0, 0, 0, 0),
(11, 1, 12, 0, 0, 0, 0, 0, 0),
(12, 1, 13, 0, 0, 0, 0, 0, 0),
(16, 1, 14, 0, 0, 0, 0, 0, 0),
(17, 1, 17, 0, 0, 0, 0, 0, 0),
(18, 1, 18, 0, 0, 0, 0, 0, 0),
(22, 2, 1, 0, 0, 0, 0, 1, 1),
(27, 3, 1, 0, 1, 1, 1, 1, 1),
(28, 3, 4, 0, 1, 1, 1, 1, 1),
(29, 3, 14, 0, 1, 1, 1, 1, 1),
(30, 3, 10, 0, 1, 1, 1, 1, 1),
(31, 4, 1, 0, 1, 1, 1, 0, 1),
(32, 4, 10, 0, 1, 0, 1, 0, 1),
(34, 4, 14, 0, 0, 1, 1, 1, 1),
(35, 1, 19, 0, 0, 0, 0, 0, 0),
(36, 1, 20, 0, 0, 0, 0, 0, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `rol_operaciones`
--
ALTER TABLE `rol_operaciones`
  ADD PRIMARY KEY (`id_rol_operaciones`),
  ADD UNIQUE KEY `id_rol_operaciones` (`id_rol_operaciones`),
  ADD KEY `Rol_Vista` (`id_rol`),
  ADD KEY `Vista_Rol` (`id_lista_vista`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `rol_operaciones`
--
ALTER TABLE `rol_operaciones`
  MODIFY `id_rol_operaciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `rol_operaciones`
--
ALTER TABLE `rol_operaciones`
  ADD CONSTRAINT `Rol_Vista` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE,
  ADD CONSTRAINT `Vista_Rol` FOREIGN KEY (`id_lista_vista`) REFERENCES `lista_vista` (`id_lista_vista`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
