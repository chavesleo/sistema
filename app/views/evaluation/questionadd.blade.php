@extends('layouts.default')

@section('conteudo')

	<div class="col-sm-9 col-md-10 affix-content">

		<div class="container">

			<div class="row"> &nbsp; </div>

			{{-- AVISOS DE ALERTA DA SESSAO--}}
			@if (Session::has('alert'))
				<div class="row">
					<div class="alert alert-{{Session::get('alert.tipo')}} alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
						<p>{{Session::get('alert.texto')}}</p>
					</div>
					{{Session::forget('alert')}}
				</div>
			@endif
			@if( count($errors) > 0)
				@foreach ($errors->all() as $msg)
				<div class="row">
					<div class="alert alert-warning alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
						<p>
							<span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
							&nbsp;&nbsp;&nbsp;{{$msg}}
						</p>
					</div>
				</div>
				@endforeach
			@endif

			<div class="row">

				<div class="panel panel-default">

					<div class="panel-heading">
						<div class="row">
							<div class="col-md-10">
								<h4>{{$evaluation->title}} <small>{{$evaluation->description}}</small></h4>
							</div>
							<div class="col-md-2">
								<button type="button" class="btn btn-primary pull-right action-usr" pk="1" title="{{Lang::get('textos.tit_adicionar')}}" data-toggle="modal" data-target="#modalNewEva">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
									&nbsp;&nbsp;{{Lang::get('textos.tit_adicionar')}}
									</a>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection