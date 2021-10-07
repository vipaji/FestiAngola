<?php
/**
 * Description of EstiloController
 *
 * @author Administrator
 */
class EstiloController extends Controller {

    public function init() {
        $this->auth = new AuthHelper();
        $this->auth->setLoginControllerAction('Login', 'index')->checkLogin('redirect');

        $this->session = new SessionHelper();
        $this->dados['userInfo'] = $this->session->selectSession('userData');

        $this->dados['title_page'] = "Estilo Musical | Festival Nacional da Canção Patriótica &#8482;";
        $this->dados['page_context'] = "Estilo Musical";
        $this->dados['page_icon'] = "fas fa-cog";
        $this->dados['page_url'] = '/Estilo/';
        $this->dados['home_url'] = '/Dashboard/';
    }

    public function indexAction() {
        try {
            $estiloDAO = new EstiloDAO();
            $this->dados['entities'] = $estiloDAO->listarTodos();
            $this->view('estilo/index', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function novoAction() {
        try {
            $this->dados['page_context'] = "Adicionar estilos";
            $this->view('estilo/novo', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function registarAction() {
        try {
            $estilo = new Estilo();

            $estilo->setNome(filter_input(INPUT_POST, 'nome'));
            $estilo->setDescricao(filter_input(INPUT_POST, 'descricao'));

            $estiloDAO = new EstiloDAO();
            $estiloDAO->salvar($estilo);
            $redirector = new RedirectorHelper();
            $redirector->goToControllerAction('Estilo', 'index');
            unset($redirector);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function verAction() {
        try {
            $estiloDAO = new EstiloDAO();
            $retorno = $estiloDAO->buscarID($this->getParams('id'));
            $this->dados['entity'] = ($retorno != null ? $retorno : null);
            if ($this->dados['entity'] == null) {
                throw new \Exception("Entidade não encontrada.");
            }
            //$this->dados['empresas'] = (new EmpresaDAO())->listarTodas();
            $this->view('estilo/ver', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function editarAction() {
        try {
            $estiloDAO = new EstiloDAO();
            $retorno = $estiloDAO->buscarID($this->getParams('id'));
            $this->dados['entity'] = ($retorno != null ? $retorno : null);
            if ($this->dados['entity'] == null) {
                throw new \Exception("Entidade não encontrada.");
            }

            $this->view('estilo/editar', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function actualizarAction() {
        try {
            $estiloDAO = new EstiloDAO();
            $retorno = $estiloDAO->buscarID(filter_input(INPUT_POST, 'id_estilo'));

            $estilo = $retorno;

            $estilo->setNome(filter_input(INPUT_POST, 'nome'));
            $estilo->setDescricao(filter_input(INPUT_POST, 'descricao'));

            $estiloDAO->actualizar($estilo);
            $redirector = new RedirectorHelper();
            $redirector->setUrlParameter('id', $estilo->getId());
            $redirector->goToControllerAction('Estilo', 'ver');

            unset($redirector);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function eliminarAction() {
        try {
            $estiloDAO = new EstiloDAO();

            $retorno = $estiloDAO->buscarID($this->getParams('id'));
            if (!$retorno) {
                echo 'Não encontrou';
            }
            $estiloDAO->eliminar($retorno);
            $redirector = new RedirectorHelper();
            $redirector->goToControllerAction('Estilo', 'index');
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
            $this->dados['entities'] = (new EstiloDAO())->listarTodos();
            $this->view('estilo/RelatorioGeralPDF', $this->dados, ".php");
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

}
