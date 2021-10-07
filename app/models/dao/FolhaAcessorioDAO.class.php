<?php

/**
 * Description of FolhaAcessorioDAO
 *
 * @author mrvipaji
 */
class FolhaAcessorioDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(FolhaAcessorio $folhaacessorio) {

        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO folha_acessorio (folha, acessorio) VALUES (:folha, :acessorio)'
            );

            $stmt->bindValue(':folha', $folhaacessorio->getFolha());
            $stmt->bindValue(':acessorio', $folhaacessorio->getAcessorio());
           
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

    public function actualizar(FolhaAcessorio $folhaacessorio) {

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE  folha_acessorio SET folha=:folha,acessorio=:acessorio, data=:data WHERE id =:id'
            );
            $stmt->bindValue(':folha', $folhaacessorio->getFolha());
            $stmt->bindValue(':acessorio', $folhaacessorio->getAcessorio());
            $stmt->bindValue(':id', $folhaacessorio->getId());

            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
                    if ($this->pdo->inTransaction()){
            $this->pdo->rollback();
        }
            throw $e;
        }
    }

    public function eliminar(FolhaAcessorio $folhaacessorio) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE FROM folha_acessorio WHERE id = :id'
            );
            $stmt->bindValue(':id', $folhaacessorio->getId());

            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
                    if ($this->pdo->inTransaction()){
            $this->pdo->rollback();
        }
            throw $e;
        }
    }
    public function listarAcessorios($folha) {
        $statement = $this->pdo->prepare('SELECT * FROM folha_acessorio WHERE folha=:folha ORDER BY id desc');
        $statement->bindValue(':folha', $folha);
        $statement->execute();

        return  $this->processResults($statement);
        
    }

    public function buscarID($id) {
        $statement = $this->pdo->prepare('SELECT * FROM folha_acessorio WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function listarTodos($orderby = 'folha') {
        $statement = $this->pdo->query('SELECT * FROM folha_acessorio ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $folhaacessorio = new FolhaAcessorio();
                
                $folhaacessorio->setId($row->id);
                #Folha
                $folhaDAO = new FolhaDAO();
                $folhaacessorio->setFolha($folhaDAO->buscarID($row->folha));

                #Acessorio
                $acessorioDAO = new AcessorioDAO();
                $folhaacessorio->setAcessorio($acessorioDAO->buscarID($row->acessorio));

                $results[] = $folhaacessorio;
            }
        }

        return $results;
    }

}
