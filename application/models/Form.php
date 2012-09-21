<?php

/**
 * Description of Form
 *
 * @author victor
 */
class Form {

    /**
     * Retorna uma variável de formulário passada por POST ou GET.
     * Quando a variável não foi definida, retorna o valor padrão
     * informado.
     *
     * 	@param	var		nome da variável passada por POST ou GET.
     * 	@param	def		valor padrão caso a variável não exista.
     * 	@return string	String do valor da variável.
     * */
    private static function Value($var, $def) {
        if (isset($_POST[$var]))
            return $_POST[$var];
        elseif (isset($_GET[$var]))
            return $_GET[$var];
        else
            return $def;
    }

    /**
     * Retorna uma variável de formulário passada por POST ou GET.
     * Quando a variável não foi definida, retorna o valor padrão
     * informado.
     *
     * 	@param	var		nome da variável passada por POST ou GET.
     * 	@param	def		valor padrão caso a variável não exista.
     * 	@return string	String do valor da variável.
     * */
    public static function ValueString($var, $def) {
        return self::Value($var, $def);
    }

    /**
     * Retorna uma variável de formulário passada por POST ou GET.
     * Quando a variável não foi definida, retorna o valor padrão
     * informado.
     *
     * 	@param	var		nome da variável passada por POST ou GET.
     * 	@param	def		valor padrão caso a variável não exista.
     * 	@return float	Float do valor da variável.
     * */
    public static function ValueFloat($var, $def) {
        return floatval(self::Value($var, $def));
    }

    /**
     * Retorna uma variável de formulário passada por POST ou GET.
     * Quando a variável não foi definida, retorna o valor padrão
     * informado.
     *
     * 	@param	var		nome da variável passada por POST ou GET.
     * 	@param	def		valor padrão caso a variável não exista.
     * 	@return int		Integer do valor da variável.
     * */
    public static function ValueInt($var, $def) {
        return intval(self::Value($var, $def));
    }

    /**
     * Retorna uma variável de formulário passada por POST ou GET.
     * Quando a variável não foi definida, retorna o valor padrão
     * informado.
     *
     * 	@param	var		nome da variável passada por POST ou GET.
     * 	@param	def		valor padrão caso a variável não exista.
     * 	@return string	String do valor da variável com escape para MySQL.
     * */
    public static function ValueEsc($var, $def) {
        return mysql_real_escape_string(self::Value($var, $def));
    }

    /**
     * Retorna uma flag definida em formulário passado por POST ou GET.
     *
     * 	@param	var		nome da variável passada por POST ou GET.
     * 	@param	flagval	valor para retornar caso o flag esteja definido.
     * 	@param	undef	valor para retoranr caso o flag não esteja definido.
     * 	@return int		Um dos dois valores passados por parâmetro, dependendo de se o flag está definido ou não.
     * */
    public static function ValueFlag($var, $flagval, $undef) {
        return (isset($_POST[$var]) || isset($_GET[$var])) ? $flagval : $undef;
    }

    /**
     * Imprime uma lista de <options> para um <select>, a partir de uma array.
     *
     * 	@param 	array	Array onde a primeira coluna é o valor do <option> e a segunda é o texto do option.
     * 	@return void
     * */
    public static function CriarComboboxOptions($cmbArray) {
        for ($i = 0; $i < count($cmbArray); $i++)
            echo "<option value=\"" . $cmbArray[$i][0] . "\">" . $cmbArray[$i][1] . "</option>";
    }

}

?>
