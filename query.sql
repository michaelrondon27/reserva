/* Eliminar tablas */
DROP TABLE proyectos_vendedores;
DROP TABLE proyectos;
DROP TABLE esquema_comision;
DROP TABLE descuentos;
UPDATE `lista_vista` SET `url_lista_vista` = 'Comision' WHERE `url_lista_vista` = `EsquemaComision`;
DELETE FROM auditoria WHERE tabla = 'esquema_comision';
DELETE FROM auditoria WHERE tabla = 'descuentos';
/*-----------------*/

CREATE TABLE `vendedores_clientes` (
  	`id_vendedor_cliente` int(11) NOT NULL,
  	`cod_vendedor` int(11) NOT NULL,
  	`cod_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `vendedores_clientes`
  	ADD PRIMARY KEY (`id_vendedor_cliente`),
  	ADD KEY `cod_vendedor` (`cod_vendedor`),
  	ADD KEY `cod_cliente` (`cod_cliente`);

ALTER TABLE `vendedores_clientes`
  	MODIFY `id_vendedor_cliente` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `vendedores_clientes`
  	ADD CONSTRAINT `vendedores_clientes_ibfk_1` FOREIGN KEY (`cod_vendedor`) REFERENCES `vendedores` (`cod_vendedor`) ON DELETE CASCADE ON UPDATE CASCADE;


CREATE TABLE `proyectos` (
  	`cod_proyecto` int(11) NOT NULL,
  	`nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  	`descripcion` text COLLATE utf8_unicode_ci,
  	`director_proyecto` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  	`plano_proyecto` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `proyectos`
  	ADD PRIMARY KEY (`cod_proyecto`);

ALTER TABLE `proyectos`
  	MODIFY `cod_proyecto` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `proyectos_clasificacion` (
	`id_proyecto_clasificacion` int(11) NOT NULL,
	`cod_proyecto` int(11) NOT NULL,
	`cod_clasificacion` int(11) NOT NULL,
	`precio` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `proyectos_clasificacion`
	ADD PRIMARY KEY (`id_proyecto_clasificacion`),
	ADD KEY `cod_proyecto` (`cod_proyecto`),
	ADD KEY `cod_clasificacion` (`cod_clasificacion`);

ALTER TABLE `proyectos_clasificacion`
 	 MODIFY `id_proyecto_clasificacion` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `proyectos_clasificacion`
	ADD CONSTRAINT `proyectos_clasificacion_ibfk_1` FOREIGN KEY (`cod_proyecto`) REFERENCES `proyectos` (`cod_proyecto`) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `proyectos_clasificacion_ibfk_2` FOREIGN KEY (`cod_clasificacion`) REFERENCES `lval` (`codlval`);

CREATE TABLE `inmobiliarias` (
	`id_inmobiliarias` int(11) NOT NULL,
	`nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `inmobiliarias`
  	ADD PRIMARY KEY (`id_inmobiliarias`);

ALTER TABLE `inmobiliarias`
  	MODIFY `id_inmobiliarias` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `inmobiliarias_proyectos` (
	`id_inmobiliaria_proyecto` int(11) NOT NULL,
	`cod_proyecto` int(11) NOT NULL,
	`cod_clasificacion` int(11) NOT NULL,
	`cod_inmobiliaria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `inmobiliarias_proyectos`
	ADD PRIMARY KEY (`id_inmobiliaria_proyecto`),
	ADD KEY `cod_proyecto` (`cod_proyecto`),
	ADD KEY `cod_clasificacion` (`cod_clasificacion`),
	ADD KEY `cod_inmobiliaria` (`cod_inmobiliaria`);

ALTER TABLE `inmobiliarias_proyectos`
  	MODIFY `id_inmobiliaria_proyecto` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `inmobiliarias_proyectos`
	ADD CONSTRAINT `inmobiliarias_proyectos_ibfk_1` FOREIGN KEY (`cod_proyecto`) REFERENCES `proyectos` (`cod_proyecto`) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `inmobiliarias_proyectos_ibfk_2` FOREIGN KEY (`cod_clasificacion`) REFERENCES `lval` (`codlval`),
	ADD CONSTRAINT `inmobiliarias_proyectos_ibfk_3` FOREIGN KEY (`cod_inmobiliaria`) REFERENCES `inmobiliarias` (`id_inmobiliarias`);

CREATE TABLE `inmobiliarias_vendedores` (
	`id_inmobiliaria_vendedor` int(11) NOT NULL,
	`cod_inmobiliaria` int(11) NOT NULL,
	`cod_vendedor` int(11) NOT NULL,
	`cod_coordinador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `inmobiliarias_vendedores`
	ADD PRIMARY KEY (`id_inmobiliaria_vendedor`),
	ADD KEY `cod_inmobiliaria` (`cod_inmobiliaria`),
	ADD KEY `cod_vendedor` (`cod_vendedor`),
	ADD KEY `cod_coordinador` (`cod_coordinador`);

ALTER TABLE `inmobiliarias_vendedores`
	MODIFY `id_inmobiliaria_vendedor` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `inmobiliarias_vendedores`
	ADD CONSTRAINT `inmobiliarias_vendedores_ibfk_1` FOREIGN KEY (`cod_inmobiliaria`) REFERENCES `inmobiliarias` (`id_inmobiliarias`) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `inmobiliarias_vendedores_ibfk_2` FOREIGN KEY (`cod_vendedor`) REFERENCES `vendedores` (`cod_vendedor`),
	ADD CONSTRAINT `inmobiliarias_vendedores_ibfk_3` FOREIGN KEY (`cod_coordinador`) REFERENCES `vendedores` (`cod_vendedor`);

CREATE TABLE `proyectos_vendedores` (
	`id_proyecto_vendedor` int(11) NOT NULL,
	`cod_proyecto` int(11) NOT NULL,
	`cod_clasificacion` int(11) NOT NULL,
	`cod_vendedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `proyectos_vendedores`
	ADD PRIMARY KEY (`id_proyecto_vendedor`),
	ADD KEY `cod_proyecto` (`cod_proyecto`),
	ADD KEY `cod_vendedor` (`cod_vendedor`),
	ADD KEY `cod_clasificacion` (`cod_clasificacion`);

ALTER TABLE `proyectos_vendedores`
	MODIFY `id_proyecto_vendedor` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `proyectos_vendedores`
	ADD CONSTRAINT `fk_cod_proyecto` FOREIGN KEY (`cod_proyecto`) REFERENCES `proyectos` (`cod_proyecto`),
	ADD CONSTRAINT `fk_cod_vendedor` FOREIGN KEY (`cod_vendedor`) REFERENCES `vendedores` (`cod_vendedor`),
	ADD CONSTRAINT `proyectos_vendedores_ibfk_1` FOREIGN KEY (`cod_clasificacion`) REFERENCES `lval` (`codlval`);

INSERT INTO `tipolval` (`tipolval`, `descriplval`) VALUES ('ESQUEMAS', 'TIPOS DE ESQUEMAS');

INSERT INTO `lval` (`codlval`, `tipolval`, `descriplval`) VALUES
(297, 'ESQUEMAS', 'COMISIÓN'),
(298, 'ESQUEMAS', 'DESCUENTO');

INSERT INTO `auditoria` (`tabla`, `cod_reg`, `status`, `fec_status`, `usr_regins`, `fec_regins`, `usr_regmod`, `fec_regmod`) VALUES
('lval', 297, 1, NULL, 11, '2018-02-01', NULL, NULL),
('lval', 298, 1, NULL, 11, '2018-02-01', NULL, NULL);

CREATE TABLE `esquemas` (
	`id_esquema` int(11) NOT NULL,
	`tipo` int(11) NOT NULL,
	`cod_esquema` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
	`descripcion` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `esquemas` (`id_esquema`, `tipo`, `cod_esquema`, `descripcion`) VALUES
(1, 297, 'COMIS0001', 'ESQUEMAS DE COMISIONES ETAPA I'),
(2, 298, 'DESC00001', 'ESQUEMA DESCUENTO ESTAPA I');

ALTER TABLE `esquemas`
	ADD PRIMARY KEY (`id_esquema`),
	ADD KEY `tipo` (`tipo`);

ALTER TABLE `esquemas`
  	MODIFY `id_esquema` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `esquemas`
 	ADD CONSTRAINT `esquemas_ibfk_1` FOREIGN KEY (`tipo`) REFERENCES `lval` (`codlval`);

INSERT INTO `auditoria` (`tabla`, `cod_reg`, `status`, `fec_status`, `usr_regins`, `fec_regins`, `usr_regmod`, `fec_regmod`) VALUES
('esquemas', 1, 1, NULL, 11, '2018-02-02', NULL, NULL),
('esquemas', 2, 1, NULL, 11, '2018-02-02', NULL, NULL);

CREATE TABLE `comisiones` (
	`id_comision` int(11) NOT NULL,
	`id_vendedor` int(11) NOT NULL,
	`tipo_vendedor` int(11) NOT NULL,
	`num_ventas_mes` int(11) NOT NULL,
	`tipo_plazo` int(11) NOT NULL,
	`porctj_comision` double NOT NULL,
	`cod_esquema` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `comisiones`
	ADD PRIMARY KEY (`id_comision`),
	ADD KEY `id_vendedor` (`id_vendedor`),
	ADD KEY `tipo_vendedor` (`tipo_vendedor`),
	ADD KEY `tipo_plazo` (`tipo_plazo`),
	ADD KEY `cod_esquema` (`cod_esquema`);

ALTER TABLE `comisiones`
	MODIFY `id_comision` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `comisiones`
	ADD CONSTRAINT `comisiones_ibfk_1` FOREIGN KEY (`cod_esquema`) REFERENCES `esquemas` (`id_esquema`),
	ADD CONSTRAINT `fk_id_vendedor` FOREIGN KEY (`id_vendedor`) REFERENCES `lval` (`codlval`),
	ADD CONSTRAINT `fk_tipo_plazo` FOREIGN KEY (`tipo_plazo`) REFERENCES `lval` (`codlval`),
	ADD CONSTRAINT `fk_tipo_vendedor` FOREIGN KEY (`tipo_vendedor`) REFERENCES `lval` (`codlval`);

INSERT INTO `comisiones` (`id_comision`, `id_vendedor`, `tipo_vendedor`, `num_ventas_mes`, `tipo_plazo`, `porctj_comision`, `cod_esquema`) VALUES
(1, 275, 279, 1, 281, 0.05, 1),
(2, 275, 279, 2, 281, 0.05, 1),
(3, 275, 279, 3, 281, 0.05, 1),
(4, 275, 279, 4, 281, 0.055, 1),
(5, 275, 279, 5, 281, 0.055, 1),
(6, 275, 279, 6, 281, 0.055, 1),
(7, 275, 279, 7, 281, 0.06, 1),
(8, 275, 279, 8, 281, 0.06, 1),
(9, 275, 279, 9, 281, 0.06, 1),
(10, 275, 279, 10, 281, 0.065, 1),
(11, 275, 279, 1, 282, 0.0375, 1),
(12, 275, 279, 2, 282, 0.0375, 1),
(13, 275, 279, 3, 282, 0.0375, 1),
(14, 275, 279, 4, 282, 0.04, 1),
(15, 275, 279, 5, 282, 0.04, 1),
(16, 275, 279, 6, 282, 0.04, 1),
(17, 275, 279, 7, 282, 0.045, 1),
(18, 275, 279, 8, 282, 0.045, 1),
(19, 275, 279, 9, 282, 0.045, 1),
(20, 275, 279, 10, 282, 0.05, 1),
(21, 275, 279, 1, 283, 0.035, 1),
(22, 275, 279, 2, 283, 0.035, 1),
(23, 275, 279, 3, 283, 0.035, 1),
(24, 275, 279, 4, 283, 0.035, 1),
(25, 275, 279, 5, 283, 0.035, 1),
(26, 275, 279, 6, 283, 0.035, 1),
(27, 275, 279, 7, 283, 0.04, 1),
(28, 275, 279, 8, 283, 0.04, 1),
(29, 275, 279, 9, 283, 0.04, 1),
(30, 275, 279, 10, 283, 0.045, 1),
(31, 275, 279, 1, 284, 0.03, 1),
(32, 275, 279, 2, 284, 0.03, 1),
(33, 275, 279, 3, 284, 0.03, 1),
(34, 275, 279, 4, 284, 0.03, 1),
(35, 275, 279, 5, 284, 0.03, 1),
(36, 275, 279, 6, 284, 0.03, 1),
(37, 275, 279, 7, 284, 0.035, 1),
(38, 275, 279, 8, 284, 0.035, 1),
(39, 275, 279, 9, 284, 0.035, 1),
(40, 275, 279, 10, 284, 0.04, 1),
(41, 275, 280, 1, 281, 0.05, 1),
(42, 275, 280, 2, 281, 0.05, 1),
(43, 275, 280, 3, 281, 0.05, 1),
(44, 275, 280, 4, 281, 0.055, 1),
(45, 275, 280, 5, 281, 0.055, 1),
(46, 275, 280, 6, 281, 0.055, 1),
(47, 275, 280, 7, 281, 0.06, 1),
(48, 275, 280, 8, 281, 0.06, 1),
(49, 275, 280, 9, 281, 0.06, 1),
(50, 275, 280, 10, 281, 0.065, 1),
(51, 275, 280, 1, 282, 0.04, 1),
(52, 275, 280, 2, 282, 0.04, 1),
(53, 275, 280, 3, 282, 0.04, 1),
(54, 275, 280, 4, 282, 0.045, 1),
(55, 275, 280, 5, 282, 0.045, 1),
(56, 275, 280, 6, 282, 0.045, 1),
(57, 275, 280, 7, 282, 0.05, 1),
(58, 275, 280, 8, 282, 0.05, 1),
(59, 275, 280, 9, 282, 0.05, 1),
(60, 275, 280, 10, 282, 0.06, 1),
(61, 275, 280, 1, 283, 0.04, 1),
(62, 275, 280, 2, 283, 0.04, 1),
(63, 275, 280, 3, 283, 0.04, 1),
(64, 275, 280, 4, 283, 0.045, 1),
(65, 275, 280, 5, 283, 0.045, 1),
(66, 275, 280, 6, 283, 0.045, 1),
(67, 275, 280, 7, 283, 0.05, 1),
(68, 275, 280, 8, 283, 0.05, 1),
(69, 275, 280, 9, 283, 0.05, 1),
(70, 275, 280, 10, 283, 0.06, 1),
(71, 275, 280, 1, 284, 0.04, 1),
(72, 275, 280, 2, 284, 0.04, 1),
(73, 275, 280, 3, 284, 0.04, 1),
(74, 275, 280, 4, 284, 0.045, 1),
(75, 275, 280, 5, 284, 0.045, 1),
(76, 275, 280, 6, 284, 0.045, 1),
(77, 275, 280, 7, 284, 0.05, 1),
(78, 275, 280, 8, 284, 0.05, 1),
(79, 275, 280, 9, 284, 0.05, 1),
(80, 275, 280, 10, 284, 0.06, 1),
(81, 278, 280, 1, 281, 0.006, 1),
(82, 278, 280, 2, 281, 0.006, 1),
(83, 278, 280, 3, 281, 0.006, 1),
(84, 278, 280, 4, 281, 0.01, 1),
(85, 278, 280, 1, 282, 0.004, 1),
(86, 278, 280, 2, 282, 0.004, 1),
(87, 278, 280, 3, 282, 0.004, 1),
(88, 278, 280, 4, 282, 0.0075, 1),
(89, 278, 280, 1, 283, 0.004, 1),
(90, 278, 280, 2, 283, 0.004, 1),
(91, 278, 280, 3, 283, 0.004, 1),
(92, 278, 280, 4, 283, 0.0075, 1),
(93, 278, 280, 1, 284, 0.004, 1),
(94, 278, 280, 2, 284, 0.004, 1),
(95, 278, 280, 3, 284, 0.004, 1),
(96, 278, 280, 4, 284, 0.0075, 1),
(97, 277, 279, 1, 281, 0.004, 1),
(98, 277, 279, 2, 281, 0.004, 1),
(99, 277, 279, 3, 281, 0.004, 1),
(100, 277, 279, 4, 281, 0.006, 1),
(101, 277, 279, 5, 281, 0.006, 1),
(102, 277, 279, 6, 281, 0.006, 1),
(103, 277, 279, 7, 281, 0.008, 1),
(104, 277, 279, 8, 281, 0.008, 1),
(105, 277, 279, 9, 281, 0.008, 1),
(106, 277, 279, 10, 281, 0.0125, 1),
(107, 277, 279, 1, 282, 0.0025, 1),
(108, 277, 279, 2, 282, 0.0025, 1),
(109, 277, 279, 3, 282, 0.0025, 1),
(110, 277, 279, 4, 282, 0.004, 1),
(111, 277, 279, 5, 282, 0.004, 1),
(112, 277, 279, 6, 282, 0.004, 1),
(113, 277, 279, 7, 282, 0.006, 1),
(114, 277, 279, 8, 282, 0.006, 1),
(115, 277, 279, 9, 282, 0.006, 1),
(116, 277, 279, 10, 282, 0.01, 1),
(117, 277, 279, 1, 283, 0.0025, 1),
(118, 277, 279, 2, 283, 0.0025, 1),
(119, 277, 279, 3, 283, 0.0025, 1),
(120, 277, 279, 4, 283, 0.004, 1),
(121, 277, 279, 5, 283, 0.004, 1),
(122, 277, 279, 6, 283, 0.004, 1),
(123, 277, 279, 7, 283, 0.006, 1),
(124, 277, 279, 8, 283, 0.006, 1),
(125, 277, 279, 9, 283, 0.006, 1),
(126, 277, 279, 10, 283, 0.01, 1),
(127, 277, 279, 1, 284, 0.0025, 1),
(128, 277, 279, 2, 284, 0.0025, 1),
(129, 277, 279, 3, 284, 0.0025, 1),
(130, 277, 279, 4, 284, 0.004, 1),
(131, 277, 279, 5, 284, 0.004, 1),
(132, 277, 279, 6, 284, 0.004, 1),
(133, 277, 279, 7, 284, 0.006, 1),
(134, 277, 279, 8, 284, 0.006, 1),
(135, 277, 279, 9, 284, 0.006, 1),
(136, 277, 279, 10, 284, 0.01, 1),
(137, 277, 280, 1, 281, 0.004, 1),
(138, 277, 280, 2, 281, 0.004, 1),
(139, 277, 280, 3, 281, 0.004, 1),
(140, 277, 280, 4, 281, 0.006, 1),
(141, 277, 280, 1, 282, 0.003, 1),
(142, 277, 280, 2, 282, 0.003, 1),
(143, 277, 280, 3, 282, 0.003, 1),
(144, 277, 280, 4, 282, 0.005, 1),
(145, 277, 280, 1, 283, 0.003, 1),
(146, 277, 280, 2, 283, 0.003, 1),
(147, 277, 280, 3, 283, 0.003, 1),
(148, 277, 280, 4, 283, 0.005, 1),
(149, 277, 280, 1, 284, 0.003, 1),
(150, 277, 280, 2, 284, 0.003, 1),
(151, 277, 280, 3, 284, 0.003, 1);

INSERT INTO `auditoria` (`tabla`, `cod_reg`, `status`, `fec_status`, `usr_regins`, `fec_regins`, `usr_regmod`, `fec_regmod`) VALUES
('comisiones', 4, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 5, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 6, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 7, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 8, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 9, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 10, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 11, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 12, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 13, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 14, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 15, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 16, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 17, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 18, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 19, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 20, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 21, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 22, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 23, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 24, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 25, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 26, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 27, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 28, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 29, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 30, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 31, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 32, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 33, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 34, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 35, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 36, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 37, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 38, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 39, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 40, 1, NULL, 1, '2018-01-31', NULL, NULL),
('comisiones', 41, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 42, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 43, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 44, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 45, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 46, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 47, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 48, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 49, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 50, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 51, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 52, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 53, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 54, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 55, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 56, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 57, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 58, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 59, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 60, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 61, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 62, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 63, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 64, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 65, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 66, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 67, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 68, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 69, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 70, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 71, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 72, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 73, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 74, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 75, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 76, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 77, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 78, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 79, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 80, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 81, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 82, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 83, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 84, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 85, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 86, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 87, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 88, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 89, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 90, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 91, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 92, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 93, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 94, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 95, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 96, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 97, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 98, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 99, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 100, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 101, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 102, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 103, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 104, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 105, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 106, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 107, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 108, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 109, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 110, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 111, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 112, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 113, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 114, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 115, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 116, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 117, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 118, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 119, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 120, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 121, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 122, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 123, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 124, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 125, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 126, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 127, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 128, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 129, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 130, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 131, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 132, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 133, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 134, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 135, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 136, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 137, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 138, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 139, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 140, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 141, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 142, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 143, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 144, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 145, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 146, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 147, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 148, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 149, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 150, 1, NULL, 11, '2018-01-31', NULL, NULL),
('comisiones', 151, 1, NULL, 11, '2018-01-31', NULL, NULL);

CREATE TABLE `descuentos` (
	`id_descuento` int(11) NOT NULL,
	`tipo_plazo` int(11) NOT NULL,
	`descuento` double NOT NULL,
	`cod_esquema` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `descuentos`
	ADD PRIMARY KEY (`id_descuento`),
	ADD KEY `tipo_plazo` (`tipo_plazo`),
	ADD KEY `cod_esquema` (`cod_esquema`);

ALTER TABLE `descuentos`
  	MODIFY `id_descuento` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `descuentos`
	ADD CONSTRAINT `descuentos_ibfk_1` FOREIGN KEY (`tipo_plazo`) REFERENCES `lval` (`codlval`),
	ADD CONSTRAINT `descuentos_ibfk_2` FOREIGN KEY (`cod_esquema`) REFERENCES `esquemas` (`id_esquema`);

INSERT INTO `descuentos` (`tipo_plazo`, `descuento`, `cod_esquema`) VALUES
(281, 0.12, 2),
(281, 0.13, 2),
(281, 0.14, 2),
(281, 0.15, 2),
(282, 0.08, 2),
(283, 0.04, 2),
(284, 0, 2);

INSERT INTO `auditoria` (`tabla`, `cod_reg`, `status`, `fec_status`, `usr_regins`, `fec_regins`, `usr_regmod`, `fec_regmod`) VALUES
('descuentos', 1, 1, NULL, 1, '2018-01-31', NULL, NULL),
('descuentos', 2, 1, NULL, 1, '2018-01-31', NULL, NULL),
('descuentos', 3, 1, NULL, 1, '2018-01-31', NULL, NULL),
('descuentos', 4, 1, NULL, 1, '2018-01-31', NULL, NULL),
('descuentos', 5, 1, NULL, 1, '2018-01-31', NULL, NULL),
('descuentos', 6, 1, NULL, 1, '2018-01-31', NULL, NULL),
('descuentos', 7, 1, NULL, 1, '2018-01-31', NULL, NULL);