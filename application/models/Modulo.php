<?php
/**
 * Description of Modulo
 *
 * @author victor
 */
class Modulo extends Zend_Db_Table_Abstract {

    protected $_name = "modulo";
    protected $_primary = array("codModulo");
    protected $_dependentTables = array('AplicacaoModulo', 'ModuloAcao', 'PerfilAplicacaoModuloAcao');
    
    protected $_modelModuloAcao;
    
    private function inicializar() {
        $this->_modelModuloAcao = new ModuloAcao();
    }
    
    const NIVELMENU = 10;
    const VISIBILIDADEMUNU = 1;
    
    public static function listar($aplicacao) {
        $modulo = new Modulo();
        $sql = $modulo->getAdapter()->select()
                                ->from(array("m" => "modulo"),
                                        array('m.*'))
                                ->join(array('ap' => 'aplicacao_modulo'), 'm.codModulo = ap.codModulo',
                                        array('ap.*'))
                                ->where("ap.codAplicacao = " . $aplicacao)
                                ->where("m.nivelModulo = ?", 0)
                                ->order("m.nomeModulo ASC");

        $result = $modulo->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $modulo->getAdapter()->fetchAll($sql);
        
        return $result;
    }
    
    public static function listarPorAplicacao($aplicacao) {
        $modulo = new Modulo();
        $sql = $modulo->getAdapter()->select()
                                ->from(array("m" => "modulo"),
                                        array('m.*'))
                                ->join(array('ap' => 'aplicacao_modulo'), 'm.codModulo = ap.codModulo',
                                        array('ap.*'))
                                ->where("ap.codAplicacao = " . $aplicacao)
                                ->order("m.nivelModulo ASC");

        $result = $modulo->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $modulo->getAdapter()->fetchAll($sql);
        
        return $result;
    }
    
    public static function listarAutocomplete($nomeModulo) {
        $modulo = new Modulo();
        
        $sql = $modulo->getAdapter()->select()
                    ->from(array("m" => "modulo"), array("m.*"))
                    ->where("m.nomeModulo LIKE '%$nomeModulo%'")
                    ->order("m.nomeModulo ASC");
        
        $result = $modulo->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public function listarTodos() {
        
        $sql = $this->getAdapter()->select()
                                ->from(array("m" => "modulo"),
                                        array('m.*'))
                                ->order("m.nomeModulo ASC");

        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);
        
        return $result;
    }
    
    public static function buscar($codModulo) {
        $modulo = new Modulo();
        
        $sql = $modulo->getAdapter()->select()
                    ->from(array("m" => "modulo"), array("m.*"))
                    ->joinLeft(array("mo" => "modulo"), "m.codModuloPai = mo.codModulo",
                            array("moduloPai" => "mo.nomeModulo"))
                    ->where("m.codModulo = ?", $codModulo);       
        
        $result = $modulo->getAdapter()->fetchRow($sql);

        return $result;
    }

    public static function buscarNome($nome) {
        $modulo = new Modulo();
        $sql = $modulo->getAdapter()->select()
                                ->from(array("modulo"),
                                        array('*'))
                                ->where("linkModulo = '" . $nome . "'");
        
        $result = $modulo->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $modulo->getAdapter()->fetchRow($sql);

        return $result;
    }
    
    public static function listarModuloFilho($codAplicacao, $codModuloPai) {
        $modulo = new Modulo();
        $sql = $modulo->getAdapter()->select()
                                ->from(array("am" => "aplicacao_modulo"))
                                ->join(array("m" => "modulo"), "am.codModulo = m.codModulo", array("m.*"))
                                ->where("am.codAplicacao = ?", $codAplicacao)
                                ->where("m.codModuloPai = ?", $codModuloPai);
        
        $result = $modulo->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $modulo->getAdapter()->fetchAll($sql);
        
        return $result;
    }
    
    public static function listarModuloUser($codPerfil) {
        $modulo = new Modulo();
        
        if ($codPerfil == 1) {
            $sql = $modulo->getAdapter()->select()
                                ->from(array("m" => "modulo"), array("m.*"))
                                ->where("m.nivelModulo = ?", Modulo::NIVELMENU)
                                ->order("m.visibilidadeModulo ASC")
                                ->order("m.nomeModulo ASC");
        } else {
            $sql = $modulo->getAdapter()->select()
                                ->from(array("m" => "modulo"), array("m.*"))
                                ->where("m.nivelModulo = ?", Modulo::NIVELMENU)
                                ->where("m.visibilidadeModulo = ?", Modulo::VISIBILIDADEMUNU)
                                ->order("m.visibilidadeModulo ASC")
                                ->order("m.nomeModulo ASC");
        }
        
        
        
        $result = $modulo->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $modulo->getAdapter()->fetchAll($sql);
        
        return $result;
    }

    public static function buscarId($id) {
        $modulo = new Modulo();
        $sql = $modulo->getAdapter()->select()
                                ->from(array("modulo"),
                                        array('*'))
                                ->where("id = " . $id)
                                ->order("nomeModulo ASC");

        $result = $modulo->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $modulo->getAdapter()->fetchRow($sql);

        return $result;
    }
    
    public static function inserir($dados) {
        $modulo = new Modulo();
        
        $modulo->inicializar();
        
        $modulo->getAdapter()->beginTransaction();
        
        $codModulo = $modulo->add($dados);
        $dados['codModulo'] = $codModulo;
        
        $modulo->_modelModuloAcao->inserir($dados);

        $modulo->getAdapter()->commit();
        
        return true;
    }
    
    private function add($dados) {
        $data = array(
            'nomeModulo' => $dados['nomeModulo'],
            'nivelModulo' => $dados['nivelModulo'],
            'codModuloPai' => $dados['codModuloPai'],
            'linkModulo' => Functions::gerarLink($dados['nomeModulo'])
        );
        
        try {
            $this->insert($data);
            $idModulo = $this->getAdapter()->lastInsertId();
        } catch (Zend_Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar o módulo" . $e->getMessage());
        }
        
        return $idModulo;
    }
    
    public function editar($dados) {
        $this->inicializar();
        
        $this->getAdapter()->beginTransaction();
        
        $this->edit($dados);
        
        $this->_modelModuloAcao->inserir($dados);
        
        $this->getAdapter()->commit();
        
        return true;
    }
    
    private function edit($dados) {
        $data = array(
            'nomeModulo' => $dados['nomeModulo'],
            'nivelModulo' => $dados['nivelModulo'],
            'linkModulo' => Functions::gerarLink($dados['nomeModulo'])
        );
        
        if ($dados['codModuloPai'] != "") {
            $data['codModuloPai'] = $dados['codModuloPai'];
        }
        
        try {
            $where = $this->getAdapter()->quoteInto("codModulo = ?", $dados['codModulo']);
            $this->update($data, $where);
        } catch (Zend_Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados do módulo" . $e->getMessage());
        }
    }
    
    public static function excluir($codModulo) {
        $modulo = new Modulo();
        $moduloAcao = new ModuloAcao();
        $aplicacaoModulo = new AplicacaoModulo();
        $pama = new PerfilAplicacaoModuloAcao();
        
        $wherePama = $pama->getAdapter()->quoteInto("codModulo = ?", $codModulo);
        $resultPama = $pama->delete($wherePama);
        
        $whereAM = $aplicacaoModulo->getAdapter()->quoteInto("codModulo = ?", $codModulo);
        $resultAM = $aplicacaoModulo->delete($whereAM);
        
        $whereMa = $moduloAcao->getAdapter()->quoteInto("codModulo = ?", $codModulo);
        $resultMa = $moduloAcao->delete($whereMa);
            
        $where = $modulo->getAdapter()->quoteInto("codModulo = ?", $codModulo);
        $result = $modulo->delete($where);

        return $result;
    }
    
}