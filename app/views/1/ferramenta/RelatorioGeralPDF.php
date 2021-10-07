<?php

$dataActual = new Data();

$utilizador = $v_userInfo;

$direitosAutor = " | Ango Segu | Departamento de Tecnologias de Informação";

//P - Vertical, L - Horizontal
$pdf = new DocPdf('P', Method::upperGeneral('Lista geral de ferramentas'), $utilizador->getNome(), $dataActual->getDataActual(), $direitosAutor);

/* Posicionamento da coluna: L - Esquerda, R - Direita, C - Centro
  array( array(titulo_coluna1, tamanho_da_coluna1,posicionamento_coluna1),
  array(titulo_coluna2, tamanho_da_coluna2,posicionamento_coluna2),.....
 */
$cabecalho = array(
    array('#', 10, 'L'),
    array('Nome', 80, 'C'),
    array('Quantidade', 100, 'C'));

$pdf->imprimirTabelaFerramentas($cabecalho, $v_entities);
$pdf->visualizar();
