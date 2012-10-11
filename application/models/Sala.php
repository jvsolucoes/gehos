<?php
/**
 * Description of Sala
 *
 * @author victor
 */
class Sala extends Zend_Db_Table_Abstract {

    protected $_name = "unidadesala";
    protected $_primary = array("codUnidadeSala");
    
    protected $_referenceMap = array(
        'Unidade' => array(
            'refTableClass' => 'Unidade',
            'refColumns' => 'codUnidade',
            'columns' => 'codUnidade',
            'onDelete' => self::CASCADE
        )
    );
    
    public function listarAutocomplete($codUnidade, $nomeSala) {
        $sql = $this->getAdapter()->select()
                    ->from(array("unidadesala"), array("*"))
                    ->where("codUnidade = ?", $codUnidade)
                    ->where("nomeSala LIKE '%$nomeSala%'")
                    ->order("nomeSala ASC");
        
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public function buscar($codUnidadeSala) {
        $sql = $this->getAdapter()->select()
                    ->from(array("unidadesala"), array("*"))
                    ->where("codUnidadeSala = ?", $codUnidadeSala);
        
        $result = $this->getAdapter()->fetchRow($sql);

        return $result;
    }
    
    public function inserir($dados) {
        
        $this->getAdapter()->beginTransaction();
        
        $data = array(
            'codUnidade' => $dados['codUnidade'],
            'nomeSala' => $dados['nomeSala'],
        );
        
        try {
            $this->insert($data);
            $result = true;
        } catch (Zend_Exception $e) {
            $result = false;
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar a ação" . $e->getMessage());
        }
        
        $this->getAdapter()->commit();
        
        return $result;
    }
    
    public function editar($dados) {
        $this->getAdapter()->beginTransaction();
        
        $data = array(
            'codUnidade' => $dados['codUnidade'],
            'nomeSala' => $dados['nomeSala'],
        );
        
        try {
            $where = $this->getAdapter()->quoteInto("codUnidadeSala = ?", $dados['codUnidadeSala']);
            $this->update($data, $where);
            $result = true;
        } catch (Zend_Exception $e) {
            $result = false;
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados da sala" . $e->getMessage());
        }
        
        $this->getAdapter()->commit();
        return $result;
    }
    
    public static function excluir($codAcao) {
        $acao = new Acao();
        $moduloAcao = new ModuloAcao();
        $pama = new PerfilAplicacaoModuloAcao();
        
        $wherePama = $pama->getAdapter()->quoteInto("codAcao = ?", $codAcao);
        $resultPama = $pama->delete($wherePama);
        
        $whereMa = $moduloAcao->getAdapter()->quoteInto("codAcao = ?", $codAcao);
        $resultMa = $moduloAcao->delete($whereMa);
            
        $where = $acao->getAdapter()->quoteInto("codAcao = ?", $codAcao);
        $result = $acao->delete($where);

        return $result;
    }
    
}