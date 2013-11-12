-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 12-11-2013 a las 22:31:54
-- Versión del servidor: 5.6.12-log
-- Versión de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `prestamosaj`
--
CREATE DATABASE IF NOT EXISTS `prestamosaj` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `prestamosaj`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE IF NOT EXISTS `caja` (
  `baseTotal` int(11) NOT NULL,
  `interesTotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`baseTotal`, `interesTotal`) VALUES
(3100000, 400000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `cedula` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `direccion` varchar(60) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `nPrestamos` int(11) NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`cedula`, `nombre`, `direccion`, `telefono`, `nPrestamos`) VALUES
(12548, 'prueba_2', 'direcion_prueba', '1234567', 1),
(24586, 'prueba_5', 'prueba', '1325487', 0),
(158796, 'prueba_3', 'mz_prueba', '125487955', 1),
(254879, 'prueba_7', 'final_combo', '2547986', 1),
(1243568, 'prueba_6', 'prueba_combo', '312458796', 1),
(109045258, 'prueba', 'prueba_dir', '312458695', 2),
(123456789, 'prueba_4', 'prueba', '312456687', 0),
(1093763837, 'John Andrey', '3016015787', 'Mz E-9 Lote 12', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE IF NOT EXISTS `gastos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dinero` int(11) NOT NULL,
  `concepto` text NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE IF NOT EXISTS `pagos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `abonoCapital` int(11) NOT NULL,
  `abonoInteres` int(11) NOT NULL,
  `saldo` int(11) NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `cedula` (`cedula`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`codigo`, `cedula`, `fecha`, `abonoCapital`, `abonoInteres`, `saldo`) VALUES
(1, 1093763837, '0000-00-00', 100000, 50000, 0),
(2, 1093763837, '2013-11-10', 100000, 50000, 1000000),
(3, 1093763837, '2013-11-10', 100000, 50000, 900000),
(4, 1093763837, '2013-11-10', 100000, 50000, 800000),
(5, 1093763837, '2013-11-10', 100000, 50000, 900000),
(6, 109045258, '2013-11-10', 100000, 50000, 900000),
(7, 12548, '2013-11-10', 200000, 100000, 1800000),
(8, 12548, '2013-11-10', 200000, 100000, 1600000),
(9, 254879, '2013-11-12', 100000, 50000, 900000),
(10, 158796, '2013-11-12', 100000, 50000, 900000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE IF NOT EXISTS `prestamos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` int(11) NOT NULL,
  `monto` int(11) NOT NULL,
  `saldo` int(11) NOT NULL,
  `NcuotasQ` varchar(15) NOT NULL,
  `NcuotasM` varchar(15) NOT NULL,
  `Vcuota` int(11) NOT NULL,
  `fechaPrestamo` date NOT NULL,
  `fechaPago` date NOT NULL,
  `interes` int(11) NOT NULL,
  `saldoInteres` int(11) NOT NULL,
  `condicion` varchar(8) NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `cedula` (`cedula`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`codigo`, `cedula`, `monto`, `saldo`, `NcuotasQ`, `NcuotasM`, `Vcuota`, `fechaPrestamo`, `fechaPago`, `interes`, `saldoInteres`, `condicion`) VALUES
(1, 1093763837, 900000, 0, '10', '5', 150000, '2013-10-20', '2014-02-20', 400000, 0, 'nopago'),
(2, 109045258, 900000, 0, '10', '5', 150000, '2013-10-26', '2014-03-26', 450000, 0, 'nopago'),
(3, 109045258, 1000000, 0, '10', '5', 150000, '2013-10-31', '2014-03-31', 500000, 0, 'nopago'),
(4, 1093763837, 800000, 0, '10', '5', 150000, '2013-10-31', '2014-03-31', 400000, 0, 'nopago'),
(5, 12548, 1600000, 0, '10', '5', 300000, '2013-11-08', '2014-04-08', 800000, 0, 'nopago'),
(6, 1243568, 1000000, 0, '10', '5', 150000, '2013-11-12', '2014-04-11', 500000, 0, 'nopago'),
(7, 254879, 1000000, 900000, '10', '5', 150000, '2013-11-12', '2014-04-11', 450000, 0, 'nopago'),
(8, 158796, 1000000, 900000, '10', '5', 150000, '2013-11-12', '2014-05-12', 500000, 450000, 'nopago');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `clave` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `clave`) VALUES
(1, 'alvaro', '123456');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
