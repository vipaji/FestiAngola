<?php
/**
 * Description of Folha
 *
 * @author mrvipaji
 */
class Folha {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var varchar
     */
    private $numero;

    /**
     * @var integer
     */
    private $veiculo;

    /**
     * @var string
     */
    private $area_servico;

    /**
     * @var integer
     */
    private $acessorio;

    /**
     * @var integer
     */
    private $documento;

    /**
     * @var integer
     */
    private $km;

    /**
     * @var string
     */
    private $avaria;

    /**
     * @var string
     */
    private $reparacao;

    /**
     * @var date
     */
    private $data_entrada;

    /**
     * @var date
     */
    private $data_saida;

    /**
     * Get id 
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param integer 
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Set numero
     *
     * @param varchar 
     */
    public function setNumero($numero) {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Get numero
     *
     * @return varchar 
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * Set veiculo
     *
     * @param integer 
     */
    public function setVeiculo($veiculo) {
        $this->veiculo = $veiculo;
        return $this;
    }

    /**
     * Get veiculo
     *
     * @return integer 
     */
    public function getVeiculo() {
        return $this->veiculo;
    }

    /**
     * Set area_servico
     *
     * @param string 
     */
    public function setAreaServico($area_servico) {
        $this->area_servico = $area_servico;
        return $this;
    }

    /**
     * Get area_servico
     *
     * @return string 
     */
    public function getAreaServico() {
        return $this->area_servico;
    }

    /**
     * Set acessorio
     *
     * @param integer 
     */
    public function setAcessorio($acessorio) {
        $this->acessorio = $acessorio;
        return $this;
    }
    /**
     * Get acessorio
     *
     * @return integer 
     */
    public function getAcessorio() {
        return $this->acessorio;
    }

    /**
     * Set documento
     *
     * @param integer 
     */
    public function setDocumento($documento) {
        $this->documento = $documento;
        return $this;
    }
    /**
     * Get documento
     *
     * @return integer 
     */
    public function getDocumento() {
        return $this->documento;
    }

    /**
     * Set km
     *
     * @param integer 
     */
    public function setKm($km) {
        $this->km = $km;
        return $this;
    }
    /**
     * Get km
     *
     * @return integer 
     */
    public function getKm() {
        return $this->km;
    }

    /**
     * Set avaria
     *
     * @param string 
     */
    public function setAvaria($avaria) {
        $this->avaria = $avaria;
        return $this;
    }
    /**
     * Get avaria
     *
     * @return string 
     */
    public function getAvaria() {
        return $this->avaria;
    }

    /**
     * Set reparacao
     *
     * @param string 
     */
    public function setReparacao($reparacao) {
        $this->reparacao = $reparacao;
        return $this;
    }
    /**
     * Get reparacao
     *
     * @return string 
     */
    public function getReparacao() {
        return $this->reparacao;
    }

    /**
     * Set data_entrada
     *
     * @param date 
     */
    public function setDataEntrada($data_entrada) {
        $this->data_entrada = $data_entrada;
        return $this;
    }
    /**
     * Get data_entrada
     *
     * @return date 
     */
    public function getDataEntrada() {
        return $this->data_entrada;
    }

    /**
     * Set data_saida
     *
     * @param date 
     */
    public function setDataSaida($data_saida) {
        $this->data_saida = $data_saida;
        return $this;
    }
    /**
     * Get data_saida
     *
     * @return date 
     */
    public function getDataSaida() {
        return $this->data_saida;
    }


    /**
     * toString
     *
     * @return string 
     */
    public function __toString() {
        return $this->id;
    }

}
