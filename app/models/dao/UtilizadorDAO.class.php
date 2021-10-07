<?php
/**
 * 
 *
 * @version 1.105
 * @package entity
 */
class UtilizadorDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(Utilizador $utilizador) {

        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO utilizador (nome, email, password, perfil, tentativas, estado, foto, telefone) VALUES (:nome, :email, :password, :perfil, :tentativas, :estado, :foto, :telefone)'
            );

            $stmt->bindValue(':nome', $utilizador->getNome());
            $stmt->bindValue(':email', $utilizador->getEmail());
            $stmt->bindValue(':password', $utilizador->getPassword());
            $stmt->bindValue(':tentativas', $utilizador->getTentativas());
            $stmt->bindValue(':estado', $utilizador->getEstado());
            $stmt->bindValue(':perfil', $utilizador->getPerfil());
            $stmt->bindValue(':foto', $utilizador->getFoto());
            $stmt->bindValue(':telefone', $utilizador->getTelefone());
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

    public function actualizar(Utilizador $utilizador) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE  utilizador SET nome = :nome,  email = :email, password = :password,  perfil = :perfil, estado = :estado,  tentativas = :tentativas,  foto = :foto, telefone = :telefone WHERE id = :id'
            );
            $stmt->bindValue(':id', $utilizador->getId());
            $stmt->bindValue(':nome', $utilizador->getNome());
            $stmt->bindValue(':email', $utilizador->getEmail());
            $stmt->bindValue(':password', $utilizador->getPassword());
            $stmt->bindValue(':perfil', $utilizador->getPerfil());
            $stmt->bindValue(':estado', $utilizador->getEstado());
            $stmt->bindValue(':tentativas', $utilizador->getTentativas());
            $stmt->bindValue(':foto', $utilizador->getFoto());
            $stmt->bindValue(':telefone', $utilizador->getTelefone());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function password(Utilizador $utilizador) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE  utilizador SET password =:password WHERE id = :id'
            );
            $stmt->bindValue(':id', $utilizador->getId());
            $stmt->bindValue(':password', $utilizador->getPassword());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function novafoto(Utilizador $utilizador) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE  utilizador SET foto =:foto WHERE id = :id'
            );
            $stmt->bindValue(':id', $utilizador->getId());
            $stmt->bindValue(':foto', $utilizador->getFoto());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function eliminar(Utilizador $utilizador) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM utilizador WHERE  id = :id'
            );
            $stmt->bindValue(':id', $utilizador->getId());
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
        $statement = $this->pdo->prepare('SELECT * FROM utilizador WHERE id = :id ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function listarTodos($orderby = 'nome') {
        $statement = $this->pdo->query('SELECT * FROM utilizador ORDER BY ' . $orderby . ' ASC');

        return $this->processResults($statement);
    }

    public function listarInscricoes($orderby = 'nome') {
        $statement = $this->pdo->query('SELECT utilizador.* FROM utilizador, perfil WHERE perfil.nome LIKE \'ALUNO\' AND perfil.id = utilizador.perfil ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    public function UltimasInscricoes($orderby = 'nome') {
        $statement = $this->pdo->query('SELECT utilizador.* FROM utilizador, perfil WHERE perfil.nome LIKE \'ALUNO\' AND perfil.id = utilizador.perfil ORDER BY ' . $orderby . ' Limit 7');

        return $this->processResults($statement);
    }

    public function findByEmail($email) {
        $statement = $this->pdo->query("SELECT * FROM utilizador WHERE email = '" . $email . "'");

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $utilizador = new Utilizador();

                $utilizador->setId($row->id);
                $utilizador->setNome($row->nome);
                $utilizador->setEmail($row->email);
                $utilizador->setEstado($row->estado);
                $utilizador->setPassword($row->password);
                $utilizador->setFoto($row->foto);
                $utilizador->setTentativas($row->tentativas);
                $utilizador->setTelefone($row->telefone);

                #Perfil
                $utilizador->setPerfil((new PerfilDAO())->buscarID($row->perfil));

                $results[] = $utilizador;
            }
        }

        return $results;
    }

    public function autentica(Utilizador $utilizador) {

        $statement = $this->pdo->prepare('SELECT * FROM utilizador WHERE email = :email AND password = :password LIMIT 1');
        $statement->bindValue(':email', $utilizador->getEmail());
        $statement->bindValue(':password', $utilizador->getPassword());
        $statement->execute();
        $retorno = $this->processResults($statement);
        if (!$retorno) {
            return null;
        } else {
            if ($retorno[0]->getEstado() == Geral::CONS_UTILIZADOR_DESACTIVADO) {
                return null;
            }
            return $retorno[0];
        }
    }

}
