<?php

//descricao, area, iso, iso3, nome, nome_formal
/**
 * Description of Veiculo
 *
 * @author mrvipaji
 */
class Veiculo {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $marca;

    /**
     * @var string
     */
    private $modelo;

    /**
     * @var string
     */
    private $matricula;

    /**
     * @var string
     */
    private $num_motor;

    /**
     * @var string
     */
    private $cor;

    /**
     * @var string
     */
    private $combustivel;

    /**
     * @var string
     */
    private $data_registo;

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
     * Set marca
     *
     * @param integer 
     */
    public function setMarca($marca) {
        $this->marca = $marca;
        return $this;
    }

    /**
     * Get marca
     *
     * @return integer 
     */
    public function getMarca() {
        return $this->marca;
    }

    /**
     * Set modelo
     *
     * @param string 
     */
    public function setModelo($modelo) {
        $this->modelo = $modelo;
        return $this;
    }

    /**
     * Get modelo
     *
     * @return string 
     */
    public function getModelo() {
        return $this->modelo;
    }

    /**
     * Set matricula
     *
     * @param string 
     */
    public function setMatricula($matricula) {
        $this->matricula = $matricula;
        return $this;
    }

    /**
     * Get matricula
     *
     * @return string 
     */
    public function getMatricula() {
        return $this->matricula;
    }

    /**
     * Set num_motor
     *
     * @param string 
     */
    public function setNumMotor($num_motor) {
        $this->num_motor = $num_motor;
        return $this;
    }

    /**
     * Get num_motor
     *
     * @return string 
     */
    public function getNumMotor() {
        return $this->num_motor;
    }

    /**
     * Set cor
     *
     * @param string 
     */
    public function setCor($cor) {
        $this->cor = $cor;
        return $this;
    }

    /**
     * Get cor
     *
     * @return string 
     */
    public function getCor() {
        return $this->cor;
    }

    /**
     * Set combustivel
     *
     * @param string 
     */
    public function setCombustivel($combustivel) {
        $this->combustivel = $combustivel;
        return $this;
    }

    /**
     * Get combustivel
     *
     * @return string 
     */
    public function getCombustivel() {
        return $this->combustivel;
    }

    /**
     * Set data_registo
     *
     * @param date 
     */
    public function setDataRegisto($data_registo) {
        $this->data_registo = $data_registo;
        return $this;
    }

    /**
     * Get data_registo
     *
     * @return date 
     */
    public function getDataRegisto() {
        return $this->data_registo;
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
