<?php

/**
 * 
 *
 * @version 1.105
 * @package entity
 */
class FuncaoDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(Funcao $funcao) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO funcao (nome, descricao) VALUES (:nome,  :descricao)'
            );

            $stmt->bindValue(':nome', $funcao->getNome());
            $stmt->bindValue(':descricao', $funcao->getDescricao());
            $stmt->execute();
            $lastId = $this->pdo->lastInsertId();

            //#-Inserção das Permissões 
            if (!empty($funcao->getPermissoes())) {
                foreach ($funcao->getPermissoes() as $permissao) {
                    $stmt = $this->pdo->prepare(
                            'INSERT INTO funcao_permissao (funcao, permissao) VALUES (:funcao, :permissao)'
                    );
                    $stmt->bindValue(':funcao', $lastId);
                    $stmt->bindValue(':permissao', $permissao->getId());
                    $stmt->execute();
                }
            }

            $this->pdo->commit();
            return $this->buscarID($lastId);
        } catch (Exception $e) {
                    if ($this->pdo->inTransaction()){
            $this->pdo->rollback();
        }
            throw $e;
        }
    }

    public function actualizar(Funcao $funcao) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE  funcao SET nome = :nome,   descricao =:descricao WHERE id = :id'
            );
            $stmt->bindValue(':id', $funcao->getId());
            $stmt->bindValue(':nome', $funcao->getNome());
            $stmt->bindValue(':descricao', $funcao->getDescricao());
            $stmt->execute();

            //Limpa
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM funcao_permissao WHERE  funcao = :funcao'
            );
            $stmt->bindValue(':funcao', $funcao->getId());

            $stmt->execute();

            //#-Inserção das Permissões 
            if (!empty($funcao->getPermissoes())) {
                foreach ($funcao->getPermissoes() as $permissao) {
                    $stmt = $this->pdo->prepare(
                            'INSERT INTO funcao_permissao (funcao, permissao) VALUES (:funcao, :permissao)'
                    );
                    $stmt->bindValue(':funcao', $funcao->getId());
                    $stmt->bindValue(':permissao', $permissao->getId());
                    $stmt->execute();
                }
            }


            $this->pdo->commit();
        } catch (Exception $e) {
                    if ($this->pdo->inTransaction()){
            $this->pdo->rollback();
        }
            throw $e;
        }
    }

    public function eliminar(Funcao $funcao) {
        $this->pdo->beginTransaction();
        try {

            //Limpa
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM funcao_permissao WHERE  funcao = :funcao'
            );
            $stmt->bindValue(':funcao', $funcao->getId());
            $stmt->execute();

            $stmt = $this->pdo->prepare(
                    'DELETE  FROM funcao WHERE  id = :id'
            );
            
            $stmt->bindValue(':id', $funcao->getId());
            $stmt->execute();


            $this->pdo->commit();
        } catch (Exception $e) {
                    if ($this->pdo->inTransaction()){
            $this->pdo->rollback();
        }
            throw $e;
        }
    }

    public function buscarID($id) {
        $statement = $this->pdo->prepare('SELECT * FROM funcao WHERE id = :id ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function buscarFuncao($funcao = "CLIENTE") {
        $statement = $this->pdo->prepare("SELECT * FROM funcao WHERE nome = :nome ");
        $statement->bindValue(':nome', $funcao);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function listarTodas($orderby = 'nome') {
        $statement = $this->pdo->query('SELECT * FROM funcao ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();


        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $permissoes = array();
                $funcao = new Funcao();

                $funcao->setId($row->id);
                $funcao->setNome($row->nome);
                $funcao->setDescricao($row->descricao);

                //pesquisa de permissões 
                $st = $this->pdo->prepare("SELECT permissao FROM funcao_permissao WHERE funcao = :funcao ");
                $st->bindValue(':funcao', $funcao->getId());
                $st->execute();
                while ($r = $st->fetch(PDO::FETCH_OBJ)) {
                    $permissao = (new PermissaoDAO())->buscarID($r->permissao);
                    if ($permissao != null) {
                        $permissoes[] = $permissao;
                    }
                }
                $funcao->setPermissoes($permissoes);
                $results[] = $funcao;
            }
        }

        return $results;
    }

}
