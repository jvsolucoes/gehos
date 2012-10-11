<?php
/**
 * Description of Especialidade
 *
 * @author luciana
 */
class Especialidade extends Zend_Db_Table_Abstract {

    protected $_name = "especialidade";
    protected $_primary = array("codEspecialidade");
//    protected $_dependentTables = array('EspecialidadeProfissional');
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array("especialidade"), array("*"))
                    ->order("nomeEspecialidade ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function listarAutocomplete($nomeEspecialidade) {
        $especialidade = new Especialidade();
        
        $sql = $especialidade->getAdapter()->select()
                    ->from(array("especialidade"), array("*"))
                    ->where("nomeEspecialidade LIKE '%$nomeEspecialidade%'")
                    ->order("nomeEspecialidade ASC");
        
//        $result = $acao->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $especialidade->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function buscar($codEspecialidade) {
        $especialidade = new Especialidade();
        
        $sql = $especialidade->getAdapter()->select()
                    ->from(array("especialidade"), array("*"))
                    ->where("codEspecialidade = ?", $codEspecialidade);
        
//        $result = $acao->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $especialidade->getAdapter()->fetchRow($sql);

        return $result;
    }
    
    public static function buscarId($codEspecialidade){
        $especialidade = new Especialidade();
        
        $sql = $especialidade->getAdapter()->select()
                    ->from(array("especialidade"), array("*"))
                    ->where("codEspecialidade = ?", $codEspecialidade);
                
        $result = $especialidade->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $especialidade->getAdapter()->fetchRow($sql);

        return $result;
    }
        
    public static function inserir($dados) {
        $especialidade = new Especialidade();
        
        $especialidade->getAdapter()->beginTransaction();
        
        $data = array(
            'nomeEspecialidade' => $dados['nomeEspecialidade']
        );
        
        try {
            $especialidade->insert($data);
            $result = true;
        } catch (Zend_Exception $e) {
            $especialidade->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar a especialidade" . $e->getMessage());
        }
        
        $especialidade->getAdapter()->commit();
        
        return $result;
    }
    
    public static function editar($dados) {
        $especialidade = new Especialidade();
        $especialidade->getAdapter()->beginTransaction();
        
        $data = array(
            'nomeEspecialidade' => $dados['nomeEspecialidade']
        );
        
        try {
            $where = $especialidade->getAdapter()->quoteInto("codEspecialidade = ?", $dados['codEspecialidade']);
            $especialidade->update($data, $where);
            $result = true;
        } catch (Zend_Exception $e) {
            $especialidade->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados da especialidade" . $e->getMessage());
        }
        
        $especialidade->getAdapter()->commit();
        return $result;
    }
    
    public static function ativarDesativar($codEspecialidade, $status) {
        $especialidade = new Especialidade();
        $especialidade->getAdapter()->beginTransaction();

        $data = array(
            'ativoEspecialidade' => $status
        );

        try {
            $where = $especialidade->getAdapter()->quoteInto("codEspecialidade = ?", $codEspecialidade);
            $especialidade->update($data, $where);
            $result = true;
        } catch (Zend_Exception $e) {
            $especialidade->getAdapter()->rollBack();
            $msg = $status == '0' ? "N&atilde;o foi possível desativar a especialidade " : "N&atilde;o foi possível ativar a especialidade";
            throw new Zend_Exception($msg . $e->getMessage());
        }

        $especialidade->getAdapter()->commit();
        return $result;
    }
    
}