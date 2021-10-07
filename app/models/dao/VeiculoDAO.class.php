<?php
/**
 * Description of VeiculoDAO
 *
 * @author mrvipaji
 */
class VeiculoDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(Veiculo $veiculo) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO veiculo (marca, modelo, matricula, num_motor, cor, combustivel, data_registo) VALUES (:marca, :modelo, :matricula, :num_motor, :cor, :combustivel, :data_registo)'
            );

            $stmt->bindValue(':marca', $veiculo->getMarca());
            $stmt->bindValue(':modelo', $veiculo->getModelo());
            $stmt->bindValue(':matricula', $veiculo->getMatricula());
            $stmt->bindValue(':num_motor', $veiculo->getNumMotor());
            $stmt->bindValue(':cor', $veiculo->getCor());
            $stmt->bindValue(':combustivel', $veiculo->getCombustivel());
            $stmt->bindValue(':data_registo', $veiculo->getDataRegisto());
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

    public function actualizar(Veiculo $veiculo) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE veiculo SET marca=:marca, modelo=:modelo, matricula=:matricula, num_motor=:num_motor, cor=:cor, combustivel=:combustivel, data_registo=:data_registo WHERE id = :id'
            );
            $stmt->bindValue(':id', $veiculo->getId());
            $stmt->bindValue(':marca', $veiculo->getMarca());
            $stmt->bindValue(':modelo', $veiculo->getModelo());
            $stmt->bindValue(':matricula', $veiculo->getMatricula());
            $stmt->bindValue(':num_motor', $veiculo->getNumMotor());
            $stmt->bindValue(':cor', $veiculo->getCor());
            $stmt->bindValue(':combustivel', $veiculo->getCombustivel());
            $stmt->bindValue(':data_registo', $veiculo->getDataRegisto());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function eliminar(Veiculo $veiculo) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM veiculo WHERE id = :id'
            );
            $stmt->bindValue(':id', $veiculo->getId());
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
        $statement = $this->pdo->prepare('SELECT * FROM veiculo WHERE id = :id ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    /*public function findByArea($area) {
        $statement = $this->pdo->query("SELECT * FROM veiculo WHERE area = $area ORDER BY id ASC ");
        return $this->processResults($statement);
    }*/

    public function listarTodos($orderby = 'marca') {
        $statement = $this->pdo->query('SELECT * FROM veiculo ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    public function UltimosVeiculos($orderby = 'marca') {
        $statement = $this->pdo->query('SELECT * FROM veiculo ORDER BY ' . $orderby . ' limit 5');

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $veiculo = new Veiculo();
                $veiculo->setId($row->id);

                #Marca
                $veiculo->setMarca((new MarcaDAO())->buscarID($row->marca));
                $veiculo->setModelo($row->modelo);
                $veiculo->setMatricula($row->matricula);
                $veiculo->setNumMotor($row->num_motor);
                $veiculo->setCor($row->cor);
                $veiculo->setCombustivel($row->combustivel);
                $veiculo->setDataRegisto($row->data_registo);

                $results[] = $veiculo;
            }
        }

        return $results;
    }

}
