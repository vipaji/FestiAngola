<?php
/**
 * Description of FerramentaDAO
 *
 * @author mrvipaji
 */
class FerramentaDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(Ferramenta $ferramenta) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO ferramenta (nome, quantidade) VALUES (:nome, :quantidade)'
            );

            $stmt->bindValue(':nome', $ferramenta->getNome());
            $stmt->bindValue(':quantidade', $ferramenta->getQuantidade());
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

    public function actualizar(Ferramenta $ferramenta) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE ferramenta SET nome = :nome, quantidade = :quantidade WHERE id = :id'
            );
            $stmt->bindValue(':id', $ferramenta->getId());
            $stmt->bindValue(':nome', $ferramenta->getNome());
            $stmt->bindValue(':quantidade', $ferramenta->getQuantidade());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function eliminar(Ferramenta $ferramenta) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM ferramenta WHERE id = :id'
            );
            $stmt->bindValue(':id', $ferramenta->getId());
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
        $statement = $this->pdo->prepare('SELECT * FROM ferramenta WHERE id = :id ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function listarTodas($orderby = 'nome') {
        $statement = $this->pdo->query('SELECT * FROM ferramenta ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $ferramenta = new Ferramenta();

                $ferramenta->setId($row->id);
                $ferramenta->setNome($row->nome);
                $ferramenta->setQuantidade($row->quantidade);

                $results[] = $ferramenta;
            }
        }

        return $results;
    }

}
