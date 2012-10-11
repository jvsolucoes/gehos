<?php
/**
 * Description of Acao
 *
 * @author luciana
 */
class Empresa extends Zend_Db_Table_Abstract {

    protected $_name = "empresa";
    protected $_primary = array("codEmpresa");
    protected $_dependentTables = array('Unidade');    
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array("empresa"), array("*"))
                    ->order("nomeFantasiaEmpresa ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function buscarNome($nome) {
        $empresa = new Empresa();
        $sql = $empresa->getAdapter()->select()
                                ->from(array("empresa"),
                                        array('*'))
                                ->where("nomeFantasiaEmpresa = '" . $nome . "'");
        
        $result = $empresa->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $empresa->getAdapter()->fetchRow($sql);

        return $result;
    }
    
    public static function buscar($codEmpresa) {
        $empresa = new Empresa();

        $sql = $empresa->getAdapter()->select()
                ->from(array("empresa"), array("*"))
                ->where("codEmpresa = ?", $codEmpresa);

        $result = $empresa->getAdapter()->fetchRow($sql);

        return $result;
    }
    
    public static function listarAutocomplete($nomeEmpresa) {
        $empresa = new Empresa();
        
        $sql = $empresa->getAdapter()->select()
                    ->from(array("empresa"), array("*"))
                    ->where("nomeFantasiaEmpresa LIKE '%$nomeEmpresa%'")
                    ->order("nomeFantasiaEmpresa ASC");
        
        $result = $empresa->getAdapter()->fetchAll($sql);

        return $result;
    }
        
    public static function inserir($dados) {
        $empresa = new Empresa();
        
        $empresa->getAdapter()->beginTransaction();
        
        $data = array(
            'razaoSocialEmpresa' => $dados['razaoSocialEmpresa'],
            'nomeFantasiaEmpresa' => $dados['nomeFantasiaEmpresa']
        );
        
        try {
            $empresa->insert($data);
            $result = true;
        } catch (Zend_Exception $e) {
            $empresa->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar a empresa" . $e->getMessage());
        }
        
        $empresa->getAdapter()->commit();
        
        return $result;
    }
    
    public static function editar($dados) {
        $empresa = new Empresa();
        $empresa->getAdapter()->beginTransaction();
        
        $data = array(
            'razaoSocialEmpresa' => $dados['razaoSocialEmpresa'],
            'nomeFantasiaEmpresa' => $dados['nomeFantasiaEmpresa']
        );
        
        try {
            $where = $empresa->getAdapter()->quoteInto("codEmpresa = ?", $dados['codEmpresa']);
            $empresa->update($data, $where);
            $result = true;
        } catch (Zend_Exception $e) {
            $empresa->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados da empresa" . $e->getMessage());
        }
        
        $empresa->getAdapter()->commit();
        
        return $result;
    }    
    
    public static function ativarDesativar($codEmpresa, $status) {
        $empresa = new Empresa();
        $empresa->getAdapter()->beginTransaction();

        $data = array(
            'ativoEmpresa' => $status
        );

        try {
            $where = $empresa->getAdapter()->quoteInto("codEmpresa = ?", $codEmpresa);
            $empresa->update($data, $where);
            $result = true;
        } catch (Zend_Exception $e) {
            $empresa->getAdapter()->rollBack();
            $msg = $status == '0' ? "N&atilde;o foi possível desativar a empresa " : "N&atilde;o foi possível ativar a empresa";
            throw new Zend_Exception($msg . $e->getMessage());
        }

        $empresa->getAdapter()->commit();
        return $result;
    }
}