@extends('layout.principal')

@section('conteudo')
<div zclass="w3-main" id="main" style="margin-left: 15%">

	<div class="w3-esamc">
			<div class="w3-container">
				<div class="col-md-9">
		    		<h1 class="w3-text-white">Novo Projeto</h1>				
				</div>
	    		<div class="col-md-3" style="text-align: center; padding-top: 2%; color: white; font-size: 120%;">
	    			Bem-vindo {{$dados['usu_nome']}}
	    		</div>
			</div>			
	 	</div>

	<div class="w3-container" style="padding: 1%;">

		<div class="w3-card-4">
			<div class="w3-container w3-esamc-light">
				<h2 class="w3-text-white">Projeto</h2>
			</div>

			<form class="w3-container" action="/projetos/novo" method="POST">

				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				
				<p>
					<label>Título</label>
					<input class="w3-input w3-border" type="text" name="titulo">
				</p>

				<p>
					<label>Descrição</label>
					<textarea class="w3-input w3-border" type="text" name="descricao"></textarea>
				</p>
			
				<hr style="border-top: 1px solid #223658;">

				<p>
					<h3 class="w3-third">Integrantes do Grupo: </h3>
					<h5 class="w3-third">
						<select id="selectQuantidade" name="qntUsuario" onchange="numAlunos()">
							<option value="1">1
							<option value="2">2
							<option value="3">3
							<option value="4">4
							<option value="5">5
							<option value="6">6
							<option value="7">7
							<option value="8">8
							<option value="9">9
							<option value="10">10
						</select>
					</h5>
				</p>
				
				<div id="formAlunos">
					<div id class="w3-row-padding" style="padding: 0.5%;">
						<div class="col-md-6">
							<label>Nome</label>
							<input class="w3-input w3-border" type="text" name="nome_0">
						</div>
						<div class="col-md-6">
							<label>E-mail</label>
							<input class="w3-input w3-border" type="email" name="email_0">
						</div>
					</div>
				</div>


				<hr style="border-top: 1px solid #223658;">

				<h3>Cronograma:</h3>

				<div id class="w3-row-padding" style="padding: 0.5%;">
					<div class="col-md-4">
						<label>Data Inicio</label>
						<input class="w3-input w3-border" type="date" id="dt_inicio" value="<?php echo date("Y-m-d"); ?>">
					</div>
					<div class="col-md-4">
						<label>Data Entrega</label>
						<input class="w3-input w3-border" type="date" id="dt_entrega" value="<?php echo date("Y-m-d"); ?>" min="<?php echo date("Y-m-d"); ?>">
					</div>
					<div class="col-md-2">
						<label>Dia de reunião</label>
						<select id="diaSemana" class="w3-input w3-border">
							<option value="0">Domingo
							<option selected="selected" value="1">Segunda
							<option value="2">Terça
							<option value="3">Quarta
							<option value="4">Quinta
							<option value="5">Sexta
							<option value="6">Sábado
						</select>
					</div>
					<div class="col-md-2">
						<a class="w3-button w3-esamc w3-text-white w3-large" style="margin-top: 14%;" onclick="semanaCronograma(<?php echo date('w') ?>)">Calcular Dias</a>
					</div>

				</div>


				<div id="formCronograma">

				</div>


				<hr style="border-top: 1px solid #223658;">

				<p>
					<label>Orientador</label>
					<input class="w3-input w3-border" type="text" value="{{$dados['usu_nome']}}" disabled="disabled">
				</p>

				<button class="w3-btn w3-esamc w3-text-white" type="submit" style="margin: 1%; width: 10%;">
					Salvar
				</button>

			</form>

			<hr style="border-top: 1px solid #223658;">

		</div>
	</div>

</div>
 @stop