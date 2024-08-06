var link = "../Controlador/AreaCurricular.php?op=";
var tabla;

function init() {
    $("#frm_form").on("submit", function (e) {
        guardaryeditar(e);
    });
    MostrarListado();
    cargar_niveles();
    actualizarFechaHora();
    setInterval(actualizarFechaHora, 1000);
}

function cargar_niveles(selectElement = null) {
    $.post(link + "listar_niveles_activos", function (r) {
        if (selectElement) {
            $(selectElement).html(r);
            $(selectElement).selectpicker("refresh");
        } else {
            // Carga los niveles activos en todos los selects
            $("select[name='institucion_nivel_id[]']").each(function() {
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
    $("#detalles tbody").html('<tr><td><input type="text" name="nombre[]" class="form-control" required></td><td><select name="institucion_nivel_id[]" class="form-control selectpicker" data-live-search="true" required></select></td><td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)">Eliminar</button></td></tr>');
    cargar_niveles();
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
    var nuevaFila = '<tr><td><input type="text" name="nombre[]" class="form-control" required></td><td><select name="institucion_nivel_id[]" class="form-control selectpicker" data-live-search="true" required></select></td><td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)">Eliminar</button></td></tr>';
    $("#detalles tbody").append(nuevaFila);
    cargar_niveles($("#detalles tr:last-child select"));
}

function eliminarFila(btn) {
    $(btn).closest('tr').remove();
}

function guardaryeditar(e) {
    e.preventDefault();
    var id = $("#id").val();
    var datos = [];
    $("#detalles tr").each(function() {
        var nombre = $(this).find('input[name="nombre[]"]').val();
        var institucion_nivel_id = $(this).find('select[name="institucion_nivel_id[]"]').val();
        if (nombre && institucion_nivel_id) {
            datos.push({nombre: nombre, institucion_nivel_id: institucion_nivel_id});
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
                    nombre: datos[0].nombre, 
                    institucion_nivel_id: datos[0].institucion_nivel_id
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
        alert("Debe agregar al menos un área curricular.");
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
            var nuevaFila = '<tr><td><input type="text" name="nombre[]" class="form-control" required value="' + data.nombre + '"></td><td><select name="institucion_nivel_id[]" class="form-control selectpicker" data-live-search="true" required></select></td><td></td></tr>';
            $("#detalles tbody").html(nuevaFila);

            // Cargar los niveles activos en el select de la fila creada
            cargar_niveles($("#detalles tr:last-child select"));

            // Establecer el valor del select con el valor de la base de datos
            setTimeout(function() {
                $("#detalles tr:last-child select").val(data.institucion_nivel_id);
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
