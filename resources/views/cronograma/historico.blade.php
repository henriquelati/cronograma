@extends('layout.principal')

@section('conteudo')
	<div zclass="w3-main" id="main" style="margin-left: 15%">

		<div class="w3-esamc">
			<div class="w3-container">
				<div class="col-md-9">
		    		<h1 class="w3-text-white">Histórico</h1>				
				</div>
	    		<div class="col-md-3" style="text-align: center; padding-top: 2%; color: white; font-size: 120%;">
	    			Bem-vindo {{$dados['usu_nome']}}
	    		</div>
			</div>			
	 	</div>

	 	@if (! $dados['projetos'])
	 		<div class="col-md-12">
	        	<h2 style="text-align: left;"><b>NÃO HÁ HISTÓRICO DE PROJETO</b></h2>
	        </div>
	 	@else
		 	@foreach ($dados['projetos'] as $p)
		 	<div class="w3-container col-md-4" style="padding: 1%;">
			 	<div class="w3-card-4 ">
			 		<div class="w3-container w3-esamc-old" style="background-color: #4C4C4C;">
						<h5 class="w3-text-white">Projeto: {{$p->prj_titulo}}</h5>
					</div>

					<div style="padding: 1%">
						<label><b>Titulo:</b> {{$p->prj_titulo}}</label><br>
						<label><b>Descrição:</b> {{$p->prj_descricao}}</label><br>
					</div>

					<a href="{{action('CronogramaController@detalhes', $p->prj_cod)}}" class="w3-button w3-block w3-esamc-old-light w3-text-white">Detalhes</a>

			 	</div>
			</div>
		 	@endforeach
		 @endif

 	</div>
 @stop