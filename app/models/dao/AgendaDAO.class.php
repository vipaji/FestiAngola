<?php
/**
 * Description of AgendaDAO
 *
 * @author mrvipaji
 */
class AgendaDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(Agenda $agenda) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO agenda (data, hora, local, descricao) VALUES (:data, :hora, :local, :descricao)'
            );

            $stmt->bindValue(':data', $agenda->getData());
            $stmt->bindValue(':hora', $agenda->getHora());
            $stmt->bindValue(':local', $agenda->getLocal());
            $stmt->bindValue(':descricao', $agenda->getDescricao());
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

    public function actualizar(Agenda $agenda) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE agenda SET data = :data, hora = :hora, local = :local, descricao = :descricao WHERE id = :id'
            );
            $stmt->bindValue(':id', $agenda->getId());
            $stmt->bindValue(':data', $agenda->getData());
            $stmt->bindValue(':hora', $agenda->getHora());
            $stmt->bindValue(':local', $agenda->getLocal());
            $stmt->bindValue(':descricao', $agenda->getDescricao());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function eliminar(Agenda $agenda) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM agenda WHERE id = :id'
            );
            $stmt->bindValue(':id', $agenda->getId());
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
        $statement = $this->pdo->prepare('SELECT * FROM agenda WHERE id = :id ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function listarTodos($orderby = 'data') {
        $statement = $this->pdo->query('SELECT * FROM agenda ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    public function listarUltimasDatas() {
        $statement = $this->pdo->query('SELECT * FROM agenda ORDER BY data DESC limit 5');
        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $agenda = new Agenda();

                $agenda->setId($row->id);
                $agenda->setData($row->data);
                $agenda->setHora($row->hora);
                $agenda->setLocal($row->local);
                $agenda->setDescricao($row->descricao);

                $results[] = $agenda;
            }
        }

        return $results;
    }

}
