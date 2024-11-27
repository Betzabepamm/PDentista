-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-11-2024 a las 22:55:34
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `consultorio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `Id_cita` int(11) NOT NULL,
  `Fecha` varchar(25) DEFAULT NULL,
  `Hora` varchar(25) DEFAULT NULL,
  `Id_paciente` int(11) DEFAULT NULL,
  `Id_pago` int(11) DEFAULT NULL,
  `Id_personal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`Id_cita`, `Fecha`, `Hora`, `Id_paciente`, `Id_pago`, `Id_personal`) VALUES
(8, '2024-12-03', '12:00', 1, NULL, 15),
(15, '2024-11-29', '17:00', 2, NULL, 21),
(16, '2024-12-01', '09:00', 2, NULL, 2),
(17, '2024-11-24', '11:00', 2, NULL, 2),
(18, '2024-11-24', '09:00', 3, NULL, 3),
(20, '2024-11-24', '09:00', 3, NULL, 9),
(24, '2024-11-28', '09:00', 2, NULL, 15),
(30, '2024-11-20', '09:00', 1, NULL, 1),
(31, '2024-11-19', '09:00', 1, NULL, 1),
(32, '2024-11-19', '09:00', 1, NULL, 1),
(33, '2024-11-19', '09:00', 1, NULL, 1),
(34, '2024-11-19', '09:00', 1, NULL, 1),
(35, '2024-11-19', '09:00', 1, NULL, 1),
(37, '2024-11-19', '09:00', 4, NULL, 2),
(38, '2024-11-20', '09:00', 1, NULL, 19),
(39, '2024-11-19', '09:00', 1, NULL, 15),
(41, '2024-11-24', '09:00', 1, NULL, 21),
(42, '2024-11-24', '09:00', 7, NULL, 19),
(43, '2024-11-21', '17:00', 1, NULL, 21),
(47, '2024-11-22', '11:30', 17, 3, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `Id_paciente` int(10) NOT NULL,
  `Nombre` varchar(25) NOT NULL,
  `Apellido_Paterno` varchar(25) NOT NULL,
  `Apellido_Materno` varchar(30) NOT NULL,
  `Fecha_nacimiento` date NOT NULL,
  `Genero` varchar(10) NOT NULL,
  `Direccion` varchar(50) NOT NULL,
  `Telefono` int(10) NOT NULL,
  `Diagnostico` varchar(200) NOT NULL,
  `Usuario` varchar(25) NOT NULL,
  `Contrasena` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`Id_paciente`, `Nombre`, `Apellido_Paterno`, `Apellido_Materno`, `Fecha_nacimiento`, `Genero`, `Direccion`, `Telefono`, `Diagnostico`, `Usuario`, `Contrasena`) VALUES
(1, 'BETZABE PAMELA', 'RODRIGUEZ', 'LOPEZ', '2024-11-22', 'Femenino', 'AV. MORELOS S/N', 2147483647, 'Pendiente', 'BPRL', '1'),
(2, 'BETZABE PAMELA', 'RODRIGUEZ', 'LOPEZ', '2022-12-09', 'Femenino', 'AV. MORELOS S/N', 2147483647, 'Pendiente', 'BP', '1'),
(3, 'Aixa', 'Cabrera', 'Gutierrez', '2024-11-05', 'Femenino', 'AV. MORELOS S/N', 2147483647, 'Pendiente', 'Thaily', '1'),
(4, 'SANDRA', 'ALVARADO', 'LOPEZ', '2024-11-10', 'Femenino', 'AV. MORELOS S/N', 2147483647, 'Pendiente', 'MARY', '1'),
(5, 'CAMILA', 'RODRIGUEZ', 'LOPEZ', '2024-11-20', 'Femenino', 'AV. MORELOS S/N', 2147483647, 'Pendiente', 'Cami', '1'),
(6, 'CAMILA', 'RODRIGUEZ', 'LOPEZ', '2024-11-14', 'Femenino', 'AV. MORELOS S/N', 2147483647, 'Pendiente', 'CA', '1'),
(7, 'KAREN', 'ALVARADO', 'MILLAN', '2024-11-05', 'Femenino', 'AV. MORELOS S/N', 2147483647, 'Pendiente', 'KAM', '1'),
(8, 'CAMILA', 'RODRIGUEZ', 'LOPEZ', '2024-11-13', 'Femenino', 'AV. MORELOS S/N', 2147483647, 'Pendiente', 'C', '1'),
(10, 'Lezly', 'Lopez', 'Mendoza', '2024-11-06', 'Femenino', 'AV. MORELOS S/N', 2147483647, 'Pendiente', 'Lez', '1'),
(14, 'Iris', 'RODRIGUEZ', 'LOPEZ', '2024-11-02', 'Femenino', 'AV. MORELOS S/N', 2147483647, 'huhi', 'C', '$2y$10$QVk9JFzrMFThZdx.Kd'),
(16, 'Emilio', 'RODRIGUEZ', 'LOPEZ', '2024-11-23', 'Masculino', 'AV. MORELOS S/N', 2147483647, 'DHVUISD', 'M', '$2y$10$Yn5sQpfih0YBlIniyM'),
(17, 'Jesus', 'Mares', 'Montes', '2024-11-12', 'Masculino', 'AV. MORELOS S/N', 2147483647, 'Pendiente', 'jesus', '12345');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `Id_personal` int(10) NOT NULL,
  `Nombre` varchar(25) NOT NULL,
  `Apellido_Paterno` varchar(25) NOT NULL,
  `Apellido_Materno` varchar(30) NOT NULL,
  `Tipo` varchar(25) NOT NULL,
  `Especialidad` varchar(30) NOT NULL,
  `Telefono` int(10) NOT NULL,
  `Correo` varchar(25) NOT NULL,
  `Estatus` varchar(25) NOT NULL,
  `Usuario` varchar(20) NOT NULL,
  `Contrasena` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`Id_personal`, `Nombre`, `Apellido_Paterno`, `Apellido_Materno`, `Tipo`, `Especialidad`, `Telefono`, `Correo`, `Estatus`, `Usuario`, `Contrasena`) VALUES
(1, 'BETZABE PAMELA', 'RODRIGUEZ', 'LOPEZ', 'dentista', '', 2147483647, 'pr6766759@gmail.com', 'activo', 'Pamm', '1234'),
(2, 'CAMILA', 'RODRIGUEZ', 'LOPEZ', 'dentista', 'Pedriatra', 2147483647, 'pr6766759@gmail.com', 'Activo', 'Cam', '1'),
(3, 'EMILIO', 'RODRIGUEZ', 'LOPEZ', 'dentista', '', 2147483647, 'pr6766759@gmail.com', 'activo', 'Emi', '$2y$10$R1/'),
(4, 'Alexander', 'RODRIGUEZ', 'LOPEZ', 'administrador', '', 2147483647, 'pr6766759@gmail.com', 'activo', 'Ale', '$2y$10$jEH'),
(5, 'BETZABE PAMELA', 'RODRIGUEZ', 'LOPEZ', 'dentista', '', 2147483647, 'pr6766759@gmail.com', 'activo', 'Betza', '$2y$10$r0K'),
(6, 'PAMELA', 'RODRIGUEZ', 'LOPEZ', 'dentista', '', 2147483647, 'pr6766759@gmail.com', 'activo', 'Pame', '$2y$10$KV7'),
(7, 'DIANA', 'RODRIGUEZ', 'LOPEZ', 'administrador', '', 2147483647, 'estradamezaangel@gmail.co', 'activo', 'Dian', '$2y$10$mLg'),
(8, 'BETZABE PAMELA', 'RODRIGUEZ', 'LOPEZ', 'dentista', '', 2147483647, 'pr6766759@gmail.com', 'activo', 'Ro', '1234567891'),
(9, 'JUAN', 'RODRIGUEZ', 'LOPEZ', 'dentista', '', 2147483647, 'pr6766759@gmail.com', 'activo', 'EDU', '1'),
(10, 'BETZABE PAMELA', 'RODRIGUEZ', 'LOPEZ', 'recepcionista', '', 2147483647, 'pr6766759@gmail.com', 'activo', 'BETZABE', '2'),
(11, 'BETZABE PAMELA', 'RODRIGUEZ', 'LOPEZ', 'paciente', '', 0, '', 'activo', 'BPRL', '1'),
(13, 'BETZABE PAMELA', 'RODRIGUEZ', 'LOPEZ', 'paciente', '', 0, '', 'activo', 'BP', '1'),
(14, 'Aixa', 'Cabrera', 'Gutierrez', 'paciente', '', 0, '', 'activo', 'Thaily', '1'),
(15, 'AXEL', 'MENDOZA', 'MEJIA', 'dentista', 'Ortodoncia', 2147483647, 'estradamezaangel@gmail.co', 'activo', 'ALEX', '1'),
(16, 'SANDRA', 'ALVARADO', 'LOPEZ', 'paciente', '', 0, '', 'activo', 'MARY', '1'),
(17, 'CAMILA', 'RODRIGUEZ', 'LOPEZ', 'paciente', '', 0, '', 'activo', 'Cami', '1'),
(18, 'CAMILA', 'RODRIGUEZ', 'LOPEZ', 'paciente', '', 0, '', 'activo', 'CA', '1'),
(19, 'JACOB', 'GONZALEZ', 'REZA', 'dentista', 'Endodoncia', 2147483647, 'pr6766759@gmail.com', 'activo', 'Jacob', '1'),
(20, 'KAREN', 'ALVARADO', 'MILLAN', 'paciente', '', 0, '', 'activo', 'KAM', '1'),
(21, 'Alonso', 'RODRIGUEZ', 'SANCHEZ', 'dentista', 'Pedriatra', 2147483647, 'pr6766759@gmail.com', 'activo', 'ALO', '1234'),
(22, 'Janeth', 'RODRIGUEZ', 'LOPEZ', 'administrador', '', 2147483647, 'pr6766759@gmail.com', 'activo', 'Ja', '$2y$10$fi/'),
(23, 'Daniela', 'RODRIGUEZ', 'LOPEZ', 'administrador', '', 2147483647, 'pr6766759@gmail.com', 'activo', 'DA', '1'),
(24, 'CAMILA', 'RODRIGUEZ', 'LOPEZ', 'paciente', '', 0, '', 'activo', 'C', '1'),
(25, 'MARTHA', 'RODRIGUEZ', 'LOPEZ', 'paciente', '', 0, '', 'activo', 'M', '1'),
(26, 'Lezly', 'Lopez', 'Mendoza', 'paciente', '', 0, '', 'activo', 'Lez', '1'),
(36, 'Ivan', 'MENDOZA', 'LOPEZ', 'paciente', '', 0, '', 'activo', 'I', '1'),
(37, 'Liam', 'RODRIGUEZ', 'LOPEZ', 'paciente', '', 0, '', 'activo', 'L', '1'),
(38, 'Thaily', 'Cabrera', 'Gutierrez', 'paciente', '', 0, '', 'activo', 'A', '1'),
(39, 'Jesus', 'Mares', 'Montes', 'paciente', '', 0, '', 'activo', 'jesus', '12345');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_de_pago`
--

CREATE TABLE `registro_de_pago` (
  `Id_pago` int(11) NOT NULL,
  `Fecha_pago` int(11) NOT NULL,
  `Monto` int(11) NOT NULL,
  `Id_cita` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_de_pago`
--

INSERT INTO `registro_de_pago` (`Id_pago`, `Fecha_pago`, `Monto`, `Id_cita`) VALUES
(3, 2024, 600, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tratamiento`
--

CREATE TABLE `tratamiento` (
  `Id_tratamiento` int(11) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `observaciones` varchar(200) DEFAULT NULL,
  `historial_de_cambios` varchar(255) NOT NULL,
  `Id_cita` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tratamiento`
--

INSERT INTO `tratamiento` (`Id_tratamiento`, `descripcion`, `observaciones`, `historial_de_cambios`, `Id_cita`) VALUES
(2, 'ortodondcia', 'qwdijdcowdj', '', 24),
(3, 'jvdjvcidj', 'jciojoic', '', 24),
(4, 'fcjdojcdo', 'kfcdkci', '', 24),
(5, 'dicjdcj', 'jdcjidjc', 'Tratamiento registrado: dicjdcj - jdcjidjc (Fecha: 2024-11-19 18:11:36)', 24),
(6, 'dkcdskc', 'jncdi', 'Tratamiento registrado: dkcdskc - jncdi (Fecha: 2024-11-19 18:12:18)', 8),
(7, 'dvnidvpds', 'kjdvikdpok', 'Tratamiento registrado: dkcdskc - jncdi (Fecha: 2024-11-19 18:12:18)\nTratamiento registrado: dvnidvpds - kjdvikdpok (Fecha: 2024-11-19 18:12:33)', 8),
(8, 'jviofjvo', 'j oijdi', 'Tratamiento registrado: dkcdskc - jncdi (Fecha: 2024-11-19 18:12:18)\nTratamiento registrado: jviofjvo - j oijdi (Fecha: 2024-11-19 18:16:07)', 8),
(9, 'hdhvoi', 'jvifjvio', 'Tratamiento registrado: dkcdskc - jncdi (Fecha: 2024-11-19 18:12:18)\nTratamiento registrado: hdhvoi - jvifjvio (Fecha: 2024-11-19 18:18:49)', 8),
(10, 'jfiosdjvoidj', 'jopdskvop', 'Tratamiento registrado: dkcdskc - jncdi (Fecha: 2024-11-19 18:12:18)\nTratamiento registrado: jfiosdjvoidj - jopdskvop (Fecha: 2024-11-19 18:19:48)', 8);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`Id_cita`),
  ADD KEY `Id_paciente` (`Id_paciente`),
  ADD KEY `Id_pago` (`Id_pago`),
  ADD KEY `Id_personal` (`Id_personal`) USING BTREE;

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`Id_paciente`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`Id_personal`);

--
-- Indices de la tabla `registro_de_pago`
--
ALTER TABLE `registro_de_pago`
  ADD PRIMARY KEY (`Id_pago`),
  ADD KEY `Id_cita` (`Id_cita`);

--
-- Indices de la tabla `tratamiento`
--
ALTER TABLE `tratamiento`
  ADD PRIMARY KEY (`Id_tratamiento`),
  ADD KEY `Id_cita` (`Id_cita`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `Id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `Id_paciente` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `Id_personal` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `registro_de_pago`
--
ALTER TABLE `registro_de_pago`
  MODIFY `Id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tratamiento`
--
ALTER TABLE `tratamiento`
  MODIFY `Id_tratamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`Id_paciente`) REFERENCES `pacientes` (`Id_paciente`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`Id_pago`) REFERENCES `registro_de_pago` (`Id_pago`),
  ADD CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`Id_personal`) REFERENCES `personal` (`Id_personal`);

--
-- Filtros para la tabla `registro_de_pago`
--
ALTER TABLE `registro_de_pago`
  ADD CONSTRAINT `registro_de_pago_ibfk_1` FOREIGN KEY (`Id_cita`) REFERENCES `citas` (`Id_cita`);

--
-- Filtros para la tabla `tratamiento`
--
ALTER TABLE `tratamiento`
  ADD CONSTRAINT `tratamiento_ibfk_1` FOREIGN KEY (`Id_cita`) REFERENCES `citas` (`Id_cita`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
