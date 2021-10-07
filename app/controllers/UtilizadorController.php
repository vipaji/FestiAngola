<?php

Class UtilizadorController extends Controller {

    public function init() {
        $this->auth = new AuthHelper();
        $this->auth->setLoginControllerAction('Login', 'index')->checkLogin('redirect');

        $this->session = new SessionHelper();
        $this->dados['userInfo'] = $this->session->selectSession('userData');

        $this->dados['title_page'] = "Utilizadores &bull; Festival Nacional da Canção Patriótica &#8482;";
        $this->dados['page_context'] = "Utilizadores";
        $this->dados['page_icon'] = "fa fa-users";
        $this->dados['page_url'] = '/Utilizador/';
        $this->dados['page_home'] = '/Dashboard/';
        $this->dados['home_url'] = '/Dashboard/';
    }

    /**
     * @Método: indexAction
     * @Descrição: Apresenta a lista de utilizadores  
     * 
     */
    public function indexAction() {
        try {
            
            if (!$this->auth->HasPermission("listarUtilizador")) {
                throw new \Exception("Utilizador não autorizado a visualizar lista de utilizadores.");
            }
            //Carrega utilizadores para a visão 
            $this->dados['entities'] = (new UtilizadorDAO())->listarTodos();

            //Carrega as perfis para a visão 
            $this->dados['perfis'] = (new PerfilDAO())->listarTodos();

            $this->view('utilizador/index', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    /**
     * @Método: novoAction
     * @Descrição: Apresenta o formulário para cadastro do utilizador 
     * 
     */
    public function novoAction() {
        try {
            
            if (!$this->auth->HasPermission("criarUtilizador")) {
                throw new \Exception("Utilizador não autorizado a criar outro utilizador.");
            }
            //Carrega as perfis para a visão 
            $this->dados['perfis'] = (new PerfilDAO())->listarTodos();

            $this->view('utilizador/novo', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    /**
     * @Método: registarAction
     * @Descrição: Processa o registo do utilizador 
     * 
     */
    public function registarAction() {
        try {

            $utilizadores = (new UtilizadorDAO())->findByEmail(filter_input(INPUT_POST, 'email'));

            if (count($utilizadores) > 0) {
                throw new \Exception("Já existe um utilizador com este e-mail.");
            }

            $utilizadorDAO = new UtilizadorDAO();

            $utilizador = new Utilizador();

            $utilizador->setNome(filter_input(INPUT_POST, 'nome'));
            $utilizador->setEmail(filter_input(INPUT_POST, 'email'));
            $utilizador->setPassword(md5(filter_input(INPUT_POST, 'password')));
            $utilizador->setTelefone(filter_input(INPUT_POST, 'telefone'));

            $utilizador->setEstado(Geral::CONS_UTILIZADOR_DESACTIVADO);
            $utilizador->setPerfil((new PerfilDAO())->buscarID(filter_input(INPUT_POST, 'perfil')));

            $utilizadorDAO->salvar($utilizador);

            $redirector = new RedirectorHelper();
            $redirector->goToControllerAction('Utilizador', 'index');

            unset($redirector);
            unset($utilizador);
            unset($utilizadorDAO);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }

    /**
     * @Método: novafotoAction
     * @Descrição:  
     * 
     */
    public function novafotoAction() {
        try {
            if (!$this->auth->HasPermission("NovaFotoutilizador")) {
                throw new \Exception("Utilizador não autorizado.");
            }

            $utilizadorDAO = new UtilizadorDAO();
            $utilizador = $utilizadorDAO->buscarID(filter_input(INPUT_POST, 'id_utilizador'));

            //$utilizador = new Utilizador();

            if (isset($_FILES)) {
                $uploadImagem = new UploadImagem($_FILES['foto_utilizador'], $_SERVER['DOCUMENT_ROOT'].'/web-files/uploads/utilizadores/');

                $up = $uploadImagem->upload();
                if ($up == TRUE) {
                    $utilizador->setFoto($uploadImagem->getImagemNome());
                    $utilizadorDAO->novafoto($utilizador);
                } else {
                    $utilizador->setFoto('indisponivel');
                    print_r($uploadImagem->getErrors());
                    die;
                    //Apresentar erros
                }
            }


            $redirector = new RedirectorHelper();
            $redirector->setUrlParameter('id', $utilizador->getId());
            $redirector->goToControllerAction('Utilizador', 'perfil');
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    /**
     * @Método: verAction
     * @Descrição: Apresenta a tela de visualização da informação dos utilizadores  
     * 
     */
    public function verAction() {
        try {
            $utilizadorDAO = new UtilizadorDAO();
            $retorno = $utilizadorDAO->buscarID($this->getParams('id'));

            $this->dados['entity'] = ($retorno != null ? $retorno : null);

            //Carrega as perfis para a visão 
            $this->dados['perfis'] = (new PerfilDAO())->listarTodos();


            $this->view('utilizador/ver', $this->dados);
        } catch (Exception $exc) {
            //$this->dados['mensagem'] = ($exc->getCode() == "23000" ? "Dado(s) duplicado(s)" : "");
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/erro', $this->dados);
        }
    }

    /**
     * @Método: editarAction
     * @Descrição: Apresenta a tela de edição da informação dos utilizadores  
     * 
     */
    public function editarAction() {
        try {
            
            if (!$this->auth->HasPermission("editarUtilizador")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }
            $utilizadorDAO = new UtilizadorDAO();
            $retorno = $utilizadorDAO->buscarID($this->getParams('id'));

            $this->dados['entity'] = ($retorno != null ? $retorno : null);

            //Carrega as perfis para a visão 
            $this->dados['perfis'] = (new PerfilDAO())->listarTodos();

            $this->view('utilizador/editar', $this->dados);
        } catch (Exception $exc) {
            //$this->dados['mensagem'] = ($exc->getCode() == "23000" ? "Dado(s) duplicado(s)" : "");
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }

    /**
     * @Método: actualizarAction
     * @Descrição: processa a actualização da informação dos utilizadores   
     * 
     */
    public function actualizarAction() {
        try {

            $utilizadorDAO = new UtilizadorDAO();
            $retorno = $utilizadorDAO->buscarID(filter_input(INPUT_POST, 'id_utilizador'));

            $utilizadores = (new UtilizadorDAO())->findByEmail(filter_input(INPUT_POST, 'email'));


            if (count($utilizadores) > 1) {
                throw new \Exception("Já existe um utilizador com este e-mail.");
            }

            $utilizador = $retorno;
            $utilizador->setNome(filter_input(INPUT_POST, 'nome'));
            $utilizador->setEmail(filter_input(INPUT_POST, 'email'));
            $utilizador->setTelefone(filter_input(INPUT_POST, 'telefone'));
            $utilizador->setEstado(filter_input(INPUT_POST, 'estado'));
            $utilizador->setPerfil((new PerfilDAO())->buscarID(filter_input(INPUT_POST, 'perfil')));

            $utilizadorDAO->actualizar($utilizador);
            $redirector = new RedirectorHelper();
            $redirector->setUrlParameter('id', $utilizador->getId());
            $redirector->goToControllerAction('Utilizador', 'ver');

            unset($redirector);
            unset($utilizador);
            unset($retorno);
            unset($utilizadorDAO);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }

    /**
     * @Método: passwordAction
     * @Descrição: processa a actualização da informação dos utilizadores   
     * 
     */
    public function passwordAction() {
        try {
            $utilizadorDAO = new UtilizadorDAO();
            $retorno = $utilizadorDAO->buscarID(filter_input(INPUT_POST, 'id_utilizador'));

            $utilizador = $retorno;
            if ($utilizador->getPassword() != md5(filter_input(INPUT_POST, 'password_actual'))) {
                throw new \Exception("Palavra-passe actual que inseriu está incorrecta.");
            } else {
                $utilizador->setPassword(md5(filter_input(INPUT_POST, 'password_utilizador')));
            }

            $utilizadorDAO->password($utilizador);
            $redirector = new RedirectorHelper();
            $redirector->setUrlParameter('id', $utilizador->getId());
            $redirector->goToControllerAction('Utilizador', 'perfil');

            unset($redirector);
            unset($utilizador);
            unset($utilizadorDAO);
        } catch (Exception $exc) {
            //$this->dados['mensagem'] = ($exc->getCode() == "23000" ? "Dado(s) duplicado(s)" : "");
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/erro', $this->dados);
        }
    }

    /**
     * @Método: redifinirAction
     * @Descrição: Repor a palavra-passe (padrão) do utilizador
     * 
     */
    public function redifinirAction() {
        try {

            $utilizadorDAO = new UtilizadorDAO();
            $retorno = $utilizadorDAO->buscarID($this->getParams('id'));

            $utilizador = $retorno;
            
            $utilizador->setPassword(md5("123"));

            $utilizadorDAO->password($utilizador);
            $redirector = new RedirectorHelper();
            $redirector->setUrlParameter('id', $utilizador->getId());
            $redirector->goToControllerAction('Utilizador', 'ver');

            unset($redirector);
            unset($utilizador);
            unset($utilizadorDAO);
        } catch (Exception $exc) {
            //$this->dados['mensagem'] = ($exc->getCode() == "23000" ? "Dado(s) duplicado(s)" : "");
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/erro', $this->dados);
        }
    }

    /**
     * @Método: eliminarAction
     * @Descrição: Processa a eliminação da informação do utilizador 
     * 
     */
    public function eliminarAction() {
        try {

            if (!$this->auth->HasPermission("eliminarUtilizador")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }

            $utilizadorDAO = new UtilizadorDAO();
            $retorno = $utilizadorDAO->buscarID($this->getParams('id_utilizador'));
            if (!$retorno) {
                echo 'O utilizador que pretende eliminar não existe.';
                die;
            }
            UploadImagem::eliminaImagem(Geral::DIR_IMG_UTILIZADORES . $retorno->getFoto());
            $utilizadorDAO->eliminar($retorno);
            $redirector = new RedirectorHelper();
            $redirector->goToControllerAction('Utilizador', 'index');
            unset($redirector);
            unset($utilizadorDAO);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }

    /**
     * @Método: imprimirAction
     * @Descrição: Processa a impressão (pdf) da informação do utilizador 
     * 
     */
    public function imprimirAction() {
        try {
            //Carrega as perfis para a visão 
            $this->dados['perfis'] = (new PerfilDAO())->listarTodos();
            $this->view('utilizador/imprimirPDF', $this->dados, '.php');
        } catch (Exception $exc) {
            //$this->dados['mensagem'] = ($exc->getCode() == "23000" ? "Dado(s) duplicado(s)" : "");
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/erro', $this->dados);
        }
    }

    public function perfilAction() {
        try {
            $utilizadorDAO = new UtilizadorDAO();
            $retorno = $utilizadorDAO->buscarID($this->getParams('id'));

            $this->dados['entity'] = ($retorno != null ? $retorno : null);

            //Carrega as perfis para a visão 
            $this->dados['perfis'] = (new PerfilDAO())->listarTodos();

            $this->view('utilizador/perfil', $this->dados);
        } catch (Exception $exc) {
            //$this->dados['mensagem'] = ($exc->getCode() == "23000" ? "Dado(s) duplicado(s)" : "");
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    /**
     * @Método: imprimirPdfAction
     * @Descrição: Processa a impressão (pdf) da informação do utilizador 
     * 
     */
    public function imprimirPdfAction() {
        try {
            $this->dados['entities'] = (new UtilizadorDAO())->listarTodos();
            $this->view('utilizador/RelatorioGeralPDF', $this->dados, ".php");
        } catch (Exception $exc) {
            //$this->dados['mensagem'] = ($exc->getCode() == "23000" ? "Dado(s) duplicado(s)" : "");
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/erro', $this->dados);
        }
    }

}
