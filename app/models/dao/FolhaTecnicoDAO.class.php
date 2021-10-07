<?php

/**
 * Description of FolhaTecnicoDAO
 *
 * @author mrvipaji
 */
class FolhaTecnicoDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(FolhaTecnico $folhatecnico) {

        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO folha_tecnico (folha, tecnico) VALUES (:folha, :tecnico)'
            );

            $stmt->bindValue(':folha', $folhatecnico->getFolha());
            $stmt->bindValue(':tecnico', $folhatecnico->getTecnico());
           
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

    public function actualizar(FolhaTecnico $folhatecnico) {

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE  folha_tecnico SET folha=:folha,tecnico=:tecnico, data=:data WHERE id =:id'
            );
            $stmt->bindValue(':folha', $folhatecnico->getFolha());
            $stmt->bindValue(':tecnico', $folhatecnico->getTecnico());
            $stmt->bindValue(':id', $folhatecnico->getId());

            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
                    if ($this->pdo->inTransaction()){
            $this->pdo->rollback();
        }
            throw $e;
        }
    }

    public function eliminar(FolhaTecnico $folhatecnico) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE FROM folha_tecnico WHERE id = :id'
            );
            $stmt->bindValue(':id', $folhatecnico->getId());

            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
                    if ($this->pdo->inTransaction()){
            $this->pdo->rollback();
        }
            throw $e;
        }
    }

    public function addtecnico(FolhaTecnico $folhatecnico) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO folha_tecnico (folha, tecnico) VALUES (:folha, :tecnico)'
            );

            $stmt->bindValue(':folha', $folhatecnico->getFolha());
            $stmt->bindValue(':tecnico', $folhatecnico->getTecnico());
            $stmt->execute();
            $lastId = $this->pdo->lastInsertId();

            $this->pdo->commit();
            return $this->buscarID($lastId);
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function listarTecnicos($folha) {
        $statement = $this->pdo->prepare('SELECT * FROM folha_tecnico WHERE folha=:folha ORDER BY id desc');
        $statement->bindValue(':folha', $folha);
        $statement->execute();

        return  $this->processResults($statement);
        
    }

    public function buscarID($id) {
        $statement = $this->pdo->prepare('SELECT * FROM folha_tecnico WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function listarTodos($orderby = 'folha') {
        $statement = $this->pdo->query('SELECT * FROM folha_tecnico ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $folhatecnico = new FolhaTecnico();
                
                $folhatecnico->setId($row->id);
                #Folha
                $folhaDAO = new FolhaDAO();
                $folhatecnico->setFolha($folhaDAO->buscarID($row->folha));

                #Tecnico
                $tecnicoDAO = new TecnicoDAO();
                $folhatecnico->setTecnico($tecnicoDAO->buscarID($row->tecnico));

                $results[] = $folhatecnico;
            }
        }

        return $results;
    }

}
