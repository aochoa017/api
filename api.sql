-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:8889
-- Tiempo de generación: 07-06-2017 a las 00:43:00
-- Versión del servidor: 5.5.42
-- Versión de PHP: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `api`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `name` text NOT NULL,
  `surname` text NOT NULL,
  `adress` text NOT NULL,
  `city` text NOT NULL,
  `country` text NOT NULL,
  `zipCode` varchar(5) NOT NULL,
  `biography` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `profiles`
--

INSERT INTO `profiles` (`id`, `avatar`, `email`, `phone`, `name`, `surname`, `adress`, `city`, `country`, `zipCode`, `biography`) VALUES
(1, '1-admin-20170606223346.jpg', 'admin@admin.com', '692717117', 'Aitor', 'Ochoa Ramos', 'Arenal, 5 dpto. 312', 'Bilbao', 'EspaÃ±a', '48005', ''),
(2, '', 'aitor@aitor.com', '944270000', '', '', '', '', '', '', ''),
(16, '16-pris-20170604182641.jpg', 'pris@pris.com', '34567890', 'Priscila', 'Frugoni Backhaus', 'Calle de Castilla 36 4Âº iz', 'Madrid', 'EspaÃ±a', '28039', 'Profesora de inglÃ©s y alemÃ¡n'),
(17, '', 'pris@pris.com', '34567890', '', '', '', '', '', '', ''),
(18, '', 'admin@root.com', '11111111', 'admin', 'Root', 'Plaza de Haro 2, 12', 'Bilbao', 'EspaÃ±a', '48012', 'Resumen de mi biografÃ­a');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `dateCreated` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `user`, `password`, `dateCreated`) VALUES
(1, 'admin', 'admin', '2017-05-22'),
(2, 'aitor', 'inicio', '2017-05-23'),
(16, 'pris', '987654', '2017-12-05'),
(17, 'pris2', '9876', '2017-12-05'),
(18, 'admin2', 'rooooot2', '2017-06-03');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `profiles`
--
ALTER TABLE `profiles`
  ADD KEY `id` (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
