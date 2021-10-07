<?php
/**
 * Description of Agenda
 *
 * @author mrvipaji
 */
class Agenda {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var date
     */
    private $data;

    /**
     * @var time
     */
    private $hora;

    /**
     * @var string
     */
    private $local;

    /**
     * @var string
     */
    private $descricao;

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
     * Set data
     *
     * @param date 
     */
    public function setData($data) {
        $this->data = $data;
        return $this;
    }
    /**
     * Get data
     *
     * @return date 
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Set hora
     *
     * @param time 
     */
    public function setHora($hora) {
        $this->hora = $hora;
        return $this;
    }
    /**
     * Get hora
     *
     * @return time 
     */
    public function getHora() {
        return $this->hora;
    }

    /**
     * Set local
     *
     * @param string 
     */
    public function setLocal($local) {
        $this->local = $local;
        return $this;
    }
    /**
     * Get local
     *
     * @return string 
     */
    public function getLocal() {
        return $this->local;
    }

    /**
     * Set descricao
     *
     * @param string 
     */
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
        return $this;
    }
    /**
     * Get descricao
     *
     * @return string 
     */
    public function getDescricao() {
        return $this->descricao;
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
