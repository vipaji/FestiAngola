function candidaturaAjax() {
    $.ajax({
        type: 'POST',
        url: '/Index/registar',
        dataType: 'json',
        data: {
            nome: $("#nome").val(),
            bi: $("#bi").val(),
            email: $("#email").val(),
            nascimento: $("#nascimento").val(),
            provincia: $("#provincia").val(),
            genero: $("#genero").val(),
            telefone: $("#telefone").val(),
            estilo: $("#estilo").val(),
            link: $("#link").val()
        },
        cache: false,
        beforeSend: function(xhr) {
            $("#processando").show();
        },
        success: function(data) {
            //alert(data.codigo);
            if (data.codigo == 200) {
                $("#processando").hide();
                $("#divIdade").hide();
                $("#divError").hide();
                $("#divBiExistente").hide();
                $("#sucesso").text(data.sucesso);
                $("#divSucesso").show();
                $("#divEncerrado").hide();
            }
            if (data.codigo == 300) {
                $("#processando").hide();
                $("#divIdade").hide();
                $("#divError").hide();
                $("#divBiExistente").show();
                $("#divEncerrado").hide();
            }
            if (data.codigo == 400) {
                $("#processando").hide();
                $("#divIdade").show();
                $("#divBiExistente").hide();
                $("#divEncerrado").hide();
            }
            if (data.codigo == 500) {
                $("#processando").hide();
                $("#divError").show();
                $("#divIdade").hide();
                $("#divBiExistente").hide();
                $("#divEncerrado").hide();
            }
            if (data.codigo == 600) {
                $("#divEncerrado").show();
                $("#processando").hide();
                $("#divError").hide();
                $("#divIdade").hide();
                $("#divBiExistente").hide();
            }
        },
        error: function(xhr, status, error) {
            $("#processando").hide();
            $("#divIdade").hide();
            $("#divBiExistente").hide();
            $("#divError").show();
        },
        complete: function(xhr) {
            limparAjax();
        }
    });
}
$('#submit-candidatura').click(function(event) {
    if ($("#nome").val() == "") {
        $("#nome").focus();
        $("#divVazio").show();
    } else if ($("#bi").val() == "") {
        $("#bi").focus();
        $("#divVazio").show();
    } else if ($("#nascimento").val() == "") {
        $("#nascimento").focus();
        $("#divVazio").show();
    } else if ($("#genero").val() == "") {
        $("#genero").focus();
        $("#divVazio").show();
    } else if ($("#provincia").val() == "") {
        $("#provincia").focus();
        $("#divVazio").show();
    } else if ($("#telefone").val() == "") {
        $("#telefone").focus();
        $("#divVazio").show();
    } else if ($("#estilo").val() == "") {
        $("#estilo").focus();
        $("#divVazio").show();
    } else if ($("#link").val() == "") {
        $("#link").focus();
        $("#divVazio").show();
    } else {
        candidaturaAjax();
    }

})

function limparAjax() {
    $("#nome").val("");
    $("#bi").val("");
    $("#email").val("");
    $("#nascimento").val("")
    $("#provincia").val("")
    $("#genero").val("")
    $("#telefone").val("")
    $("#estilo").val("")
    $("#link").val("")
    $("#divVazio").hide();
}