<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EscolaDAO
 *
 * @author mrvipaji
 */
class EscolaDAO {

    private $pdo;

    public function __construct() {
        $this->pdo = Model::conectar();
    }

    public function salvar(Escola $escola) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'INSERT INTO escola (nome, descricao) VALUES (:nome,  :descricao)'
            );

            $stmt->bindValue(':nome', $escola->getNome());
            $stmt->bindValue(':descricao', $escola->getDescricao());
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

    public function actualizar(Escola $escola) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'UPDATE escola SET nome = :nome,  descricao = :descricao WHERE id = :id'
            );
            $stmt->bindValue(':id', $escola->getId());
            $stmt->bindValue(':nome', $escola->getNome());
            $stmt->bindValue(':descricao', $escola->getDescricao());
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollback();
            }
            throw $e;
        }
    }

    public function eliminar(Escola $escola) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                    'DELETE  FROM escola WHERE id = :id'
            );
            $stmt->bindValue(':id', $escola->getId());
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
        $statement = $this->pdo->prepare('SELECT * FROM escola WHERE id = :id ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $retorno = $this->processResults($statement);
        return (!empty($retorno) ? $retorno[0] : null);
    }

    /*public function findByArea($area) {
        $statement = $this->pdo->query("SELECT * FROM escola WHERE area = $area ORDER BY id ASC ");
        return $this->processResults($statement);
    }*/

    public function listarTodas($orderby = 'nome') {
        $statement = $this->pdo->query('SELECT * FROM escola ORDER BY ' . $orderby . '');

        return $this->processResults($statement);
    }

    public function UltimasEscolas($orderby = 'nome') {
        $statement = $this->pdo->query('SELECT * FROM escola ORDER BY ' . $orderby . ' limit 5');

        return $this->processResults($statement);
    }

    private function processResults($statement) {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
                $escola = new Escola();

                $escola->setId($row->id);
                $escola->setNome($row->nome);
                $escola->setDescricao($row->descricao);

                $results[] = $escola;
            }
        }

        return $results;
    }

}
