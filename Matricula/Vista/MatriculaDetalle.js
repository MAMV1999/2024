var link = "../Controlador/MatriculaDetalle.php?op=";
var tabla;

function init() {
    $("#frm_form").on("submit", function (e) {
        guardaryeditar(e);
    });
    MostrarListado();
    cargarSelectores();
    fecha();
    actualizarPagoMonto(); // Ensure this is called to set the initial value
}

function fecha() {
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $("#pago_fecha").val(today);
}

$(document).ready(function () {
    tabla = $("#myTable").DataTable({
        ajax: link + "listar",
    });
});

function limpiar() {
    $("#apoderado_id").val("");
    $("#apoderado_dni").val("");
    $("#apoderado_nombreyapellido").val("");
    $("#apoderado_telefono").val("");
    $("#apoderado_observaciones").val("");

    $("#alumno_dni").val("");
    $("#alumno_nombreyapellido").val("");
    $("#alumno_nacimiento").val("");
    $("#alumno_sexo").val("");
    $("#alumno_observaciones").val("");

    $("#detalle").val("");
    $("#matricula_observaciones").val("");

    $("#pago_numeracion").val("");
    $("#pago_fecha").val("");
    $("#pago_monto").val("");
    $("#pago_observaciones").val("");
}

function MostrarListado() {
    limpiar();
    $("#listado").show();
    $("#formulario").hide();
}

function MostrarFormulario() {
    $("#listado").hide();
    $("#formulario").show();
    fecha();
    cargarSelectores();
    obtenerPagoNumeracion();
    setTimeout(function() {  
        actualizarPagoMonto(); // Ensure this is called to set the initial value
    }, 200);
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

function cargarSelectores() {
    cargarMatriculas();
    cargarRazones();
    cargarMetodosPago();
}

function cargarMatriculas() {
    $.post(link + "listarMatriculas", function (r) {
        $("#matricula_id").html(r);
        $("#matricula_id").selectpicker("refresh");
        setTimeout(function() {  
            var selectedOption = $("#matricula_id option:selected");
            var preciomatricula = selectedOption.data('preciomatricula');
            $("#pago_monto").val(preciomatricula);
        }, 200);
    });
}

function cargarRazones() {
    $.post(link + "listarRazones", function (r) {
        $("#matricula_razon_id").html(r);
        $("#matricula_razon_id").selectpicker("refresh");
    });
}

function cargarMetodosPago() {
    $.post(link + "listarMetodosPago", function (r) {
        $("#metodo_pago_id").html(r);
        $("#metodo_pago_id").selectpicker("refresh");
    });
}

function obtenerPagoNumeracion() {
    $.post(link + "getNextPagoNumeracion", function (data) {
        $("#pago_numeracion").val(data);
    });
}

function actualizarPagoMonto() {
    var selectedOption = $("#matricula_id option:selected");
    var preciomatricula = selectedOption.data('preciomatricula');
    $("#pago_monto").val(preciomatricula);

    $("#matricula_id").change(function () {
        var selectedOption = $("#matricula_id option:selected");
        var preciomatricula = selectedOption.data('preciomatricula');
        $("#pago_monto").val(preciomatricula);
    });
}

function buscarApoderado() {
    var dni = $("#apoderado_dni").val();
    if (dni) {
        $.post(link + "buscarApoderado", {dni: dni}, function (data) {
            if (data) {
                var apoderado = JSON.parse(data);
                $("#apoderado_id").val(apoderado.id);
                $("#apoderado_nombreyapellido").val(apoderado.nombreyapellido);
                $("#apoderado_telefono").val(apoderado.telefono);
                $("#apoderado_observaciones").val(apoderado.observaciones);
            } else {
                alert("No se encontró un apoderado con el DNI proporcionado.");
            }
        });
    } else {
        alert("Por favor, ingrese un DNI para buscar.");
    }
}

function desactivar(id) {
    var codigo = prompt("Ingrese el código de verificación:");
    if (codigo) {
        $.post(link + "desactivar", {id: id, codigo: codigo}, function (data) {
            alert(data);
            tabla.ajax.reload();
        });
    } else {
        alert("El código de verificación es requerido para desactivar.");
    }
}

init();
