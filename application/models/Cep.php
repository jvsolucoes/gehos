<?php
/**
 * Description of Acao
 *
 * @author luciana
 */
class Cep extends Zend_Db_Table_Abstract {

    protected $_name = "cep";
    protected $_primary = array("codCep");
    protected $_dependentTables = array('Pessoa');
    
    protected $_referenceMap = array(
        'Bairro' => array(
            'refTableClass' => 'Bairro',
            'refColumns' => 'codBairro',
            'columns' => 'codBairro',
            'onDelete' => self::CASCADE
        ),
        'Cidade' => array(
            'refTableClass' => 'Cidade',
            'refColumns' => 'codCidade',
            'columns' => 'codCidade',
            'onDelete' => self::CASCADE
        )
    );
    
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array("cep"), array("*"))
                    ->order("codCep ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function listarAutocomplete($codCep) {
        $cep = new Cep();
        
        $sql = $cep->getAdapter()->select()
                    ->from(array("cep"), array("*"))
                    ->where("codCep LIKE '%$codCep%'")
                    ->order("codCep ASC");
        
        $result = $cep->getAdapter()->fetchAll($sql);

        return $result;
    }
        
    public static function inserir($dados) {
        $cep = new Cep();
        
        $cep->getAdapter()->beginTransaction();
        
        $data = array(
            'codCep' => $dados['codCep'],
            'endCep' => $dados['endCep'],
            'codCidade' => $dados['codCidade'],
            'codBairro' => $dados['codBairro']
        );
        
        try {
            $cep->insert($data);
            $result = true;
        } catch (Zend_Exception $e) {
            $cep->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar o cep" . $e->getMessage());
        }
        
        $cep->getAdapter()->commit();
        
        return $result;
    }
    
    public static function editar($dados) {
        $this->getAdapter()->beginTransaction();
        
        $data = array(
            'codCep' => $dados['codCep'],
            'endCep' => $dados['endCep'],
            'codCidade' => $dados['codCidade'],
            'codBairro' => $dados['codBairro']
        );
        
        try {
            $where = $this->getAdapter()->quoteInto("codCep = ?", $dados['codCep']);
            $this->update($data, $where);
        } catch (Zend_Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados do cep" . $e->getMessage());
        }
        
        $this->getAdapter()->commit();
    }    
}