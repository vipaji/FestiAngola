<?php
/**
 * Description of AgendaController
 *
 * @author Administrator
 */
class AgendaController extends Controller {

    public function init() {
        $this->auth = new AuthHelper();
        $this->auth->setLoginControllerAction('Login', 'index')->checkLogin('redirect');

        $this->session = new SessionHelper();
        $this->dados['userInfo'] = $this->session->selectSession('userData');

        $this->dados['title_page'] = "Agenda &bull; Festival Nacional da Canção &#8482;";
        $this->dados['page_context'] = "Agenda ";
        $this->dados['page_icon'] = "fas fa-cog";
        $this->dados['page_url'] = '/Agenda/';
        $this->dados['home_url'] = '//Dashboard/';
    }

    public function indexAction() {
        try {
            $agendaDAO = new AgendaDAO();
            $this->dados['entities'] = $agendaDAO->listarTodos();
            $this->view('agenda/index', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function novaAction() {
        try {
            $this->dados['page_context'] = "Adicionar agenda";
            $this->view('agenda/nova', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function registarAction() {
        try {
            $agenda = new Agenda();

            $agenda->setData(filter_input(INPUT_POST, 'data'));
            $agenda->setHora(filter_input(INPUT_POST, 'hora'));
            $agenda->setLocal(filter_input(INPUT_POST, 'local'));
            $agenda->setDescricao(filter_input(INPUT_POST, 'descricao'));

            $agendaDAO = new AgendaDAO();
            $agendaDAO->salvar($agenda);
            $redirector = new RedirectorHelper();
            $redirector->goToControllerAction('Agenda', 'index');
            unset($redirector);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function verAction() {
        try {
            $agendaDAO = new AgendaDAO();
            $retorno = $agendaDAO->buscarID($this->getParams('id'));
            $this->dados['entity'] = ($retorno != null ? $retorno : null);
            if ($this->dados['entity'] == null) {
                throw new \Exception("Entidade não encontrada.");
            }
            //$this->dados['empresas'] = (new EmpresaDAO())->listarTodas();
            $this->view('agenda/ver', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function editarAction() {
        try {
            $agendaDAO = new AgendaDAO();
            $retorno = $agendaDAO->buscarID($this->getParams('id'));
            $this->dados['entity'] = ($retorno != null ? $retorno : null);
            if ($this->dados['entity'] == null) {
                throw new \Exception("Entidade não encontrada.");
            }

            $this->view('agenda/editar', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function actualizarAction() {
        try {
            $agendaDAO = new AgendaDAO();
            $retorno = $agendaDAO->buscarID(filter_input(INPUT_POST, 'id_agenda'));

            $agenda = $retorno;

            $agenda->setData(filter_input(INPUT_POST, 'data'));
            $agenda->setHora(filter_input(INPUT_POST, 'hora'));
            $agenda->setLocal(filter_input(INPUT_POST, 'local'));
            $agenda->setDescricao(filter_input(INPUT_POST, 'descricao'));

            $agendaDAO->actualizar($agenda);
            $redirector = new RedirectorHelper();
            $redirector->setUrlParameter('id', $agenda->getId());
            $redirector->goToControllerAction('Agenda', 'ver');

            unset($redirector);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function eliminarAction() {
        try {
            $agendaDAO = new AgendaDAO();

            $retorno = $agendaDAO->buscarID($this->getParams('id'));
            if (!$retorno) {
                echo 'Não encontrou';
            }
            $agendaDAO->eliminar($retorno);
            $redirector = new RedirectorHelper();
            $redirector->goToControllerAction('Agenda', 'index');
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
            $this->dados['entities'] = (new AgendaDAO())->listarTodos();
            $this->view('agenda/RelatorioGeralPDF', $this->dados, ".php");
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

}
