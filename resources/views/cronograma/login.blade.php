<!DOCTYPE html>
<html>
<head>
	<title>Cronograma</title>
		
	<meta charset='utf-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' type='text/css' href='../css/w3.css'>
	<link rel='stylesheet' type='text/css' href='../css/app.css'>
	<link rel='stylesheet' type='text/css' href='../css/font-awesome.css'>
    <link rel="stylesheet" href="../css/clndr.css">
    <link rel="stylesheet/less" type="text/css" href="../less/clndr.less">
	<script src='../js/cronograma.js'></script>

</head>
<body>

	<div zclass="w3-main" id="main">

		<div class="w3-esamc">
			<div class="w3-container">
				<div class="col-md-12">
		    		<h1 class="w3-text-white" style="text-align: center; font-size: 350%;">CRONOGRAMA</h1>				
				</div>
			</div>			
	 	</div>
	 	<div class="w3-container col-md-12" style="padding: 0px 35%;">
	 		<br><br>
		 	<div class="w3-card-4">
		 		<div class="w3-container w3-esamc" style="background-color: #4C4C4C;">
					<h5 class="w3-text-white" style="text-align: center; font-size: 150%;">LOGIN</h5>
				</div>

				<form name="form" class="w3-container" action="/logar" method="POST">
					
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />

					<br>
					<b>E-mail:</b>
					<input type="email" name="email" class="w3-input w3-border">
					<label class="alert-danger" style="display:none;" id="alert_email">Preencha o campo e-mail</label>

					<br>
					<b>Senha:</b>
					<input type="password" name="password" class="w3-input w3-border">
					<label class="alert-danger" style="display:none;" id="alert_senha">Preencha o campo senha</label>

					<br>
					<div style="text-align: right; margin-bottom: 2%;">						
						<a href="javascript:void(0);" onclick="mostrar_recuperar();"">Esqueceu a senha?</a>
					</div>

					<button style="margin-bottom: 3%;" class="w3-button w3-block w3-esamc-light w3-text-white" type="submit" onclick="return validar_login()">
						ENTRAR
					</button>

				</form>

		 	</div>

		 	@if(isset($error))
			 	<div class="alert alert-danger" style="margin: 2%;">
				 	<strong>Atenção!</strong> Usuário ou senha inválido.
				</div>
			@endif
		</div>
	</div>

	<div zclass="w3-main" id="recuperar">
	 	<div class="w3-container col-md-12" id="div_recuperar" style="padding: 0px 35%; display: none;">
	 		<br><br>
		 	<div class="w3-card-4">
		 		<div class="w3-container w3-esamc" style="background-color: #4C4C4C;">
					<h5 class="w3-text-white" style="text-align: center; font-size: 150%;">RECUPERAR SENHA</h5>
				</div>

				<form name="form_recuperacao" class="w3-container" action="/recuperar_senha" method="POST">
					
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

					<br>
					<b>E-mail:</b>
					<input type="email" name="email" class="w3-input w3-border">
					<label style="color: red; display:none;" id="alert_email_recuperacao">Preencha o campo e-mail</label>

					<button style="margin-bottom: 3%;" class="w3-button w3-block w3-esamc-light w3-text-white" type="submit" onclick="return validar_recuperacao()">
						RECUPERAR
					</button>

				</form>

		 	</div>
		</div>

 	</div>

 <script type="text/javascript">
 	
 	function mostrar_recuperar()
 	{
 		document.getElementById("div_recuperar").style.display = 'block';
 	}


 </script>
