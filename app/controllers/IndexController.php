<?php
class IndexController extends Controller
{
    public function init()
    {

        $this->dados['title_page'] = 'FestiAngola 2021&#8482; - Festival Nacional da Canção Patriótica';
        $this->dados['page_context'] = "FestiAngola 2021&#8482; - Festival Nacional da Canção Patriótica";
        $this->dados['page_url'] = '/Index/';
    }

    public function indexAction()
    {
        try {
            $this->dados['page'] = "home";
            $this->dados['inscritos'] = count((new CandidatoDAO())->listarTodos());
            $this->dados['agendas'] = (new AgendaDAO())->listarUltimasDatas();
            $this->dados['blogs'] = (new BlogDAO())->listarUltimasPublicadas();
            //$this->dados['provincias'] = (new ProvinciaDAO())->listarTodas();
            $this->view('index/index', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }

    public function festivalAction()
    {
        try {
            $this->dados['page'] = "festival";
            $this->view('index/festival', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }

    public function regulamentoAction()
    {
        try {
            $this->dados['page'] = "regulamento";
            $this->view('index/regulamento', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }

    public function contactoAction()
    {
        try {
            $this->dados['page'] = "contacto";
            $this->view('index/contacto', $this->dados);
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
            $this->view('index/ler', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }

    public function sucessoAction()
    {
        try {
            $this->dados['page'] = "";
            $candidatoDAO = new CandidatoDAO();
            $retorno = $candidatoDAO->buscarNumero($this->getParams('candidato'));
            $this->dados['entity'] = ($retorno != null ? $retorno : null);
            if ($this->dados['entity'] == null) {
                throw new \Exception("A informação que pretende não foi encontrada.");
            }
            $this->dados['blogs'] = (new BlogDAO())->listarPublicadas($this->getParams('id'));
            $this->view('index/sucesso', $this->dados);
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
            
            $data1 = '28-08-2021';
            $encerrado = '31-08-2021';
            // Comparando as Datas
            if((strtotime(date('d-m-Y'))) > (strtotime($encerrado)))
            {
                $this->view('index/encerrado', $this->dados);
            }
            else
            {
                $this->view('index/inscricao', $this->dados);
            }
            
            
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }
    
    public function encerradoAction()
    {
        try {
            $this->dados['page'] = "festival";
            $this->dados['provincias'] = (new ProvinciaDAO())->listarTodas();
            $this->dados['estilos'] = (new EstiloDAO())->listarTodos();
            $this->view('index/encerrado', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }
    
    public function apuradoAction()
    {
        try {
            $this->dados['page'] = "festival";
            $this->dados['provincias'] = (new ProvinciaDAO())->listarTodas();
            $this->dados['estilos'] = (new EstiloDAO())->listarTodos();
            $this->dados['apurados'] = (new CandidatoDAO())->listarApurados();
            $this->view('index/apurado', $this->dados);
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }

    public function registarAction()
    {
        try {
            $encerrado = '31-08-2021';
            
            // Comparando as Datas
            if((strtotime(date('d-m-Y'))) > (strtotime($encerrado)))
            {
                $arr_resposta["codigo"] = 600;
                echo json_encode($arr_resposta);
            }
            else
            {
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
                        $candidato->setEmail(filter_input(INPUT_POST, 'email'));
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
    
                            //echo json_encode(["codigo" => 200, "sucesso" => "Parabéns. A sua inscrição foi realizada com sucesso. O seu n.º de Inscrição é " . $objCandidato->getNumero() . "."]);
    
                            // Enviar email 
                            Ini_set('display_errors', 1);
                            Error_reporting(E_ALL);
    
                            $to = filter_input(INPUT_POST, 'email');
                            $from = "geral@festiangola.co.ao";
    
                            if (empty($to)) {
                                echo json_encode(["codigo" => 200, "sucesso" => "Parabéns. A sua inscrição foi realizada com sucesso. O seu n.º de Inscrição é " . $objCandidato->getNumero() . "."]);
                            } else {
    
                                $headers = "From: $from";
                                $headers = "From: " . $from . "\r\n";
                                $headers .= "Reply-To: " . $from . "\r\n";
                                $headers .= "MIME-Version: 1.0\r\n";
                                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    
                                $subject = "Candidatura | Festival Nacional da Canção Patriótica";
    
                                $logo = 'https://festiangola.co.ao/web-files/assets/img/email.png';
                                $link = 'https://festiangola.co.ao/';
    
                                $body = "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Inscri&ccedil;&aatildeo</title></head><body>";
                                $body .= "<div style='width:100%'>";
                                $body .= "<h3 style='color:#DC3E0F; text-align:center;'>Festival Nacional da Can&ccedil;&atilde;o Patri&oacute;tica</h3><hr>";
                                $body .= "<p style='text-align:justify; color:#000000;'><span style='font-size:18px;'><b>Parab&eacute;ns!</b></span><br>A sua inscri&ccedil;&atilde;o foi com sucesso.<br><br>";
                                $body .= "O seu n&uacute;mero de Candidato &eacute;:  <a href='https://www.festiangola.co.ao/Index/sucesso/" . base64_encode("candidato") . '/' . base64_encode($objCandidato->getNumero()) . "' target='_blank'>" . $objCandidato->getNumero() . "</a>";
                                $body .= "<br><br><hr><a href='{$link}'><img src='{$logo}' alt='FestiAngola 2021' style='width:100%;'></a>";
                                $body .= "<br><span style='font-size:12px;'>Tel: +244 923 698 943 &bull; +244 944 692 347<br>Urbaniza&ccedil;&atilde;o Boa Vida<br>Luanda, Angola</span>";
                                $body .= "</div>";
                                $body .= "</body></html>";
    
                                if (@mail($to, $subject, $body, $headers)) {
                                    echo json_encode(["codigo" => 200, "sucesso" => "Parabéns. A sua inscrição foi realizada com sucesso. O seu n.º de Inscrição é " . $objCandidato->getNumero() . "."]);
                                } else {
                                    $arr_resposta["resposta"] = 500;
                                    echo json_encode($arr_resposta);
                                }
                            }
                            // Fim do envio de email para activação da conta
    
                        } else {
                            $arr_resposta["codigo"] = 500;
                            echo json_encode($arr_resposta);
                        }
                    }
                } else {
                    $arr_resposta["codigo"] = 400;
                    echo json_encode($arr_resposta);
                }
            }
            
        } catch (Exception $exc) {
            $this->dados['mensagem'] = $exc->getMessage();
            $this->view('erro/index', $this->dados);
        }
    }
}
