<?php

/**
 * Description of FolhaPecaDAO
 *
 * @author mrvipaji
 */
class FolhaPecaDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(FolhaPeca $folhapeca) {

        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO folha_peca (folha, peca, quantidade, preco) VALUES (:folha, :peca, :quantidade, :preco)'
            );

            $stmt->bindValue(':folha', $folhapeca->getFolha());
            $stmt->bindValue(':peca', $folhapeca->getPeca());
            $stmt->bindValue(':quantidade', $folhapeca->getQuantidade());
            $stmt->bindValue(':preco', $folhapeca->getPreco());
           
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

    public function actualizar(FolhaPeca $folhapeca) {

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE  folha_peca SET folha=:folha,peca=:peca, quantidade=:quantidade, preco=:preco WHERE id =:id'
            );
            $stmt->bindValue(':folha', $folhapeca->getFolha());
            $stmt->bindValue(':peca', $folhapeca->getPeca());
            $stmt->bindValue(':quantidade', $folhapeca->getQuantidade());
            $stmt->bindValue(':preco', $folhapeca->getPreco());
            $stmt->bindValue(':id', $folhapeca->getId());

            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
                    if ($this->pdo->inTransaction()){
            $this->pdo->rollback();
        }
            throw $e;
        }
    }

    public function eliminar(FolhaPeca $folhapeca) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE FROM folha_peca WHERE id = :id'
            );
            $stmt->bindValue(':id', $folhapeca->getId());

            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
                    if ($this->pdo->inTransaction()){
            $this->pdo->rollback();
        }
            throw $e;
        }
    }

    public function listarPecas($folha) {
        $statement = $this->pdo->prepare('SELECT * FROM folha_peca WHERE folha=:folha ORDER BY id desc');
        $statement->bindValue(':folha', $folha);
        $statement->execute();

        return  $this->processResults($statement);
        
    }

    public function buscarID($id) {
        $statement = $this->pdo->prepare('SELECT * FROM folha_peca WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function listarTodos($orderby = 'folha') {
        $statement = $this->pdo->query('SELECT * FROM folha_peca ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $folhapeca = new FolhaPeca();
                
                $folhapeca->setId($row->id);

                #Folha
                $folhaDAO = new FolhaDAO();
                $folhapeca->setFolha($folhaDAO->buscarID($row->folha));

                $folhapeca->setPeca($row->peca);
                $folhapeca->setQuantidade($row->quantidade);
                $folhapeca->setPreco($row->preco);

                $results[] = $folhapeca;
            }
        }

        return $results;
    }

}
