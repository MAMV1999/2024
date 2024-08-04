BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE documentoNombre VARCHAR(255);
    DECLARE documentosCursor CURSOR FOR SELECT nombre FROM documento;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    -- Inicializa la consulta SQL base
    SET @sql = 'SELECT 
            md.id,
            ins.nombre AS institucion_nombre,
            ins.telefono AS institucion_telefono,
            ins.correo AS institucion_correo,
            ins.direccion AS institucion_direccion,
            il.nombre AS institucion_lectivo,
            niv.nombre AS institucion_nivel, 
            ig.nombre AS institucion_grado,
            mr.nombre AS matricula_razon,
            a.id AS apoderado_id,
            a.nombreyapellido AS apoderado_nombre, 
            a.telefono AS apoderado_telefono,
            al.id AS alumno_id,
            al.nombreyapellido AS alumno_nombre';

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
            ', MAX(CASE WHEN d.nombre = ''', documentoNombre, ''' THEN dd.entregado ELSE ''NO'' END) AS `', documentoNombre, '`,
            MAX(CASE WHEN d.nombre = ''', documentoNombre, ''' THEN dd.observaciones ELSE \'\' END) AS `', documentoNombre, '_observaciones`'
        );
    END LOOP;

    -- Cierra el cursor
    CLOSE documentosCursor;

    -- Completa el SQL dinámico con las cláusulas FROM, WHERE, GROUP BY y ORDER BY
    SET @sql = CONCAT(@sql, '
        FROM 
            matricula_detalle md
        JOIN 
            matricula m ON md.matricula_id = m.id
        JOIN 
            institucion_grado ig ON m.institucion_grado_id = ig.id
        JOIN 
            institucion_nivel niv ON ig.institucion_nivel_id = niv.id
        JOIN 
            institucion_lectivo il ON niv.institucion_lectivo_id = il.id
        JOIN 
            institucion ins ON il.institucion_id = ins.id
        JOIN 
            matricula_razon mr ON md.matricula_razon_id = mr.id
        JOIN 
            apoderado a ON md.apoderado_id = a.id
        JOIN 
            alumno al ON md.alumno_id = al.id
        LEFT JOIN 
            documento_detalle dd ON md.id = dd.matricula_detalle_id
        LEFT JOIN 
            documento d ON dd.documento_id = d.id
        WHERE
            md.id = ', p_id, '
        GROUP BY 
            md.id, ins.nombre, ins.telefono, ins.correo, ins.direccion, niv.nombre, ig.nombre, mr.nombre, a.id, a.nombreyapellido, a.telefono, al.id, al.nombreyapellido
        ORDER BY 
            ins.nombre ASC, niv.nombre ASC, ig.nombre ASC, al.nombreyapellido ASC
    ');

    -- Prepara y ejecuta la declaración SQL dinámica
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END