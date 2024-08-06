var link = "../Controlador/Competencia.php?op=";
var tabla;

function init() {
    $("#frm_form").on("submit", function (e) {
        guardaryeditar(e);
    });
    MostrarListado();
    cargar_areas_curriculares();
    actualizarFechaHora();
    setInterval(actualizarFechaHora, 1000);
}

function cargar_areas_curriculares(selectElement = null) {
    $.post(link + "listar_areas_curriculares_activas", function (r) {
        if (selectElement) {
            $(selectElement).html(r);
            $(selectElement).selectpicker("refresh");
        } else {
            // Carga las áreas curriculares activas en todos los selects
            $("select[name='area_curricular_id[]']").each(function() {
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
    $("#detalles tbody").html('<tr><td><input type="text" name="nombre[]" class="form-control" required></td><td><select name="area_curricular_id[]" class="form-control selectpicker" data-live-search="true" required></select></td><td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)">Eliminar</button></td></tr>');
    cargar_areas_curriculares();
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
    var nuevaFila = '<tr><td><input type="text" name="nombre[]" class="form-control" required></td><td><select name="area_curricular_id[]" class="form-control selectpicker" data-live-search="true" required></select></td><td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)">Eliminar</button></td></tr>';
    $("#detalles tbody").append(nuevaFila);
    cargar_areas_curriculares($("#detalles tr:last-child select"));
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
        var area_curricular_id = $(this).find('select[name="area_curricular_id[]"]').val();
        if (nombre && area_curricular_id) {
            datos.push({nombre: nombre, area_curricular_id: area_curricular_id});
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
                    area_curricular_id: datos[0].area_curricular_id
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
        alert("Debe agregar al menos una competencia.");
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
            var nuevaFila = '<tr><td><input type="text" name="nombre[]" class="form-control" required value="' + data.nombre + '"></td><td><select name="area_curricular_id[]" class="form-control selectpicker" data-live-search="true" required></select></td><td></td></tr>';
            $("#detalles tbody").html(nuevaFila);

            // Cargar las áreas curriculares activas en el select de la fila creada
            cargar_areas_curriculares($("#detalles tr:last-child select"));

            // Establecer el valor del select con el valor de la base de datos
            setTimeout(function() {
                $("#detalles tr:last-child select").val(data.area_curricular_id);
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
