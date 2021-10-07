<?php
/**
 * Description of Documento
 *
 * @author mrvipaji
 */
class Documento {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $tipo_doc;

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
     * Set tipo_doc
     *
     * @param string 
     */
    public function setTipoDoc($tipo_doc) {
        $this->tipo_doc = $tipo_doc;
        return $this;
    }
    /**
     * Get tipo_doc
     *
     * @return string 
     */
    public function getTipoDoc() {
        return $this->tipo_doc;
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
