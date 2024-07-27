var link = "../Controlador/Consulta.php?op=";

function init() {
    cargarDatos();
    cargarApoderadosAlumnos();
    cargarAlumnosPorMes();
    cargarMatriculados();
}

function cargarDatos() {
    $.ajax({
        url: link + "listar",
        type: "GET",
        success: function (data) {
            var registros = JSON.parse(data);
            var html = '';
            
            var lectivoCounts = {};
            var nivelCounts = {};
            var gradoCounts = {};

            // Primero, contamos las ocurrencias
            registros.forEach(function (registro) {
                lectivoCounts[registro.lectivo] = (lectivoCounts[registro.lectivo] || 0) + 1;
                nivelCounts[registro.lectivo + registro.nivel] = (nivelCounts[registro.lectivo + registro.nivel] || 0) + 1;
                gradoCounts[registro.lectivo + registro.nivel + registro.grado] = (gradoCounts[registro.lectivo + registro.nivel + registro.grado] || 0) + 1;
            });

            var lastLectivo = '';
            var lastNivel = '';
            var lastGrado = {};

            registros.forEach(function (registro) {
                html += '<tr>';

                if (registro.lectivo !== lastLectivo) {
                    html += '<td rowspan="' + lectivoCounts[registro.lectivo] + '">' + registro.lectivo + '</td>';
                    lastLectivo = registro.lectivo;
                    lastNivel = '';  // reset nivel when lectivo changes
                }

                if (registro.lectivo + registro.nivel !== lastNivel) {
                    html += '<td rowspan="' + nivelCounts[registro.lectivo + registro.nivel] + '">' + registro.nivel + '</td>';
                    lastNivel = registro.lectivo + registro.nivel;
                    lastGrado[registro.lectivo + registro.nivel] = '';  // reset grado when nivel changes
                }

                if (registro.lectivo + registro.nivel + registro.grado !== lastGrado[registro.lectivo + registro.nivel]) {
                    html += '<td rowspan="' + gradoCounts[registro.lectivo + registro.nivel + registro.grado] + '">' + registro.grado + '</td>';
                    lastGrado[registro.lectivo + registro.nivel] = registro.lectivo + registro.nivel + registro.grado;
                }

                html += '<td>' + registro.alumno + '</td>';
                html += '</tr>';
            });

            $('#myTable tbody').html(html);
        }
    });
}

function cargarApoderadosAlumnos() {
    $.ajax({
        url: link + "listarApoderadosAlumnos",
        type: "GET",
        success: function (data) {
            var registros = JSON.parse(data);
            var html = '';
            
            var apoderadoCounts = {};

            // Primero, contamos las ocurrencias
            registros.forEach(function (registro) {
                apoderadoCounts[registro.apoderado] = (apoderadoCounts[registro.apoderado] || 0) + 1;
            });

            var lastApoderado = '';

            registros.forEach(function (registro) {
                html += '<tr>';

                if (registro.apoderado !== lastApoderado) {
                    html += '<td rowspan="' + apoderadoCounts[registro.apoderado] + '">' + registro.apoderado + '</td>';
                    // html += '<td rowspan="' + apoderadoCounts[registro.apoderado] + '">' + registro.telefono + '</td>';
                    lastApoderado = registro.apoderado;
                }

                html += '<td>' + registro.alumno + '</td>';
                html += '</tr>';
            });

            $('#apoderadosTable tbody').html(html);
        }
    });
}

function cargarAlumnosPorMes() {
    $.ajax({
        url: link + "listarAlumnosPorMes",
        type: "GET",
        success: function (data) {
            var registros = JSON.parse(data);
            var html = '';
            
            var mesCounts = {};
            var diaCounts = {};

            // Primero, contamos las ocurrencias
            registros.forEach(function (registro) {
                mesCounts[registro.mes] = (mesCounts[registro.mes] || 0) + 1;
                diaCounts[registro.mes + registro.dia] = (diaCounts[registro.mes + registro.dia] || 0) + 1;
            });

            var lastMes = '';
            var lastDia = {};

            registros.forEach(function (registro) {
                html += '<tr>';

                if (registro.mes !== lastMes) {
                    html += '<td rowspan="' + mesCounts[registro.mes] + '">' + getMesNombre(registro.mes) + '</td>';
                    lastMes = registro.mes;
                    lastDia[registro.mes] = '';  // reset dia when mes changes
                }

                if (registro.mes + registro.dia !== lastDia[registro.mes]) {
                    html += '<td rowspan="' + diaCounts[registro.mes + registro.dia] + '">' + registro.dia + '</td>';
                    lastDia[registro.mes] = registro.mes + registro.dia;
                }

                html += '<td>' + registro.alumno + '</td>';
                html += '</tr>';
            });

            $('#mesesTable tbody').html(html);
        }
    });
}

function getMesNombre(mes) {
    var meses = [
        "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", 
        "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"
    ];
    return meses[mes - 1];
}

function cargarMatriculados() {
    $.ajax({
        url: link + "listarMatriculados",
        type: "GET",
        success: function (data) {
            var registros = JSON.parse(data);
            var html = '';
            
            var lectivoCounts = {};
            var nivelCounts = {};
            var gradoCounts = {};
            var totalMatriculados = 0;

            // Primero, contamos las ocurrencias y sumamos los matriculados
            registros.forEach(function (registro) {
                lectivoCounts[registro.lectivo] = (lectivoCounts[registro.lectivo] || 0) + 1;
                nivelCounts[registro.lectivo + registro.nivel] = (nivelCounts[registro.lectivo + registro.nivel] || 0) + 1;
                gradoCounts[registro.lectivo + registro.nivel + registro.grado] = (gradoCounts[registro.lectivo + registro.nivel + registro.grado] || 0) + 1;
                totalMatriculados += parseInt(registro.cantidad_matriculados);
            });

            var lastLectivo = '';
            var lastNivel = '';
            var lastGrado = {};

            registros.forEach(function (registro) {
                html += '<tr>';

                if (registro.lectivo !== lastLectivo) {
                    html += '<td rowspan="' + lectivoCounts[registro.lectivo] + '">' + registro.lectivo + '</td>';
                    lastLectivo = registro.lectivo;
                    lastNivel = '';  // reset nivel when lectivo changes
                }

                if (registro.lectivo + registro.nivel !== lastNivel) {
                    html += '<td rowspan="' + nivelCounts[registro.lectivo + registro.nivel] + '">' + registro.nivel + '</td>';
                    lastNivel = registro.lectivo + registro.nivel;
                    lastGrado[registro.lectivo + registro.nivel] = '';  // reset grado when nivel changes
                }

                if (registro.lectivo + registro.nivel + registro.grado !== lastGrado[registro.lectivo + registro.nivel]) {
                    html += '<td rowspan="' + gradoCounts[registro.lectivo + registro.nivel + registro.grado] + '">' + registro.grado + '</td>';
                    lastGrado[registro.lectivo + registro.nivel] = registro.lectivo + registro.nivel + registro.grado;
                }

                html += '<td>' + registro.cantidad_matriculados + '</td>';
                html += '</tr>';
            });

            // Añadir fila con la suma total
            html += '<tr>';
            html += '<td colspan="3"><b>TOTAL MATRICULADOS</b></td>';
            html += '<td><b>' + totalMatriculados + ' ALUMNOS</b></td>';
            html += '</tr>';

            $('#matriculadosTable tbody').html(html);
        }
    });
}

init();
