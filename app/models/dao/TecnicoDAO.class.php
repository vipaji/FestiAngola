<?php
/**
 * Description of TecnicoDAO
 *
 * @author mrvipaji
 */
class TecnicoDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(Tecnico $tecnico) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO tecnico (nome, funcao) VALUES (:nome, :funcao)'
            );

            $stmt->bindValue(':nome', $tecnico->getNome());
            $stmt->bindValue(':funcao', $tecnico->getFuncao());
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

    public function actualizar(Tecnico $tecnico) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE tecnico SET nome = :nome, funcao = :funcao WHERE id = :id'
            );
            $stmt->bindValue(':id', $tecnico->getId());
            $stmt->bindValue(':nome', $tecnico->getNome());
            $stmt->bindValue(':funcao', $tecnico->getFuncao());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function eliminar(Tecnico $tecnico) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM tecnico WHERE id = :id'
            );
            $stmt->bindValue(':id', $tecnico->getId());
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
        $statement = $this->pdo->prepare('SELECT * FROM tecnico WHERE id = :id ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function listarTodos($orderby = 'nome') {
        $statement = $this->pdo->query('SELECT * FROM tecnico ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $tecnico = new Tecnico();

                $tecnico->setId($row->id);
                $tecnico->setNome($row->nome);
                $tecnico->setFuncao($row->funcao);

                $results[] = $tecnico;
            }
        }

        return $results;
    }

}
