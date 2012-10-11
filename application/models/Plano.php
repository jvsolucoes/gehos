<?php
/**
 * Description of Acao
 *
 * @author luciana
 */
class Plano extends Zend_Db_Table_Abstract {

    protected $_name = "plano";
    protected $_primary = array("codPlano");
    protected $_dependentTables = array('Pessoa');    
    
    protected $_referenceMap = array(
        'Convenio' => array(
            'refTableClass' => 'Convenio',
            'refColumns' => 'codAnsConvenio',
            'columns' => 'codAnsConvenio',
            'onDelete' => self::CASCADE
        ),
        'Unidade' => array(
            'refTableClass' => 'Unidade',
            'refColumns' => 'codUnidade',
            'columns' => 'codUnidade',
            'onDelete' => self::CASCADE
        )
    );
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array('plano'), array("*"))
                    ->order("nomePlano ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function buscar($nomePlano){
        $plano = new Plano();
        
        $sql = $plano->getAdapter()->select()
                    ->from(array("plano"), array("*"))
                    ->where("nomePlano = ?", $nomePlano);
                
        $result = $plano->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $plano->getAdapter()->fetchRow($sql);

        return $result;
    }    
    
    public static function buscarId($codPlano){
        $plano = new Plano();
        
        $sql = $plano->getAdapter()->select()
                    ->from(array("plano"), array("*"))
                    ->where("codPlano = ?", $codPlano);
                
        $result = $plano->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $plano->getAdapter()->fetchRow($sql);

        return $result;
    }
    
    public static function listarAutocomplete($nomePlano) {
        $plano = new Plano();
        
        $sql = $plano->getAdapter()->select()
                    ->from(array("plano"), array("*"))
                    ->where("nomePlano LIKE '%$nomePlano%'")
                    ->order("nomePlano ASC");
        
        $result = $plano->getAdapter()->fetchAll($sql);

        return $result;
    }
        
    public static function inserir($dados) {
        $plano = new Plano();
        
        $plano->getAdapter()->beginTransaction();
        
        $data = array(
            'codAnsConvenio' => $dados['codAnsConvenio'],
            'codUnidade' => $dados['codUnidade'],
            'nomePlano' => $dados['nomePlano']
        );
        
        try {
//            var_dump($data);
//            die();
            $plano->insert($data);
            $result = true;
        } catch (Zend_Exception $e) {
            $plano->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar o plano" . $e->getMessage());
        }
        
        $plano->getAdapter()->commit();
        
        return $result;
    }
    
    public static function editar($dados) {
        $this->getAdapter()->beginTransaction();
        
        $data = array(
            'codAnsConvenio' => $dados['codAnsConvenio'],
            'codUnidade' => $dados['codUnidade'],
            'nomePlano' => $dados['nomePlano']
        );
        
        try {
            $where = $this->getAdapter()->quoteInto("codPlano = ?", $dados['codPlano']);
            $this->update($data, $where);
        } catch (Zend_Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados do plano" . $e->getMessage());
        }
        
        $this->getAdapter()->commit();
    }    
}