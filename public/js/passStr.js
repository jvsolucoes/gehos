/**
 * Calcula a força de uma senha.
 * Algoritmo baseado no número de letras maiúsculas, minúsculas, dígitos, caracteres especiais, repetições, sequencias e alternações.
 *
 * @author Dimas Melo Filho
 * @param passwd Senha para calcular a força.
 */
 
function passStrength(passwd) {
	var m = passwd.match(/[a-z]/g);
	var alphaLowCount = m != null ? m.length : 0;
	m = passwd.match(/[A-Z]/g);
	var alphaHighCount = m != null ? m.length : 0;
	m = passwd.match(/[0-9]/g);
	var numberCount = m != null ? m.length : 0;
	var otherCount = passwd.length - alphaLowCount - alphaHighCount - numberCount;
	var repetitions = 0;
	var sequences = 0;
	var toggles = 0;
	for (var i = 0, last = '', aLast = ''; i < passwd.length; i++) {
		if (passwd.charAt(i) == last) repetitions++;
		if (passwd.charCodeAt(i) == (last.charCodeAt(0)+1) || passwd.charCodeAt(i) == (last.charCodeAt(0)-1)) sequences++;
		if (passwd.charAt(i) == aLast) toggles++;
		aLast = last;
		last = passwd.charAt(i);
	}
	var bonus = (alphaHighCount > 0 ? 1 : 0) + (numberCount > 0 ? 1 : 0) + (otherCount > 0 ? 1 : 0);
	return Math.round(Math.max(Math.min((alphaLowCount + (alphaHighCount * 1.3) + (numberCount * 1.3) + (otherCount * 1.5) - repetitions - sequences + bonus - (toggles * 0.4)) * 6, 100),passwd.length*2));	
}

