
<?php
/**
 * Description of Profissional
 *
 * @author luciana
 */
class Profissional extends Zend_Db_Table_Abstract {

    protected $_name = "profissional";
    protected $_primary = array("codProfissional");
//    protected $_dependentTables = array('profissional');
    protected $_referenceMap = array(
        'TipoProfissional' => array(
            'refTableClass' => 'TipoProfissional',
            'refColumns' => 'codTipoProfissional',
            'columns' => 'codTipoProfissional',
            'onDelete' => self::CASCADE
        ),
        'SubTipoProfissional' => array(
            'refTableClass' => 'TipoProfissional',
            'refColumns' => 'codTipoProfissional',
            'columns' => 'codTipoProfissional',
            'onDelete' => self::CASCADE
        )
    );
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array("profissional"), array("*"))
                    ->order("codProfissional ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function listarAutocomplete($nomeProfissional) {
        $profissional = new Profissional();
        
        $sql = $profissional->getAdapter()->select()
                    ->from(array("profissional"), array("*"))
                    ->where("nomeProfissional LIKE '%$nomeProfissional%'")
                    ->order("nomeProfissional ASC");
        
        $result = $profissional->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public static function buscar($codProfissional) {
        $profissional = new Profissional();
        
        $sql = $profissional->getAdapter()->select()
                    ->from(array("profissional"), array("*"))
                    ->where("codProfissional = ?", $codProfissional);
        
        $result = $profissional->getAdapter()->fetchRow($sql);

        return $result;
    }
        
    public static function inserir($dados) {
        $profissional = new Profissional();
        
        $profissional->getAdapter()->beginTransaction();
        
        $data = array(
            'cpfProfissional' => $dados['cpfProfissional'],
            'codSubTipoProfissional' => $dados['codSubTipoProfissional'],
            'codTipoProfissional' => $dados['codTipoProfissional'],
            'codConselhoProfissional' => $dados['codConselhoProfissional'],
            'numConselhoProfissional' => $dados['numConselhoProfissional'],
            'ufConselhoProfissional' => $dados['ufConselhoProfissional'],
            'nomeProfissional' => $dados['nomeProfissional'],
            'dtNascimentoProfissional' => $dados['dtNascimentoProfissional'],
            'cepProfissional' => $dados['cepProfissional'],
            'endProfissional' => $dados['endProfissional'],
            'numEndProfissional' => $dados['numEndProfissional'],
            'cidadeEndProfissional' => $dados['cidadeEndProfissional'],
            'bairroEndProfissional' => $dados['bairroEndProfissional'],
            'complEndProfissional' => $dados['complEndProfissional'],
            'fone1Profissional' => $dados['fone1Profissional'],
            'fone2Profissional' => $dados['fone2Profissional'],
            'emailProfissional' => $dados['emailProfissional'],
            'vinculoProfissional' => $dados['vinculoProfissional'],
            'codCooperativa' => $dados['codCooperativa'],
            'sexoProfissional' => $dados['sexoProfissional'],
            'matriculaProfissional' => $dados['matriculaProfissional']
        );
        
        try {
            $profissional->insert($data);
            $result = true;
        } catch (Zend_Exception $e) {
            $profissional->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar o dados do profissional" . $e->getMessage());
        }
        
        $profissional->getAdapter()->commit();
        
        return $result;
    }
    
    public static function editar($dados) {
        $profissional = new Profissional();
        $profissional->getAdapter()->beginTransaction();
        
        $data = array(
            'cpfProfissional' => $dados['cpfProfissional'],
            'codSubTipoProfissional' => $dados['codSubTipoProfissional'],
            'codTipoProfissional' => $dados['codTipoProfissional'],
            'codConselhoProfissional' => $dados['codConselhoProfissional'],
            'numConselhoProfissional' => $dados['numConselhoProfissional'],
            'ufConselhoProfissional' => $dados['ufConselhoProfissional'],
            'nomeProfissional' => $dados['nomeProfissional'],
            'dtNascimentoProfissional' => $dados['dtNascimentoProfissional'],
            'cepProfissional' => $dados['cepProfissional'],
            'endProfissional' => $dados['endProfissional'],
            'numEndProfissional' => $dados['numEndProfissional'],
            'cidadeEndProfissional' => $dados['cidadeEndProfissional'],
            'bairroEndProfissional' => $dados['bairroEndProfissional'],
            'complEndProfissional' => $dados['complEndProfissional'],
            'fone1Profissional' => $dados['fone1Profissional'],
            'fone2Profissional' => $dados['fone2Profissional'],
            'emailProfissional' => $dados['emailProfissional'],
            'vinculoProfissional' => $dados['vinculoProfissional'],
            'codCooperativa' => $dados['codCooperativa'],
            'sexoProfissional' => $dados['sexoProfissional'],
            'matriculaProfissional' => $dados['matriculaProfissional']
        );
        
        try {
            $where = $profissional->getAdapter()->quoteInto("codProfissional = ?", $dados['codProfissional']);
            $profissional->update($data, $where);
        } catch (Zend_Exception $e) {
            $profissional->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados do profissional" . $e->getMessage());
        }
        
        $profissional->getAdapter()->commit();
    }    
}