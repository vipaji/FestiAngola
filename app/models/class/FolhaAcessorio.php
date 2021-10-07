<?php
/**
 * Description of Acessorio
 *
 * @author mrvipaji
 */
class FolhaAcessorio {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $folha;

    /**
     * @var integer
     */
    private $acessorio;

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
     * toString
     *
     * @return string 
     */
    public function __toString() {
        return $this->id;
    }

}
