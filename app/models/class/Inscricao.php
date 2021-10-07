<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Inscricao
 *
 * @author mrvipaji
 */
class Inscricao {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nome;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $telefone;

    /**
     * @var integer
     */
    private $escola;

    /**
     * @var date
     */
    private $inscricao;

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
     * Set nome
     *
     * @param string 
     */
    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * Set email
     *
     * @param string 
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set telefone
     *
     * @param string 
     */
    public function setTelefone($telefone) {
        $this->telefone = $telefone;
        return $this;
    }

    /**
     * Get telefone
     *
     * @return string 
     */
    public function getTelefone() {
        return $this->telefone;
    }

    /**
     * Set escola
     *
     * @param integer 
     */
    public function setEscola($escola) {
        $this->escola = $escola;
        return $this;
    }

    /**
     * Get escola
     *
     * @return integer 
     */
    public function getEscola() {
        return $this->escola;
    }

    /**
     * Set inscricao
     *
     * @param date 
     */
    public function setDataInscricao($inscricao) {
        $this->inscricao = $inscricao;
        return $this;
    }

    /**
     * Get inscricao
     *
     * @return date 
     */
    public function getDataInscricao() {
        return $this->inscricao;
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
