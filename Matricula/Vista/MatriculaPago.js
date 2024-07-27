var link = "../Controlador/MatriculaPago.php?op=";
var tabla;

function init() {
    $("#frm_form").on("submit", function (e) {
        guardaryeditar(e);
    });
    MostrarListado();
    cargarMatriculaDetalles();
    cargarMetodosPago();
}

$(document).ready(function () {
    tabla = $("#myTable").DataTable({
        ajax: link + "listar",
    });
});

function limpiar() {
    $("#id").val("");
    $("#matricula_detalle_id").val("");
    $("#matricula_metodo_id").val("");
    $("#numeracion").val("");
    $("#fecha").val("");
    $("#monto").val("");
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
    $.post(link + "mostrar", { id: id }, function (data) {
        var pago = JSON.parse(data);
        $("#id").val(pago.id);
        $("#matricula_metodo_id").val(pago.matricula_metodo_id);
        $("#numeracion").val(pago.numeracion);
        $("#fecha").val(pago.fecha);
        $("#monto").val(pago.monto);
        $("#observaciones").val(pago.observaciones);

        // Cargar solo los detalles específicos de la matrícula
        $.post(link + "listarMatriculaDetallesPorId", { id: pago.matricula_detalle_id }, function (r) {
            $("#matricula_detalle_id").html(r);
            $("#matricula_detalle_id").val(pago.matricula_detalle_id);
        });

        MostrarFormulario();
    });
}


function desactivar(id) {
    $.post(link + "desactivar", { id: id }, function (data) {
        alert(data);
        tabla.ajax.reload();
    });
}

function activar(id) {
    $.post(link + "activar", { id: id }, function (data) {
        alert(data);
        tabla.ajax.reload();
    });
}

function cargarMatriculaDetalles() {
    $.post(link + "listarMatriculaDetalles", function (r) {
        $("#matricula_detalle_id").html(r);
    });
}

function cargarMetodosPago() {
    $.post(link + "listarMetodosPago", function (r) {
        $("#matricula_metodo_id").html(r);
    });
}

init();
