var link = "../Controlador/Matricula.php?op=";
var tabla;

function init() {
    $("#frm_form").on("submit", function (e) {
        guardaryeditar(e);
    });
    MostrarListado();
    cargar_grados();
    cargar_trabajadores();
    actualizarFechaHora();
    setInterval(actualizarFechaHora, 1000);
}

function cargar_grados() {
    $.post(link + "listar_grados_disponibles", function (r) {
        $("#institucion_grado_id").html(r);
        $("#institucion_grado_id").selectpicker("refresh");
    });
}

function cargar_trabajadores() {
    $.post(link + "listar_trabajadores_activos", function (r) {
        $("#trabajador_id").html(r);
        $("#trabajador_id").selectpicker("refresh");
    });
}

$(document).ready(function () {
    tabla = $("#myTable").DataTable({
        ajax: link + "listar",
    });
});

function limpiar() {
    cargar_grados();
    cargar_trabajadores();
    $("#id").val("");
    $("#detalle").val("");
    $("#institucion_grado_id").val("");
    $("#trabajador_id").val("");
    $("#preciomatricula").val("");
    $("#preciomensualidad").val("");
    $("#preciootros").val("");
    $("#aforo").val("");
    $("#observaciones").val("");
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

function guardaryeditar(e) {
    e.preventDefault();

    $.ajax({
        url: link + "guardaryeditar",
        type: "POST",
        data: $("#frm_form").serialize(),

        success: function (datos) {
            alert(datos);
            MostrarListado();
            tabla.ajax.reload();
        },
    });
    limpiar();
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

            $("#id").val(data.id);
            $("#detalle").val(data.detalle);
            $("#institucion_grado_id").val(data.institucion_grado_id);
            $("#trabajador_id").val(data.trabajador_id);
            $("#preciomatricula").val(data.preciomatricula);
            $("#preciomensualidad").val(data.preciomensualidad);
            $("#preciootros").val(data.preciootros);
            $("#aforo").val(data.aforo);
            $("#observaciones").val(data.observaciones);
            $('#institucion_grado_id').selectpicker('refresh');
            $('#trabajador_id').selectpicker('refresh');
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
