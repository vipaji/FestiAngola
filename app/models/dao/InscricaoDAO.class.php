<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InscricaoDAO
 *
 * @author mrvipaji
 */
class InscricaoDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(Inscricao $inscricao) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO inscricao (nome, email, telefone, escola, inscricao) VALUES (:nome, :email, :telefone, :escola, :inscricao)'
            );

            $stmt->bindValue(':nome', $inscricao->getNome());
            $stmt->bindValue(':email', $inscricao->getEmail());
            $stmt->bindValue(':telefone', $inscricao->getTelefone());
            $stmt->bindValue(':escola', $inscricao->getEscola());
            $stmt->bindValue(':inscricao', $inscricao->getDataInscricao());
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

    public function actualizar(Inscricao $inscricao) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE inscricao SET nome = :nome, email = :email, telefone = :telefone, escola = :escola WHERE id = :id'
            );
            $stmt->bindValue(':id', $inscricao->getId());
            $stmt->bindValue(':nome', $inscricao->getNome());
            $stmt->bindValue(':email', $inscricao->getEmail());
            $stmt->bindValue(':telefone', $inscricao->getTelefone());
            $stmt->bindValue(':escola', $inscricao->getEscola());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function eliminar(Inscricao $inscricao) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM inscricao WHERE id = :id'
            );
            $stmt->bindValue(':id', $inscricao->getId());
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
        $statement = $this->pdo->prepare('SELECT * FROM inscricao WHERE id = :id ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    public function findByEscola($escola) {
        $statement = $this->pdo->query("SELECT * FROM inscricao WHERE escola = $escola ORDER BY id ASC ");
        return $this->processResults($statement);
    }

    public function findByEmail($email) {
        $statement = $this->pdo->query("SELECT * FROM inscricao WHERE email = '" . $email . "'");

        return $this->processResults($statement);
    }

    public function listarTodas($orderby  = 'nome') {
        $statement = $this->pdo->query('SELECT * FROM inscricao ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    public function UltimasInscricoes($orderby  = 'nome') {
        $statement = $this->pdo->query('SELECT * FROM inscricao ORDER BY ' . $orderby . ' limit 7');

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $inscricao = new Inscricao();

                $inscricao->setId($row->id);
                $inscricao->setNome($row->nome);
                $inscricao->setEmail($row->email);
                $inscricao->setTelefone($row->telefone);
                $inscricao->setDataInscricao($row->inscricao);

                #Escola
                $inscricao->setEscola((new EscolaDAO())->buscarID($row->escola));

                $results[] = $inscricao;
            }
        }

        return $results;
    }

}
