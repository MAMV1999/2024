var link = "../Controlador/GradoCompetencia.php?op=";
var tabla;

function init() {
    $("#frm_form").on("submit", function (e) {
        guardaryeditar(e);
    });
    MostrarListado();
    cargar_grados();
    cargar_competencias();
    actualizarFechaHora();
    setInterval(actualizarFechaHora, 1000);
}

function cargar_grados(selectElement = null) {
    $.post(link + "listar_grados_activos", function (r) {
        if (selectElement) {
            $(selectElement).html(r);
            $(selectElement).selectpicker("refresh");
        } else {
            // Carga los grados activos en todos los selects
            $("select[name='grado_id[]']").each(function() {
                $(this).html(r);
                $(this).selectpicker("refresh");
            });
        }
    });
}

function cargar_competencias(selectElement = null) {
    $.post(link + "listar_competencias_activas", function (r) {
        if (selectElement) {
            $(selectElement).html(r);
            $(selectElement).selectpicker("refresh");
        } else {
            // Carga las competencias activas en todos los selects
            $("select[name='competencia_id[]']").each(function() {
                $(this).html(r);
                $(this).selectpicker("refresh");
            });
        }
    });
}

$(document).ready(function () {
    tabla = $("#myTable").DataTable({
        ajax: link + "listar",
    });
});

function limpiar() {
    $("#id").val("");
    $("#detalles tbody").html('<tr><td><select name="grado_id[]" class="form-control selectpicker" data-live-search="true" required></select></td><td><select name="competencia_id[]" class="form-control selectpicker" data-live-search="true" required></select></td><td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)">Eliminar</button></td></tr>');
    cargar_grados();
    cargar_competencias();
}

function MostrarListado() {
    limpiar();
    $("#listado").show();
    $("#formulario").hide();
}

function MostrarFormulario() {
    $("#listado").hide();
    $("#formulario").show();
}

function agregarFila() {
    var nuevaFila = '<tr><td><select name="grado_id[]" class="form-control selectpicker" data-live-search="true" required></select></td><td><select name="competencia_id[]" class="form-control selectpicker" data-live-search="true" required></select></td><td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)">Eliminar</button></td></tr>';
    $("#detalles tbody").append(nuevaFila);
    cargar_grados($("#detalles tr:last-child select[name='grado_id[]']"));
    cargar_competencias($("#detalles tr:last-child select[name='competencia_id[]']"));
}

function eliminarFila(btn) {
    $(btn).closest('tr').remove();
}

function guardaryeditar(e) {
    e.preventDefault();
    var id = $("#id").val();
    var datos = [];
    $("#detalles tr").each(function() {
        var grado_id = $(this).find('select[name="grado_id[]"]').val();
        var competencia_id = $(this).find('select[name="competencia_id[]"]').val();
        if (grado_id && competencia_id) {
            datos.push({grado_id: grado_id, competencia_id: competencia_id});
        }
    });
    if (datos.length > 0) {
        if (id) {
            // Actualizar un registro existente
            $.ajax({
                url: link + "guardaryeditar",
                type: "POST",
                data: {
                    id: id,
                    grado_id: datos[0].grado_id, 
                    competencia_id: datos[0].competencia_id
                },
                success: function (datos) {
                    alert(datos);
                    MostrarListado();
                    tabla.ajax.reload();
                },
            });
        } else {
            // Crear nuevos registros
            $.ajax({
                url: link + "guardaryeditar",
                type: "POST",
                data: {datos: JSON.stringify(datos)},
                success: function (datos) {
                    alert(datos);
                    MostrarListado();
                    tabla.ajax.reload();
                },
            });
        }
        limpiar();
    } else {
        alert("Debe agregar al menos una relación grado-competencia.");
    }
}

function mostrar(id) {
    $.post(
        link + "mostrar",
        {
            id: id,
        },
        function (data, status) {
            data = JSON.parse(data);
            MostrarFormulario();

            // Limpiar el formulario antes de mostrar los datos
            limpiar();

            // Establecer el valor del id oculto
            $("#id").val(data.id);

            // Crear una nueva fila para el registro a editar
            var nuevaFila = '<tr><td><select name="grado_id[]" class="form-control selectpicker" data-live-search="true" required></select></td><td><select name="competencia_id[]" class="form-control selectpicker" data-live-search="true" required></select><td></td></tr>';
            $("#detalles tbody").html(nuevaFila);

            // Cargar los grados y competencias activas en los selects de la fila creada
            cargar_grados($("#detalles tr:last-child select[name='grado_id[]']"));
            cargar_competencias($("#detalles tr:last-child select[name='competencia_id[]']"));

            // Establecer el valor de los selects con los valores de la base de datos
            setTimeout(function() {
                $("#detalles tr:last-child select[name='grado_id[]']").val(data.grado_id);
                $("#detalles tr:last-child select[name='competencia_id[]']").val(data.competencia_id);
                $("#detalles tr:last-child select").selectpicker('refresh');
            }, 200);
        }
    );
}

function activar(id) {
    let condicion = confirm("¿ACTIVAR?");
    if (condicion === true) {
        $.ajax({
            type: "POST",
            url: link + "activar",
            data: {
                id: id,
            },
            success: function (datos) {
                alert(datos);
                tabla.ajax.reload();
            },
        });
    } else {
        alert("CANCELADO");
    }
}

function desactivar(id) {
    let condicion = confirm("¿DESACTIVAR?");
    if (condicion === true) {
        $.ajax({
            type: "POST",
            url: link + "desactivar",
            data: {
                id: id,
            },
            success: function (datos) {
                alert(datos);
                tabla.ajax.reload();
            },
        });
    } else {
        alert("CANCELADO");
    }
}

init();
