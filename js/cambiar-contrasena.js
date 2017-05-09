$.ajaxSetup({"cache": false});

function cambiar()
{
    if (validar())
    {
        $("#msgbox").removeClass().addClass('messageboxinfo').text('Verificando ...').fadeIn(1000);
        $.post("ajax-cambiar-contrasena.php",
                {xContrasenaActual: $('#txtContrasenaActual').val(),
                    xContrasenaNueva: $('#txtContrasenaNueva').val()
                },
                function (data)
                {
                    if (data.valido)
                    {
                        $("#msgbox").fadeTo(200, 0.1, function ()
                        {
                            limpiar2();
                            $(this).html(data.msg).addClass("messageboxok").fadeTo(1500, 1, function () {
                                $("#msgbox").fadeOut("slow");
                            });

                        });
                    } else
                    {
                        $("#msgbox").fadeTo(200, 0.1, function ()
                        {
                            $(this).html(data.msg).addClass('messageboxerror').fadeTo(900, 1);
                        });
                    }
                }, 'json');
    }

}

function limpiar()
{
    $("#txtContrasenaActual").val("");
    $("#txtContrasenaNueva").val("");
    $("#txtContrasenaNueva2").val("");
    $("#frmUsuarioContrasena").submit();
}

function limpiar2()
{
    $("#txtContrasenaActual").val("");
    $("#txtContrasenaNueva").val("");
    $("#txtContrasenaNueva2").val("");
}

function validar()
{
    var valido = true;
    var msg = "";
    if ($("#txtContrasenaActual").val() === "")
    {
        msg += "Ingrese su contraseña actual. \n";
        valido = false;
    }
    if ($("#txtContrasenaNueva").val() === "")
    {
        msg += "Ingrese la contraseña nueva. \n";
        valido = false;
    }
    if ($("#txtContrasenaNueva2").val() === "")
    {
        msg += "Ingrese nuevamente la contraseña. \n";
        valido = false;
    }

    if ($("#txtContrasenaNueva").val() !== "" && $("#txtContrasenaNueva2").val() !== "")
    {
        if ($("#txtContrasenaNueva2").val() !== $("#txtContrasenaNueva").val())
        {
            $("#txtContrasenaNueva2").focus();
            msg += "Las contraseñas no coinciden. \n";
            valido = false;
        }
    }

    if (!valido)
    {
        alert(msg);
    }

    return valido;

}