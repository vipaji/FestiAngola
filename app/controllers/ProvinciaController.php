<?php
/**
 * Description of ProvinciaController
 *
 * @author Administrator
 */
class ProvinciaController extends Controller {

    public function init() {
        $this->auth = new AuthHelper();
        $this->auth->setLoginControllerAction('Login', 'index')->checkLogin('redirect');

        $this->session = new SessionHelper();
        $this->dados['userInfo'] = $this->session->selectSession('userData');

        $this->dados['title_page'] = "Província &bull; Festival Nacional da Canção Patriótica &#8482;";
        $this->dados['page_context'] = "Províncias";
        $this->dados['page_icon'] = "fas fa-map-maker";
        $this->dados['page_url'] = '/Provincia/';
        $this->dados['home_url'] = '/Dashboard/';
    }

    public function indexAction() {
        try {
            $ProvinciaDAO = new ProvinciaDAO();
            $this->dados['entities'] = $ProvinciaDAO->listarTodas();
            $this->view('Provincia/index', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function novaAction() {
        try {
            $this->dados['page_context'] = "Adicionar Provincias";
            $this->view('Provincia/nova', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function registarAction() {
        try {
            $Provincia = new Provincia();

            $Provincia->setNome(filter_input(INPUT_POST, 'nome'));
            //$Provincia->setDescricao(filter_input(INPUT_POST, 'descricao'));

            $ProvinciaDAO = new ProvinciaDAO();
            $ProvinciaDAO->salvar($Provincia);
            $redirector = new RedirectorHelper();
            $redirector->goToControllerAction('Provincia', 'index');
            unset($redirector);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function verAction() {
        try {
            $ProvinciaDAO = new ProvinciaDAO();
            $retorno = $ProvinciaDAO->buscarID($this->getParams('id'));
            $this->dados['entity'] = ($retorno != null ? $retorno : null);
            if ($this->dados['entity'] == null) {
                throw new \Exception("Entidade não encontrada.");
            }
            $this->view('Provincia/ver', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function editarAction() {
        try {
            $ProvinciaDAO = new ProvinciaDAO();
            $retorno = $ProvinciaDAO->buscarID($this->getParams('id'));
            $this->dados['entity'] = ($retorno != null ? $retorno : null);
            if ($this->dados['entity'] == null) {
                throw new \Exception("Entidade não encontrada.");
            }

            $this->view('Provincia/editar', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function actualizarAction() {
        try {
            $ProvinciaDAO = new ProvinciaDAO();
            $retorno = $ProvinciaDAO->buscarID(filter_input(INPUT_POST, 'id_Provincia'));

            $Provincia = $retorno;

            $Provincia->setNome(filter_input(INPUT_POST, 'nome'));
            //$Provincia->setDescricao(filter_input(INPUT_POST, 'descricao'));

            $ProvinciaDAO->actualizar($Provincia);
            $redirector = new RedirectorHelper();
            $redirector->setUrlParameter('id', $Provincia->getId());
            $redirector->goToControllerAction('Provincia', 'ver');

            unset($redirector);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function eliminarAction() {
        try {
            $ProvinciaDAO = new ProvinciaDAO();

            $retorno = $ProvinciaDAO->buscarID($this->getParams('id'));
            if (!$retorno) {
                echo 'Não encontrou';
            }
            $ProvinciaDAO->eliminar($retorno);
            $redirector = new RedirectorHelper();
            $redirector->goToControllerAction('Provincia', 'index');
            unset($redirector);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    /**
     * @Método: imprimirPdfAction
     * @Descrição: Processa a impressão (pdf) da informação da área 
     * 
     */
    public function imprimirPdfAction() {
        try {
            $this->dados['entities'] = (new ProvinciaDAO())->listarTodas();
            $this->view('Provincia/RelatorioGeralPDF', $this->dados, ".php");
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

}
