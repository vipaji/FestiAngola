<?php
/**
 * Description of ProvinciaDAO
 *
 * @author mrvipaji
 */
class ProvinciaDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(Provincia $provincia) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO provincia (nome) VALUES (:nome)'
            );

            $stmt->bindValue(':nome', $provincia->getNome());
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

    public function actualizar(Provincia $provincia) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE provincia SET nome = :nome WHERE id = :id'
            );
            $stmt->bindValue(':id', $provincia->getId());
            $stmt->bindValue(':nome', $provincia->getNome());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function eliminar(Provincia $provincia) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM provincia WHERE id = :id'
            );
            $stmt->bindValue(':id', $provincia->getId());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function buscarID($id) {
        $statement = $this->pdo->prepare('SELECT * FROM provincia WHERE id = :id ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function listarTodas($orderby = 'nome') {
        $statement = $this->pdo->query('SELECT * FROM provincia ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $acessorio = new Provincia();

                $acessorio->setId($row->id);
                $acessorio->setNome($row->nome);

                $results[] = $acessorio;
            }
        }

        return $results;
    }

}
