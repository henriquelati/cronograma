@extends('layout.principal')

@section('conteudo')
	<div zclass="w3-main" id="main" style="margin-left: 15%">

		<div class="w3-esamc">
			<div class="w3-container">
				<div class="col-md-9">
		    		<h1 class="w3-text-white">Projeto: {{$dados['cronograma'][0]->prj_titulo}}</h1>				
				</div>
	    		<div class="col-md-3" style="text-align: center; padding-top: 2%; color: white; font-size: 120%;">
	    			Bem-vindo {{$dados['usu_nome']}}
	    		</div>
			</div>			
	 	</div>


		<form class="w3-container" action="/salvar_assunto/{{$dados['cronograma'][0]->cro_cod}}" method="POST">


			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

		 	<div class="w3-container col-md-12">
		        <div>
		        	<h3>Integrantes</h3>

					<input type="hidden" name="quantidade_aluno" value="{{count($dados['cronograma'])}}" />

		        	@for($i = 0; $i < count($dados['cronograma']); $i ++)

			        	<div class="col-md-2">

			        		@if ($dados['cronograma'][$i]->usu_foto == '')
			        			<img src="../img/user.jpg" id="img" class="w3-circle" style="width: 100%;">
			        		@else
			        			<img src="{{$dados['cronograma'][$i]->usu_foto}}" id="img" class="w3-circle" style="width: 100%;">
			        		@endif

							<input type="hidden" name="id_aluno_{{$i}}" value="{{$dados['cronograma'][$i]->usu_cod}}" />

							@if (Session::get('usu_orientador') == 1)
			        			<input type="checkbox" class="w3-check" name="aluno_{{$i}}" checked>
			        		@endif

			        		<label style="margin-top: 10%;">{{$dados['cronograma'][$i]->usu_nome}}</label>
			        	</div>

			        @endfor
		        	
		        </div>
	    	</div>


			<input type="hidden" name="cro_cod" value="{{$dados['cronograma'][0]->cro_cod}}" />

	    	<div class="col-md-8">
	    		<p>
	    			<h3><b>ASSUNTO</b></h3>
	    				<input type="text" class="w3-input w3-border" name="assunto" id="assunto" value="{{$dados['cronograma'][0]->cro_assunto}}">
	    		</p>


	    		@if (Session::get('usu_orientador') == 1)
	    			<p><input type="checkbox" class="w3-check" name="entrega" id="entrega"> ENTREGA OK</p>
	    		@else
	    			<p><input type="checkbox" class="w3-check" name="entrega" id="entrega" disabled> ENTREGA OK</p>
	    		@endif


	    		@if (Session::get('usu_orientador') == 1)
	    			<p><h3><b>OBSERVAÇÃO</b></h3>
					<textarea class="w3-input w3-border" type="text" name="observacao" >{{$dados['cronograma'][0]->cro_observacao}}</textarea></p>
				@else 
					<p><h3><b>OBSERVAÇÃO</b></h3>
					<textarea class="w3-input w3-border" type="text" id="observacao" name="observacao" disabled >{{$dados['cronograma'][0]->cro_observacao}}</textarea></p>
				@endif

	    	</div>

	    		<div class="col-md-12">
	    			<div class="col-md-2">
				    	<button class="w3-btn w3-esamc w3-text-white" type="submit" style="margin: 1%;">
							Salvar
						</button>
					</div>
				</div>

	   	</form>
	   	<br>


 	</div>
 @stop