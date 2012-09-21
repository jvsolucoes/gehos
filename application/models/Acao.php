<?php
/**
 * Description of Acao
 *
 * @author victor
 */
class Acao extends Zend_Db_Table_Abstract {

    protected $_name = "acao";
    protected $_primary = array("codAcao");
    protected $_dependentTables = array('ModuloAcao', 'PerfilAplicacaoModuloAcao');
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array("acao"), array("*"))
                    ->order("nomeAcao ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function listarAutocomplete($nomeAcao) {
        $acao = new Acao();
        
        $sql = $acao->getAdapter()->select()
                    ->from(array("acao"), array("*"))
                    ->where("nomeAcao LIKE '%$nomeAcao%'")
                    ->order("nomeAcao ASC");
        
//        $result = $acao->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $acao->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function buscar($codAcao) {
        $acao = new Acao();
        
        $sql = $acao->getAdapter()->select()
                    ->from(array("acao"), array("*"))
                    ->where("codAcao = ?", $codAcao);
        
//        $result = $acao->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $acao->getAdapter()->fetchRow($sql);

        return $result;
    }
    
    public static function buscarPorModulo($modulo) {
        $acao = new Acao();
        $sql = $acao->getAdapter()->select()
                                ->from(array("a" => "acao"),
                                        array('a.*'))
                                ->join(array('ma' => 'modulo_acao'), 'a.codAcao = ma.codAcao',
                                        array('ma.*'))
                                ->where("ma.codModulo = " . $modulo)
                                ->order("a.nomeAcao ASC");

        $result = $acao->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $acao->getAdapter()->fetchAll($sql);
        
        return $result;
    }
    
    public static function inserir($dados) {
        $acao = new Acao();
        
        $acao->getAdapter()->beginTransaction();
        
        $data = array(
            'nomeAcao' => $dados['nomeAcao'],
            'linkAcao' => Functions::gerarLink($dados['nomeAcao'])
        );
        
        try {
            $acao->insert($data);
            $result = true;
        } catch (Zend_Exception $e) {
            $acao->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar a ação" . $e->getMessage());
        }
        
        $acao->getAdapter()->commit();
        
        return $result;
    }
    
    public function editar($dados) {
        $this->getAdapter()->beginTransaction();
        
        $data = array(
            'nomeAcao' => $dados['descricao'],
            'linkAcao' => Functions::gerarLink($dados['descricao'])
        );
        
        try {
            $where = $this->getAdapter()->quoteInto("codAcao = ?", $dados['id']);
            $this->update($data, $where);
        } catch (Zend_Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados da ação" . $e->getMessage());
        }
        
        $this->getAdapter()->commit();
    }
    
}