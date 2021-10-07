<?php

/**
 * Description of CandidatoController
 *
 * @author Administrator
 */
class CandidatoController extends Controller
{

    public function init()
    {
        $this->auth = new AuthHelper();
        $this->auth->setLoginControllerAction('Login', 'index')->checkLogin('redirect');

        $this->session = new SessionHelper();
        $this->dados['userInfo'] = $this->session->selectSession('userData');

        $this->dados['title_page'] = "Candidato | Festival Nacional da Canção Patriótica &#8482;";
        $this->dados['page_context'] = "Candidato";
        $this->dados['page_icon'] = "fas fa-cog";
        $this->dados['page_url'] = '/Candidato/';
        $this->dados['home_url'] = '/Dashboard/';
    }

    public function indexAction()
    {
        try {
            if (!$this->auth->HasPermission("listarCandidato")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }
            $candidatoDAO = new CandidatoDAO();
            $this->dados['entities'] = $candidatoDAO->listarTodos();
            $this->view('candidato/index', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function novoAction()
    {
        try {
            if (!$this->auth->HasPermission("novoCandidato")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }
            $this->dados['page_context'] = "Adicionar candidatos";
            $this->dados['provincias'] = (new ProvinciaDAO())->listarTodas();
            $this->dados['estilos'] = (new EstiloDAO())->listarTodos();
            $this->view('candidato/novo', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function registarAction()
    {
        try {

            // Verificar se a idade está entre 18 e 35 anos
            $nascimento = filter_input(INPUT_POST, 'nascimento');
            $data = new DateTime($nascimento);
            $idade = $data->diff(new DateTime(date('Y-m-d')));
            $idade_actual = $idade->format('%Y');

            if(($idade_actual>=18)and($idade_actual<=35))
            {
                //print_r($idade_actual." anos de idade. Vamos adicionar."); die;
                //throw new \Exception("Para ser candidato deve ter idade entre 18 a 25 anos de idade.");
            }
            else
            {
                //print_r($idade_actual." anos de idade. Vamos adicionar.."); die;
                throw new \Exception("Para ser <b>candidato</b> deve ter idade entre <b>18 a 25 anos</b> de idade.");
            }

            $candidatos = (new CandidatoDAO())->findByBi(filter_input(INPUT_POST, 'bi'));
            if (count($candidatos) > 0) {
                throw new \Exception("Já existe um candidato com este número de bilhete de identidade.");
            }

            $candidato = new Candidato();

            $ultimoCandidato = (new CandidatoDAO())->ultimoCandidato();
            $NumUltimoCandidato = $ultimoCandidato == null ? date("Y") . "." . "00000" : substr($ultimoCandidato->getNumero(), 5);
            $numero = $ultimoCandidato == null ? $NumUltimoCandidato : date("Y") . "." . Method::geraNumeroCandidato($NumUltimoCandidato);

            $candidato->setNumero($numero);
            $candidato->setNome(filter_input(INPUT_POST, 'nome'));
            $candidato->setBi(filter_input(INPUT_POST, 'bi'));
            $candidato->setEmail(filter_input(INPUT_POST, 'email'));
            $candidato->setGenero(filter_input(INPUT_POST, 'genero'));
            $candidato->setNascimento(filter_input(INPUT_POST, 'nascimento'));
            $candidato->setProvincia(filter_input(INPUT_POST, 'provincia'));
            $candidato->setTelefone(filter_input(INPUT_POST, 'telefone'));
            $candidato->setEstilo(filter_input(INPUT_POST, 'estilo'));
            $candidato->setLink(filter_input(INPUT_POST, 'link'));
            $candidato->setData(date('Y-m-d'));

            $candidatoDAO = new CandidatoDAO();
            $candidatoDAO->salvar($candidato);
            $redirector = new RedirectorHelper();
            $redirector->goToControllerAction('Candidato', 'index');
            unset($redirector);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function verAction()
    {
        try {
            if (!$this->auth->HasPermission("verCandidato")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }
            $candidatoDAO = new CandidatoDAO();
            $retorno = $candidatoDAO->buscarID($this->getParams('id'));
            $this->dados['provincias'] = (new ProvinciaDAO())->listarTodas();
            $this->dados['estilos'] = (new EstiloDAO())->listarTodos();
            $this->dados['entity'] = ($retorno != null ? $retorno : null);
            if ($this->dados['entity'] == null) {
                throw new \Exception("Entidade não encontrada.");
            }
            //$this->dados['empresas'] = (new EmpresaDAO())->listarTodas();
            $this->view('candidato/ver', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function editarAction()
    {
        try {
            if (!$this->auth->HasPermission("editarCandidato")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }
            $candidatoDAO = new CandidatoDAO();
            $retorno = $candidatoDAO->buscarID($this->getParams('id'));
            $this->dados['provincias'] = (new ProvinciaDAO())->listarTodas();
            $this->dados['estilos'] = (new EstiloDAO())->listarTodos();
            $this->dados['entity'] = ($retorno != null ? $retorno : null);
            if ($this->dados['entity'] == null) {
                throw new \Exception("Entidade não encontrada.");
            }

            $this->view('candidato/editar', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function actualizarAction()
    {
        try {
            if (!$this->auth->HasPermission("editarCandidato")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }
            // Verificar se a idade está entre 18 e 35 anos
            $nascimento = filter_input(INPUT_POST, 'nascimento');
            $data = new DateTime($nascimento);
            $idade = $data->diff(new DateTime(date('Y-m-d')));
            $idade_actual = $idade->format('%Y');

            if(($idade_actual>=18)and($idade_actual<=35))
            {
                //print_r($idade_actual." anos de idade. Vamos adicionar."); die;
                //throw new \Exception("Para ser candidato deve ter idade entre 18 a 25 anos de idade.");
            }
            else
            {
                //print_r($idade_actual." anos de idade. Vamos adicionar.."); die;
                throw new \Exception("Para ser <b>candidato</b> deve ter idade entre <b>18 a 25 anos</b> de idade.");
            }

            $candidatoDAO = new CandidatoDAO();
            $retorno = $candidatoDAO->buscarID(filter_input(INPUT_POST, 'id_candidato'));

            $candidato = $retorno;

            $candidato->setNome(filter_input(INPUT_POST, 'nome'));
            $candidato->setBi(filter_input(INPUT_POST, 'bi'));
            $candidato->setEmail(filter_input(INPUT_POST, 'email'));
            $candidato->setGenero(filter_input(INPUT_POST, 'genero'));
            $candidato->setNascimento(filter_input(INPUT_POST, 'nascimento'));
            $candidato->setProvincia(filter_input(INPUT_POST, 'provincia'));
            $candidato->setTelefone(filter_input(INPUT_POST, 'telefone'));
            $candidato->setEstilo(filter_input(INPUT_POST, 'estilo'));
            $candidato->setLink(filter_input(INPUT_POST, 'link'));

            $candidatoDAO->actualizar($candidato);
            $redirector = new RedirectorHelper();
            $redirector->setUrlParameter('id', $candidato->getId());
            $redirector->goToControllerAction('Candidato', 'ver');

            unset($redirector);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function eliminarAction()
    {
        try {
            if (!$this->auth->HasPermission("eliminarCandidato")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }
            $candidatoDAO = new CandidatoDAO();

            $retorno = $candidatoDAO->buscarID($this->getParams('id'));
            if (!$retorno) {
                echo 'Não encontrou';
            }
            $candidatoDAO->eliminar($retorno);
            $redirector = new RedirectorHelper();
            $redirector->goToControllerAction('Candidato', 'index');
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
    public function imprimirPdfAction()
    {
        try {
            $this->dados['entities'] = (new CandidatoDAO())->listarTodos();
            $this->view('candidato/RelatorioGeralPDF', $this->dados, ".php");
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }
}
