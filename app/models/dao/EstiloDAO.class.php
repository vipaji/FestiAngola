<?php
/**
 * Description of EstiloDAO
 *
 * @author mrvipaji
 */
class EstiloDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(Estilo $estilo) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO estilo (nome, descricao) VALUES (:nome, :descricao)'
            );

            $stmt->bindValue(':nome', $estilo->getNome());
            $stmt->bindValue(':descricao', $estilo->getDescricao());
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

    public function actualizar(Estilo $estilo) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE estilo SET nome = :nome, descricao = :descricao WHERE id = :id'
            );
            $stmt->bindValue(':id', $estilo->getId());
            $stmt->bindValue(':nome', $estilo->getNome());
            $stmt->bindValue(':descricao', $estilo->getDescricao());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function eliminar(Estilo $estilo) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM estilo WHERE id = :id'
            );
            $stmt->bindValue(':id', $estilo->getId());
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
        $statement = $this->pdo->prepare('SELECT * FROM estilo WHERE id = :id ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function listarTodos($orderby = 'nome') {
        $statement = $this->pdo->query('SELECT * FROM estilo ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $estilo = new Estilo();

                $estilo->setId($row->id);
                $estilo->setNome($row->nome);
                $estilo->setDescricao($row->descricao);

                $results[] = $estilo;
            }
        }

        return $results;
    }

}
