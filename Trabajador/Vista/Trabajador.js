var link = '../Controlador/Trabajador.php?op=';
var tabla;

function init() {
    $("#frm_form").on("submit", function (e) {
        guardaryeditar(e);
    });
    MostrarListado();
    actualizarFechaHora();
    setInterval(actualizarFechaHora, 1000);
}

$(document).ready(function () {
    tabla = $('#myTable').DataTable({
        "ajax": link + 'listar'
    });
});

function limpiar() {
    $("#id").val("");
    $("#usuario").val("");
    $("#contraseña").val("");
    $("#dni").val("");
    $("#nombre_apellido").val("");
    $("#nacimiento").val("");
    $("#sexo").val("");
    $("#estado_civil").val("");
    $("#cargo").val("");
    $("#direccion").val("");
    $("#telefono").val("");
    $("#correo").val("");
    $("#sueldo").val("");
    $("#cuenta_bcp").val("");
    $("#interbancario_bcp").val("");
    $("#sunat_ruc").val("");
    $("#sunat_usuario").val("");
    $("#sunat_contraseña").val("");
    $("#observaciones").val("");
    $("#usuariocrea").val("");
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
        }
    });
    limpiar();
}

function mostrar(id) {
    $.post(link + "mostrar", {
        id: id
    },
        function (data, status) {
            data = JSON.parse(data);
            MostrarFormulario();

            $("#id").val(data.id);
            $("#usuario").val(data.usuario);
            $("#contraseña").val(data.contraseña);
            $("#dni").val(data.dni);
            $("#nombre_apellido").val(data.nombre_apellido);
            $("#nacimiento").val(data.nacimiento);
            $("#sexo").val(data.sexo);
            $("#estado_civil").val(data.estado_civil);
            $("#cargo").val(data.cargo);
            $("#direccion").val(data.direccion);
            $("#telefono").val(data.telefono);
            $("#correo").val(data.correo);
            $("#sueldo").val(data.sueldo);
            $("#cuenta_bcp").val(data.cuenta_bcp);
            $("#interbancario_bcp").val(data.interbancario_bcp);
            $("#sunat_ruc").val(data.sunat_ruc);
            $("#sunat_usuario").val(data.sunat_usuario);
            $("#sunat_contraseña").val(data.sunat_contraseña);
            $("#observaciones").val(data.observaciones);
            $("#usuariocrea").val(data.usuariocrea);
        })
}

function activar(id) {
    let condicion = confirm("¿ACTIVAR?");
    if (condicion === true) {
        $.ajax({
            type: "POST",
            url: link + "activar",
            data: {
                id: id
            },
            success: function (datos) {
                alert(datos);
                tabla.ajax.reload();
            }
        })
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
                id: id
            },
            success: function (datos) {
                alert(datos);
                tabla.ajax.reload();
            }
        })
    } else {
        alert("CANCELADO");
    }
}

init();
