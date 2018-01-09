@extends('layout.principal')

@section('conteudo')
	<div zclass="w3-main" id="main" style="margin-left: 15%">

		<div class="w3-esamc">
			<div class="w3-container">
				<div class="col-md-9">
		    		<h1 class="w3-text-white">Dashboard</h1>				
				</div>
	    		<div class="col-md-3" style="text-align: center; padding-top: 2%; color: white; font-size: 120%;">
	    			Bem-vindo {{$dados['usu_nome']}}
	    		</div>
			</div>			
	 	</div>

	 	<div class="w3-container">

			<br>

	        <div class="col-md-12">
	        	<h4 style="font-size: 140%; margin: -1%; text-align: left;"><b>PROJETOS DO DIA</b></h4>

	        	@if (! $dados['projetos_dia'])
	        		<h5 style="text-align: left;">Não há projetos hoje</h5>
	        	@else

		        	<ul class="w3-ul w3-hoverable">
		        	@foreach ($dados['projetos_dia'] as $p)
		        		<a href="detalhes_assunto/{{$p->cro_cod}}">
			        	<li>
			        		<b>PROJETO: </b> {{$p->prj_titulo}}
			        		<b>ASSUNTO: </b> {{$p->cro_assunto}}
			        	</li>
			        	</a>
		        	@endforeach
		        	</ul>
		        @endif
	        	
	        </div>

	 		<br>
	 		<br>

	 		@if (Session::get('usu_orientador') == 1)
	        <div class="col-md-4">
				<hr style="border-top: 1px solid #9f9f9f;">	        	
	        	<h4 style="font-size: 140%; margin: -1%; text-align: left;"><b>NOVA CONSULTORIA</b></h4>
		        <a href="/cronograma/novo" class="w3-button w3-block w3-esamc-light w3-text-white" style="width: 100%;">Criar novo consulta</a>
	        </div>
	        @endif

    	</div>

 	</div>
 @stop