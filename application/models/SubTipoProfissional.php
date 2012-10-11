<?php
/**
 * Description of SubTipoProfissional
 *
 * @author luciana
 */
class SubTipoProfissional extends Zend_Db_Table_Abstract {

    protected $_name = "subtipoprofissional";
    protected $_primary = array("codSubTipoProfissional");
    protected $_dependentTables = array('profissional');
    protected $_referenceMap = array(
        'TipoProfissional' => array(
            'refTableClass' => 'TipoProfissional',
            'refColumns' => 'codTipoProfissional',
            'columns' => 'codTipoProfissional',
            'onDelete' => self::CASCADE
        )
    );
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array("subtipoprofissional"), array("*"))
                    ->order("codSubTipoProfissional ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function listarAutocomplete($descSubTipoProfissional) {
        $subTipoProfissional = new SubTipoProfissional();
        
        $sql = $subTipoProfissional->getAdapter()->select()
                    ->from(array("subtipoProfissional"), array("*"))
                    ->where("descSubTipoProfissional LIKE '%$descSubTipoProfissional%'")
                    ->order("descSubTipoProfissional ASC");
        
        $result = $subTipoProfissional->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function buscar($codSubTipoProfissional) {
        $subTipoProfissional = new SubTipoProfissional();
        
        $sql = $subTipoProfissional->getAdapter()->select()
                    ->from(array("subtipoprofissional"), array("*"))
                    ->where("codSubProfissional = ?", $codSubTipoProfissiona);
        
        $result = $subTipoProfissional->getAdapter()->fetchRow($sql);

        return $result;
    }
        
    public static function inserir($dados) {
        $subTipoProfissional = new SubTipoProfissional();
        
        $subTipoProfissional->getAdapter()->beginTransaction();
        
        $data = array(
            'codTipoProfissional' => $dados['codTipoProfissional'],
            'descSubTipoProfissional' => $dados['descSubTipoProfissional']
        );
        
        try {
            $subTipoProfissional->insert($data);
            $result = true;
        } catch (Zend_Exception $e) {
            $subTipoProfissional->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar o subtipo do profissional" . $e->getMessage());
        }
        
        $subTipoProfissional->getAdapter()->commit();
        
        return $result;
    }
    
    public static function editar($dados) {
        $subTipoProfissional = new SubTipoProfissional();
        $subTipoProfissional->getAdapter()->beginTransaction();
        
        $data = array(
            'codTipoProfissional' => $dados['codTipoProfissional'],
            'descSubTipoProfissional' => $dados['descSubTipoProfissional']
        );
        
        try {
            $where = $subTipoProfissional->getAdapter()->quoteInto("codSubTipoProfissional = ?", $dados['codSubTipoProfissional']);
            $subTipoProfissional->update($data, $where);
        } catch (Zend_Exception $e) {
            $subTipoProfissional->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados do subtipo do profissional" . $e->getMessage());
        }
        
        $subTipoProfissional->getAdapter()->commit();
    }    
}