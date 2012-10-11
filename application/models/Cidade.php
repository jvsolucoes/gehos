<?php
/**
 * Description of Acao
 *
 * @author luciana
 */
class Cidade extends Zend_Db_Table_Abstract {

    protected $_name = "cidade";
    protected $_primary = array("codCidade");
    protected $_dependentTables = array('Pessoa', 'Cep', 'Bairro');
    
    protected $_referenceMap = array(
        'Uf' => array(
            'refTableClass' => 'Uf',
            'refColumns' => 'codUf',
            'columns' => 'codUf',
            'onDelete' => self::CASCADE
        )
    );
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array("cidade"), array("*"))
                    ->order("nomeCidade ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function listarAutocomplete($nomeCidade) {
        $cidade = new Cidade();
        
        $sql = $cidade->getAdapter()->select()
                    ->from(array("cidade"), array("*"))
                    ->where("nomeCidade LIKE '%$nomeCidade%'")
                    ->order("nomeCidade ASC");
        
        $result = $cidade->getAdapter()->fetchAll($sql);

        return $result;
    }
        
    public static function inserir($dados) {
        $cidade = new Cidade();
        
        $cidade->getAdapter()->beginTransaction();
        
        $data = array(
            'nomeCidade' => $dados['nomeCidade'],
            'codUf' => $dados['codUf']
        );
        
        try {
            $cidade->insert($data);
            $result = true;
        } catch (Zend_Exception $e) {
            $cidade->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar a cidade" . $e->getMessage());
        }
        
        $cidade->getAdapter()->commit();
        
        return $result;
    }
    
    public static function editar($dados) {
        $this->getAdapter()->beginTransaction();
        
        $data = array(
            'nomeCidade' => $dados['nomeCidade'],
            'codUf' => $dados['codUf']
        );
        
        try {
            $where = $this->getAdapter()->quoteInto("codCidade = ?", $dados['codCidade']);
            $this->update($data, $where);
        } catch (Zend_Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados da cidade" . $e->getMessage());
        }
        
        $this->getAdapter()->commit();
    }    
}