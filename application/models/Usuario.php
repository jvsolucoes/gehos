<?php
/**
 * Description of Usuario
 *
 * @author victor
 */
class Usuario extends Zend_Db_Table_Abstract {
    
    protected $_name = "usuario";
    protected $_primary = array("codUsuario");
    
    protected $_referenceMap = array(
        'Perfil' => array(
            'refTableClass' => 'Perfil',
            'refColumns' => 'codPerfil',
            'columns' => 'codPerfil',
            'onDelete' => self::CASCADE
        ),
        'Unidade' => array(
            'refTableClass' => 'Unidade',
            'refColumns' => 'codUnidade',
            'columns' => 'codUnidade',
            'onDelete' => self::CASCADE
        )
    );
    
    public static function isLogged() {
        return Zend_Auth::getInstance()->hasIdentity();
    }
    
    public function listarAutocomplete($nomeUsuario) {
        
        $sql = $this->getAdapter()->select()
                    ->from(array("u" => "usuario"), array("u.*"))
                    ->where("u.nomeUsuario LIKE '%$nomeUsuario%'")
                    ->order("u.nomeUsuario ASC");
        
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }
    
    public function buscar($codUsuario) {
        $sql = $this->getAdapter()->select()
                    ->from(array("u" => "usuario"), array("u.*"))
//                    ->join(array("p" => "perfil"), "p.codPerfil = u.codPerfil",
//                            array("perfil" => "p.codPerfil", "p.nomePerfil"))
//                    ->joinLeft(array("un" => "unidade"), "u.codUnidade = un.codUnidade",
//                            array("unidade" => "un.codUnidade", "un.nomeUnidade", "empresa" => "un.codEmpresa"))
//                    ->join(array("e" => "empresa"), "e.codEmpresa = un.codEmpresa",
//                            array("e.*"))
                    ->where("u.codUsuario = ?", $codUsuario);
//        echo $sql;
//        die();
//        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchRow($sql);

        return $result;
    }
    
    public function listar() {
        $sql = $this->getAdapter()->select()
                    ->from(array("usuario1"), array("*"))
                    ->order("nome ASC");
        
        $result = $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->getAdapter()->fetchAll($sql);

        return $result;
    }

    public static function validarModulo($app, $model) {
        $usuario = Zend_Auth::getInstance()->getIdentity();
        
        $permissao = PerfilAplicacaoModuloAcao::buscarPermissoesModulo($usuario->codPerfil, $app, $model);
        
        if(!$permissao) {
            return false;
        }

        return true;
    }

    public static function validarAcao($app, $model, $action) {
        $usuario = Zend_Auth::getInstance()->getIdentity();

        $aplicacao = Aplicacao::buscarNome($app);
        $modulo = Modulo::buscarNome($model);
        $acao = Acao::buscarNome($action);

        $permissao = PerfilAplicacaoModuloAcao::buscarPermissoes($usuario->id, $aplicacao->id, $modulo->id, $acao->id);

        if($permissao) {
            return true;
        } else if ($usuario->id_pessoa == NULL) {
            return true;
        }

        return false;
    }
    
    public function inserir($dados) {
        $this->getAdapter()->beginTransaction();
//        var_dump($dados);
//        die();
        
        $date = date("Y-m-d H:i:s");
        $data = array(
            'codUsuarioCadastrou' => $dados['codUsuarioCadastrador'],
            'codPerfil' => $dados['codPerfil'],
            'codEmpresa' => $dados['codEmpresa'],
            'codUnidade' => $dados['codUnidade'],
            'nomeUsuario' => $dados['nomeUsuario'],
            'matriculaUsuario' => $dados['matriculaUsuario'],
            'loginUsuario' => $dados['loginUsuario'],
            'hashSenhaUsuario' => sha1($dados['hashSenhaUsuario']),
            'emailUsuario' => $dados['emailUsuario'],
            'dtCadastroUsuario' => $date
        );
//        var_dump($data);
//        die();

//        if (isset($dados['cpf'])) {
//            $data['cpf_cnpj'] = Functions::replace($dados['cpf']);
//        } else if (isset($dados['cnpj'])) {
//            $data['cpf_cnpj'] = Functions::replace($dados['cnpj']);
//        }
        
        try {
            $this->insert($data);
        } catch (Zend_Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível cadastrar o usuário" . $e->getMessage());
        }
        
        $this->getAdapter()->commit();
        
        return true;
    }   
    
    public function editar($dados) {
        $this->getAdapter()->beginTransaction();
//        var_dump($dados);
//        die();
        $data = array(
            'codUsuarioCadastrou' => $dados['codUsuarioCadastrador'],
            'codPerfil' => $dados['codPerfil'],
            'codUnidade' => $dados['codUnidade'],
            'nomeUsuario' => $dados['nomeUsuario'],
            'matriculaUsuario' => $dados['matriculaUsuario'],
            'loginUsuario' => $dados['loginUsuario'],
            'emailUsuario' => $dados['emailUsuario']
        );
        
        if (isset($dados['senha'])) {
            $data['hashSenhaUsuario'] = sha1($dados['hashSenhaUsuario']);
        }
        
//        var_dump($data);
//        die();
        
        try {
            $where = $this->getAdapter()->quoteInto("codUsuario = ?", $dados['codUsuario']);
            $this->update($data, $where);
        } catch (Zend_Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Zend_Exception("N&atilde;o foi possível editar os dados do usuário" . $e->getMessage());
        }
        
        $this->getAdapter()->commit();
        
        return true;
    }
    
}