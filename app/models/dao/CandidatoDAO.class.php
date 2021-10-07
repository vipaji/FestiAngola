<?php
/**
 * Description of CandidatoDAO
 *
 * @author mrvipaji
 */
class CandidatoDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(Candidato $candidato) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO candidato (numero, nome, bi, email, genero, nascimento, provincia, telefone, estilo, link, data) VALUES (:numero, :nome, :bi, :email, :genero, :nascimento, :provincia, :telefone, :estilo, :link, :data)'
            );

            $stmt->bindValue(':numero', $candidato->getNumero());
            $stmt->bindValue(':nome', $candidato->getNome());
            $stmt->bindValue(':bi', $candidato->getBi());
            $stmt->bindValue(':email', $candidato->getEmail());
            $stmt->bindValue(':genero', $candidato->getGenero());
            $stmt->bindValue(':nascimento', $candidato->getNascimento());
            $stmt->bindValue(':provincia', $candidato->getProvincia());
            $stmt->bindValue(':telefone', $candidato->getTelefone());
            $stmt->bindValue(':estilo', $candidato->getEstilo());
            $stmt->bindValue(':link', $candidato->getLink());
            $stmt->bindValue(':data', $candidato->getData());
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

    public function actualizar(Candidato $candidato) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE candidato SET nome = :nome, bi = :bi, email = :email, genero = :genero, nascimento = :nascimento, provincia = :provincia, telefone = :telefone, estilo = :estilo, link = :link WHERE id = :id'
            );
            $stmt->bindValue(':id', $candidato->getId());
            $stmt->bindValue(':nome', $candidato->getNome());
            $stmt->bindValue(':bi', $candidato->getBi());
            $stmt->bindValue(':email', $candidato->getEmail());
            $stmt->bindValue(':genero', $candidato->getGenero());
            $stmt->bindValue(':nascimento', $candidato->getNascimento());
            $stmt->bindValue(':provincia', $candidato->getProvincia());
            $stmt->bindValue(':telefone', $candidato->getTelefone());
            $stmt->bindValue(':estilo', $candidato->getEstilo());
            $stmt->bindValue(':link', $candidato->getLink());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function eliminar(Candidato $candidato) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM candidato WHERE id = :id'
            );
            $stmt->bindValue(':id', $candidato->getId());
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
        $statement = $this->pdo->prepare('SELECT * FROM candidato WHERE id = :id ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }
    
    public function buscarNumero($numero) {
        $statement = $this->pdo->prepare('SELECT * FROM candidato WHERE numero = :numero ');
        $statement->bindValue(':numero', $numero);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function findByBi($bi) {
        $statement = $this->pdo->query("SELECT * FROM candidato WHERE bi = '" . $bi . "'");

        return $this->processResults($statement);
    }

    public function listarTodos($orderby = 'nome') {
        $statement = $this->pdo->query('SELECT * FROM candidato ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }
    
    public function listarApurados($orderby = 'nome') {
        $statement = $this->pdo->query('SELECT * FROM candidato where estado = 1 ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    public function listarUltimos() {
        $statement = $this->pdo->query('SELECT * FROM candidato ORDER BY id DESC limit 5');
        return $this->processResults($statement);
    }

    public function ultimoCandidato() {
        $statement = $this->pdo->query('SELECT * FROM candidato ORDER BY id DESC limit 1');
        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $candidato = new Candidato();

                $candidato->setId($row->id);
                $candidato->setNumero($row->numero);
                $candidato->setNome($row->nome);
                $candidato->setBi($row->bi);
                $candidato->setEmail($row->email);
                $candidato->setGenero($row->genero);
                $candidato->setNascimento($row->nascimento);
                #Provincia
                $candidato->setProvincia((new ProvinciaDAO())->buscarID($row->provincia));
                $candidato->setTelefone($row->telefone);
                #Estilo
                $candidato->setEstilo((new EstiloDAO())->buscarID($row->estilo));
                $candidato->setLink($row->link);
                $candidato->setEstado($row->estado);
                $candidato->setData($row->data);

                $results[] = $candidato;
            }
        }

        return $results;
    }

}
