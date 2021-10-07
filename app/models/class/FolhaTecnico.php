<?php
/**
 * Description of Tecnico
 *
 * @author mrvipaji
 */
class FolhaTecnico {

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
    private $tecnico;

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
     * Set tecnico
     *
     * @param integer 
     */
    public function setTecnico($tecnico) {
        $this->tecnico = $tecnico;
        return $this;
    }

    /**
     * Get tecnico
     *
     * @return integer 
     */
    public function getTecnico() {
        return $this->tecnico;
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
