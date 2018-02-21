-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-02-2018 a las 01:24:04
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
-- Estructura de tabla para la tabla `lista_vista`
--

CREATE TABLE `lista_vista` (
  `id_lista_vista` int(11) NOT NULL,
  `id_modulo_vista` int(11) DEFAULT NULL,
  `nombre_lista_vista` varchar(100) NOT NULL,
  `descripcion_lista_vista` varchar(200) DEFAULT NULL,
  `posicion_lista_vista` int(2) NOT NULL,
  `url_lista_vista` varchar(100) NOT NULL,
  `visibilidad_lista_vista` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `lista_vista`
--

INSERT INTO `lista_vista` (`id_lista_vista`, `id_modulo_vista`, `nombre_lista_vista`, `descripcion_lista_vista`, `posicion_lista_vista`, `url_lista_vista`, `visibilidad_lista_vista`) VALUES
(1, 1, 'Bancos', 'index de la vista y muestra los datos', 1, 'Bancos', 0),
(2, 2, 'Módulos', 'para crear dinamicamente el menu del sistema', 1, 'Modulos', 0),
(3, 2, 'Funciones', '', 2, 'ListaVista', 0),
(4, 2, 'Roles', 'control de la permisologia de los usuarios', 3, 'Roles', 0),
(9, 1, 'Lista Valores', '', 2, 'ListaValores', 0),
(10, 1, 'Mi Correo', '', 3, 'MiCorreo', 0),
(11, 1, 'Mi Empresa', '', 4, 'MiEmpresa', 0),
(12, 1, 'Plazas Bancarias', '', 5, 'PlazasBancarias', 0),
(13, 1, 'Sepomex', '', 6, 'Sepomex', 0),
(14, 2, 'Usuarios', 'una sola pantalla para todo el crud', 4, 'Usuarios', 0),
(17, 3, 'Comisiones', 'esquema de comision por vendedor', 4, 'Comision', 0),
(18, 3, 'Descuentos', '', 2, 'Descuento', 0),
(19, 3, 'Esquemas', '', 3, 'Esquemas', 0),
(20, 3, 'Inmobiliarias', '', 1, 'Inmobiliarias', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `lista_vista`
--
ALTER TABLE `lista_vista`
  ADD PRIMARY KEY (`id_lista_vista`),
  ADD UNIQUE KEY `id_lista_vista` (`id_lista_vista`),
  ADD UNIQUE KEY `nombre_lista_vista` (`nombre_lista_vista`),
  ADD KEY `Vistas_Modulo` (`id_modulo_vista`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `lista_vista`
--
ALTER TABLE `lista_vista`
  MODIFY `id_lista_vista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `lista_vista`
--
ALTER TABLE `lista_vista`
  ADD CONSTRAINT `Vistas_Modulo` FOREIGN KEY (`id_modulo_vista`) REFERENCES `modulo_vista` (`id_modulo_vista`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
