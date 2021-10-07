<?php
/**
 * Description of Candidato
 *
 * @author mrvipaji
 */
class Candidato {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $numero;

    /**
     * @var string
     */
    private $nome;

    /**
     * @var string
     */
    private $bi;
    
    /**
     * @var string
     */
    private $email;
    
    /**
     * @var string
     */
    private $genero;

    /**
     * @var date
     */
    private $nascimento;

    /**
     * @var integer
     */
    private $provincia;

    /**
     * @var string
     */
    private $telefone;

    /**
     * @var integer
     */
    private $estilo;

    /**
     * @var integer
     */
    private $link;
    
    /**
     * @var integer
     */
    private $estado;

    /**
     * @var date
     */
    private $data;

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
     * @param string 
     */
    public function setNumero($numero) {
        $this->numero = $numero;
        return $this;
    }
    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero() {
        return $this->numero;
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
     * Set bi
     *
     * @param string 
     */
    public function setBi($bi) {
        $this->bi = $bi;
        return $this;
    }
    /**
     * Get bi
     *
     * @return string 
     */
    public function getBi() {
        return $this->bi;
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
     * Set genero
     *
     * @param string 
     */
    public function setGenero($genero) {
        $this->genero = $genero;
        return $this;
    }
    /**
     * Get genero
     *
     * @return string 
     */
    public function getGenero() {
        return $this->genero;
    }

    /**
     * Set nascimento
     *
     * @param date 
     */
    public function setNascimento($nascimento) {
        $this->nascimento = $nascimento;
        return $this;
    }
    /**
     * Get nascimento
     *
     * @return date 
     */
    public function getNascimento() {
        return $this->nascimento;
    }

    /**
     * Set provincia
     *
     * @param integer 
     */
    public function setProvincia($provincia) {
        $this->provincia = $provincia;
        return $this;
    }
    /**
     * Get provincia
     *
     * @return integer 
     */
    public function getProvincia() {
        return $this->provincia;
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
     * Set estilo
     *
     * @param integer 
     */
    public function setEstilo($estilo) {
        $this->estilo = $estilo;
        return $this;
    }
    /**
     * Get estilo
     *
     * @return integer 
     */
    public function getEstilo() {
        return $this->estilo;
    }

    /**
     * Set link
     *
     * @param string 
     */
    public function setLink($link) {
        $this->link = $link;
        return $this;
    }
    /**
     * Get link
     *
     * @return integer 
     */
    public function getLink() {
        return $this->link;
    }
    
    /**
     * Set estado
     *
     * @param string 
     */
    public function setEstado($estado) {
        $this->estado = $estado;
        return $this;
    }
    /**
     * Get estado
     *
     * @return integer 
     */
    public function geEstado() {
        return $this->estado;
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
     * @return integer 
     */
    public function getData() {
        return $this->data;
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
