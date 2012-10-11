<?php
/**
 * Description of Pessoa
 *
 * @author luciana
 */
class Pessoa extends Zend_Db_Table_Abstract {

    protected $_name = "pessoa";
    protected $_primary = array("codPessoa");
    protected $_referenceMap = array(
        'Cep' => array(
            'refTableClass' => 'Cep',
            'refColumns' => 'codCep',
            'columns' => 'codCep',
            'onDelete' => self::CASCADE
        ),
        'Cidade' => array(
            'refTableClass' => 'Cidade',
            'refColumns' => 'codCidade',
            'columns' => 'codCidade',
            'onDelete' => self::CASCADE
        ),
        'Plano' => array(
            'refTableClass' => 'Plano',
            'refColumns' => 'codPlano',
            'columns' => 'codPlano',
            'onDelete' => self::CASCADE
        ),
        'Convenio' => array(
            'refTableClass' => 'Convenio',
            'refColumns' => 'codAnsConvenio',
            'columns' => 'codAnsConvenio',
            'onDelete' => self::CASCADE
        )
    );

    public function listar() {
        $sql = $this->getAdapter()->select()
                ->from(array("pessoa"), array("*"))
                ->order("nomePessoa ASC");

        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }

    public static function listarAutocomplete($nomePessoa) {
        $pessoa = new Pessoa();

        $sql = $pessoa->getAdapter()->select()
                ->from(array("p" => "pessoa"), array("p.*"))
                ->where("p.nomePessoa LIKE '%$nomePessoa%'")
                ->order("p.nomePessoa ASC");

        $result = $pessoa->getAdapter()->fetchAll($sql);

        return $result;
    }

    public static function buscar($codPessoa) {
        $pessoa = new Pessoa();

        $sql = $pessoa->getAdapter()->select()
                ->from(array("pessoa"), array("*"))
                ->where("codPessoa = ?", $codPessoa);

        $result = $pessoa->getAdapter()->fetchRow($sql);

        return $result;
    }

    public static function inserir($dados) {
        $pessoa = new Pessoa();

        $pessoa->getAdapter()->beginTransaction();

        $numEndPessoa = ($dados['numEndPessoa'] == "") ? null : $dados['numEndPessoa'];       
        
        $data = array(
            'codCep' => $dados['codCep'],
            'codCidade' => $dados['codCidade'],
            'tipoPessoa' => $dados['tipoPessoa'],
            'nomePessoa' => $dados['nomePessoa'],
            'sexoPessoa' => $dados['sexoPessoa'],
            'dtNascPessoa' => Functions::formatarData($dados['dtNascPessoa']),
            'cpfPessoa' => $dados['cpfPessoa'],
            'rgPessoa' => $dados['rgPessoa'],
            'fone1Pessoa' => $dados['fone1Pessoa'],
            'fone2Pessoa' => $dados['fone2Pessoa'],
            'emailPessoa' => $dados['emailPessoa'],
            'endPessoa' => $dados['endPessoa'],
            'numEndPessoa' => $numEndPessoa,
            'complEndPessoa' => $dados['complEndPessoa'],
            'bairroPessoa' => $dados['bairroPessoa'],
            'ufEndPessoa' => $dados['codUf'],
            'nacionalidadePessoa' => $dados['nacionalidadePessoa'],
            'naturalidadePessoa' => $dados['naturalidadePessoa'],
            'estadoCivilPessoa' => $dados['estadoCivilPessoa'],
            'profissaoPessoa' => $dados['profissaoPessoa'],
            'nomePaiPessoa' => $dados['nomePaiPessoa'],
            'nomeMaePessoa' => $dados['nomeMaePessoa'],
            'quemIndicouPessoa' => $dados['quemIndicouPessoa']
        );

        try {
            $pessoa->insert($data);
        } catch (Zend_Exception $e) {
            $pessoa->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar o paciente" . $e->getMessage());
        }

        $pessoa->getAdapter()->commit();
        return true;
    }

    public static function editar($dados) {
        $pessoa = new Pessoa();
        $pessoa->getAdapter()->beginTransaction();
        
        $numEndPessoa = ($dados['numEndPessoa'] == "") ? null : $dados['numEndPessoa'];
        
        $data = array(
            'codCep' => $dados['codCep'],
            'codCidade' => $dados['codCidade'],
            'tipoPessoa' => $dados['tipoPessoa'],
            'nomePessoa' => $dados['nomePessoa'],
            'sexoPessoa' => substr($dados['sexoPessoa'], 0, 1),
            'dtNascPessoa' => Functions::formatarData($dados['dtNascPessoa']),
            'cpfPessoa' => $dados['cpfPessoa'],
            'rgPessoa' => $dados['rgPessoa'],
            'fone1Pessoa' => $dados['fone1Pessoa'],
            'fone2Pessoa' => $dados['fone2Pessoa'],
            'emailPessoa' => $dados['emailPessoa'],
            'endPessoa' => $dados['endPessoa'],
            'numEndPessoa' => $numEndPessoa,
            'complEndPessoa' => $dados['complEndPessoa'],
            'bairroPessoa' => $dados['bairroPessoa'],
            'ufEndPessoa' => $dados['codUf'],
            'nacionalidadePessoa' => $dados['nacionalidadePessoa'],
            'naturalidadePessoa' => $dados['naturalidadePessoa'],
            'estadoCivilPessoa' => $substr($dados['estadoCivilPessoa'], 0, 1),
            'profissaoPessoa' => $dados['profissaoPessoa'],
            'nomePaiPessoa' => $dados['nomePaiPessoa'],
            'nomeMaePessoa' => $dados['nomeMaePessoa'],
            'quemIndicouPessoa' => $dados['quemIndicouPessoa']
        );

        try {
            $where = $pessoa->getAdapter()->quoteInto("codPessoa = ?", $dados['codPessoa']);
            $pessoa->update($data, $where);
            $result = true;
        } catch (Zend_Exception $e) {
            $pessoa->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados do paciente" . $e->getMessage());
        }

        $pessoa->getAdapter()->commit();
        return $result;
    }

    public static function ativarDesativar($codPessoa, $status) {
        $pessoa = new Pessoa();
        $pessoa->getAdapter()->beginTransaction();

        $data = array(
            'ativoPessoa' => $status
        );

        try {
            $where = $pessoa->getAdapter()->quoteInto("codPessoa = ?", $codPessoa);
            $pessoa->update($data, $where);
            $result = true;
        } catch (Zend_Exception $e) {
            $pessoa->getAdapter()->rollBack();
            $msg = $status == '0' ? "N&atilde;o foi possível desativar o paciente " : "N&atilde;o foi possível ativar o paciente";
            throw new Zend_Exception($msg . $e->getMessage());
        }

        $pessoa->getAdapter()->commit();
        return $result;
    }

}