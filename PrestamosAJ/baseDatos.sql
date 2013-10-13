-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 13-10-2013 a las 17:06:21
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
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `cedula` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `direccion` varchar(60) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `dinero` int(11) NOT NULL,
  `fechaPago` date NOT NULL,
  `interes` int(11) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`cedula`, `nombre`, `direccion`, `telefono`, `dinero`, `fechaPago`, `interes`, `fecha`) VALUES
(12345, 'prueba_4', 'prueba_4', '123', 50000, '2013-10-31', 0, '2013-10-13'),
(102345, 'prueba', 'prueba dire', 'tel pruaba', 0, '2013-10-31', 25, '2013-10-01'),
(456123, 'prueba_6', '', '', 50000, '2013-10-31', 0, '2013-10-13'),
(123456789, 'prueba_3', 'prueba_3', '1234', 100000, '2013-10-31', 0, '2013-10-13'),
(1093763837, 'john andrey', 'MZ E-9 Lote 12', '3016015787', 200000, '2013-10-31', 20000, '2013-10-01'),
(2147483647, 'prueba_5', '', '', 50000, '2013-10-31', 0, '2013-10-13');

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
