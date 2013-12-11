-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 11-12-2013 a las 16:03:57
-- Versión del servidor: 5.5.27
-- Versión de PHP: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `prestamosaj`
--
CREATE DATABASE `prestamosaj` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
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
(5600300, 2200000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `cedulaCliente` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `direccion` varchar(60) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `nPrestamos` int(11) NOT NULL,
  PRIMARY KEY (`cedulaCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`cedulaCliente`, `nombre`, `direccion`, `telefono`, `nPrestamos`) VALUES
(12548, 'prueba_2', '1234568', 'direcion_prueba', 3),
(24586, 'prueba_5', 'prueba', '1325487', 0),
(158796, 'prueba_3', 'mz_prueba', '125487955', 2),
(254879, 'prueba_7', 'final_combo', '2547986', 2),
(1243568, 'prueba_6', 'prueba_combo', '312458796', 2),
(1254896, 'prueba_nuevoclien', 'prueba', '235487', 0),
(12348577, 'prueba registro', 'prueba', '1235873', 2),
(12348578, 'prueba registro', 'prueba', '1235873', 1),
(12354487, 'prueba registro2', 'prueba', '1254875', 2),
(109045258, 'prueba', 'prueba_dir', '312458695', 2),
(123456789, 'prueba_4', 'prueba', '312456687', 1),
(125468745, 'registro de prueba', 'prueba', '123456789', 1),
(1093763837, 'john andrey', 'Mz E-9 lote 12', '3016015787', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`id`, `dinero`, `concepto`, `fecha`) VALUES
(1, 100000, 'Factura\r\n				', '2013-11-28'),
(2, 50000, 'factura celular				', '2013-11-28'),
(3, 50000, 'prueba\r\n				', '2013-11-28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE IF NOT EXISTS `pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cedulaPagos` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `abonoCapital` int(11) NOT NULL,
  `abonoInteres` int(11) NOT NULL,
  `saldo` int(11) NOT NULL,
  `numeroPresta` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cedula` (`cedulaPagos`),
  KEY `numeroPresta` (`numeroPresta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `cedulaPagos`, `fecha`, `abonoCapital`, `abonoInteres`, `saldo`, `numeroPresta`) VALUES
(1, 254879, '2013-12-10', 100000, 100000, 800000, 7),
(2, 1243568, '2013-12-10', 100000, 100000, 0, 6),
(3, 1243568, '2013-12-10', 100000, 100000, 0, 6),
(4, 254879, '2013-12-10', 100000, 100000, 0, 7),
(5, 254879, '2013-12-10', 100000, 100000, 0, 7),
(6, 12348577, '2013-12-11', 100000, 100000, 700000, 16),
(7, 12548, '2013-12-11', 100000, 100000, 900000, 17);

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
  `notificacion` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `cedula` (`cedula`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`codigo`, `cedula`, `monto`, `saldo`, `NcuotasQ`, `NcuotasM`, `Vcuota`, `fechaPrestamo`, `fechaPago`, `interes`, `saldoInteres`, `condicion`, `notificacion`, `mes`) VALUES
(5, 12548, 1000000, 1000000, '10', '5', 150000, '2013-11-29', '2013-12-23', 500000, 500000, 'nopago', 3, 0),
(6, 1243568, 1000000, 0, '10', '5', 150000, '2013-11-12', '2014-04-11', 500000, 0, 'nopago', 3, 1),
(7, 254879, 1000000, 0, '10', '5', 150000, '2013-11-12', '2014-04-11', 450000, 0, 'nopago', 3, 1),
(8, 158796, 1000000, 500000, '10', '5', 150000, '2013-11-12', '2014-05-12', 500000, 50000, 'nopago', 3, 1),
(9, 12348577, 1000000, 500000, '15', '7.5', 116667, '2013-11-20', '2014-03-20', 750000, 350000, 'nopago', 0, 0),
(10, 12354487, 1000000, 1000000, '12', '6', 133333, '2013-11-20', '2014-04-20', 600000, 600000, 'nopago', 0, 0),
(11, 12354487, 2000000, 2000000, '10', '5', 300000, '2013-11-20', '2014-03-20', 1000000, 1000000, 'nopago', 0, 0),
(13, 1243568, 1000000, 900000, '10', '5', 150000, '2013-11-20', '2013-11-30', 500000, 400000, 'nopago', 0, 0),
(14, 123456789, 1000000, 1000000, '15', '7.5', 116667, '2013-11-20', '2014-02-21', 750000, 750000, 'nopago', 0, 0),
(15, 158796, 1000000, 1000000, '10', '5', 150000, '2013-11-20', '2014-03-31', 500000, 500000, 'nopago', 0, 0),
(16, 12348577, 1000000, 700000, '10', '5', 150000, '2013-11-20', '2014-04-20', 500000, 300000, 'nopago', 1, 1),
(17, 12548, 1000000, 900000, '10', '5', 150000, '2013-11-29', '2013-12-23', 500000, 400000, 'nopago', 1, 1),
(18, 254879, 1000000, 1000000, '15', '7.5', 116667, '2013-11-23', '2014-03-23', 750000, 750000, 'nopago', 0, 0),
(19, 12348578, 1000000, 900000, '10', '5', 150000, '2013-11-23', '2014-03-24', 500000, 400000, 'nopago', 1, 0),
(20, 1093763837, 1000000, 900000, '10', '5', 150000, '2013-12-01', '2014-03-31', 500000, 400000, 'nopago', 0, 0),
(21, 125468745, 2000000, 1800000, '10', '5', 300000, '2013-12-02', '2013-12-16', 1000000, 900000, 'nopago', 1, 0);

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
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`numeroPresta`) REFERENCES `prestamos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`cedulaPagos`) REFERENCES `clientes` (`cedulaCliente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `clientes` (`cedulaCliente`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
