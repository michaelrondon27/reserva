/* Eliminar tablas */
DROP TABLE proyectos_vendedores;
DROP TABLE proyectos;
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
  	MODIFY `id_esquema` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `esquemas`
 	ADD CONSTRAINT `esquemas_ibfk_1` FOREIGN KEY (`tipo`) REFERENCES `lval` (`codlval`);

INSERT INTO `auditoria` (`tabla`, `cod_reg`, `status`, `fec_status`, `usr_regins`, `fec_regins`, `usr_regmod`, `fec_regmod`) VALUES
('esquemas', 1, 1, NULL, 11, '2018-02-02', NULL, NULL),
('esquemas', 2, 1, NULL, 11, '2018-02-02', NULL, NULL);