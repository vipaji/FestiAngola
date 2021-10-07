<?php
/**
 * Description of BlogDAO
 *
 * @author mrvipaji
 */
class BlogDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(Blog $blog) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO blog (titulo, texto, estado, data) VALUES (:titulo, :texto, :estado, :data)'
            );

            $stmt->bindValue(':titulo', $blog->getTitulo());
            $stmt->bindValue(':texto', $blog->getTexto());
            $stmt->bindValue(':estado', $blog->getEstado());
            $stmt->bindValue(':data', $blog->getData());
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

    public function actualizar(Blog $blog) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE blog SET titulo = :titulo, texto = :texto, estado = :estado WHERE id = :id'
            );
            $stmt->bindValue(':id', $blog->getId());
            $stmt->bindValue(':titulo', $blog->getTitulo());
            $stmt->bindValue(':texto', $blog->getTexto());
            $stmt->bindValue(':estado', $blog->getEstado());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function novafoto(Blog $blog) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE  blog SET foto =:foto WHERE id = :id'
            );
            $stmt->bindValue(':id', $blog->getId());
            $stmt->bindValue(':foto', $blog->getFoto());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function eliminar(Blog $blog) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM blog WHERE id = :id'
            );
            $stmt->bindValue(':id', $blog->getId());
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
        $statement = $this->pdo->prepare('SELECT * FROM blog WHERE id = :id ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function buscarIDLer($id) {
        $statement = $this->pdo->prepare('SELECT * FROM blog WHERE id = :id and estado = 1');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function listarTodas($orderby = 'titulo') {
        $statement = $this->pdo->query('SELECT * FROM blog ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    public function listarUltimas() {
        $statement = $this->pdo->query('SELECT * FROM blog ORDER BY id DESC limit 5');
        return $this->processResults($statement);
    }

    public function listarUltimasPublicadas() {
        $statement = $this->pdo->query('SELECT * FROM blog Where estado = 1 ORDER BY id DESC limit 3');
        return $this->processResults($statement);
    }

    public function listarOutrasPublicadas($id) {
        $statement = $this->pdo->query('SELECT * FROM blog Where estado = 1 and id <> '.$id.' ORDER BY id DESC limit 3');
        return $this->processResults($statement);
    }
    
    public function listarPublicadas($id) {
        $statement = $this->pdo->query('SELECT * FROM blog Where estado = 1 ORDER BY id DESC limit 3');
        return $this->processResults($statement);
    }


    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $blog = new Blog();

                $blog->setId($row->id);
                $blog->setTitulo($row->titulo);
                $blog->setTexto($row->texto);
                $blog->setFoto($row->foto);
                $blog->setEstado($row->estado);
                $blog->setData($row->data);

                $results[] = $blog;
            }
        }

        return $results;
    }

}
