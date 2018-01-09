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
		    		<h1 class="w3-text-white" style="text-align: center; font-size: 350%;">NOVO ORIENTADOR</h1>				
				</div>
			</div>			
	 	</div>

	 	<div class="col-md-2"></div>

	 	<div class="col-md-8">
		 	<form name="form" class="w3-container" action="../salvar_orientador/1020304050_" method="POST">

		 		<div class="col-md-12">
		 		
			 		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
						
					<p>
						<label><b>Nome</b></label>
						<input class="w3-input w3-border" type="text" name="nome">
						<label class="alert-danger"  style="display:none;" id="alert_nome">Preencha o campo nome</label>
					</p>

					<p>
						<label><b>E-mail</b></label>
						<input class="w3-input w3-border" type="email" name="email">
						<label class="alert-danger"  style="display:none;" id="alert_email">Preencha o campo e-mail</label>
					</p>

					<p>
						<label><b>CPF</b></label>
						<input class="w3-input w3-border" type="text" name="cpf">
						<label class="alert-danger"  style="display:none;" id="alert_cpf">Preencha o campo CPF</label>
					</p>

					<p>
						<label><b>Senha</b></label>
						<input class="w3-input w3-border" type="password" name="senha">

						<label><b>Confirmar senha</b></label>
						<input class="w3-input w3-border" type="password" name="senha_confirmar">
						<label class="alert-danger"  style="display:none;" id="alert_senha_diferente">As senha não são iguais</label>
						<label class="alert-danger"  style="display:none;" id="alert_senha_menor">As senha devem ter mais que 6 caracteres</label>
					</p>
				</div>

				<div class="col-md-12">
					<button class="w3-btn w3-esamc w3-text-white" type="submit" style="margin: 1%; width: 50%;" type="submit" onclick="return validar_orientador()">
							Salvar
					</button>
				</div>

		 	</form>
		 </div>
	 	
	</div>

 </body>
 </html>