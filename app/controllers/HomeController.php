<?php
class HomeController extends Controller
{
    public function init()
    {

        $this->dados['title_page'] = 'FestiAngola 2021&#8482; - Festival Nacional da Canção Patriótica';
        $this->dados['page_context'] = "FestiAngola 2021&#8482; - Festival Nacional da Canção Patriótica";
        $this->dados['page_url'] = '/festival/Home/';
    }

    public function indexAction()
    {
        try {
            $this->dados['page'] = "home";
            $this->dados['inscritos'] = count((new CandidatoDAO())->listarTodos());
            $this->dados['agendas'] = (new AgendaDAO())->listarUltimasDatas();
            $this->dados['blogs'] = (new BlogDAO())->listarUltimasPublicadas();
            //$this->dados['provincias'] = (new ProvinciaDAO())->listarTodas();
            $this->view('home/index', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }

    public function festivalAction()
    {
        try {
            $this->dados['page'] = "festival";
            $this->view('home/festival', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }

    public function regulamentoAction()
    {
        try {
            $this->dados['page'] = "regulamento";
            $this->view('home/regulamento', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }

    public function contactoAction()
    {
        try {
            $this->dados['page'] = "contacto";
            $this->view('home/contacto', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }

    public function lerAction()
    {
        try {
            $this->dados['page'] = "";
            $blogDAO = new BlogDAO();
            $retorno = $blogDAO->buscarIDLer($this->getParams('id'));
            $this->dados['entity'] = ($retorno != null ? $retorno : null);
            if ($this->dados['entity'] == null) {
                throw new \Exception("A informação que pretende não foi encontrada.");
            }
            $this->dados['blogs'] = (new BlogDAO())->listarOutrasPublicadas($this->getParams('id'));
            $this->view('home/ler', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }

    public function inscricaoAction()
    {
        try {
            $this->dados['page'] = "festival";
            $this->dados['provincias'] = (new ProvinciaDAO())->listarTodas();
            $this->dados['estilos'] = (new EstiloDAO())->listarTodos();
            $this->view('home/inscricao', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
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
            if (($idade_actual >= 18) and ($idade_actual <= 35)) {

                $candidatos = (new CandidatoDAO())->findByBi(filter_input(INPUT_POST, 'bi'));
                if (count($candidatos) > 0) {
                    $arr_resposta["codigo"] = 300;
                    echo json_encode($arr_resposta);
                } else {
                    $candidato = new Candidato();

                    $ultimoCandidato = (new CandidatoDAO())->ultimoCandidato();
                    $NumUltimoCandidato = $ultimoCandidato == null ? date("Y") . "." . "00000" : substr($ultimoCandidato->getNumero(), 5);
                    $numero = $ultimoCandidato == null ? $NumUltimoCandidato : date("Y") . "." . Method::geraNumeroCandidato($NumUltimoCandidato);

                    $candidato->setNumero($numero);
                    $candidato->setNome(filter_input(INPUT_POST, 'nome'));
                    $candidato->setBi(filter_input(INPUT_POST, 'bi'));
                    $candidato->setGenero(filter_input(INPUT_POST, 'genero'));
                    $candidato->setNascimento(filter_input(INPUT_POST, 'nascimento'));
                    $candidato->setProvincia(filter_input(INPUT_POST, 'provincia'));
                    $candidato->setTelefone(filter_input(INPUT_POST, 'telefone'));
                    $candidato->setEstilo(filter_input(INPUT_POST, 'estilo'));
                    $candidato->setLink(filter_input(INPUT_POST, 'link'));
                    $candidato->setData(date('Y-m-d'));

                    $candidatoDAO = new CandidatoDAO();
                    $objCandidato = $candidatoDAO->salvar($candidato);
                    if ($objCandidato != null) {

                        echo json_encode(["codigo"=>200,"sucesso"=>"Parabéns. A sua inscrição foi realizada com sucesso. O seu n.º de Inscrição é ".$objCandidato->getNumero()."."]);
                    } else {
                        $arr_resposta["codigo"] = 500;
                        echo json_encode($arr_resposta);
                    }
                }
            } else {
                $arr_resposta["codigo"] = 400;
                echo json_encode($arr_resposta);
            }
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }
}
