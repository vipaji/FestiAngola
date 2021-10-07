<?php
/**
 * Description of FolhaPeca
 *
 * @author mrvipaji
 */
class FolhaPeca {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $folha;

    /**
     * @var varchar
     */
    private $peca;

    /**
     * @var integer
     */
    private $quantidade;

    /**
     * @var integer
     */
    private $preco;

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
     * Get folha
     *
     * @return integer 
     */
    public function getFolha() {
        return $this->folha;
    }

    /**
     * Set folha
     *
     * @param integer 
     */
    public function setFolha($folha) {
        $this->folha = $folha;
        return $this;
    }

    /**
     * Get peca
     *
     * @return varchar 
     */
    public function getPeca() {
        return $this->peca;
    }

    /**
     * Set peca
     *
     * @param varchar 
     */
    public function setPeca($peca) {
        $this->peca = $peca;
        return $this;
    }

    /**
     * Set quantidade
     *
     * @param integer 
     */
    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Get quantidade
     *
     * @return integer 
     */
    public function getQuantidade() {
        return $this->quantidade;
    }

    /**
     * Set preco
     *
     * @param integer 
     */
    public function setPreco($preco) {
        $this->preco = $preco;
        return $this;
    }

    /**
     * Get preco
     *
     * @return integer 
     */
    public function getPreco() {
        return $this->preco;
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
