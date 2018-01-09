function myAccFuncProjeto() {
    var x = document.getElementById("listaProjeto");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-grey";
    } else { 
        x.className = x.className.replace(" w3-show", "");
        x.previousElementSibling.className = 
        x.previousElementSibling.className.replace(" w3-grey", "");
    }
}

function myAccFuncPerfil() {
    var x = document.getElementById("listaPerfil");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-grey";
    } else { 
        x.className = x.className.replace(" w3-show", "");
        x.previousElementSibling.className = 
        x.previousElementSibling.className.replace(" w3-grey", "");
    }
}

function semanaCronograma(diaAtual)
{
	var inicio = new Date(document.getElementById("dt_inicio").value);
	var entrega = new Date(document.getElementById("dt_entrega").value);

	var diaAtual = inicio.getDay();
	var diaEntrega = document.getElementById("diaSemana").value;


	//calcula dia da semana
	if (diaAtual > diaEntrega) {
		var dataInicio = 7 - (diaAtual - diaEntrega);
	}
	else if (diaAtual == diaEntrega) {
		var dataInicio = 0;
	}
	else {
		var dataInicio = diaEntrega - diaAtual;
	}

	var dataReal = new Date();

	inicio.setDate(inicio.getDate() + dataInicio);

	var timeDiff = Math.abs(inicio.getTime() - entrega.getTime());

	var semanas = 1 + (Math.floor(timeDiff / (1000 * 3600 * 24 * 7))); 


	for (var i = 1; i <= semanas; i++) 
	{
		
		if (i == 1) 
		{
			document.getElementById("formCronograma").innerHTML = 
    		'<div id class="w3-row-padding" style="padding: 0.5%;">' +
				'<div class="col-md-2">' +
					'Semana: <label id="semana">'+ semanas + '</label>' +
					'<input type="hidden" name="qntSemana" value="'+ semanas +'" >' +
					'<input class="w3-input w3-border" type="text" value="Semana '+ i +'" disabled>' +
				'</div>' +
				'<div class="col-md-2">' +
					'<label>Data</label>' +
					'<input class="w3-input w3-border" type="text" name="data_'+ i +'" value="'+ inicio.toLocaleDateString() +'" >' +
				'</div>' +
				'<div class="col-md-8">' +
					'<label>Assuntos</label>' +
					'<input class="w3-input w3-border" type="text" name="assunto_'+ i +'" value="Assunto da semana '+i+'">' +
				'</div>' +
		  	'</div>';
		}

		else 
		{
			inicio.setDate(inicio.getDate() + 7);

			document.getElementById("formCronograma").innerHTML += 
    		'<div id class="w3-row-padding" style="padding: 0.5%;">' +
				'<div class="col-md-2">' +
					'<input class="w3-input w3-border" type="text" value="Semana '+ i +'" disabled>' +
				'</div>' +
				'<div class="col-md-2">' +
					'<input class="w3-input w3-border" type="text" name="data_'+ i +'" value="'+ inicio.toLocaleDateString() +'" >' +
				'</div>' +
				'<div class="col-md-8">' +
					'<input class="w3-input w3-border" type="text" name="assunto_'+ i +'" value="Assunto da semana '+i+'">' +
				'</div>' +
		  	'</div>';
			 
		}

	}


	

}

function numAlunos()
{
    var x = document.getElementById("selectQuantidade").value;

    for (var i = 0; i < x; i++) 
    {
    	if (i == 0) 
    	{
    		document.getElementById("formAlunos").innerHTML = 
    		'<div id class="w3-row-padding" style="padding: 0.5%;">' +
				'<div class="w3-half">' +
					'<label>Nome</label>' +
					'<input class="w3-input w3-border" type="text" name="nome_'+ i +'">' +
				'</div>' +
				'<div class="w3-half">' +
					'<label>E-mail</label>' +
					'<input class="w3-input w3-border" type="email" name="email_'+ i +'">' +
				'</div>' +
		  	'</div>';	
    	}
    	else 
    	{
	    	document.getElementById("formAlunos").innerHTML += 
	    	'<div id class="w3-row-padding" style="padding: 0.5%;">' +
				'<div class="w3-half">' +
					'<label>Nome</label>' +
					'<input class="w3-input w3-border" type="text" name="nome_'+ i +'">' +
				'</div>' +
				'<div class="w3-half">' +
					'<label>E-mail</label>' +
					'<input class="w3-input w3-border" type="email" name="email_'+ i +'">' +
				'</div>' +
		   	'</div>';
	    }
    }
}

function validar_recuperacao() {

	var email = form_recuperacao.email.value;

	var valida = true;

	if (email == ""){
		document.getElementById("alert_email_recuperacao").style.display = 'block';
		valida = false;
	}
	else {
		document.getElementById("alert_email_recuperacao").style.display = 'none';
	}

	if (valida == false) {
		return false;
	}

	alert('Uma mensagem foi enviada para o e-mail informado com os dados de acesso!');
}

function validar_login() {

	var email = form.email.value;
	var password = form.password.value;

	var valida = true;

	if (email == ""){
		document.getElementById("alert_email").style.display = 'block';
		valida = false;
	}
	else {
		document.getElementById("alert_email").style.display = 'none';
	}

	if (password == "") {
		document.getElementById("alert_senha").style.display = 'block';
		valida = false;
	}
	else {
		document.getElementById("alert_senha").style.display = 'none';
	}

	if (valida == false) {
		return false;
	}
}

function validar_perfil() {

	var nome = form.nome.value;
	var email = form.email.value;
	var cpf = form.cpf.value;
	var ra = form.ra.value;
	var senha = form.senha.value;
	var senha_confirmar = form.senha_confirmar.value;

	var valida = true;



	if (nome == ""){
		document.getElementById("alert_nome").style.display = 'block';
		valida = false;
	}
	else {
		document.getElementById("alert_nome").style.display = 'none';
	}

	if (email == "") {
		document.getElementById("alert_email").style.display = 'block';
		valida = false;
	}
	else {
		document.getElementById("alert_email").style.display = 'none';
	}


	if (ra == "") {
		document.getElementById("alert_ra").style.display = 'block';
		valida = false;
	}
	else {
		document.getElementById("alert_ra").style.display = 'none';
	}

	if (senha != "") {

		if (senha != senha_confirmar) {
			document.getElementById("alert_senha_diferente").style.display = 'block';
			valida = false;
		}
		else {
			document.getElementById("alert_senha_diferente").style.display = 'none';
		}

		if (senha.length < 6 || senha_confirmar.length < 6) {
			document.getElementById("alert_senha_menor").style.display = 'block';
			valida = false;
		}
		else {
			document.getElementById("alert_senha_menor").style.display = 'none';
		}

	}

	if (valida == false) {
		return false;
	}

}

function validar_orientador() {

	var nome = form.nome.value;
	var email = form.email.value;
	var cpf = form.cpf.value;
	var senha = form.senha.value;
	var senha_confirmar = form.senha_confirmar.value;
	var valida = true;

	if (nome == ""){
		document.getElementById("alert_nome").style.display = 'block';
		valida = false;
	}
	else {
		document.getElementById("alert_nome").style.display = 'none';
	}

	if (email == "") {
		document.getElementById("alert_email").style.display = 'block';
		valida = false;
	}
	else {
		document.getElementById("alert_email").style.display = 'none';
	}

	if (cpf == ""){
		document.getElementById("alert_cpf").style.display = 'block';
		valida = false;
	}
	else {
		document.getElementById("alert_cpf").style.display = 'none';
	}

	if (senha != senha_confirmar) {
		document.getElementById("alert_senha_diferente").style.display = 'block';
		valida = false;
	}
	else {
		document.getElementById("alert_senha_diferente").style.display = 'none';
	}

	if (senha.length < 6 || senha_confirmar.length < 6) {
		document.getElementById("alert_senha_menor").style.display = 'block';
		valida = false;
	}
	else {
		document.getElementById("alert_senha_menor").style.display = 'none';
	}


	if (valida == false) {
		return false;
	}

}
