<?php
/**
 * Description of PerfilController
 *
 * @author victor
 */
class Clinica_PerfilController extends Zend_Controller_Action {

    private $_usuario;
    private $_modelAplicacao;
    private $_modelPerfil;
    private $_modelPerfilAplicacao;
    
    public function init() {
        parent::init();
        
        $this->_modelAplicacao = new Aplicacao();
        $this->_modelPerfil = new Perfil();
        $this->_modelPerfilAplicacao = new PerfilAplicacaoModuloAcao();
        
        if (!Usuario::isLogged()) {
            $this->_forward("index");
        } else {
            $this->_usuario = Usuario::isLogged();
            $this->view->usuario = $this->_usuario;
        }
    }

    public function perfilAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
        
        $aplicacoes = $this->_modelAplicacao->fetchAll(null, "nomeAplicacao ASC");
        $this->view->aplicacoes = $aplicacoes;
    }
    
    public function cadastrarAction() {
        $dados = $this->_request->getPost();
        
        if (!$dados['perfilSubmit']) {
            $result = $this->_modelPerfil->inserir($dados);
        } else {
            $result = $this->_modelPerfil->editar($dados);
        }
        
        if ($result) {
            echo "ok";
        } else {
            echo "naopassou";
        }
        die();
    }
    
    public function listarAction() {
        $perfil = $this->_request->getPost("perfil");
        $input = $this->_request->getPost("input");
        
        $result = $this->_modelPerfil->listarAutocomplete($perfil);
        
        if ($result) {
            $html = "<table id='div_".$input."' style='width: 100%;' class='listagem' cellpadding='0' cellspacing='0' border='0'>";
            $cont = 0;
            foreach ($result as $r) {
                $color = ($cont % 2 == 0) ? "bgcolor='#c4c4c4'" : "" ;
                $html .= "<tr $color style='height: 25px;'>
                            <td id='perfil_".$input."' perfil='" . $r['codPerfil'] . "'>";
                
                $html .= $r['nomePerfil'];
                
                $html .= "  
                            </td>
                            <td width='30px' id='excluir_".$input."'>
                                <img src='" . dirname($_SERVER['PHP_SELF']) . "/img/excluir_32x32.png' alt='' width='20px' />
                            </td>
                        </tr>";
                $cont++;
            }
                            
            $html .= "</table>";
            
            echo $html;
        } else {
            echo "<div id='div_".$input."'>Não existe registro para essa pesquisa</div>";
        }
        
        die();
    }
    
    public function buscarAction() {
        $perfil = $this->_request->getPost("idPerfil");
        $result = $this->_modelPerfil->buscar($perfil);
        
        if ($result) {            
            echo json_encode($result, true);
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function buscaraplicacaomoduloacaoAction() {
        $perfil = $this->_request->getPost("idPerfil");
        $result = $this->_modelPerfilAplicacao->buscarIdPerfil($perfil);
        
        if ($result) {            
            echo json_encode($result, true);
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function excluirAction() {
        $perfil = $this->_request->getPost("idPerfil");
        $result = $this->_modelPerfil->excluir($perfil);
        
        if ($result) {            
            echo "Perfil excluído com sucesso!";
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
}