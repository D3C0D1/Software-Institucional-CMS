-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 26-11-2025 a las 14:14:36
-- Versión del servidor: 10.11.14-MariaDB
-- Versión de PHP: 8.3.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `poli_policaribe`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `descripcion_corta` text NOT NULL,
  `contenido_completo` longtext NOT NULL,
  `imagen_principal` varchar(255) DEFAULT NULL,
  `autor` varchar(100) NOT NULL,
  `fecha_publicacion` datetime NOT NULL,
  `estado` enum('borrador','publicado','archivado') DEFAULT 'borrador',
  `visitas` int(11) DEFAULT 0,
  `destacado` tinyint(1) DEFAULT 0,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id`, `titulo`, `slug`, `descripcion_corta`, `contenido_completo`, `imagen_principal`, `autor`, `fecha_publicacion`, `estado`, `visitas`, `destacado`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(25, 'Participación en la Mesa Sectorial de Cultura e Innovación Ciudadana', 'participacin-en-la-mesa-sectorial-de-cultura-e-innovacin-ciudadana', 'Nuestro Rector Darwin Romero, participó en la sesión de la Mesa Sectorial de Cultura e Innovación Ciudadana en las instalaciones de la Corporación Universitaria', '<p>Nuestro Rector <strong>Darwin Romero</strong>, particip&oacute; en la sesi&oacute;n de la <strong>Mesa Sectorial de Cultura e Innovaci&oacute;n Ciudadana</strong> en las instalaciones de la <strong>Corporaci&oacute;n Universitaria del Caribe - CECAR</strong>, quien conjuntamente con <strong>UNISUCRE</strong>, <strong>ProSincelejo</strong> y la <strong>Comisi&oacute;n Regional de Competitividad e Innovaci&oacute;n</strong> articulan estrategias para el fortalecimiento de la cultura ciudadana del Departamento de Sucre.</p>\r\n<p>La apuesta es lograr transformar realidades sociales a partir de apuestas conjuntas de las entidades participantes en el territorio sucre&ntilde;o.</p>', 'https://policaribe.edu.co/uploads/articulos/img_6919bc88957c44.37013207_1763294344.jpg', 'Comunicaciones', '2025-03-15 19:30:00', 'publicado', 16, 0, '2025-11-16 01:43:06', '2025-11-22 14:21:42'),
(26, 'La Secretaría de Educación de Sincelejo aprueba nuevos programas del Politécnico del Caribe', 'la-secretara-de-educacin-de-sincelejo-aprueba-nuevos-programas-del-politcnico-del-caribe', 'Programas Aprobados:\r\n  \r\n    Técnico Laboral en Auxiliar de Educación para la Primera Infancia\r\n    Técnico Laboral en Auxiliar Administrativo\r\n    Técnico Laboral', '<h2>Programas Aprobados:</h2>\r\n<ol>\r\n<li>T&eacute;cnico Laboral en Auxiliar de Educaci&oacute;n para la Primera Infancia</li>\r\n<li>T&eacute;cnico Laboral en Auxiliar Administrativo</li>\r\n<li>T&eacute;cnico Laboral en Auxiliar Contable y Financiero</li>\r\n<li>T&eacute;cnico Laboral en Entrenadores y Preparadores F&iacute;sicos</li>\r\n<li>T&eacute;cnico Laboral en Asistente de Marketing y Comunicaciones</li>\r\n<li>T&eacute;cnico Laboral en Animador Gr&aacute;fico y de Multimedia</li>\r\n<li>T&eacute;cnico Laboral en Auxiliar en Seguridad Ocupacional y Laboral</li>\r\n</ol>\r\n<p>Cada programa cuenta con una duraci&oacute;n entre <strong>840 y 960 horas</strong>, distribuidas equitativamente entre formaci&oacute;n te&oacute;rica y pr&aacute;ctica, y se ofrece en jornadas diurnas y sabatinas bajo metodolog&iacute;as presencial y a distancia. El valor total de cada programa fue fijado en <strong>$3.800.000</strong>.</p>\r\n<p>El <strong>Secretario de Educaci&oacute;n Municipal</strong>, <strong>Diego Alberto Galv&aacute;n Mestra</strong>, destac&oacute; que esta aprobaci&oacute;n responde al compromiso con el fortalecimiento de la formaci&oacute;n para el trabajo en el municipio, asegurando que la oferta educativa se ajuste a las necesidades del entorno productivo regional y nacional.</p>\r\n<p>La resoluci&oacute;n estipula adem&aacute;s que la vigencia de estos registros depender&aacute; de su renovaci&oacute;n oportuna por parte de la instituci&oacute;n, y delega a la Secretar&iacute;a la evaluaci&oacute;n, inspecci&oacute;n y control del servicio.</p>\r\n<p>Con esta aprobaci&oacute;n, el Polit&eacute;cnico del Caribe contin&uacute;a consolid&aacute;ndose como una alternativa de calidad para los j&oacute;venes de Sincelejo y la regi&oacute;n Caribe que buscan formaci&oacute;n t&eacute;cnica laboral pertinente, accesible y con proyecci&oacute;n laboral inmediata.</p>', 'https://policaribe.edu.co/uploads/articulos/img_6919bc30475c36.55817529_1763294256.jpg', 'Comunicaciones', '2025-03-15 19:30:00', 'publicado', 19, 0, '2025-11-16 01:43:06', '2025-11-25 19:32:42'),
(27, '🎓 El Politécnico del Caribe celebró la graduación de una nueva generación de técnicos en Sincé', 'el-politcnico-del-caribe-celebr-la-graduacin-de-una-nueva-generacin-de-tcnicos-en-sinc', 'Estudiantes recibieron su título como técnicos laborales por competencias durante la ceremonia de grados del Politécnico del Caribe, celebrada el pasado 23 de m', 'Estudiantes recibieron su título como técnicos laborales por competencias durante la ceremonia de grados del Politécnico del Caribe, celebrada el pasado 23 de mayo en el Centro Familiar Cristiano de Sincé.\n\nEl evento congregó a familias, docentes, directivos y miembros de la comunidad educativa, quienes acompañaron con emoción este logro que marca el inicio de una nueva etapa profesional para los egresados.\n\nProgramas como Auxiliar Administrativo, Educación Infantil, Contabilidad y Finanzas, Seguridad Ocupacional, Marketing Digital y Animación Gráfica, fueron protagonistas en esta jornada que destacó el poder transformador de la formación técnica en el territorio.\n\n\n“Este título no solo les pertenece a ustedes, sino también a sus familias. Con él, se abren puertas al mundo laboral y a nuevas oportunidades de crecimiento personal”, expresó el Dr. Noel Alfonso Morales Tuesca, presidente del Politécnico del Caribe, durante su intervención.\n\n\nDesde la Rectoría, se reafirmó el compromiso de la institución con el desarrollo del departamento y la formación pertinente: “Nuestros graduandos son ahora embajadores de esta casa académica, preparados para contribuir a una sociedad más productiva y humana”, sostuvo el rector Darwin Romero Vergara.\n\n\nLa ceremonia finalizó entre aplausos, fotografías y abrazos, ratificando que la educación técnica sigue siendo una alternativa real y poderosa para la transformación del Caribe colombiano\n\n<img src=\"../images/500911189_18359072110178622_2553736421064823199_n.jpg\" alt=\"\">\n\n<img src=\"../images/501597692_18359072119178622_1341319888138504766_n.jpg\" alt=\"\">\n\n<img src=\"../images/501299158_18359072167178622_1946865859928436657_n.jpg\" alt=\"\">\n\n<img src=\"../images/500966653_18359072188178622_5891724596366718670_n.jpg\" alt=\"\">\n\n<img src=\"../images/500703098_18359072146178622_703373854213069675_n.jpg\" alt=\"\">', 'policaribe/images/25%20de%20mayo%202025/500911189_18359072110178622_2553736421064823199_n.jpg', 'Super User', '2025-05-25 14:04:31', 'publicado', 29, 0, '2025-11-16 01:43:06', '2025-11-23 14:13:08'),
(28, 'Politécnico del Caribe fortalece la formación de sus estudiantes con visita académica a la Corporación Universitaria Antonio José de Sucre.', 'politcnico-del-caribe-fortalece-la-formacin-de-sus-estudiantes-con-visita-acadmica-a-la-corporacin-universitaria-antonio-jos-de-sucre', 'El Politécnico del Caribe realizó una visita académica desde su programa de Entrenamiento y Preparación Física a las instalaciones de la Corporación Universitaria Antonio José de S...', '<p>El Polit&eacute;cnico del Caribe realiz&oacute; una visita acad&eacute;mica desde su programa de Entrenamiento y Preparaci&oacute;n F&iacute;sica a las instalaciones de la Corporaci&oacute;n Universitaria Antonio Jos&eacute; de Sucre, en donde recibieron distintas charlas en varios espacios donde se trataron temas de psicolog&iacute;a, fisioterapia y rehabilitaci&oacute;n f&iacute;sica adem&aacute;s de primeros auxilios.</p>\r\n<p>Darwin Vergara, Rector de POLICARIBE asegur&oacute; que estos espacios se generan en pro de la mejora continua en cuanto a el aprendizaje de los estudiantes del programa de deportes, adem&aacute;s, que se buscan constantemente alianzas y convenios para que los futuros egresados de la instituci&oacute;n obtengan mejores resultados de aprendizaje.</p>\r\n<p>De igual forma se le present&oacute; a los estudiantes la oferta acad&eacute;mica de UAJS en donde conocieron por parte del &aacute;rea de promoci&oacute;n los programas por los cuales pudieran estar interesados para continuar en formaci&oacute;n.</p>\r\n<p>Cristian Hern&aacute;ndez estudiante del programa de deportes, indic&oacute; que el acompa&ntilde;amiento por parte de varios profesionales que retroalimentan el conocimiento y mejoran sus conocimientos. Agradeci&oacute; a UAJS por el recorrido por los distintos laboratorios y las practicas que realizaron durante la jornada.</p>\r\n<p>Angie Villegas, estudiante del programa indic&oacute; que las charlas recibidas fueron de gran aporte y de satisfacci&oacute;n como estudiantes en formaci&oacute;n del Polit&eacute;cnico del Caribe, resaltando que siguen en el proceso de aprendizaje y form&aacute;ndose en &aacute;reas de entrenamiento y salud, adem&aacute;s agradeci&oacute; a UAJS por abrir las puertas de la instituci&oacute;n.</p>\r\n<p>El Polit&eacute;cnico del Caribe reafirma su compromiso con la educaci&oacute;n de calidad y la formaci&oacute;n integral de sus estudiantes, promoviendo constantemente espacios de intercambio acad&eacute;mico y experiencias pr&aacute;cticas que fortalezcan su desarrollo profesional. A trav&eacute;s de alianzas con instituciones como la Corporaci&oacute;n Universitaria Antonio Jos&eacute; de Sucre, POLICARIBE contin&uacute;a impulsando el aprendizaje significativo, la excelencia acad&eacute;mica y la preparaci&oacute;n de futuros profesionales co</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>', '/showimagen/showimage/noticia%201/WhatsApp%20Image%202025-11-03%20at%2017.18.14%20%281%29.jpeg', 'Policaribe', '2025-11-07 12:00:00', 'publicado', 20, 0, '2025-11-16 05:59:07', '2025-11-22 15:25:50'),
(29, 'Politécnico del Caribe impulsa la gestión deportiva y empresarial en Sucre.', 'politcnico-del-caribe-impulsa-la-gestin-deportiva-y-empresarial-en-sucre', 'El Politécnico del Caribe realizó el Seminario Deportivo y Gestión Empresarial, donde...', '<p>El Polit&eacute;cnico del Caribe realiz&oacute; el Seminario Deportivo y Gesti&oacute;n Empresarial, donde</p>\r\n<p>participaron distintos invitados del &aacute;mbito acad&eacute;mico y gubernamental, quienes</p>\r\n<p>compartieron estrategias y alianzas que apunten al beneficio de los estudiantes del</p>\r\n<p>POLICARIBE.</p>\r\n<p>El Rector de la instituci&oacute;n Darwin Vergara, expres&oacute; que estos espacios ayudan a</p>\r\n<p>consolidar la competitividad de los futuros egresados apuntando al beneficio de la</p>\r\n<p>regi&oacute;n en especial del departamento de Sucre, atendiendo la realizaci&oacute;n de los juegos</p>\r\n<p>nacionales y para nacionales 2027.</p>\r\n<p>El director de INDER Sucre, Samuel &Aacute;lvarez quien hizo parte del evento, asegur&oacute; que la</p>\r\n<p>jornada acad&eacute;mica fue todo un &eacute;xito, pues se logra que los entrenadores que se est&aacute;n</p>\r\n<p>formando en el Polit&eacute;cnico del Caribe logren obtener su t&iacute;tulo oficial como T&eacute;cnico</p>\r\n<p>Laboral en el mes de diciembre a trav&eacute;s del convenio establecido con el INDER y as&iacute;</p>\r\n<p>puedan acceder a la contrataci&oacute;n p&uacute;blica.</p>\r\n<p>De igual forma, Alfredo Casta&ntilde;eda, director del Programa de Deportes de la Universidad</p>\r\n<p>Aut&oacute;noma del Caribe, se refiri&oacute; al convenio alcanzado entre las dos instituciones para el</p>\r\n<p>proceso de homologaci&oacute;n de su formaci&oacute;n y pasar de formaci&oacute;n t&eacute;cnica a formaci&oacute;n</p>\r\n<p>profesional.</p>\r\n<p>Noel Morales presidente del Polit&eacute;cnico del Caribe, asegur&oacute; que es necesario entender</p>\r\n<p>la importancia de la gesti&oacute;n deportiva para el desarrollo de la regi&oacute;n atendiendo los</p>\r\n<p>pr&oacute;ximos juegos nacionales.</p>\r\n<p>Por su parte Elba Barrera directora de la Comisi&oacute;n Regional de Competitividad e</p>\r\n<p>innovaci&oacute;n, asegur&oacute; que estos procesos garantizan el desarrollo de la regi&oacute;n de manera</p>\r\n<p>integral pues deja un beneficio en t&eacute;rminos de infraestructura y econom&iacute;a.</p>\r\n<p>A trav&eacute;s de estos eventos el Polit&eacute;cnico del Caribe, contin&uacute;a mejorando las capacidades</p>\r\n<p>de sus estudiantes brind&aacute;ndole las herramientas y mostrarle todos los beneficios que</p>\r\n<p>se gestan desde la direcci&oacute;n de la instituci&oacute;n para la mejora continua de la educaci&oacute;n.</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>', '/showimagen/showimage/noticia%202/WhatsApp%20Image%202025-11-03%20at%2017.04.15%20%281%29.jpeg', 'Policaribe', '2025-11-07 12:00:00', 'publicado', 24, 0, '2025-11-16 06:06:48', '2025-11-25 00:05:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_categoria`
--

CREATE TABLE `articulo_categoria` (
  `articulo_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `articulo_categoria`
--

INSERT INTO `articulo_categoria` (`articulo_id`, `categoria_id`) VALUES
(25, 5),
(26, 5),
(28, 5),
(29, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `orden` int(11) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `slug`, `descripcion`, `orden`, `activo`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'Pregrados', 'pregrados', 'Noticias relacionadas con programas de pregrado', 1, 1, '2025-11-05 08:32:46', '2025-11-05 08:32:46'),
(2, 'Posgrados', 'posgrados', 'Noticias relacionadas con programas de posgrado', 2, 1, '2025-11-05 08:32:46', '2025-11-05 08:32:46'),
(3, 'Educación Continuada', 'educacion-continuada', 'Noticias sobre educación continuada', 3, 1, '2025-11-05 08:32:46', '2025-11-05 08:32:46'),
(4, 'Destacadas', 'destacadas', 'Noticias destacadas de la universidad', 4, 1, '2025-11-05 08:32:46', '2025-11-05 08:32:46'),
(5, 'Estudiantes', 'estudiantes', 'Noticias de interés para estudiantes', 5, 1, '2025-11-05 08:32:46', '2025-11-05 08:32:46'),
(6, 'Profesores', 'profesores', 'Noticias para el personal docente', 6, 1, '2025-11-05 08:32:46', '2025-11-05 08:32:46'),
(7, 'Internacionalización', 'internacionalizacion', 'Noticias sobre programas internacionales', 7, 1, '2025-11-05 08:32:46', '2025-11-05 08:32:46'),
(8, 'Internas', 'internas', 'Noticias internas de la universidad', 8, 1, '2025-11-05 08:32:46', '2025-11-05 08:32:46'),
(9, 'Responsabilidad Social Universitaria', 'rsu', 'Noticias sobre RSU', 9, 1, '2025-11-05 08:32:46', '2025-11-05 08:32:46'),
(10, 'Blog del Rector', 'blog-rector', 'Artículos del blog del rector', 10, 1, '2025-11-05 08:32:46', '2025-11-05 08:32:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pqrs`
--

CREATE TABLE `pqrs` (
  `id` int(11) NOT NULL,
  `radicado` varchar(50) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `identificacion` varchar(50) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `tipo` enum('felicitacion','peticion','queja','reclamo','sugerencia') NOT NULL,
  `resumen` varchar(255) NOT NULL,
  `detalle` longtext NOT NULL,
  `estado` enum('radicado','en_proceso','resuelto','cerrado') DEFAULT 'radicado',
  `fecha_radicado` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `respuesta` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pqrs_adjuntos`
--

CREATE TABLE `pqrs_adjuntos` (
  `id` int(11) NOT NULL,
  `pqrs_id` int(11) NOT NULL,
  `nombre_original` varchar(255) NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `mime` varchar(100) NOT NULL,
  `size` int(11) NOT NULL,
  `fecha_subida` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','editor') DEFAULT 'editor',
  `activo` tinyint(1) DEFAULT 1,
  `ultimo_acceso` datetime DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `activo`, `ultimo_acceso`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(2, 'Administrador', 'admin', '$2y$10$Xrw5B6IoQkM8ux2W0b5YouFXIfzYqCrGvvCsJ9Bqjbt5jCCT6NmoG', 'admin', 1, '2025-11-20 06:56:29', '2025-11-13 14:01:36', '2025-11-26 09:31:15');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_fecha_publicacion` (`fecha_publicacion`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_destacado` (`destacado`);

--
-- Indices de la tabla `articulo_categoria`
--
ALTER TABLE `articulo_categoria`
  ADD PRIMARY KEY (`articulo_id`,`categoria_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `pqrs`
--
ALTER TABLE `pqrs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `radicado` (`radicado`),
  ADD KEY `idx_tipo` (`tipo`),
  ADD KEY `idx_estado` (`estado`);

--
-- Indices de la tabla `pqrs_adjuntos`
--
ALTER TABLE `pqrs_adjuntos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pqrs_id` (`pqrs_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `pqrs`
--
ALTER TABLE `pqrs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pqrs_adjuntos`
--
ALTER TABLE `pqrs_adjuntos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articulo_categoria`
--
ALTER TABLE `articulo_categoria`
  ADD CONSTRAINT `articulo_categoria_ibfk_1` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articulo_categoria_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pqrs_adjuntos`
--
ALTER TABLE `pqrs_adjuntos`
  ADD CONSTRAINT `pqrs_adjuntos_ibfk_1` FOREIGN KEY (`pqrs_id`) REFERENCES `pqrs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
