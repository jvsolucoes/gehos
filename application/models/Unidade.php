<?php
/**
 * Description of Unidade
 *
 * @author luciana
 */
class Unidade extends Zend_Db_Table_Abstract {

    protected $_name = "unidade";
    protected $_primary = array("codUnidade");
    protected $_dependentTables = array('Usuario');
    
    protected $_referenceMap = array(
        'Empresa' => array(
            'refTableClass' => 'Empresa',
            'refColumns' => 'codEmpresa',
            'columns' => 'codEmpresa',
            'onDelete' => self::CASCADE
        )
    );
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array("unidade"), array("*"))
                    ->order("nomeUnidade ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function listarAutocomplete($nomeUnidade) {
        $unidade = new Unidade();
        
        $sql = $unidade->getAdapter()->select()
                    ->from(array("unidade"), array("*"))
                    ->where("nomeUnidade LIKE '%$nomeUnidade%'")
                    ->order("nomeUnidade ASC");
        
        $result = $unidade->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public function listarPorEmpresa($codEmpresa) {
        $sql = $this->getAdapter()->select()
                    ->from(array("unidade"), array("*"))
                    ->where("codEmpresa = ?", $codEmpresa)
                    ->order("nomeUnidade ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function buscar($codUnidade) {
        $unidade = new Unidade();

        $sql = $unidade->getAdapter()->select()
                ->from(array("unidade"), array("*"))
                ->where("codUnidade = ?", $codUnidade);

        $result = $unidade->getAdapter()->fetchRow($sql);

        return $result;
    }
    
    public static function buscarId($codUnidade){
        $unidade = new Unidade();
        
        $sql = $unidade->getAdapter()->select()
                    ->from(array("unidade"), array("*"))
                    ->where("codUnidade = ?", $codUnidade);
                
        $result = $unidade->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $unidade->getAdapter()->fetchRow($sql);

        return $result;
    }
        
    public static function inserir($dados) {
        $unidade = new Unidade();
        
        $unidade->getAdapter()->beginTransaction();
        
        $codEmpresa = Empresa::buscarNome($dados['nomeEmpresaUnidade']);
        $numeroEndUnidade = ($dados['numeroEndUnidade'] == "") ? null : $dados['numeroEndUnidade'];        
        
        $data = array(
            'codEmpresa' => 4,
            'nomeUnidade' => $dados['nomeUnidade'],
            'cnpjUnidade' => Functions::replace($dados['cnpjUnidade']),
            'cepUnidade' => Functions::replace($dados['cepUnidade']),
            'enderecoUnidade' => $dados['enderecoUnidade'],
            'numeroEndUnidade' => $numeroEndUnidade,
            'bairroUnidade' => $dados['bairroUnidade'],
            'cidadeUnidade' => $dados['cidadeUnidade'],
            'ufUnidade' => $dados['ufUnidade'],
            'fone1Unidade' => $dados['fone1Unidade'],
            'fone2Unidade' => $dados['fone2Unidade'],
            'faxUnidade' => $dados['faxUnidade']
        );
        
        try {
            $unidade->insert($data);
            $result = true;
        } catch (Zend_Exception $e) {
            $unidade->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar a unidade" . $e->getMessage());
        }
        
        $unidade->getAdapter()->commit();
        return $result;
    }
    
    public static function editar($dados) {
        $this->getAdapter()->beginTransaction();
        
        $data = array(
            'codEmpresa' => $dados['codEmpresa'],
            'nomeUnidade' => $dados['nomeUnidade'],
            'cnpjUnidade' => $dados['cnpjUnidade'],
            'cepUnidade' => $dados['cepUnidade'],
            'enderecoUnidade' => $dados['enderecoUnidade'],
            'numeroUnidade' => $dados['numeroUnidade'],
            'bairroUnidade' => $dados['bairroUnidade'],
            'cidadeUnidade' => $dados['cidadeUnidade'],
            'ufUnidade' => $dados['ufUnidade'],
            'fone1Unidade' => $dados['fone1Unidade'],
            'fone2Unidade' => $dados['fone2Unidade'],
            'faxUnidade' => $dados['faxUnidade']
        );
        
        try {
            $where = $this->getAdapter()->quoteInto("codUnidade = ?", $dados['codUnidade']);
            $this->update($data, $where);
            $result = true;
        } catch (Zend_Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados da unidade" . $e->getMessage());
        }
        
        $this->getAdapter()->commit();
        return $result;
    }    
    
    public static function ativarDesativar($codUnidade, $status) {
        $unidade = new Unidade();
        $unidade->getAdapter()->beginTransaction();

        $data = array(
            'ativoUnidade' => $status
        );

        try {
            $where = $unidade->getAdapter()->quoteInto("codUnidade = ?", $codUnidade);
            $unidade->update($data, $where);
            $result = true;
        } catch (Zend_Exception $e) {
            $unidade->getAdapter()->rollBack();
            $msg = $status == '0' ? "N&atilde;o foi possível desativar a unidade " : "N&atilde;o foi possível ativar a unidade";
            throw new Zend_Exception($msg . $e->getMessage());
        }

        $unidade->getAdapter()->commit();
        return $result;
    }
}