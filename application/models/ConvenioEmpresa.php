<?php
/**
 * Description of ConvenioEmpresa
 *
 * @author victor
 */
class ConvenioEmpresa extends Zend_Db_Table_Abstract {

    protected $_name = "convenioempresa";
    protected $_primary = array("codEmpresa", "codConvenio");
    
    protected $_referenceMap = array(
        'Convenio' => array(
            'refTableClass' => 'Convenio',
            'refColumns' => 'codConvenio',
            'columns' => 'codConvenio',
            'onDelete' => self::CASCADE
        ),
        'Empresa' => array(
            'refTableClass' => 'Empresa',
            'refColumns' => 'codEmpresa',
            'columns' => 'codEmpresa',
            'onDelete' => self::CASCADE
        )
    );
    
    public function inserir($dados) {
        $this->getAdapter()->beginTransaction();
        
        $data = array (
            'codEmpresa' => $dados['codEmpresa'],
            'codConvenio' => $dados['codConvenio'],
            'codHospConvenioEmpresa' => $dados['codHospConvenio'],
            'avisoFimFaixaConvenioEmpresa' => $dados['avisoFimFaixaConvenio'],
            'nomeFantasiaConvenioEmpresa' => $dados['nomeFantasiaConvenio'],
            'inscricaoEstadualConvenioEmpresa' => $dados['inscricaoEstadualConvenio'],
            'inscricaoMunicipalConvenioEmpresa' => $dados['inscricaoMunicipalConvenio'],
            'diasRetornoConsultaConvenioEmpresa' => $dados['diasRetornoConsultaConvenio'],
            'qtdFaturaRemessaConvenioEmpresa' => $dados['qtdFaturaRemessaConvenio'],
            'mascaraMatriculaConvenioEmpresa' => $dados['mascaraMatriculaConvenio'],
//            'imprProcFatUrgConvenioEmpresa' => $dados['imprProcFatUrgConvenio'],
//            'fabOpmFaturaConvenioEmpresa' => $dados['fabOpmFaturaConvenio'],
//            'codAltaContaParcialConvenioEmpresa' => $dados['codConvenio'],
            'qtdDiaPagamentoConvenioEmpresa' => $dados['qtdDiaPagamentoConvenio'],
            'pctUrgenciaConvenioEmpresa' => $dados['pctUrgenciaConvenio'],
            'fone1GlosaConvenioEmpresa' => $dados['fone1GlosaConvenio'],
            'fone2GlosaConvenioEmpresa' => $dados['fone2GlosaConvenio'],
            'emailGlosaConvenioEmpresa' => $dados['emailGlosaConvenio'],
            'contatoGlosaConvenioEmpresa' => $dados['contatoGlosaConvenio'],
            'qtdDiaParcialConvenioEmpresa' => $dados['qtdDiaParcialConvenio'],
            'tabelaProcConvenioEmpresa' => $dados['tabelaProcConvenio'],
            'tabelaMatConvenioEmpresa' => $dados['tabelaMatConvenio'],
            'tabelaMedConvenioEmpresa' => $dados['tabelaMedConvenio'],
//            'imprimeFatAudConvenioEmpresa' => $dados['imprimeFatAudConvenio'],
            'iniFaixaInternacaoConvenioEmpresa' => $dados['iniFaixaInternacaoConvenio'],
            'fimFaixaInternacaoConvenioEmpresa' => $dados['fimFaixaInternacaoConvenio'],
            'iniFaixaSpsadtConvenioEmpresa' => $dados['iniFaixaSpsadtConvenio'],
            'fimFaixaSpsadtConvenioEmpresa' => $dados['fimFaixaSpsadtConvenio'],
            'iniFaixaResumoConvenioEmpresa' => $dados['iniFaixaResumoConvenio'],
            'fimFaixaResumoConvenioEmpresa' => $dados['fimFaixaResumoConvenio'],
            'iniFaixaHonorarioConvenioEmpresa' => $dados['iniFaixaHonorarioConvenio'],
            'fimFaixaHonorarioConvenioEmpresa' => $dados['fimFaixaHonorarioConvenio'],
            'iniFaixaConsultaConvenioEmpresa' => $dados['iniFaixaConsultaConvenio'],
            'fimFaixaConsultaConvenioEmpresa' => $dados['fimFaixaConsultaConvenio'],
            'iniFaixaOutrasDespesasConvenioEmpresa' => $dados['iniFaixaOutrasDespesasConvenio'],
            'fimFaixaOutrasDespesasConvenioEmpresa' => $dados['fimFaixaOutrasDespesasConvenio']
        );

        try {
            $this->insert($data);
            $retorno = true;
        } catch (Exception $e) {
            $this->getAdapter()->rollBack();
            $retorno = false;
            throw new Exception("N&atilde;o foi possível cadastrar os dados de configuração do convênio" . $e->getMessage());
        }
        
        $this->getAdapter()->commit();
        
        return $retorno;
        
    }
    
    
}