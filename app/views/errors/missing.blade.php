<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>404 - Não encontrado.</title>
	<link href="{{Config::get('define.urlPadrao')}}packages/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
	<style type="text/css" media="screen">
		body { background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAxMC8yOS8xMiKqq3kAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzVxteM2AAABHklEQVRIib2Vyw6EIAxFW5idr///Qx9sfG3pLEyJ3tAwi5EmBqRo7vHawiEEERHS6x7MTMxMVv6+z3tPMUYSkfTM/R0fEaG2bbMv+Gc4nZzn+dN4HAcREa3r+hi3bcuu68jLskhVIlW073tWaYlQ9+F9IpqmSfq+fwskhdO/AwmUTJXrOuaRQNeRkOd5lq7rXmS5InmERKoER/QMvUAPlZDHcZRhGN4CSeGY+aHMqgcks5RrHv/eeh455x5KrMq2yHQdibDO6ncG/KZWL7M8xDyS1/MIO0NJqdULLS81X6/X6aR0nqBSJcPeZnlZrzN477NKURn2Nus8sjzmEII0TfMiyxUuxphVWjpJkbx0btUnshRihVv70Bv8ItXq6Asoi/ZiCbU6YgAAAABJRU5ErkJggg==); }
		.error-template { padding: 40px 15px;
						  text-align: center;
						}
		.error-actions { margin-top:15px;
						 margin-bottom:15px;
						}
		.error-actions .btn { margin-right:10px; }
	</style>
</head>
<body>

	<div class="container">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="error-template">
	                <h1> Oops! </h1>
	                <h2> 404 - Não encontrado </h2>
	                <div class="error-details">
	                    O Formulário que você tentou acessar não existe.
	                </div>
	                <div class="error-actions">
	                    <a href="{{URL::to('')}}" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>
	                        Ir para o Início
                        </a>
                        <a href="mailto:teste@teste.com" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-envelope"></span> Enviar Mensagem</a>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<script src="{{Config::get('define.urlPadrao')}}packages/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
</body>
</html>