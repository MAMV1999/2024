-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-08-2024 a las 08:06:02
-- Versión del servidor: 10.1.31-MariaDB
-- Versión de PHP: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `2024`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerReporteDinamico` (IN `p_id` INT)  BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE documentoNombre VARCHAR(255);
    DECLARE documentosCursor CURSOR FOR SELECT nombre FROM documento;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    -- Inicializa la consulta SQL base
    SET @sql = 'SELECT \r\n            md.id,\r\n            ins.nombre AS institucion_nombre,\r\n            ins.telefono AS institucion_telefono,\r\n            ins.correo AS institucion_correo,\r\n            ins.direccion AS institucion_direccion,\r\n            il.nombre AS institucion_lectivo,\r\n            niv.nombre AS institucion_nivel, \r\n            ig.nombre AS institucion_grado,\r\n            mr.nombre AS matricula_razon,\r\n            a.id AS apoderado_id,\r\n            a.nombreyapellido AS apoderado_nombre, \r\n            a.telefono AS apoderado_telefono,\r\n            al.id AS alumno_id,\r\n            al.nombreyapellido AS alumno_nombre';

    -- Abre el cursor para recorrer los nombres de los documentos
    OPEN documentosCursor;
    
    -- Bucle para recorrer los resultados del cursor
    read_loop: LOOP
        FETCH documentosCursor INTO documentoNombre;
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Añade al SQL dinámico las columnas correspondientes a cada documento
        SET @sql = CONCAT(@sql, 
            ', MAX(CASE WHEN d.nombre = ''', documentoNombre, ''' THEN dd.entregado ELSE ''NO'' END) AS `', documentoNombre, '`,\r\n            MAX(CASE WHEN d.nombre = ''', documentoNombre, ''' THEN dd.observaciones ELSE '''' END) AS `', documentoNombre, '_observaciones`'
        );
    END LOOP;

    -- Cierra el cursor
    CLOSE documentosCursor;

    -- Completa el SQL dinámico con las cláusulas FROM, WHERE, GROUP BY y ORDER BY
    SET @sql = CONCAT(@sql, '\r\n        FROM \r\n            matricula_detalle md\r\n        JOIN \r\n            matricula m ON md.matricula_id = m.id\r\n        JOIN \r\n            institucion_grado ig ON m.institucion_grado_id = ig.id\r\n        JOIN \r\n            institucion_nivel niv ON ig.institucion_nivel_id = niv.id\r\n        JOIN \r\n            institucion_lectivo il ON niv.institucion_lectivo_id = il.id\r\n        JOIN \r\n            institucion ins ON il.institucion_id = ins.id\r\n        JOIN \r\n            matricula_razon mr ON md.matricula_razon_id = mr.id\r\n        JOIN \r\n            apoderado a ON md.apoderado_id = a.id\r\n        JOIN \r\n            alumno al ON md.alumno_id = al.id\r\n        LEFT JOIN \r\n            documento_detalle dd ON md.id = dd.matricula_detalle_id\r\n        LEFT JOIN \r\n            documento d ON dd.documento_id = d.id\r\n        WHERE\r\n            md.id = ', p_id, '\r\n        GROUP BY \r\n            md.id, ins.nombre, ins.telefono, ins.correo, ins.direccion, niv.nombre, ig.nombre, mr.nombre, a.id, a.nombreyapellido, a.telefono, al.id, al.nombreyapellido\r\n        ORDER BY \r\n            ins.nombre ASC, niv.nombre ASC, ig.nombre ASC, al.nombreyapellido ASC\r\n    ');

    -- Prepara y ejecuta la declaración SQL dinámica
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(50) NOT NULL,
  `apoderado_id` int(11) NOT NULL,
  `dni` varchar(15) NOT NULL,
  `nombreyapellido` varchar(100) NOT NULL,
  `observaciones` text,
  `fechacreado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('0','1') DEFAULT '1',
  `nacimiento` date NOT NULL,
  `sexo` enum('MASCULINO','FEMENINO') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`id`, `usuario`, `contraseña`, `apoderado_id`, `dni`, `nombreyapellido`, `observaciones`, `fechacreado`, `estado`, `nacimiento`, `sexo`) VALUES
(1, '91970022', '91970022', 1, '91970022', 'ORE CARHUAS, MICAELA NOEMI', '', '2024-07-24 06:11:55', '1', '2020-08-13', 'FEMENINO'),
(2, '79992036', '79992036', 1, '79992036', 'ORE CARHUAS, KALET EMANUEL', '', '2024-07-24 06:13:00', '1', '2016-12-09', 'MASCULINO'),
(3, '78412418', '78412418', 1, '78412418', 'ORE CARHUAS, JESUS DAVID', '', '2024-07-24 06:19:57', '1', '2013-12-08', 'MASCULINO'),
(4, '90994341', '90994341', 2, '90994341', 'QUISPE RIVAS, SAMANTHA NATALIA', '', '2024-07-24 06:25:34', '1', '2018-10-05', 'FEMENINO'),
(5, '77939178', '77939178', 2, '77939178', 'HERNANDEZ RIVAS, NAIA CRISTAL', '', '2024-07-24 06:26:46', '1', '2012-12-09', 'FEMENINO'),
(6, '79654643', '79654643', 3, '79654643', 'MELGAREJO CABRERA, ANTUANNETH CATALEYA INÉS', '', '2024-07-26 02:09:14', '1', '0000-00-00', 'FEMENINO'),
(7, '78636373', '78636373', 3, '78636373', 'MELGAREJO CABRERA, RAMSÉS FELIPE TADEO', '', '2024-07-26 02:10:01', '1', '0000-00-00', 'MASCULINO'),
(8, '91601455', '91601455', 4, '91601455', 'SOVERO PEREA, NICOLAS FABRIZIO', '', '2024-07-26 04:16:30', '1', '2019-11-20', 'MASCULINO'),
(9, '78169353', '78169353', 4, '78169353', 'SOVERO PEREA, CARLOS MOISES', '', '2024-07-26 04:17:26', '1', '2013-07-03', 'MASCULINO'),
(10, '77878568', '77878568', 5, '77878568', 'MEZA TORRES, JEANPERRIN JAASIEL', '', '2024-07-26 04:20:38', '1', '2012-10-29', 'MASCULINO'),
(11, '77945775', '77945775', 6, '77945775', 'CARRILLO MENDO, JOEL BERNABE', '', '2024-07-26 07:09:41', '1', '2013-01-02', 'MASCULINO'),
(12, '77875564', '77875564', 7, '77875564', 'BANCAYAN PULIDO, JAYDER SEBASTIAN SANTOS', '', '2024-07-26 07:15:50', '1', '2012-10-21', 'MASCULINO'),
(13, '77879226', '77879226', 8, '77879226', 'GARCIA SAYAVERDE, JAIR ABDIEL', '', '2024-07-26 07:50:14', '1', '2012-11-16', 'MASCULINO'),
(14, '77958880', '77958880', 9, '77958880', 'CARDENAS ARIAS, STHEFANO MATHIAS', '', '2024-07-26 07:51:53', '1', '2012-12-26', 'MASCULINO'),
(15, '77829344', '77829344', 10, '77829344', 'CIPRIANO SANCHO, GABRIELA ELIZABETH', '', '2024-07-26 08:43:56', '1', '2012-08-26', 'FEMENINO'),
(16, '77951180', '77951180', 11, '77951180', 'CASTRO VARGAS, EVANGELINA ELISA', '', '2024-07-26 08:47:26', '1', '2013-01-12', 'FEMENINO'),
(17, '77617360', '77617360', 12, '77617360', 'GIL HUACANCA, DAYIRO DAVID', '', '2024-07-26 08:49:29', '1', '0000-00-00', 'MASCULINO'),
(18, '78035180', '78035180', 13, '78035180', 'GUADALUPE CASTILLO, JULIO MATHIAS GIANLUCA', '', '2024-07-27 05:23:51', '1', '2013-02-27', 'MASCULINO'),
(19, '77723174', '77723174', 14, '77723174', 'NOLASCO VARGAS, MATEO SANTIAGO', '', '2024-07-27 05:26:25', '1', '2011-06-16', 'MASCULINO'),
(20, '77718004', '77718004', 15, '77718004', 'ZURITA GONZALES, DAYIRO ANGELO', '', '2024-07-27 05:27:27', '1', '2012-06-05', 'MASCULINO'),
(21, '78358823', '78358823', 16, '78358823', 'ACUÑA LEDESMA, JAAZIEL VALENTINA', '', '2024-07-27 06:20:40', '1', '2013-12-07', 'FEMENINO'),
(22, '78264132', '78264132', 17, '78264132', 'CALLIRGOS PERAMAS, ALEXIS GERAD', '', '2024-07-27 06:36:52', '1', '2013-09-27', 'MASCULINO'),
(23, '78150260', '78150260', 18, '78150260', 'CHAVEZ PALOMINO, AHARON FERNANDO', '', '2024-07-27 06:40:43', '1', '2013-06-23', 'MASCULINO'),
(24, '78309084', '78309084', 19, '78309084', 'DAMASO TITO, DILAN MISAEL', '', '2024-07-27 06:45:26', '1', '2013-10-10', 'MASCULINO'),
(25, '90721032', '90721032', 20, '90721032', 'AYESTA MOLINA, DANNA ISABELLA', '', '2024-08-02 09:02:56', '1', '2018-03-17', 'FEMENINO'),
(26, '90586662', '90586662', 21, '90586662', 'RAYMUNDO SOSA, LUNA KRISNA', '', '2024-08-02 09:06:29', '1', '2018-01-17', 'FEMENINO'),
(27, '91998486', '91998486', 21, '91998486', 'RAYMUNDO SOSA, ISABELLA KRISHA', '', '2024-08-02 09:09:05', '1', '2020-09-02', 'FEMENINO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apoderado`
--

CREATE TABLE `apoderado` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(50) NOT NULL,
  `dni` varchar(15) NOT NULL,
  `nombreyapellido` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `observaciones` text,
  `fechacreado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `apoderado`
--

INSERT INTO `apoderado` (`id`, `usuario`, `contraseña`, `dni`, `nombreyapellido`, `telefono`, `observaciones`, `fechacreado`, `estado`) VALUES
(1, '41281051', '41281051', '41281051', 'MARIA DE LOS ANGELES CARHUAS YJUMA', '932262539', '', '2024-07-24 06:11:55', '1'),
(2, '44345419', '44345419', '44345419', 'SUSANA VICTORIA RIVAS CORTEZ', '997838185', '', '2024-07-24 06:25:34', '1'),
(3, '40103192', '40103192', '40103192', 'BILMA CABRERA VIENA', '959326327', '', '2024-07-26 02:09:14', '1'),
(4, '46340294', '46340294', '46340294', 'CARMEN PEREA ALVAREZ', '997179976', '', '2024-07-26 04:16:30', '1'),
(5, '40477767', '40477767', '40477767', 'AGAPITA BASILIA TORRES FELIPE', '971636093', '', '2024-07-26 04:20:38', '1'),
(6, '10164574', '10164574', '10164574', 'BENERALDA MENDO CARRILLO DE CARRILLO', '951786144', '', '2024-07-26 07:09:41', '1'),
(7, '32737459', '32737459', '32737459', 'EDITH MARGARITA PULIDO TORRES', '997623072', '', '2024-07-26 07:15:50', '1'),
(8, '44617909', '44617909', '44617909', 'JESSICA RAQUEL SAYAVERDE YLLATUPA', '992973469', '', '2024-07-26 07:50:14', '1'),
(9, '40694041', '40694041', '40694041', 'NELLY MARLENI ARIAS MALLQUI', '923157921', '', '2024-07-26 07:51:53', '1'),
(10, '09786286', '09786286', '09786286', 'NELLY ROSA SANCHO ALMIDON', '922800780', '', '2024-07-26 08:43:56', '1'),
(11, '10509263', '10509263', '10509263', 'NORMA ISABEL VARGAS AGUILAR', '985299748', '', '2024-07-26 08:47:26', '1'),
(12, '45115914', '45115914', '45115914', 'PERLA MARLENE HUACANCA PACHAS', '967586278', '', '2024-07-26 08:49:29', '1'),
(13, '80121855', '80121855', '80121855', 'TERESA FELICITA CASTILLO RODRIGUEZ', '987016448', '', '2024-07-27 05:23:51', '1'),
(14, '45631340', '45631340', '45631340', 'SUSY VARGAS OBREGON', '906475503', '', '2024-07-27 05:26:25', '1'),
(15, '43184714', '43184714', '43184714', 'YURITSA ANTONIA GONZALES ALMIDON', '955188598', '', '2024-07-27 05:27:27', '1'),
(16, '07628967', '07628967', '07628967', 'SONIA MARGOT HUAYTA HUAMAN', '957241365', '', '2024-07-27 06:20:40', '1'),
(17, '71386363', '71386363', '71386363', 'ALEXANDRA PERAMAS PUELLES', '943446491', '', '2024-07-27 06:36:52', '1'),
(18, '43046327', '43046327', '43046327', 'MIRIAM MARIBEL PALOMINO COSTILLA', '934413015', '', '2024-07-27 06:40:43', '1'),
(19, '42817048', '42817048', '42817048', 'MARIA ELENA TITO QUISPE', '984034603', '', '2024-07-27 06:45:26', '1'),
(20, '43886356', '43886356', '43886356', 'GENY LAYCES MOLINA CHIRCCA', '999 239 205', '', '2024-08-02 09:02:56', '1'),
(21, '46673942', '46673942', '46673942', 'KRSNA SOSA HUAMANÑAHUI', '936 307 966', '', '2024-08-02 09:06:29', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area_curricular`
--

CREATE TABLE `area_curricular` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `institucion_nivel_id` int(11) NOT NULL,
  `fechacreado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `area_curricular`
--

INSERT INTO `area_curricular` (`id`, `nombre`, `institucion_nivel_id`, `fechacreado`, `estado`) VALUES
(1, 'COMUNICACIÓN', 1, '2024-08-06 08:02:53', '1'),
(2, 'PERSONAL SOCIAL', 1, '2024-08-06 08:02:53', '1'),
(3, 'PSICOMOTRIZ', 1, '2024-08-06 08:02:53', '1'),
(4, 'DESCUBRIMIENTO DEL MUNDO', 1, '2024-08-06 08:02:53', '1'),
(5, 'CASTELLANO COMO SEGUNDA LENGUA', 1, '2024-08-06 08:02:53', '1'),
(6, 'CIENCIA Y TECNOLOGÍA', 1, '2024-08-06 08:02:53', '1'),
(7, 'MATEMÁTICA', 1, '2024-08-06 08:02:53', '1'),
(8, 'SE DESENVUELVE EN ENTORNOS VIRTUALES GENERADOS POR LAS TIC', 1, '2024-08-06 08:02:53', '1'),
(9, 'GESTIONA SU APRENDIZAJE DE MANERA AUTÓNOMA', 1, '2024-08-06 08:02:53', '1'),
(10, 'TUTORÍA', 1, '2024-08-06 08:02:53', '1'),
(11, 'COMUNICACIÓN', 2, '2024-08-06 08:05:04', '1'),
(12, 'PERSONAL SOCIAL', 2, '2024-08-06 08:05:04', '1'),
(13, 'CASTELLANO COMO SEGUNDA LENGUA', 2, '2024-08-06 08:05:04', '1'),
(14, 'CIENCIA Y TECNOLOGÍA', 2, '2024-08-06 08:05:04', '1'),
(15, 'MATEMÁTICA', 2, '2024-08-06 08:05:04', '1'),
(16, 'ARTE Y CULTURA', 2, '2024-08-06 08:05:04', '1'),
(17, 'INGLÉS COMO LENGUA EXTRANJERA', 2, '2024-08-06 08:05:04', '1'),
(18, 'SE DESENVUELVE EN ENTORNOS VIRTUALES GENERADOS POR LAS TIC', 2, '2024-08-06 08:05:04', '1'),
(19, 'GESTIONA SU APRENDIZAJE DE MANERA AUTÓNOMA', 2, '2024-08-06 08:05:04', '1'),
(20, 'EDUCACIÓN FÍSICA', 2, '2024-08-06 08:05:04', '1'),
(21, 'EDUCACIÓN RELIGIOSA', 2, '2024-08-06 08:05:04', '1'),
(22, 'TUTORÍA', 2, '2024-08-06 08:05:04', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `competencia`
--

CREATE TABLE `competencia` (
  `id` int(11) NOT NULL,
  `area_curricular_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `fechacreado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `competencia`
--

INSERT INTO `competencia` (`id`, `area_curricular_id`, `nombre`, `fechacreado`, `estado`) VALUES
(2, 1, 'Se comunica oralmente en su lengua materna', '2024-08-06 08:50:07', '1'),
(3, 1, 'Lee diversos tipos de texto en su lengua materna', '2024-08-06 08:50:07', '1'),
(4, 1, 'Crea proyectos desde los lenguajes del arte', '2024-08-06 08:50:07', '1'),
(5, 1, 'Escribe diversos tipos de texto en su lengua materna', '2024-08-06 08:50:07', '1'),
(6, 2, 'Construye su identidad', '2024-08-06 08:59:57', '1'),
(7, 2, 'Convive y participa democráticamente en la búsqueda del bien común', '2024-08-06 08:59:57', '1'),
(8, 2, 'Construye su identidad, como persona humana, amada por Dios, digna, libre y trascendente, comprendiendo la doctrina de su propia religión, abierto al diálogo con las que le son cercanas', '2024-08-06 08:59:57', '1'),
(9, 3, 'Se desenvuelve de manera autónoma a través de su motricidad', '2024-08-06 08:59:57', '1'),
(10, 4, 'Resuelve problemas de cantidad', '2024-08-06 08:59:57', '1'),
(11, 4, 'Resuelve problemas de forma, movimiento y localización', '2024-08-06 08:59:57', '1'),
(12, 4, 'Indaga mediante métodos científicos para construir sus conocimientos', '2024-08-06 08:59:57', '1'),
(13, 5, 'Se comunica oralmente', '2024-08-06 08:59:57', '1'),
(14, 6, 'Indaga mediante métodos científicos para construir sus conocimientos', '2024-08-06 08:59:57', '1'),
(15, 7, 'Resuelve problemas de cantidad', '2024-08-06 08:59:57', '1'),
(16, 7, 'Resuelve problemas de forma, movimiento y localización', '2024-08-06 08:59:57', '1'),
(17, 8, 'SE DESENVUELVE EN ENTORNOS VIRTUALES GENERADOS POR LAS TIC', '2024-08-06 08:59:57', '1'),
(18, 9, 'GESTIONA SU APRENDIZAJE DE MANERA AUTÓNOMA', '2024-08-06 08:59:57', '1'),
(19, 10, 'Tutoría', '2024-08-06 08:59:57', '1'),
(20, 11, 'Se comunica oralmente en su lengua materna', '2024-08-06 09:16:18', '1'),
(21, 11, 'Escribe diversos tipos de textos en su lengua materna', '2024-08-06 09:16:18', '1'),
(22, 11, 'Lee diversos tipos de textos escritos en su lengua materna', '2024-08-06 09:16:18', '1'),
(23, 12, 'Construye su identidad', '2024-08-06 09:16:18', '1'),
(24, 12, 'Convive y participa democráticamente en la búsqueda del bien común', '2024-08-06 09:16:18', '1'),
(25, 12, 'Construye interpretaciones históricas', '2024-08-06 09:16:18', '1'),
(26, 12, 'Gestiona responsablemente el espacio y el ambiente', '2024-08-06 09:16:18', '1'),
(27, 12, 'Gestiona responsablemente los recursos económicos', '2024-08-06 09:16:18', '1'),
(28, 13, 'Se comunica oralmente', '2024-08-06 09:16:18', '1'),
(29, 13, 'Lee diversos tipos de textos escritos', '2024-08-06 09:16:18', '1'),
(30, 13, 'Escribe diversos tipos de textos', '2024-08-06 09:16:18', '1'),
(31, 14, 'Indaga mediante métodos científicos para construir sus conocimientos', '2024-08-06 09:16:18', '1'),
(32, 14, 'Explica el mundo físico basándose en conocimientos sobre los seres vivos; materia y energía; biodiversidad, Tierra y Universo', '2024-08-06 09:16:18', '1'),
(33, 14, 'Diseña y construye soluciones tecnológicas para resolver problemas de su entorno', '2024-08-06 09:16:18', '1'),
(34, 15, 'Resuelve problemas de cantidad', '2024-08-06 09:16:18', '1'),
(35, 15, 'Resuelve problemas de regularidad, equivalencia y cambio', '2024-08-06 09:16:18', '1'),
(36, 15, 'Resuelve problemas de forma, movimiento y localización', '2024-08-06 09:16:18', '1'),
(37, 15, 'Resuelve problemas de gestión de datos e incertidumbre', '2024-08-06 09:16:18', '1'),
(38, 16, 'Aprecia de manera crítica manifestaciones artístico-culturales', '2024-08-06 09:16:18', '1'),
(39, 16, 'Crea proyectos desde los lenguajes artísticos', '2024-08-06 09:16:18', '1'),
(40, 17, 'Se comunica oralmente en inglés como lengua extranjera', '2024-08-06 09:16:18', '1'),
(41, 17, 'Lee diversos tipos de textos escritos en inglés como lengua extranjera', '2024-08-06 09:16:18', '1'),
(42, 17, 'Escribe diversos tipos de textos en inglés como lengua extranjera', '2024-08-06 09:16:18', '1'),
(43, 18, 'SE DESENVUELVE EN ENTORNOS VIRTUALES GENERADOS POR LAS TIC', '2024-08-06 09:16:18', '1'),
(44, 19, 'GESTIONA SU APRENDIZAJE DE MANERA AUTÓNOMA', '2024-08-06 09:16:18', '1'),
(45, 20, 'Se desenvuelve de manera autónoma a través de su motricidad', '2024-08-06 09:16:18', '1'),
(46, 20, 'Asume una vida saludable', '2024-08-06 09:16:18', '1'),
(47, 20, 'Interactúa a través de sus habilidades sociomotrices', '2024-08-06 09:16:18', '1'),
(48, 21, 'Construye su identidad como persona humana, amada por Dios, digna, libre y trascendente, comprendiendo la doctrina de su propia religión, abierto al diálogo con las que le son cercanas', '2024-08-06 09:16:18', '1'),
(49, 21, 'Asume la experiencia del encuentro personal y comunitario con Dios en su proyecto de vida en coherencia con su creencia religiosa', '2024-08-06 09:16:18', '1'),
(50, 22, 'Tutoría', '2024-08-06 09:16:18', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE `documento` (
  `id` int(11) NOT NULL,
  `detalle` text NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `obligatorio` enum('SI','NO') NOT NULL,
  `observaciones` text,
  `fechacreado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `documento`
--

INSERT INTO `documento` (`id`, `detalle`, `nombre`, `obligatorio`, `observaciones`, `fechacreado`, `estado`) VALUES
(1, 'C.P.', 'FICHA ÚNICA DE MATRICULA', 'SI', '', '2024-07-28 04:24:21', '1'),
(2, 'C.P.', 'CONSTANCIA DE MATRICULA', 'SI', '', '2024-07-28 04:26:00', '1'),
(3, 'C.P.', 'CERTIFICADO DE ESTUDIOS', 'NO', '', '2024-07-28 04:26:15', '1'),
(4, 'C.P.', 'INFORME DE PROGRESO / LIBRETA DE NOTAS', 'NO', '', '2024-07-28 04:26:29', '1'),
(5, 'C.P.', 'CONSTANCIA DE NO ADEUDO', 'SI', '', '2024-07-28 04:26:48', '1'),
(6, 'C.P.', 'RESOLUCIÓN DIRECTORAL', 'NO', '', '2024-07-28 04:27:09', '1'),
(7, 'AP.', 'CARNE DE VACUNACIÓN (NIÑO SANO / COVID)', 'NO', '', '2024-07-28 04:27:26', '1'),
(8, 'AP.', 'PARTIDA / ACTA DE NACIMIENTO', 'NO', '', '2024-07-28 04:28:06', '1'),
(9, 'AP.', 'COPIA DNI ALUMNO', 'SI', '', '2024-07-28 04:28:19', '1'),
(10, 'AP.', 'COPIA DNI APODERADO', 'SI', '', '2024-07-28 04:28:32', '1'),
(11, 'AP.', '6 FOTOS (TAMAÑO CARNET)', 'NO', '', '2024-07-28 04:28:48', '1'),
(12, 'AP.', 'FOTO FAMILIAR (TAMAÑO JUMBO)', 'NO', '', '2024-07-28 04:29:01', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento_detalle`
--

CREATE TABLE `documento_detalle` (
  `id` int(11) NOT NULL,
  `matricula_detalle_id` int(11) NOT NULL,
  `documento_id` int(11) NOT NULL,
  `entregado` enum('SI','NO') DEFAULT 'NO',
  `observaciones` text,
  `fechacreado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `documento_detalle`
--

INSERT INTO `documento_detalle` (`id`, `matricula_detalle_id`, `documento_id`, `entregado`, `observaciones`, `fechacreado`, `estado`) VALUES
(1, 26, 1, 'SI', '', '2024-08-02 09:09:56', '1'),
(2, 26, 2, 'SI', '', '2024-08-02 09:09:56', '1'),
(3, 26, 3, 'NO', '', '2024-08-02 09:09:56', '1'),
(4, 26, 4, 'NO', '', '2024-08-02 09:09:56', '1'),
(5, 26, 5, 'SI', '', '2024-08-02 09:09:56', '1'),
(6, 26, 6, 'SI', '', '2024-08-02 09:09:56', '1'),
(7, 26, 7, 'NO', '', '2024-08-02 09:09:56', '1'),
(8, 26, 8, 'NO', '', '2024-08-02 09:09:56', '1'),
(9, 26, 9, 'SI', '', '2024-08-02 09:09:56', '1'),
(10, 26, 10, 'SI', '', '2024-08-02 09:09:56', '1'),
(11, 26, 11, 'NO', '', '2024-08-02 09:09:56', '1'),
(12, 26, 12, 'NO', '', '2024-08-02 09:09:56', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grado_competencia`
--

CREATE TABLE `grado_competencia` (
  `id` int(11) NOT NULL,
  `grado_id` int(11) NOT NULL,
  `competencia_id` int(11) NOT NULL,
  `fechacreado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institucion`
--

CREATE TABLE `institucion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `estado` enum('1','0') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `institucion`
--

INSERT INTO `institucion` (`id`, `nombre`, `id_trabajador`, `telefono`, `correo`, `direccion`, `estado`) VALUES
(1, 'CB EBENEZER', 1, '958 197 047', 'cbebenezer0791@gmail.com', 'CAL.LOS PENSAMIENTOS NRO. 261 P.J. EL ERMITAÑO LIMA - LIMA - INDEPENDENCIA', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institucion_grado`
--

CREATE TABLE `institucion_grado` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `institucion_nivel_id` int(11) NOT NULL,
  `estado` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `institucion_grado`
--

INSERT INTO `institucion_grado` (`id`, `nombre`, `institucion_nivel_id`, `estado`) VALUES
(1, '3 AÑOS', 1, '1'),
(2, '4 AÑOS', 1, '1'),
(3, '5 AÑOS', 1, '1'),
(4, '1 GRADO', 2, '1'),
(5, '2 GRADO', 2, '1'),
(6, '3 GRADO', 2, '1'),
(7, '4 GRADO', 2, '1'),
(8, '5 GRADO', 2, '1'),
(9, '6 GRADO', 2, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institucion_lectivo`
--

CREATE TABLE `institucion_lectivo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `lectivo_nombre` varchar(255) NOT NULL,
  `institucion_id` int(11) NOT NULL,
  `estado` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `institucion_lectivo`
--

INSERT INTO `institucion_lectivo` (`id`, `nombre`, `lectivo_nombre`, `institucion_id`, `estado`) VALUES
(1, '2024', 'Año del Bicentenario, de la consolidación de nuestra Independencia, y de la conmemoración de las heroicas batallas de Junín y Ayacucho', 1, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institucion_nivel`
--

CREATE TABLE `institucion_nivel` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `institucion_lectivo_id` int(11) NOT NULL,
  `estado` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `institucion_nivel`
--

INSERT INTO `institucion_nivel` (`id`, `nombre`, `institucion_lectivo_id`, `estado`) VALUES
(1, 'INICIAL', 1, '1'),
(2, 'PRIMARIA', 1, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matricula`
--

CREATE TABLE `matricula` (
  `id` int(11) NOT NULL,
  `detalle` text NOT NULL,
  `institucion_grado_id` int(11) NOT NULL,
  `trabajador_id` int(11) NOT NULL,
  `preciomatricula` decimal(10,2) NOT NULL,
  `preciomensualidad` decimal(10,2) NOT NULL,
  `preciootros` decimal(10,2) NOT NULL,
  `aforo` int(11) NOT NULL,
  `observaciones` text,
  `fechacreado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trabajador_sesion_id` int(11) NOT NULL,
  `estado` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `matricula`
--

INSERT INTO `matricula` (`id`, `detalle`, `institucion_grado_id`, `trabajador_id`, `preciomatricula`, `preciomensualidad`, `preciootros`, `aforo`, `observaciones`, `fechacreado`, `trabajador_sesion_id`, `estado`) VALUES
(1, '', 1, 3, '240.00', '270.00', '25.00', 18, '', '2024-07-24 06:01:53', 2, '1'),
(2, '', 2, 4, '240.00', '270.00', '25.00', 18, '', '2024-07-24 06:02:48', 2, '1'),
(3, '', 3, 4, '240.00', '280.00', '25.00', 18, '', '2024-07-24 06:03:18', 2, '1'),
(4, '', 4, 5, '240.00', '300.00', '25.00', 18, '', '2024-07-24 06:05:21', 2, '1'),
(5, '', 5, 6, '240.00', '300.00', '25.00', 18, '', '2024-07-24 06:07:37', 2, '1'),
(6, '', 6, 7, '240.00', '300.00', '25.00', 18, '', '2024-07-24 06:08:06', 2, '1'),
(7, '', 7, 12, '240.00', '300.00', '25.00', 18, '', '2024-07-24 06:08:59', 2, '1'),
(8, '', 8, 8, '240.00', '300.00', '25.00', 18, '', '2024-07-24 06:09:54', 2, '1'),
(9, '', 9, 9, '240.00', '300.00', '25.00', 25, '', '2024-07-24 06:10:46', 2, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matricula_detalle`
--

CREATE TABLE `matricula_detalle` (
  `id` int(11) NOT NULL,
  `detalle` text NOT NULL,
  `matricula_razon_id` int(11) NOT NULL,
  `matricula_id` int(11) NOT NULL,
  `apoderado_id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `observaciones` text,
  `fechacreado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trabajador_sesion_id` int(11) NOT NULL,
  `estado` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `matricula_detalle`
--

INSERT INTO `matricula_detalle` (`id`, `detalle`, `matricula_razon_id`, `matricula_id`, `apoderado_id`, `alumno_id`, `observaciones`, `fechacreado`, `trabajador_sesion_id`, `estado`) VALUES
(1, '', 2, 1, 1, 1, '', '2024-07-24 06:11:55', 2, '1'),
(2, '', 1, 5, 1, 2, '', '2024-07-24 06:13:00', 2, '1'),
(3, '', 1, 8, 1, 3, '', '2024-07-24 06:19:57', 2, '1'),
(4, '', 1, 3, 2, 4, '', '2024-07-24 06:25:34', 2, '1'),
(5, '', 1, 9, 2, 5, '', '2024-07-24 06:26:46', 2, '1'),
(6, '', 2, 6, 3, 6, '', '2024-07-26 02:09:14', 2, '1'),
(7, '', 2, 7, 3, 7, '', '2024-07-26 02:10:01', 2, '1'),
(8, '', 1, 2, 4, 8, '', '2024-07-26 04:16:30', 2, '1'),
(9, '', 1, 8, 4, 9, '', '2024-07-26 04:17:26', 2, '1'),
(10, '', 1, 9, 5, 10, '', '2024-07-26 04:20:38', 2, '1'),
(11, '', 1, 9, 6, 11, '', '2024-07-26 07:09:41', 2, '1'),
(12, '', 1, 9, 7, 12, '', '2024-07-26 07:15:50', 2, '1'),
(13, '', 1, 9, 8, 13, '', '2024-07-26 07:50:14', 2, '1'),
(14, '', 1, 9, 9, 14, '', '2024-07-26 07:51:53', 2, '1'),
(15, '', 1, 9, 10, 15, '', '2024-07-26 08:43:56', 2, '1'),
(16, '', 1, 9, 11, 16, '', '2024-07-26 08:47:26', 2, '1'),
(17, '', 2, 9, 12, 17, '', '2024-07-26 08:49:29', 2, '1'),
(18, '', 1, 9, 13, 18, '', '2024-07-27 05:23:51', 2, '1'),
(19, '', 1, 9, 14, 19, '', '2024-07-27 05:26:25', 2, '1'),
(20, '', 1, 9, 15, 20, '', '2024-07-27 05:27:27', 2, '1'),
(21, '', 1, 8, 16, 21, '', '2024-07-27 06:20:40', 2, '1'),
(22, '', 2, 8, 17, 22, '', '2024-07-27 06:36:52', 2, '1'),
(23, '', 1, 8, 18, 23, '', '2024-07-27 06:40:43', 2, '1'),
(24, '', 1, 8, 19, 24, '', '2024-07-27 06:45:26', 2, '1'),
(25, '', 1, 4, 20, 25, '', '2024-08-02 09:02:56', 2, '1'),
(26, '', 2, 4, 21, 26, '', '2024-08-02 09:06:29', 2, '1'),
(27, '', 2, 1, 21, 27, '', '2024-08-02 09:09:05', 2, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matricula_metodo`
--

CREATE TABLE `matricula_metodo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `estado` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `matricula_metodo`
--

INSERT INTO `matricula_metodo` (`id`, `nombre`, `estado`) VALUES
(1, 'YAPE', '1'),
(2, 'EFECTIVO', '1'),
(3, 'TRANSFERENCIA', '1'),
(4, 'INTERBANCARIO', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matricula_pago`
--

CREATE TABLE `matricula_pago` (
  `id` int(11) NOT NULL,
  `matricula_detalle_id` int(11) NOT NULL,
  `matricula_metodo_id` int(11) NOT NULL,
  `numeracion` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `observaciones` text,
  `fechacreado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `matricula_pago`
--

INSERT INTO `matricula_pago` (`id`, `matricula_detalle_id`, `matricula_metodo_id`, `numeracion`, `fecha`, `monto`, `observaciones`, `fechacreado`, `estado`) VALUES
(1, 1, 2, '000001', '2024-07-24', '200.00', '', '2024-07-24 06:11:55', '1'),
(2, 2, 1, '000002', '2024-07-24', '200.00', '', '2024-07-24 06:13:00', '1'),
(3, 3, 1, '000003', '2024-07-24', '200.00', '', '2024-07-24 06:19:57', '1'),
(4, 4, 1, '000004', '2024-07-24', '180.00', '', '2024-07-24 06:25:34', '1'),
(5, 5, 1, '000005', '2024-07-24', '200.00', '', '2024-07-24 06:26:46', '1'),
(6, 6, 1, '000006', '2024-07-25', '240.00', '', '2024-07-26 02:09:14', '1'),
(7, 7, 1, '000007', '2024-07-25', '240.00', '', '2024-07-26 02:10:01', '1'),
(8, 8, 1, '000008', '2024-07-25', '170.00', '', '2024-07-26 04:16:30', '1'),
(9, 9, 1, '000009', '2024-07-25', '200.00', '', '2024-07-26 04:17:26', '1'),
(10, 10, 1, '000010', '2024-07-25', '200.00', '', '2024-07-26 04:20:38', '1'),
(11, 11, 2, '000011', '2024-07-26', '240.00', '', '2024-07-26 07:09:41', '1'),
(12, 12, 1, '000012', '2024-07-26', '220.00', '', '2024-07-26 07:15:50', '1'),
(13, 13, 1, '000013', '2024-07-26', '240.00', '', '2024-07-26 07:50:14', '1'),
(14, 14, 1, '000014', '2024-07-26', '240.00', '', '2024-07-26 07:51:53', '1'),
(15, 15, 2, '000015', '2024-07-26', '200.00', '', '2024-07-26 08:43:56', '1'),
(16, 16, 1, '000016', '2024-07-26', '200.00', '', '2024-07-26 08:47:26', '1'),
(17, 17, 2, '000017', '2024-07-26', '200.00', '', '2024-07-26 08:49:29', '1'),
(18, 18, 2, '000018', '2024-07-27', '200.00', '', '2024-07-27 05:23:51', '1'),
(19, 19, 1, '000019', '2024-07-27', '200.00', '', '2024-07-27 05:26:25', '1'),
(20, 20, 2, '000020', '2024-07-27', '200.00', '', '2024-07-27 05:27:27', '1'),
(21, 21, 3, '000021', '2024-07-27', '200.00', '', '2024-07-27 06:20:40', '1'),
(22, 22, 2, '000022', '2024-07-27', '200.00', '', '2024-07-27 06:36:52', '1'),
(23, 23, 1, '000023', '2024-07-27', '200.00', '', '2024-07-27 06:40:43', '1'),
(24, 24, 2, '000024', '2024-07-27', '200.00', '', '2024-07-27 06:45:26', '1'),
(25, 25, 1, '000025', '2024-08-02', '200.00', '', '2024-08-02 09:02:56', '1'),
(26, 26, 2, '000026', '2024-08-02', '200.00', '', '2024-08-02 09:06:29', '1'),
(27, 27, 2, '000027', '2024-08-02', '200.00', '', '2024-08-02 09:09:05', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matricula_razon`
--

CREATE TABLE `matricula_razon` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `estado` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `matricula_razon`
--

INSERT INTO `matricula_razon` (`id`, `nombre`, `estado`) VALUES
(1, 'RATIFICACION', '1'),
(2, 'NUEVO', '1'),
(3, 'TRASLADO', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador`
--

CREATE TABLE `trabajador` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(50) NOT NULL,
  `dni` varchar(15) NOT NULL,
  `nombre_apellido` varchar(100) NOT NULL,
  `nacimiento` date NOT NULL,
  `sexo` enum('MASCULINO','FEMENINO') NOT NULL,
  `estado_civil` enum('SOLTERO','CASADO','DIVORCIADO','VIUDO') NOT NULL,
  `cargo` enum('DIRECTOR','SECRETARIO','PROFESOR','AUXILIAR') NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `sueldo` decimal(10,2) NOT NULL,
  `cuenta_bcp` varchar(50) DEFAULT NULL,
  `interbancario_bcp` varchar(50) DEFAULT NULL,
  `sunat_ruc` varchar(20) DEFAULT NULL,
  `sunat_usuario` varchar(50) DEFAULT NULL,
  `sunat_contraseña` varchar(50) DEFAULT NULL,
  `observaciones` text,
  `usuariocrea` varchar(50) DEFAULT NULL,
  `fechacreado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('1','0') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `trabajador`
--

INSERT INTO `trabajador` (`id`, `usuario`, `contraseña`, `dni`, `nombre_apellido`, `nacimiento`, `sexo`, `estado_civil`, `cargo`, `direccion`, `telefono`, `correo`, `sueldo`, `cuenta_bcp`, `interbancario_bcp`, `sunat_ruc`, `sunat_usuario`, `sunat_contraseña`, `observaciones`, `usuariocrea`, `fechacreado`, `estado`) VALUES
(1, '10509059', '10509059', '10509059', 'CECILIA ROSARIO MANRIQUE LOPEZ', '1977-01-16', 'FEMENINO', 'CASADO', 'DIRECTOR', 'PROLONG. LAS GLADIOLAS MZ.X LT.12 EL ERMITAÑO', '976300448', 'tequirosario@hotmail.com', '0.00', '', '', '', '', '', '', '', '2024-07-24 04:59:19', '1'),
(2, '73937543', '73937543', '73937543', 'MARCO ANTONIO MANRIQUE VARILLAS', '1999-06-18', 'MASCULINO', 'SOLTERO', 'SECRETARIO', 'PROLONG. LAS GLADIOLAS MZ.X LT.12 EL ERMITAÑO', '994947452', 'mmanriquevarillas99@gmail.com', '0.00', '19101118530027', '00219110111853002750', '', '', '', '', '', '2024-07-24 05:00:41', '1'),
(3, '72302961', '72302961', '72302961', 'DINA ANTONIA MIRANDA BARRIENTOS', '0000-00-00', '', '', 'PROFESOR', '', '', '', '0.00', '', '', '', '', '', '', '', '2024-07-24 05:01:03', '1'),
(4, '73937540', '73937540', '73937540', 'DIALHU BETSABE FALLA MANRIQUE', '0000-00-00', '', '', 'PROFESOR', '', '', '', '0.00', '', '', '', '', '', '', '', '2024-07-24 05:02:04', '1'),
(5, '74641478', '74641478', '74641478', 'DARIANA GERALDINE POEMAPE JARA', '0000-00-00', '', '', 'PROFESOR', '', '', '', '0.00', '', '', '', '', '', '', '', '2024-07-24 05:02:33', '1'),
(6, '80228585', '80228585', '80228585', 'NORMA MOLINA CHIRCCA', '0000-00-00', '', '', 'PROFESOR', '', '', '', '0.00', '', '', '', '', '', '', '', '2024-07-24 05:02:52', '1'),
(7, '10157297', '10157297', '10157297', 'MARIA DEL PILAR MANRIQUE LOPEZ', '0000-00-00', '', '', 'PROFESOR', '', '', '', '0.00', '', '', '', '', '', '', '', '2024-07-24 05:03:10', '1'),
(8, '00474406', '00474406', '00474406', 'RONELL ALBERTO MALPICA', '0000-00-00', '', '', 'PROFESOR', '', '', '', '0.00', '', '', '', '', '', '', '', '2024-07-24 05:03:30', '1'),
(9, '76326172', '76326172', '76326172', 'DAVID RUBENS ABERGA RODRIGUEZ', '0000-00-00', '', '', 'PROFESOR', '', '', '', '0.00', '', '', '', '', '', '', '', '2024-07-24 05:03:52', '1'),
(10, '73948185', '73948185', '73948185', 'HERMILINDA AUCCAPIÑA MARQUEZ', '0000-00-00', '', '', 'AUXILIAR', '', '', '', '0.00', '', '', '', '', '', '', '', '2024-07-24 05:04:17', '1'),
(11, '70817870', '70817870', '70817870', 'LIMAS MORALES ABBNER SAMUEL', '0000-00-00', '', '', 'PROFESOR', '', '', '', '0.00', '', '', '', '', '', '', '', '2024-07-24 05:04:41', '1'),
(12, '46673942', '46673942', '46673942', 'SOSA HUAMANÑAHUI KRSNA', '0000-00-00', '', '', 'PROFESOR', '', '', '', '0.00', '', '', '', '', '', '', '', '2024-07-24 05:05:10', '1'),
(13, '76638331', '76638331', '76638331', 'AMY DANIELA CHIRCCA MANRIQUE', '0000-00-00', '', '', 'PROFESOR', '', '', '', '0.00', '', '', '', '', '', '', '', '2024-07-24 05:05:27', '1'),
(14, '74219683', '74219683', '74219683', 'KHRYSE ATENAS AYESTA MANRIQUE', '0000-00-00', '', '', 'PROFESOR', '', '', '', '0.00', '', '', '', '', '', '', '', '2024-07-24 05:05:44', '1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `apoderado_id` (`apoderado_id`);

--
-- Indices de la tabla `apoderado`
--
ALTER TABLE `apoderado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `area_curricular`
--
ALTER TABLE `area_curricular`
  ADD PRIMARY KEY (`id`),
  ADD KEY `institucion_nivel_id` (`institucion_nivel_id`);

--
-- Indices de la tabla `competencia`
--
ALTER TABLE `competencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `area_curricular_id` (`area_curricular_id`);

--
-- Indices de la tabla `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documento_detalle`
--
ALTER TABLE `documento_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matricula_detalle_id` (`matricula_detalle_id`),
  ADD KEY `documento_id` (`documento_id`);

--
-- Indices de la tabla `grado_competencia`
--
ALTER TABLE `grado_competencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grado_id` (`grado_id`),
  ADD KEY `competencia_id` (`competencia_id`);

--
-- Indices de la tabla `institucion`
--
ALTER TABLE `institucion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_trabajador` (`id_trabajador`);

--
-- Indices de la tabla `institucion_grado`
--
ALTER TABLE `institucion_grado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `institucion_nivel_id` (`institucion_nivel_id`);

--
-- Indices de la tabla `institucion_lectivo`
--
ALTER TABLE `institucion_lectivo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `institucion_id` (`institucion_id`);

--
-- Indices de la tabla `institucion_nivel`
--
ALTER TABLE `institucion_nivel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `institucion_lectivo_id` (`institucion_lectivo_id`);

--
-- Indices de la tabla `matricula`
--
ALTER TABLE `matricula`
  ADD PRIMARY KEY (`id`),
  ADD KEY `institucion_grado_id` (`institucion_grado_id`),
  ADD KEY `trabajador_id` (`trabajador_id`),
  ADD KEY `trabajador_sesion_id` (`trabajador_sesion_id`);

--
-- Indices de la tabla `matricula_detalle`
--
ALTER TABLE `matricula_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matricula_razon_id` (`matricula_razon_id`),
  ADD KEY `matricula_id` (`matricula_id`),
  ADD KEY `apoderado_id` (`apoderado_id`),
  ADD KEY `alumno_id` (`alumno_id`),
  ADD KEY `trabajador_sesion_id` (`trabajador_sesion_id`);

--
-- Indices de la tabla `matricula_metodo`
--
ALTER TABLE `matricula_metodo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `matricula_pago`
--
ALTER TABLE `matricula_pago`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matricula_detalle_id` (`matricula_detalle_id`),
  ADD KEY `matricula_metodo_id` (`matricula_metodo_id`);

--
-- Indices de la tabla `matricula_razon`
--
ALTER TABLE `matricula_razon`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumno`
--
ALTER TABLE `alumno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `apoderado`
--
ALTER TABLE `apoderado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `area_curricular`
--
ALTER TABLE `area_curricular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `competencia`
--
ALTER TABLE `competencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `documento`
--
ALTER TABLE `documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `documento_detalle`
--
ALTER TABLE `documento_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `grado_competencia`
--
ALTER TABLE `grado_competencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `institucion`
--
ALTER TABLE `institucion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `institucion_grado`
--
ALTER TABLE `institucion_grado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `institucion_lectivo`
--
ALTER TABLE `institucion_lectivo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `institucion_nivel`
--
ALTER TABLE `institucion_nivel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `matricula`
--
ALTER TABLE `matricula`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `matricula_detalle`
--
ALTER TABLE `matricula_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `matricula_metodo`
--
ALTER TABLE `matricula_metodo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `matricula_pago`
--
ALTER TABLE `matricula_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `matricula_razon`
--
ALTER TABLE `matricula_razon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD CONSTRAINT `alumno_ibfk_1` FOREIGN KEY (`apoderado_id`) REFERENCES `apoderado` (`id`);

--
-- Filtros para la tabla `area_curricular`
--
ALTER TABLE `area_curricular`
  ADD CONSTRAINT `area_curricular_ibfk_1` FOREIGN KEY (`institucion_nivel_id`) REFERENCES `institucion_nivel` (`id`);

--
-- Filtros para la tabla `competencia`
--
ALTER TABLE `competencia`
  ADD CONSTRAINT `competencia_ibfk_1` FOREIGN KEY (`area_curricular_id`) REFERENCES `area_curricular` (`id`);

--
-- Filtros para la tabla `documento_detalle`
--
ALTER TABLE `documento_detalle`
  ADD CONSTRAINT `documento_detalle_ibfk_1` FOREIGN KEY (`matricula_detalle_id`) REFERENCES `matricula_detalle` (`id`),
  ADD CONSTRAINT `documento_detalle_ibfk_2` FOREIGN KEY (`documento_id`) REFERENCES `documento` (`id`);

--
-- Filtros para la tabla `grado_competencia`
--
ALTER TABLE `grado_competencia`
  ADD CONSTRAINT `grado_competencia_ibfk_1` FOREIGN KEY (`grado_id`) REFERENCES `institucion_grado` (`id`),
  ADD CONSTRAINT `grado_competencia_ibfk_2` FOREIGN KEY (`competencia_id`) REFERENCES `competencia` (`id`);

--
-- Filtros para la tabla `institucion`
--
ALTER TABLE `institucion`
  ADD CONSTRAINT `institucion_ibfk_1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajador` (`id`);

--
-- Filtros para la tabla `institucion_grado`
--
ALTER TABLE `institucion_grado`
  ADD CONSTRAINT `institucion_grado_ibfk_1` FOREIGN KEY (`institucion_nivel_id`) REFERENCES `institucion_nivel` (`id`);

--
-- Filtros para la tabla `institucion_lectivo`
--
ALTER TABLE `institucion_lectivo`
  ADD CONSTRAINT `institucion_lectivo_ibfk_1` FOREIGN KEY (`institucion_id`) REFERENCES `institucion` (`id`);

--
-- Filtros para la tabla `institucion_nivel`
--
ALTER TABLE `institucion_nivel`
  ADD CONSTRAINT `institucion_nivel_ibfk_1` FOREIGN KEY (`institucion_lectivo_id`) REFERENCES `institucion_lectivo` (`id`);

--
-- Filtros para la tabla `matricula`
--
ALTER TABLE `matricula`
  ADD CONSTRAINT `matricula_ibfk_1` FOREIGN KEY (`institucion_grado_id`) REFERENCES `institucion_grado` (`id`),
  ADD CONSTRAINT `matricula_ibfk_2` FOREIGN KEY (`trabajador_id`) REFERENCES `trabajador` (`id`),
  ADD CONSTRAINT `matricula_ibfk_3` FOREIGN KEY (`trabajador_sesion_id`) REFERENCES `trabajador` (`id`);

--
-- Filtros para la tabla `matricula_detalle`
--
ALTER TABLE `matricula_detalle`
  ADD CONSTRAINT `matricula_detalle_ibfk_1` FOREIGN KEY (`matricula_razon_id`) REFERENCES `matricula_razon` (`id`),
  ADD CONSTRAINT `matricula_detalle_ibfk_2` FOREIGN KEY (`matricula_id`) REFERENCES `matricula` (`id`),
  ADD CONSTRAINT `matricula_detalle_ibfk_3` FOREIGN KEY (`apoderado_id`) REFERENCES `apoderado` (`id`),
  ADD CONSTRAINT `matricula_detalle_ibfk_4` FOREIGN KEY (`alumno_id`) REFERENCES `alumno` (`id`),
  ADD CONSTRAINT `matricula_detalle_ibfk_5` FOREIGN KEY (`trabajador_sesion_id`) REFERENCES `trabajador` (`id`);

--
-- Filtros para la tabla `matricula_pago`
--
ALTER TABLE `matricula_pago`
  ADD CONSTRAINT `matricula_pago_ibfk_1` FOREIGN KEY (`matricula_detalle_id`) REFERENCES `matricula_detalle` (`id`),
  ADD CONSTRAINT `matricula_pago_ibfk_2` FOREIGN KEY (`matricula_metodo_id`) REFERENCES `matricula_metodo` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
