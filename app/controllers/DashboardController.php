<?php
/**
 * Description of DashboardController
 *
 * @author mrvipaji
 */
class DashboardController extends Controller {

    public function init() {
        $this->auth = new AuthHelper();
        $this->auth->setLoginControllerAction('Login', 'index')->checkLogin('redirect');

        $this->session = new SessionHelper();
        $this->dados['userInfo'] = $this->session->selectSession('userData');

        $this->dados['title_page'] = "Dashboard | Festival Nacional da Canção Patriótica &#8482;";
        $this->dados['page_context'] = "Dashboard";
        $this->dados['page_icon'] = "fa fa-dashboard";
        $this->dados['page_url'] = '/Dashboard/';
        $this->dados['home_url'] = '/Dashboard/';
    }

    public function indexAction() {
        try {
            $this->dados['estilos'] = count((new EstiloDAO())->listarTodos());
            $this->dados['candidatos'] = count((new CandidatoDAO())->listarTodos());
            $this->dados['Ultimoscandidatos'] = (new CandidatoDAO())->listarUltimos();
            $this->dados['Ultimasblog'] = (new BlogDAO())->listarUltimas();
            $this->view('dashboard/index', $this->dados);
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
            $this->dados['entities'] = (new ServicoDAO())->listarTodos();
            $this->view('servico/RelatorioGeralPDF', $this->dados, ".php");
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/erro', $this->dados);
        }
    }
}