@extends('layout.principal')

@section('conteudo')
	<div zclass="w3-main" id="main" style="margin-left: 15%">

		<div class="w3-esamc">
			<div class="w3-container">
				<div class="col-md-9">
		    		<h1 class="w3-text-white">Relatórios</h1>				
				</div>
	    		<div class="col-md-3" style="text-align: center; padding-top: 2%; color: white; font-size: 120%;">
	    			Henrique
	    		</div>
			</div>			
	 	</div>

	 	<h3>Usuários</h3>
	 	@foreach ($usuarios as $u)
		 	cod: {{$u->usu_cod}} <br>
		 	nome: {{$u->usu_nome}} <br>
		 	email: {{$u->usu_email}} <br>
		 	orientador: {{$u->usu_orientador}} <br>
		 	cpf: {{$u->usu_cpf}} <br>
		 	ra: {{$u->usu_ra}} <br>
		 	<hr>
	 	@endforeach

 	</div>
 @stop