<?php
/**
 * Description of UsuarioController
 *
 * @author victor
 */
class Clinica_UsuarioController extends Zend_Controller_Action {

    private $_usuario;
    private $_modelUsuario;
    private $_modelPerfil;
    private $_modelEmpresa;
    private $_modelUnidade;
    
    public function init() {
        parent::init();
        
        $this->_modelUsuario = new Usuario();
        $this->_modelPerfil = new Perfil();
        $this->_modelEmpresa = new Empresa();
        $this->_modelUnidade = new Unidade();
        
        if (!Usuario::isLogged()) {
            $this->_forward("index");
        } else {
            $this->_usuario = Usuario::isLogged();
            $this->view->usuario = $this->_usuario;
        }
    }

    public function usuarioAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
        
        $perfis = $this->_modelPerfil->fetchAll(null, "nomePerfil ASC");
        $empresas = $this->_modelEmpresa->fetchAll(null, "nomeFantasiaEmpresa ASC");
        $this->view->perfis = $perfis;
        $this->view->empresas = $empresas;
    }
    
    public function cadastrarAction() {
        $dados = $this->_request->getPost();
        
        if (!$dados['usuarioSubmit']) {
            $result = $this->_modelUsuario->inserir($dados);
        } else {
            $result = $this->_modelUsuario->editar($dados);
        }
        
        if ($result) {
            echo "ok";
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function listarAction() {
        $usuario = $this->_request->getPost("usuario");
        $input = $this->_request->getPost("input");
        
        $result = $this->_modelUsuario->listarAutocomplete($usuario);
        
        if ($result) {
            $html = "<table id='div_".$input."' style='width: 100%;' class='listagem' cellpadding='0' cellspacing='0' border='0'>";
            $cont = 0;
            foreach ($result as $r) {
                $color = ($cont % 2 == 0) ? "bgcolor='#c4c4c4'" : "" ;
                $html .= "<tr $color style='height: 25px;'>
                            <td id='usuario_".$input."' usuario='" . $r['codUsuario'] . "'>";
                
                $html .= $r['nomeUsuario'];
                
                $html .= "  
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
        $usuario = $this->_request->getPost("idUsuario");
        $result = $this->_modelUsuario->buscar($usuario);
        
        if ($result) {        
            $perfil = $this->_modelPerfil->find($result['codPerfil'])->current();
            $result['nomePerfil'] = $perfil->nomePerfil;
            
            $unidade = $this->_modelUnidade->find($result['codUnidade'])->current();
            if ($unidade) {
                $result['nomeUnidade'] = $unidade->nomeUnidade;
            }
            
            $empresa = $this->_modelEmpresa->find($result['codEmpresa'])->current();
            if ($empresa) {
                $result['codEmpresa'] = $empresa->codEmpresa;
                $result['empresa'] = $empresa->nomeFantasiaEmpresa;
            }
                
            
            echo json_encode($result, true);
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function listarunidadeAction() {
        $empresa = $this->_request->getPost('codEmpresa');
        $id = $this->_request->getPost('input');
        $result = $this->_modelUnidade->listarPorEmpresa($empresa);
        
        if ($result) {
            $html = '<select name="codUnidade" id="' . $id . '">
                        <option value="">Selecione...</option>';

            foreach ($result as $unidade) {
                $html .= '<option value="' . $unidade->codUnidade . '">' . $unidade->nomeUnidade . '</option>';
            }
            $html .= '</select>';

            echo $html;
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function excluiracaoAction() {
        $acao = $this->_request->getPost("idAcao");
        $result = $this->_modelUsuario->excluir($acao);
        
        if ($result) {            
            echo "Ação excluída com sucesso!";
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
}