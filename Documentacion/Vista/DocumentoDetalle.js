var link = "../Controlador/DocumentoDetalle.php?op=";
var tabla;

function init() {
    listar();
    $("#frm_form").on("submit", function(e) {
        guardaryeditar(e);
    });
}

function listar() {
    if ($.fn.DataTable.isDataTable('#myTable')) {
        $('#myTable').DataTable().destroy();
    }
    
    tabla = $("#myTable").DataTable({
        ajax: {
            url: link + "listar",
            type: "GET",
            dataSrc: function (json) {
                console.log(json); // Verifica la respuesta del servidor
                return json.aaData;
            }
        },
        columns: [
            { data: "grado" },
            { data: "alumno" },
            { data: "apoderado" },
            { data: "acciones" }
        ],
        destroy: true
    });
}

function mostrar(id) {
    $.post(
        link + "mostrar",
        { id: id },
        function (data, status) {
            data = JSON.parse(data);
            MostrarFormulario();

            var documentos = data.documentos;
            var detalles = data.detalles;

            // Mostrar información adicional
            $("#apoderado").text(data.apoderado);
            $("#alumno").text(data.alumno);
            $("#lectivo").text(data.lectivo);
            $("#nivel").text(data.nivel);
            $("#grado").text(data.grado);
            $("#matricula_detalle_id").val(id);

            var html = '';
            var numero = 1;
            for (var i = 0; i < documentos.length; i++) {
                var documento = documentos[i];
                var detalle = detalles[documento.id] || {entregado: 'NO', observaciones: ''};

                if (documento.obligatorio === "SI") {
                    var importante = "***";
                } else {
                    var importante = "";
                }

                html += '<tr>';
                html += '<td>' + numero + '</td>';
                html += '<td>' + documento.nombre + '</td>';
                html += '<td>' + importante + '</td>';
                html += '<td>';
                html += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="custom-radio"><input type="radio" name="documentos[' + documento.id + '][entregado]" value="SI"' + (detalle.entregado === 'SI' ? ' checked' : '') + '> Sí</label>';
                html += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="custom-radio"><input type="radio" name="documentos[' + documento.id + '][entregado]" value="NO"' + (detalle.entregado === 'NO' ? ' checked' : '') + '> No</label>';
                html += '</td>';
                html += '<td><input type="text" name="documentos[' + documento.id + '][observaciones]" value="' + (detalle.observaciones || '') + '" class="form-control"></td>';
                html += '</tr>';
                numero++;
            }

            $("#documento_list").html(html);
        }
    );
}

function guardaryeditar(e) {
    e.preventDefault(); // No se activará la acción predeterminada del evento
    var formData = new FormData($("#frm_form")[0]);

    $.ajax({
        url: link + "guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos) {
            alert(datos);
            MostrarListado();
            listar();
        }
    });
}

function MostrarFormulario() {
    limpiar();
    $("#listado").hide();
    $("#formulario").show();
}

function MostrarListado() {
    limpiar();
    $("#formulario").hide();
    $("#listado").show();
}

function limpiar() {
    $("#id").val("");
    $("#documento_list").html("");
    // Limpiar los campos adicionales
    $("#apoderado").text("");
    $("#alumno").text("");
    $("#lectivo").text("");
    $("#nivel").text("");
    $("#grado").text("");
}

init();
