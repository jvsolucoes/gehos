<?php
/**
 * Description of Acao
 *
 * @author luciana
 */
class Convenio extends Zend_Db_Table_Abstract {

    protected $_name = "convenio";
    protected $_primary = array("codConvenio");
    protected $_dependentTables = array('ConvenioEmpresa', 'ConvenioUnidade');
    
    protected $_modelConvenioUnidade;
    protected $_modelConvenioEmpresa;
    
    private function inicializar() {
        $this->_modelConvenioUnidade = new PerfilAplicacaoModuloAcao();
        $this->_modelConvenioEmpresa = new ConvenioEmpresa();
    }
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array("convenio"), array("*"))
                    ->order("razaoSocialConvenio ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function listarAutocomplete($nomeConvenio) {
        $convenio = new Convenio();
        
        $sql = $convenio->getAdapter()->select()
                    ->from(array("convenio"), array("*"))
                    ->where("razaoSocialConvenio LIKE '%$nomeConvenio%'")
                    ->order("razaoSocialConvenio ASC");
        
        $result = $convenio->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public function inserir($dados) {
        $this->inicializar();
        $this->getAdapter()->beginTransaction();
        
        $dados['codConvenio'] = $this->add($dados);
        
        if ($dados['codConvenio']) {
            $retorno = $this->_modelConvenioEmpresa->inserir($dados);
            $result = ($retorno) ? true : false ;
        }
        
        $this->getAdapter()->commit();
        
        return $result;
    }
    
    private function add($dados) {
        
        $numLogradouroConvenio = ($dados['numLogradouroConvenio'] == "") ? null : $dados['numLogradouroConvenio']; 
        
        $data = array(
            'codConvenio' => $dados['codConvenio'],
            'codAnsConvenio' => $dados['codAnsConvenio'],
            'razaoSocialConvenio' => $dados['razaoSocialConvenio'],
            'nomeFantasiaConvenio' => $dados['nomeFantasiaConvenio'],
            'cnpjConvenio' => Functions::replace($dados['cnpjConvenio']),
            'logradouroConvenio' => $dados['logradouroConvenio'],
            'numlogradouroConvenio' => $numLogradouroConvenio,
            'complementoConvenio' => $dados['complementoConvenio'],
            'bairroConvenio' => $dados['bairroConvenio'],
            'ufConvenio' => $dados['ufConvenio'],
            'cepConvenio' => Functions::replace($dados['cepConvenio']),
        );
        
        try {
            $this->insert($data);
            $idConvenio = $this->getAdapter()->lastInsertId();
        } catch (Zend_Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar os dados do convênio" . $e->getMessage());
        }
        
        return $idConvenio;
        
    }
    
    public static function buscar($codConvenio) {
        $convenio = new Convenio();
        
        $sql = $convenio->getAdapter()->select()
                    ->from(array("convenio"), array("*"))
                    ->where("codAnsConvenio = ?", $codConvenio);
        
        $result = $convenio->getAdapter()->fetchRow($sql);

        return $result;
    }
    
    public static function editar($dados) {
        $convenio = new Convenio();
        $convenio->getAdapter()->beginTransaction();
        
        $numLogradouroConvenio = ($dados['numLogradouroConvenio'] == "") ? null : $dados['numLogradouroConvenio']; 
        
        $data = array(
            'codAnsConvenio' => $dados['codAnsConvenio'],
            'razaoSocialConvenio' => $dados['razaoSocialConvenio'],
            'cnpjConvenio' => Functions::replace($dados['cnpjConvenio']),
            'logradouroConvenio' => $dados['logradouroConvenio'],
            'numLogradouroConvenio' => $numLogradouroConvenio,
            'complementoConvenio' => $dados['complementoConvenio'],
            'bairroConvenio' => $dados['bairroConvenio'],
            'ufConvenio' => $dados['ufConvenio'],
            'cepConvenio' => Functions::replace($dados['cepConvenio']),
        );
        
        try {
            $where = $convenio->getAdapter()->quoteInto("codAnsConvenio = ?", $dados['codAnsConvenio']);
            $convenio->update($data, $where);
            $result = true;
        } catch (Zend_Exception $e) {
            $convenio->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados do convênio" . $e->getMessage());
        }
        
        $convenio->getAdapter()->commit();
        
        return $result;
    }    
    
    public static function ativarDesativar($codConvenio, $status) {
        $convenio = new Convenio();
        $convenio->getAdapter()->beginTransaction();

        $data = array(
            'ativoConvenio' => $status
        );

        try {
            $where = $convenio->getAdapter()->quoteInto("codAnsConvenio = ?", $codConvenio);
            $convenio->update($data, $where);
            $result = true;
        } catch (Zend_Exception $e) {
            $convenio->getAdapter()->rollBack();
            $msg = $status == '0' ? "N&atilde;o foi possível desativar o convenio " : "N&atilde;o foi possível ativar o convenio";
            throw new Zend_Exception($msg . $e->getMessage());
        }

        $convenio->getAdapter()->commit();
        return $result;
    }
}