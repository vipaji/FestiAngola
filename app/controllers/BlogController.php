<?php
/**
 * Description of BlogController
 *
 * @author Administrator
 */
class BlogController extends Controller {

    public function init() {
        $this->auth = new AuthHelper();
        $this->auth->setLoginControllerAction('Login', 'index')->checkLogin('redirect');

        $this->session = new SessionHelper();
        $this->dados['userInfo'] = $this->session->selectSession('userData');

        $this->dados['title_page'] = "Blog &bull; Festival Nacional da Canção &#8482;";
        $this->dados['page_context'] = "Blog";
        $this->dados['page_icon'] = "fas fa-cog";
        $this->dados['page_url'] = '/Blog/';
        $this->dados['home_url'] = '/Dashboard/';
    }

    public function indexAction() {
        try {
            $blogDAO = new BlogDAO();
            $this->dados['entities'] = $blogDAO->listarTodas();
            $this->view('blog/index', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function novoAction() {
        try {
            if (!$this->auth->HasPermission("novoBlog")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }
            $this->dados['page_context'] = "Adicionar blog";
            $this->view('blog/novo', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function registarAction() {
        try {
            if (!$this->auth->HasPermission("novoBlog")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }
            $blog = new Blog();

            $blog->setTitulo(filter_input(INPUT_POST, 'titulo'));
            $blog->setTexto(filter_input(INPUT_POST, 'texto'));
            $blog->setEstado(Geral::CONS_BLOG_N_PUBLICADO);
            $blog->setData(date('Y-m-d'));

            $blogDAO = new BlogDAO();
            $blogDAO->salvar($blog);
            $redirector = new RedirectorHelper();
            $redirector->goToControllerAction('Blog', 'index');
            unset($redirector);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    /**
     * @Método: addfotoAction
     * @Descrição: Adicionar foto a publicação 
     * 
     */
    public function addfotoAction() {
        try {
            
            if (!$this->auth->HasPermission("AddFotoBlog")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }

            $blogDAO = new BlogDAO();
            $blog = $blogDAO->buscarID(filter_input(INPUT_POST, 'id_blog'));

            //$blog = new Blog();

            if (isset($_FILES)) {
                $uploadImagem = new UploadImagem($_FILES['foto_blog'], $_SERVER['DOCUMENT_ROOT'].'/web-files/uploads/blog/');

                $up = $uploadImagem->upload();
                if ($up == TRUE) {
                    $blog->setFoto($uploadImagem->getImagemNome());
                    $blogDAO->novafoto($blog);
                } else {
                    $blog->setFoto('indisponivel');
                    print_r($uploadImagem->getErrors());
                    die;
                    //Apresentar erros
                }
            }


            $redirector = new RedirectorHelper();
            $redirector->setUrlParameter('id', $blog->getId());
            $redirector->goToControllerAction('Blog', 'ver');
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function verAction() {
        try {

            if (!$this->auth->HasPermission("verBlog")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }
            $blogDAO = new BlogDAO();
            $retorno = $blogDAO->buscarID($this->getParams('id'));
            $this->dados['entity'] = ($retorno != null ? $retorno : null);
            if ($this->dados['entity'] == null) {
                throw new \Exception("Entidade não encontrada.");
            }
            $this->view('blog/ver', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function editarAction() {
        try {
            if (!$this->auth->HasPermission("editarBlog")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }
            $blogDAO = new BlogDAO();
            $retorno = $blogDAO->buscarID($this->getParams('id'));
            $this->dados['entity'] = ($retorno != null ? $retorno : null);
            if ($this->dados['entity'] == null) {
                throw new \Exception("Entidade não encontrada.");
            }

            $this->view('blog/editar', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function actualizarAction() {
        try {
            if (!$this->auth->HasPermission("editarBlog")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }
            $blogDAO = new BlogDAO();
            $retorno = $blogDAO->buscarID(filter_input(INPUT_POST, 'id_blog'));

            $blog = $retorno;

            $blog->setTitulo(filter_input(INPUT_POST, 'titulo'));
            $blog->setTexto(filter_input(INPUT_POST, 'texto'));
            $blog->setEstado(filter_input(INPUT_POST, 'estado'));

            $blogDAO->actualizar($blog);
            $redirector = new RedirectorHelper();
            $redirector->setUrlParameter('id', $blog->getId());
            $redirector->goToControllerAction('Blog', 'ver');

            unset($redirector);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

    public function eliminarAction() {
        try {
            if (!$this->auth->HasPermission("eliminarBlog")) {
                throw new \Exception(Geral::CONS_MESSAGE_ERRO_PERFIL_SEM_PERMISSOES);
            }
            $blogDAO = new BlogDAO();

            $retorno = $blogDAO->buscarID($this->getParams('id'));
            if (!$retorno) {
                echo 'Não encontrou';
            }
            $blogDAO->eliminar($retorno);
            $redirector = new RedirectorHelper();
            $redirector->goToControllerAction('Blog', 'index');
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
            $this->dados['entities'] = (new BlogDAO())->listarTodas();
            $this->view('blog/RelatorioGeralPDF', $this->dados, ".php");
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/in', $this->dados);
        }
    }

}
