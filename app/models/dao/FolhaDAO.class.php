<?php
/**
 * Description of FolhaDAO
 *
 * @author mrvipaji
 */
class FolhaDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(Folha $folha) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO folha_obra (numero, veiculo, area_servico, km, avaria, reparacao, data_entrada, data_saida) VALUES (:numero, :veiculo, :area_servico, :km, :avaria, :reparacao, :data_entrada, :data_saida)'
            );

            $stmt->bindValue(':numero', $folha->getNumero());
            $stmt->bindValue(':veiculo', $folha->getVeiculo());
            $stmt->bindValue(':area_servico', $folha->getAreaServico());
            $stmt->bindValue(':km', $folha->getKm());
            $stmt->bindValue(':avaria', $folha->getAvaria());
            $stmt->bindValue(':reparacao', $folha->getReparacao());
            $stmt->bindValue(':data_entrada', $folha->getDataEntrada());
            $stmt->bindValue(':data_saida', $folha->getDataSaida());
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

    public function addacessorio(Folha $folha) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO folha_acessorio (folha, acessorio) VALUES (:folha, :acessorio)'
            );

            $stmt->bindValue(':folha', $folha->getId());
            $stmt->bindValue(':acessorio', $folha->getAcessorio());
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

    public function adddocumento(Folha $folha) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO folha_documento (folha, documento) VALUES (:folha, :documento)'
            );

            $stmt->bindValue(':folha', $folha->getId());
            $stmt->bindValue(':documento', $folha->getDocumento());
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

    public function addtecnico(Folha $folha) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO folha_tecnico (folha, tecnico) VALUES (:folha, :tecnico)'
            );

            $stmt->bindValue(':folha', $folha->getId());
            $stmt->bindValue(':tecnico', $folha->getTecnico());
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

    public function actualizar(Folha $folha) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE folha_obra SET numero=:numero, veiculo=:veiculo, area_servico=:area_servico, km=:km, avaria=:avaria, reparacao=:reparacao, data_entrada=:data_entrada, data_saida=:data_saida, acessorio=:acessorio WHERE id = :id'
            );
            $stmt->bindValue(':id', $folha->getId());
            $stmt->bindValue(':numero', $folha->getNumero());
            $stmt->bindValue(':veiculo', $folha->getVeiculo());
            $stmt->bindValue(':area_servico', $folha->getAreaServico());
            $stmt->bindValue(':km', $folha->getKm());
            $stmt->bindValue(':avaria', $folha->getAvaria());
            $stmt->bindValue(':reparacao', $folha->getReparacao());
            $stmt->bindValue(':data_entrada', $folha->getDataEntrada());
            $stmt->bindValue(':data_saida', $folha->getDataSaida());
            $stmt->bindValue(':acessorio', $folha->getAcessorio());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function eliminar(Folha $folha) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM folha_obra WHERE id = :id'
            );
            $stmt->bindValue(':id', $folha->getId());
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
        $statement = $this->pdo->prepare('SELECT * FROM folha_obra WHERE id = :id ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    /*public function findByArea($area) {
        $statement = $this->pdo->query("SELECT * FROM folha_obra WHERE area = $area ORDER BY id ASC ");
        return $this->processResults($statement);
    }*/

    public function listarTodas($orderby = 'veiculo') {
        $statement = $this->pdo->query('SELECT * FROM folha_obra ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    public function listarAcessorios($folha) {
        $statement = $this->pdo->query("SELECT * FROM folha_acessorio WHERE folha = $folha");

        return $this->processResults($statement);
    }

    public function UltimasFolhas($orderby = 'veiculo') {
        $statement = $this->pdo->query('SELECT * FROM folha_obra ORDER BY ' . $orderby . ' limit 5');

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $folha = new Folha();

                $folha->setId($row->id);
                $folha->setNumero($row->numero);
                #Veiculo
                $folha->setVeiculo((new VeiculoDAO())->buscarID($row->veiculo));
                $folha->setAreaServico($row->area_servico);
                #Acessorio
                $folha->setAcessorio((new AcessorioDAO())->buscarID($row->acessorio));
                #Documento
                $folha->setDocumento((new DocumentoDAO())->buscarID($row->documento));
                $folha->setKm($row->km);
                $folha->setAvaria($row->avaria);
                $folha->setreparacao($row->reparacao);
                $folha->setDataEntrada($row->data_entrada);
                $folha->setDataSaida($row->data_saida);

                $results[] = $folha;
            }
        }

        return $results;
    }

}
