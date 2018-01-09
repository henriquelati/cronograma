@extends('layout.principal')

@section('conteudo')
	<div zclass="w3-main" id="main" style="margin-left: 15%">

		<div class="w3-esamc">
			<div class="w3-container">
				<div class="col-md-9">
		    		<h1 class="w3-text-white">Perfil</h1>				
				</div>
	    		<div class="col-md-3" style="text-align: center; padding-top: 2%; color: white; font-size: 120%;">
	    			Bem-vindo {{$dados['usu_nome']}}
	    		</div>
			</div>			
	 	</div>

	 	<form name="form" class="w3-container" action="/perfil/editar" method="POST" enctype="multipart/form-data">

	 		<div class="col-md-6">
	 		
		 		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					
				<p>
					<label><b>Nome *</b></label>
					<input class="w3-input w3-border" type="text" name="nome" value="{{$dados['usuario']->usu_nome}}">
					<label class="alert-danger" style="display:none;" id="alert_nome">Preencha o campo nome</label>
				</p>

				<p>
					<label><b>E-mail *</b></label>
					<input class="w3-input w3-border" type="email" name="email" value="{{$dados['usuario']->usu_email}}">
					<label class="alert-danger" style="display:none;" id="alert_email">Preencha o campo e-mail</label>
				</p>

				<p>
					<label><b>CPF</b></label>
					<input class="w3-input w3-border" type="text" name="cpf" value="{{$dados['usuario']->usu_cpf}}">
				</p>

				<p>
					<label><b>RA *</b></label>
					<input class="w3-input w3-border" type="text" name="ra" value="{{$dados['usuario']->usu_ra}}">
					<label class="alert-danger" style="display:none;" id="alert_ra">Preencha o campo RA</label>
				</p>

				<p>
					<label><b>Senha</b></label>
					<input class="w3-input w3-border" type="password" name="senha">

					<label><b>Confirmar senha</b></label>
					<input class="w3-input w3-border" type="password" name="senha_confirmar">
					<label class="alert-danger" style="display:none;" id="alert_senha_diferente">As senha não são iguais</label>
					<label class="alert-danger" style="display:none;" id="alert_senha_menor">As senha devem ter mais que 6 caracteres</label>
				</p>
			</div>

			<div class="col-md-5" style="margin: 3%;">
				<div class="col-md-2"></div>
 				<div class="col-md-6">
 					@if ($dados['usuario']->usu_foto == '')
	        			<img src="../img/user.jpg" id="img" class="w3-circle" style="width: 100%;">
	        		@else
	        			<img src="{{$dados['usuario']->usu_foto}}" id="img" class="w3-circle" style="width: 100%;">
	        		@endif
	        	</div>
	        	<div class="col-md-12">
    				<label class="w3-btn w3-esamc w3-text-white" style="margin: 2%;">
    					<input class="w3-input" type="file" id="upload" name="foto">
    					<b>Buscar </b>
					</label>

    			</div>
			</div>

			<div class="col-md-12">
				<button class="w3-btn w3-esamc w3-text-white" type="submit" style="margin: 1%; width: 20%;" type="submit" onclick="return validar_perfil()">
						Salvar
				</button>
			</div>

	 	</form>

 	</div>

 	<script type="text/javascript">

 		$(function(){

			$('#upload').change(function(){

				var input = this;
				var url = $(this).val();
				var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();

				if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")){

					var reader = new FileReader();

					reader.onload = function (e) {
						$('#img').attr('src', e.target.result);
					}

					reader.readAsDataURL(input.files[0]);
		    	}
				else {

					$('#img').attr('src', '../img/user.jpg');
				}
			});
		});
 	</script>
 @stop