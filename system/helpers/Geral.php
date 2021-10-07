<?php

Class Geral {

    const CONS_PARAMETRO_TEMPO_ANOS = "ANOS";
    const CONS_PARAMETRO_TEMPO_MESES = "MESES";
    const CONS_PARAMETRO_TEMPO_DIAS = "DIAS";
    const CONS_PARAMETRO_TEMPO_HORAS = "HORAS";
    const CONS_PARAMETRO_TEMPO_MINUTOS = "MINUTOS";
    const CONS_PARAMETRO_TEMPO_SEGUNDOS = "SEGUNDOS";
    
    const CONS_GENERO_MASCULINO = "M";
    const CONS_GENERO_FEMININO = "F";
    
    const CONS_ESTADO_CIVIL_SOLTEIRO = "S";
    const CONS_ESTADO_CIVIL_CASADO = "C";
    const CONS_ESTADO_CIVIL_DIVORCIADO = "D";
    const CONS_ESTADO_CIVIL_VIUVO = "V";
    const CONS_ESTADO_CIVIL_UNIAO_DE_FACTO = "U";
    
    const CONS_PESSOA_SINGULAR = "S";
    const CONS_PESSOA_COLECTIVA = "C";
    
    const CONS_CONTA_DEPOSITO_A_ORDEM = "DO";
    const CONS_CONTA_CAIXA = "CA";
    
    const CONS_CONDICAO_PRONTO_PAGAMENTO = "PP";
    const CONS_CONDICAO_PRESTACOES = "PR";
    
    const CONS_BLOG_PUBLICADO = 1;
    const CONS_BLOG_N_PUBLICADO = 0;

    const CONS_UTILIZADOR_ACTIVADO = 1;
    const CONS_UTILIZADOR_DESACTIVADO = 0;
    
    const CONS_PERFIL_ADMINISTRADOR = "administrador";
    const CONS_PERFIL_GESTOR = "gestor";
    const CONS_PERFIL_OPERADOR = "operador";

    const DIR_IMG_UTILIZADORES = 'https://festiangola.co.ao/web-files/uploads/utilizadores/';
    const DIR_IMG_BLOG = 'https://festiangola.co.ao/web-files/uploads/blog/';
    const DIR_IMG_BLOG_PADRAO = 'https://festiangola.co.ao/web-files/assets/img/default.jpg';
    
    
    const CONS_MESSAGE_ERRO_PAGAMENTO_IMOVEL_SEM_CLIENTE = "Impossível efectuar pagamento!!! Cliente sem imóvel e sem extra(s).";
    const CONS_MESSAGE_ERRO_CLIENTE_NAO_ENCONTRADO = "O cliente não foi encontrado.";
    const CONS_MESSAGE_ERRO_EQUIPAMENTO_NAO_ENCONTRADO = "O equipamento não foi encontrado.";
    const CONS_MESSAGE_ERRO_ACESSORIO_NAO_ENCONTRADO = "O acessório não foi encontrado.";
    const CONS_MESSAGE_ERRO_IMOVEL_SEM_NEGOCIACAO = "Impossível efectuar pagamento, imóvel sem negociação!!!";
    const CONS_MESSAGE_ERRO_ENTIDADE_NAO_ENCONTRADO = " %s não foi encontrado(a).";
    const CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES = "Operação não permitida. O seu perfil não lhe permite realizar esta operação.";
    
    const CONS_PAGAMENTO_ESTADO_PENDENTE = "P";
    const CONS_PAGAMENTO_ESTADO_CONCLUIDO = "C";
    
    const CONS_MULTIMEDIA_TIPO_IMAGEM = "I";
    const CONS_MULTIMEDIA_TIPO_VIDEO = "V";
    const CONS_MULTIMEDIA_TIPO_DOCUMENTO = "D";
    const CONS_MULTIMEDIA_TIPO_OUTRO = "O";
    const CONS_MULTIMEDIA_TIPO_AUDIO = "A";
    
    
    const EMAIL_NOTIFICACAO= "geral@festiangola.co.ao";
    const PASSWORD_EMAIL_NOTIFICACAO="Angola@2021!";
    const SMTP_PORT=465;
    
    const CONS_METODO_POST = "POST";
    const CONS_METODO_GET = "GET";
    
    const CONS_ESTADO_NOTIFICACAO_NAO_LIDA=0;
    const CONS_ESTADO_NOTIFICACAO_LIDA=1;
    
    const CONS_N_APURADO = 0;
    const CONS_APURADO = 1;

}
