<?php
/**
 * Description of Documento
 *
 * @author mrvipaji
 */
class FolhaDocumento {

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
    private $documento;

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
     * toString
     *
     * @return string 
     */
    public function __toString() {
        return $this->id;
    }

}
