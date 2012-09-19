/* Script usado na página de login */

function init() {
	def.fieldNav(-1,['inputUser','inputPass','@submitForm()']);
}
//function submitForm() {
//	userName = $("#inputUser").val();
//	userPass = $("#inputPass").val();
//	if (userName.length <= 0) {
//		def.modalBox(['OK'],"Erro de Usu&aacute;rio",'Por favor, informe um nome de usu&aacute;rio.',function () { $("#inputUser").focus(); });
//	}
//	else if (userPass.length <= 0) {
//		def.modalBox(['OK'],"Erro de Senha",'Por favor, informe a senha.', function () { $("#inputPass").focus(); });
//	}
//	else {
//		var senhaHashSal = hex_sha256(hex_sha256(userPass).toLowerCase() + $("#inputSal").val()).toLowerCase();
//		$.ajax({
//			url: 'validaLogin.php',
//			type: 'POST',
//			data: {
//				"user": userName,
//				"passHash": senhaHashSal
//			},
//			success: function (data) {
//				if (data != null && data.length >= 2 && data.substr(0,2) == 'OK') { // Autenticado
//					window.location = 'main.php';
//				}
//				else { // Erro de Autenticação
//					def.modalBox(['OK'],"Erro de Autentica&ccedil;&atilde;o",'O nome de usu&aacute;rio ou senha est&atilde;o incorretos. Por favor, verifique seus dados e tente novamente.',function () { $("#inputUser").focus(); });
//					$("#inputUser").prop('disabled',false).focus();
//					$("#inputPass").prop('disabled',false);
//				}
//			}
//		});
//		$("#inputPass").blur().prop('disabled',true);
//		$("#inputUser").prop('disabled',true);
//	}
//	return false;
//}
//
//window.onbeforeunload = function() {
//     window.name = "Gehos Login"; 
//}
//
//if (window.name == "Gehos Login") {
//	window.name = 'Autentica&ccedil;&atilde;o Gehos';
//	window.location.reload();
//}