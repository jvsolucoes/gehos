<?php
/**
 * Description of Acao
 *
 * @author luciana
 */
class Uf extends Zend_Db_Table_Abstract {

    protected $_name = "uf";
    protected $_primary = array("codUf");
    protected $_dependentTables = array('Cidade');
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array("uf"), array("*"))
                    ->order("nomeUf ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function listarAutocomplete($nomeUf) {
        $uf = new Uf();
        
        $sql = $uf->getAdapter()->select()
                    ->from(array("uf"), array("*"))
                    ->where("nomeUf LIKE '%$nomeUf%'")
                    ->order("nomeUf ASC");
        
        $result = $uf->getAdapter()->fetchAll($sql);

        return $result;
    }
        
    public static function inserir($dados) {
        $uf = new Uf();
        
        $uf->getAdapter()->beginTransaction();
        
        $data = array( 'nomeUf' => $dados['nomeBairro']);
            
        
        try {
            $uf->insert($data);
            $result = true;
        } catch (Zend_Exception $e) {
            $uf->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar a uf" . $e->getMessage());
        }
        
        $uf->getAdapter()->commit();
        
        return $result;
    }
    
    public static function editar($dados) {
        $this->getAdapter()->beginTransaction();
        
        $data = array( 'nomeUf' => $dados['nomeBairro']);
        
        try {
            $where = $this->getAdapter()->quoteInto("codUf = ?", $dados['codUf']);
            $this->update($data, $where);
        } catch (Zend_Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados da uf" . $e->getMessage());
        }
        
        $this->getAdapter()->commit();
    }    
}