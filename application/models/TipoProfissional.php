<?php
/**
 * Description of TipoProfissional
 *
 * @author luciana
 */
class TipoProfissional extends Zend_Db_Table_Abstract {

    protected $_name = "tipoprofissional";
    protected $_primary = array("codTipoProfissional");
    protected $_dependentTables = array('SubTipoProfissional', 'profissional');
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array("tipoprofissional"), array("*"))
                    ->order("codTipoProfissional ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function listarAutocomplete($nomeTipoProfissional) {
        $tipoProfissional = new TipoProfissional();
        
        $sql = $tipoProfissional->getAdapter()->select()
                    ->from(array("tipoProfissional"), array("*"))
                    ->where("nomeTipoProfissional LIKE '%$nomeTipoProfissional%'")
                    ->order("nomeTipoProfissional ASC");
        
        $result = $tipoProfissional->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function buscar($codTipoProfissifonal) {
        $tipoProfissional = new TipoProfissional();
        
        $sql = $tipoProfissional->getAdapter()->select()
                    ->from(array("tipoprofissional"), array("*"))
                    ->where("codTipoProfissional = ?", $codSubTipoProfissiona);
        
        $result = $tipoProfissional->getAdapter()->fetchRow($sql);

        return $result;
    }
        
    public static function inserir($dados) {
        $tipoProfissional = new TipoProfissional();
        
        $tipoProfissional->getAdapter()->beginTransaction();
        
        $data = array(
            'nomeTipoProfissional' => $dados['nomeTipoProfissional']
        );
        
        try {
            $tipoProfissional->insert($data);
            $result = true;
        } catch (Zend_Exception $e) {
            $tipoProfissional->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar o tipo do profissional" . $e->getMessage());
        }
        
        $tipoProfissional->getAdapter()->commit();
        
        return $result;
    }
    
    public static function editar($dados) {
        $tipoProfissional = new TipoProfissional();
        $tipoProfissional->getAdapter()->beginTransaction();
        
        $data = array(
            'nomeTipoProfissional' => $dados['nomeTipoProfissional']
        );
        
        try {
            $where = $tipoProfissional->getAdapter()->quoteInto("codTipoProfissional = ?", $dados['codTipoProfissional']);
            $tipoProfissional->update($data, $where);
        } catch (Zend_Exception $e) {
            $tipoProfissional->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados do tipo do profissional" . $e->getMessage());
        }
        
        $tipoProfissional->getAdapter()->commit();
    }    
}