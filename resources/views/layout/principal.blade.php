 <?php  header("Access-Control-Allow-Origin: *"); ?>
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
    <script src="../js/clndr.js"></script>

</head>
<body>
	
	<div class='w3-sidebar w3-card-2 w3-bar-block' style='width: 15%;' id='menu'>

		<a href='/'><div id='logo_esamc' class='logo-tipo'></div></a>

		<a href='/index' id='dashboard' class='w3-bar-item w3-button'>
			<i class='fa fa-home fa-2x' aria-hidden ='trur'></i>
			<h7>Dashboard</h7>
		</a>


		<button id='grupos' class='w3-button w3-block w3-left-align' onclick='myAccFuncProjeto()'>
			<i class='fa fa-users fa-2x' aria-hidden='true'></i>
			<h7>Projetos</h7> <i class='fa fa-caret-down'></i>
		</button>
		<div id='listaProjeto' class='w3-hide w3-light-grey w3-card-2'>
		    
		    <a href='/projetos' id= 'novo' class='w3-bar-item w3-button'>
		    	<i class='fa fa-file-text-o' aria-hidden='true'></i>
		    	<h7>Todos</h7>
		    </a>
		    
		    @if (Session::get('usu_orientador') == 1)
			    <a href='/projetos/novo' id= 'novo' class='w3-bar-item w3-button'>
			    	<i class='fa fa-plus' aria-hidden='true'></i>
			    	<h7>Novo</h7>
			   	</a>
			@endif

		   	<a href='/projetos/historico' id='historico' class='w3-bar-item w3-button'>
				<i class='fa fa-history' aria-hidden='true'></i>
				<h7>Histórico</h7>
			</a>

	  	</div>


		<a href='/conversa' id='alunos' class='w3-bar-item w3-button'>
			<i class='fa fa-comments-o fa-2x' aria-hidden='true'></i>
			<h7>Conversas</h7>
		</a>


		<a href='/reunioes' id='reunioes' class='w3-bar-item w3-button'>
			<i class='fa fa-clock-o fa-2x' aria-hidden ='trur'></i>
			Reuniões
		</a>	


		<button id='perfil' class='w3-button w3-block w3-left-align' onclick='myAccFuncPerfil()'>
			<i class="fa fa-user fa-2x" aria-hidden="true"></i>
			<h7>Perfil</h7> <i class='fa fa-caret-down'></i>
		</button>
		<div id='listaPerfil' class='w3-hide w3-light-grey w3-card-2'>

		    <a href='/perfil' id= 'novo' class='w3-bar-item w3-button'>
		    	<i class='fa fa-edit' aria-hidden='true'></i>
		    	<h7>Editar</h7>
		    </a>

		   	<a href='/logout' id='logout' class='w3-bar-item w3-button'>
				<i class='fa fa-sign-out' aria-hidden ='true'></i>
				<h7>Sair</h7>
			</a>

	  	</div>
		
	</div>

	@yield('conteudo')


	
</body>
</html>