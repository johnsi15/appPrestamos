-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 17-12-2013 a las 17:05:54
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
(3100000, 100000);

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
(1093, 'andrey', 'mz x9', '3016015787', 1),
(1093763837, 'john', 'mz e9 lote 12', '3016015787', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `cedulaPagos`, `fecha`, `abonoCapital`, `abonoInteres`, `saldo`, `numeroPresta`) VALUES
(1, 1093, '2013-12-17', 100000, 100000, 900000, 1);

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
  `inicio` varchar(2) NOT NULL,
  `notificacion` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `tipo` varchar(5) NOT NULL,
  `porcentaje` int(11) NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `cedula` (`cedula`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`codigo`, `cedula`, `monto`, `saldo`, `NcuotasQ`, `NcuotasM`, `Vcuota`, `fechaPrestamo`, `fechaPago`, `interes`, `saldoInteres`, `inicio`, `notificacion`, `mes`, `tipo`, `porcentaje`) VALUES
(1, 1093, 1000000, 900000, '10', '5', 300000, '2013-12-16', '2014-01-01', 500000, 400000, '0', 1, 0, 'm', 10),
(2, 1093763837, 1000000, 1000000, '10', '5', 150000, '2013-12-17', '2014-01-02', 500000, 500000, '0', 0, 0, 'q', 10);

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
(1, 'alvaro', '8cb2237d0679ca88db6464eac60da96345513964');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`cedulaPagos`) REFERENCES `clientes` (`cedulaCliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`numeroPresta`) REFERENCES `prestamos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `clientes` (`cedulaCliente`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
