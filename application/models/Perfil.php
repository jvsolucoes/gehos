<?php
/**
 * Description of Usuario
 *
 * @author victor
 */
class Perfil extends Zend_Db_Table_Abstract {
    
    protected $_name = "perfil";
    protected $_primary = array("codPerfil");
    protected $_dependentTables = array('PerfilAplicacaoModuloAcao', 'Usuario');  
    
    protected $_modelPerfilAplicacaoModuloAcao;
    protected $_modelUsuario;
    
    private function inicializar() {
        $this->_modelPerfilAplicacaoModuloAcao = new PerfilAplicacaoModuloAcao();
        $this->_modelUsuario = new Usuario();
    }
    
    public static function listarAutocomplete($nomePerfil) {
        $perfil = new Perfil();
        
        $sql = $perfil->getAdapter()->select()
                    ->from(array("p" => "perfil"), array("p.*"))
                    ->where("p.nomePerfil LIKE '%$nomePerfil%'")
                    ->order("p.nomePerfil ASC");
        
        $result = $perfil->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function buscar($codPerfil) {
        $perfil = new Perfil();
        
        $sql = $perfil->getAdapter()->select()
                    ->from(array("p" => "perfil"), array("p.*"))
                    ->where("p.codPerfil = ?", $codPerfil);
        
        $result = $perfil->getAdapter()->fetchRow($sql);

        return $result;
    }
    
    public static function buscarIdPerfil($id) {
        $pa = new PerfilAplicacaoModuloAcao();
        $sql = $pa->getAdapter()->select()
                                ->from(array("perfil_aplicacao"),
                                        array("*"))
                                ->where("codPerfil = ?", $id);

        $result = $pa->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $pa->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array("perfil"), array("*"))
                    ->order("nomePerfil ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }

    public static function validarModulo($app, $model) {
        $usuario = Zend_Auth::getInstance()->getIdentity();
        
        $aplicacao = Aplicacao::buscarNome($app);
        $modulo = Modulo::buscarNome($model);
        
        $permissao = PerfilAplicacaoModuloAcao::buscarPermissoesModulo($usuario->codPerfil, $aplicacao->codAplicacao, $modulo->codModulo);
        
        if(!$permissao) {
            return false;
        }

        return true;
    }

    public static function validarAcao($app, $model, $action) {
        $usuario = Zend_Auth::getInstance()->getIdentity();

        $aplicacao = Aplicacao::buscarNome($app);
        $modulo = Modulo::buscarNome($model);
        $acao = Acao::buscarNome($action);

        $permissao = PerfilAplicacaoModuloAcao::buscarPermissoes($usuario->codPerfil, $aplicacao->codAplicacao, $modulo->codModulo, $acao->codAcao);

        if($permissao) {
            return true;
        } 
        
        return false;
    }
    
    public function inserir($dados) {
        $this->inicializar();
        
        $this->getAdapter()->beginTransaction();
        
        $usuario = $this->add($dados);
        $dados['codPerfil'] = $usuario;
        
        $this->_modelPerfilAplicacaoModuloAcao->inserir($dados);
        
        $this->getAdapter()->commit();
        
        return true;
    }
    
    private function add($dados) {
        $data = array(
            'nomePerfil' => $dados['nomePerfil']
        );
        
        try {
            $this->insert($data);
            $idPerfil = $this->getAdapter()->lastInsertId();
        } catch (Zend_Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar o perfil" . $e->getMessage());
        }
        
        return $idPerfil;
    }
    
    public function editar($dados) {
        $this->inicializar();
        
        $this->getAdapter()->beginTransaction();
        
        $this->edit($dados);
        $this->_modelPerfilAplicacaoModuloAcao->inserir($dados);
        
        $this->getAdapter()->commit();
        
        return true;
    }
    
    private function edit($dados) {
        
        $data = array(
            'nomePerfil' => $dados['nomePerfil']
        );
        
        try {
            $where = $this->getAdapter()->quoteInto("codPerfil = ?", $dados['codPerfil']);
            $this->update($data, $where);
        } catch (Zend_Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados do perfil" . $e->getMessage());
        }
    }
    
}