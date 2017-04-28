$.ajaxSetup({"cache":false});
$(document).ready(function ()
{

    $('#frmLogin').submit(function (event)
    {
        event.preventDefault();
        if ($("#txtLogin").val() === "Usuario" || $("#txtPassword").val() === "Contraseña")
        {
            return;
        }

        $("#msgbox").removeClass().addClass('messageboxinfo').text('Verificando ...').fadeIn(1000);
        $.post("php/ajax-json-acceso.php",
                {txtLogin: $('#txtLogin').val(),
                    txtPassword: $('#txtPassword').val(),
                    xUniversidad: 42
                },
                function (data)
                {
                    if (data.valido)
                    {
                        $("#msgbox").fadeTo(200, 0.1, function ()
                        {
                            $(this).html(data.msg).addClass("messageboxok").fadeTo(1500, 1, function () {
                                location.href = 'php/elegir-anio-trimestre.php';
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
        return false;
    });


});