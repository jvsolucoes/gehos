<?php
/**
 * Description of Acao
 *
 * @author luciana
 */
class Bairro extends Zend_Db_Table_Abstract {

    protected $_name = "bairro";
    protected $_primary = array("codBairro");
    protected $_dependentTables = array('Cep');
    
    protected $_referenceMap = array(
        'Cidade' => array(
            'refTableClass' => 'Cidade',
            'refColumns' => 'codCidade',
            'columns' => 'codCidade',
            'onDelete' => self::CASCADE
        )
    );
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array("bairro"), array("*"))
                    ->order("nomeBarro ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function listarAutocomplete($nomeBairro) {
        $bairro = new Bairro();
        
        $sql = $bairro->getAdapter()->select()
                    ->from(array("bairro"), array("*"))
                    ->where("nomeBairro LIKE '%$nomeBairro%'")
                    ->order("nomeBairro ASC");
        
        $result = $bairro->getAdapter()->fetchAll($sql);

        return $result;
    }
        
    public static function inserir($dados) {
        $bairro = new Bairro();
        
        $bairro->getAdapter()->beginTransaction();
        
        $data = array(
            'nomeBairro' => $dados['nomeBairro'],
            'codCidade' => $dados['codCidade']
        );
        
        try {
            $bairro->insert($data);
            $result = true;
        } catch (Zend_Exception $e) {
            $bairro->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar o bairro" . $e->getMessage());
        }
        
        $bairro->getAdapter()->commit();
        
        return $result;
    }
    
    public static function editar($dados) {
        $this->getAdapter()->beginTransaction();
        
        $data = array(
            'nomeBairro' => $dados['nomeBairro'],
            'codCidade' => $dados['codCidade']
        );
        
        try {
            $where = $this->getAdapter()->quoteInto("codBairro = ?", $dados['codBairro']);
            $this->update($data, $where);
        } catch (Zend_Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados do bairro" . $e->getMessage());
        }
        
        $this->getAdapter()->commit();
    }    
}