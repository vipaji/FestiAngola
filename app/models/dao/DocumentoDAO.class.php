<?php
/**
 * Description of DocumentoDAO
 *
 * @author mrvipaji
 */
class DocumentoDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(Documento $documento) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO documento (tipo_doc) VALUES (:tipo_doc)'
            );

            $stmt->bindValue(':tipo_doc', $documento->getTipoDoc());
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

    public function actualizar(Documento $documento) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE documento SET tipo_doc = :tipo_doc WHERE id = :id'
            );
            $stmt->bindValue(':id', $documento->getId());
            $stmt->bindValue(':tipo_doc', $documento->getTipoDoc());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function eliminar(Documento $documento) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM documento WHERE id = :id'
            );
            $stmt->bindValue(':id', $documento->getId());
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
        $statement = $this->pdo->prepare('SELECT * FROM documento WHERE id = :id ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function listarTodos($orderby = 'tipo_doc') {
        $statement = $this->pdo->query('SELECT * FROM documento ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $documento = new Documento();

                $documento->setId($row->id);
                $documento->setTipoDoc($row->tipo_doc);

                $results[] = $documento;
            }
        }

        return $results;
    }

}
