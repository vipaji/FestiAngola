<?php

/**
 * Description of FolhaDocumentoDAO
 *
 * @author mrvipaji
 */
class FolhaDocumentoDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(FolhaDocumento $folhadocumento) {

        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO folha_documento (folha, documento) VALUES (:folha, :documento)'
            );

            $stmt->bindValue(':folha', $folhadocumento->getFolha());
            $stmt->bindValue(':documento', $folhadocumento->getDocumento());
           
            $stmt->execute();

            $lastId = $this->pdo->lastInsertId();

            $this->pdo->commit();
            return $this->buscarID($lastId);
        } catch (Exception $e) {

                    if ($this->pdo->inTransaction()){
            $this->pdo->rollback();
        }
            throw $e;
        }
    }

    public function actualizar(FolhaDocumento $folhadocumento) {

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE  folha_documento SET folha=:folha,documento=:documento, data=:data WHERE id =:id'
            );
            $stmt->bindValue(':folha', $folhadocumento->getFolha());
            $stmt->bindValue(':documento', $folhadocumento->getDocumento());
            $stmt->bindValue(':id', $folhadocumento->getId());

            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
                    if ($this->pdo->inTransaction()){
            $this->pdo->rollback();
        }
            throw $e;
        }
    }

    public function eliminar(FolhaDocumento $folhadocumento) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE FROM folha_documento WHERE id = :id'
            );
            $stmt->bindValue(':id', $folhadocumento->getId());

            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
                    if ($this->pdo->inTransaction()){
            $this->pdo->rollback();
        }
            throw $e;
        }
    }
    public function listarDocumentos($folha) {
        $statement = $this->pdo->prepare('SELECT * FROM folha_documento WHERE folha=:folha ORDER BY id desc');
        $statement->bindValue(':folha', $folha);
        $statement->execute();

        return  $this->processResults($statement);
        
    }

    public function buscarID($id) {
        $statement = $this->pdo->prepare('SELECT * FROM folha_documento WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function listarTodos($orderby = 'folha') {
        $statement = $this->pdo->query('SELECT * FROM folha_documento ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $folhadocumento = new FolhaDocumento();
                
                $folhadocumento->setId($row->id);
                #Folha
                $folhaDAO = new FolhaDAO();
                $folhadocumento->setFolha($folhaDAO->buscarID($row->folha));

                #Documento
                $documentoDAO = new DocumentoDAO();
                $folhadocumento->setDocumento($documentoDAO->buscarID($row->documento));

                $results[] = $folhadocumento;
            }
        }

        return $results;
    }

}
