CREATE TABLE IF NOT EXISTS `socio` (
  `idSocio` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(55) COLLATE latin1_spanish_ci NOT NULL,
  `apellido` varchar(55) COLLATE latin1_spanish_ci NOT NULL,
  `penalizado` date DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `fechaUltimaMod` datetime NOT NULL,
  PRIMARY KEY (`idSocio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;