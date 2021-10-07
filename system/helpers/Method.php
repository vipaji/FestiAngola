<?php

Class Method {

    public static function converteParaReal($parametro) {
        $tmp = str_replace('.', '', $parametro);
        $valor = (double) (str_replace(',', '.', $tmp));
        return $valor;
    }

    public static function geraUsername($nome_completo) {

        $map = array('á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a',
            'é' => 'e', 'ê' => 'e', 'í' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ú' => 'u', 'ü' => 'u', 'ç' => 'c', 'Á' => 'A', 'À' => 'A',
            'Ã' => 'A', 'Â' => 'A', 'É' => 'E', 'Ê' => 'E',
            'Í' => 'I', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U',
            'Ü' => 'U', 'Ç' => 'C'
        );

        $nome_completo_aux = strtr(trim(strtolower($nome_completo)), $map);
        if ($nome_completo_aux == "") {
            return "mirabilisclass.mirabilisclass";
        } else if ($nome_completo_aux != "") {
            $nomes = explode(" ", $nome_completo_aux);
            return "" . $nomes[0] . "." . $nomes[sizeof($nomes) - 1];
        }
    }

    public static function retorna2nomes($nome_completo) {
        $nomes = explode(" ", $nome_completo);
        if (sizeof($nomes) > 1) {
            return "" . ucfirst(strtolower($nomes[0])) . " " . ucfirst(strtolower($nomes[sizeof($nomes) - 1]));
        }
        return $nome_completo;
    }

    public static function upperGeneral($string) {
        $map = array('á' => 'Á', 'à' => 'À', 'ã' => 'Ã', 'â' => 'Â',
            'é' => 'É', 'ê' => 'Ê', 'í' => 'Í', 'ó' => 'Ó', 'ô' => 'Ô', 'õ' => 'Õ',
            'ú' => 'Ú', 'ü' => 'Ü', 'ç' => 'Ç');
        $string_aux = strtr(trim(strtoupper($string)), $map);
        return $string_aux;
    }

    public static function lowerGeneral($string) {
        $map = array('Á' => 'á', 'À' => 'à', 'Ã' => 'ã', 'Â' => 'â',
            'É' => 'é', 'Ê' => 'ê', 'Í' => 'í', 'Ó' => 'ó', 'Ô' => 'ô', 'Õ' => 'õ',
            'Ú' => 'ú', 'Ü' => 'ü', 'Ç' => 'ç');
        $string_aux = strtr(trim(strtolower($string)), $map);
        return $string_aux;
    }

    public static function valorPorExtenso($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false) {

        $valor = self::removerFormatacaoNumero($valor);

        $singular = null;
        $plural = null;

        if ($bolExibirMoeda) {
            $singular = array("centavo", "kwanza", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "kwanzas", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        } else {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        }

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");


        if ($bolPalavraFeminina) {

            if ($valor == 1) {
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            } else {
                $u = array("", "um", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            }


            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas", "quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
        }


        $z = 0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);

        for ($i = 0; $i < count($inteiro); $i++) {
            for ($ii = mb_strlen($inteiro[$i]); $ii < 3; $ii++) {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000")
                $z++;
            elseif ($z > 0)
                $z--;

            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plural[$t];

            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        $rt = mb_substr($rt, 1);

        return (ucfirst($rt) ? ucfirst(trim($rt)) : "zero");
    }

    public static function removerFormatacaoNumero($strNumero) {

        //$strNumero = trim(str_replace("R$", null, $strNumero));

        $vetVirgula = explode(",", $strNumero);
        if (count($vetVirgula) == 1) {
            $acentos = array(".");
            $resultado = str_replace($acentos, "", $strNumero);
            return $resultado;
        } else if (count($vetVirgula) != 2) {
            return $strNumero;
        }

        $strNumero = $vetVirgula[0];
        $strDecimal = mb_substr($vetVirgula[1], 0, 2);

        $acentos = array(".");
        $resultado = str_replace($acentos, "", $strNumero);
        $resultado = $resultado . "." . $strDecimal;

        return $resultado;
    }

    public static function abreviaTexto($texto, $limite, $quebrar = true) {
        //corta as tags do texto para evitar corte errado
        $contador = strlen(strip_tags($texto));
        if ($contador <= $limite):
            //se o número do texto form menor ou igual o limite então retorna ele mesmo
            $newtext = $texto;
        else:
            if ($quebrar == true): //se for maior e $quebrar for true
                //corta o texto no limite indicado e retira o ultimo espaço branco
                $newtext = trim(mb_substr($texto, 0, $limite)) . "...";
            else:
                //localiza ultimo espaço antes de $limite
                $ultimo_espaço = strrpos(mb_substr($texto, 0, $limite), " ");
                //corta o $texto até a posição lozalizada
                $newtext = trim(mb_substr($texto, 0, $ultimo_espaço)) . "...";
            endif;
        endif;
        return $newtext;
    }

    public static function devolveConstanteTipoMultimediaByTipo($tipo) {
        $retorno = "";
        switch ($tipo) {
            case "documento":
                $retorno = Geral::CONS_MULTIMEDIA_TIPO_DOCUMENTO;
                break;
            case "imagem":
                $retorno = Geral::CONS_MULTIMEDIA_TIPO_IMAGEM;
                break;
            case "audio":
                $retorno = Geral::CONS_MULTIMEDIA_TIPO_AUDIO;
                break;
            case "video":
                $retorno = Geral::CONS_MULTIMEDIA_TIPO_VIDEO;
                break;
            case "outro":
                $retorno = Geral::CONS_MULTIMEDIA_TIPO_OUTRO;
                break;
            default:
                $retorno = null;
                break;
        }
        return $retorno;
    }

    public static function calculaDiferenca($num1, $num2) {
        $num1_aux = floatval($num1);
        $num2_aux = floatval($num2);
        return $num1_aux - $num2_aux;
    }

    public static function registaLog($operacao, $descricao, $utilizador) {
        $log = new Log();

        $log->setDatahora(date('Y-m-d H-i-s'));
        $log->setDescricao($descricao);
        $log->setOperacao($operacao);
        $log->setUtilizador((new UtilizadorDAO())->buscarID($utilizador));

        $logDAO = new LogDAO();
        $logDAO->salvar($log);
    }

    public static function geraNumeroCandidato($ultimo) {
        $candidato = "";
        $tamanho = strlen(intval($ultimo));

        switch ($tamanho) {
            case 1:
                $candidato = "0000" . ($ultimo + 1);
                break;
            case 2:
                $candidato = "000" . ($ultimo + 1);
                break;
            case 3:
                $candidato = "00" . ($ultimo + 1);
                break;
            case 4:
                $candidato = "0" . ($ultimo + 1);
                break;
            default: $candidato = $candidato . ($ultimo + 1);
                break;
        }

        return $candidato;
    }



    public function getFirstWeekDay($ano, $num_semana) {
        $primeiro = new DateTime();
        $primeiro->setISODate($ano, $num_semana);


        return $primeiro->format('Y-m-d');
    }

    public function getLastWeekDay($ano, $num_semana) {
        $primeiro = new DateTime();
        $primeiro->setISODate($ano, $num_semana);
        $ultimo = date_add(date_create($primeiro->format('Y-m-d')), date_interval_create_from_date_string("7 days"));

        return $ultimo->format('Y-m-d');
    }

    public static function sksort(&$array, $subkey = "total", $sort_ascending = false) {

        if (count($array))
            $temp_array[key($array)] = array_shift($array);

        foreach ($array as $key => $val) {
            $offset = 0;
            $found = false;
            foreach ($temp_array as $tmp_key => $tmp_val) {
                if (!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey])) {
                    $temp_array = array_merge((array) array_slice($temp_array, 0, $offset), array($key => $val), array_slice($temp_array, $offset)
                    );
                    $found = true;
                }
                $offset++;
            }
            if (!$found)
                $temp_array = array_merge($temp_array, array($key => $val));
        }

        if ($sort_ascending)
            $array = array_reverse($temp_array);
        else
            $array = $temp_array;
    }

}
