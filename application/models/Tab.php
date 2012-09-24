<?php

/**
 * Description of Tab
 *
 * @author victor
 */
class Tab {
    
    
    public function inicializar() {
        $tabNumber = Form::ValueString("tab", "r". (string) rand() . (string) time());

        /* Salva o id nas variáveis globais tabId e para jquery na variável tabIdJ */
        $tabId = "fmTab" . $tabNumber;
        $tabIdJ = "#" . $tabId;
    }
    /**
     * Calcula e coloca no formulário o id de um componente de dentro da aba baseado no seu id normal.
     * 
     * @author Dimas Melo Filho
     * @param  $id ID normal do componente, como se não houvesse aba.
     */
    public static function tabIdEcho($id) {
        $tabNumber = Form::ValueString("tab", "r". (string) rand() . (string) time());
        
        echo $id . $tabNumber;
    }

    public static function getTabId($id) {
        $tabNumber = Form::ValueString("tab", "r". (string) rand() . (string) time());
        
        return $id . $tabNumber;
    }
    
    /**
     * Calcula e retorna o id de um componente de dentro da aba baseado no seu id normal.
     * 
     * @author Dimas Melo Filho
     * @param  $id ID normal do componente, como se não houvesse aba.
     */
    public function tabId($id) {
        $this->inicializar();
        global $tabNumber;
        return $id . $tabNumber;
    }

    /**
     * Retorna o número da aba atual.
     *
     * @author Dimas Melo Filho
     */
    public function tabNumber() {
        $this->inicializar();
        global $tabNumber;
        return $tabNumber;
    }

}