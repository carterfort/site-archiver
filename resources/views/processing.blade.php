@extends('layouts.main')
@section('main')

<div class="title">Processing...</div>
<br/>
<p>Session {{$sessionID}}</p>

<div id="terminal">
	<p v-for="url in urls">
		@{{ url }}
	</p>
</div>

@stop

@section('scripts')

	<script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>
	<script>
		var socket = io('//:3000');

		new Vue({
			el : '#terminal',
			data : {
				resources : [],
				urls : ['http://outtolunchproductions.com']
			},
			ready (){
				socket.on("session-progress:{{$sessionID}}:App\\Events\\UrlWasArchived", function(message){

					console.log(message);
					
		            if(message.url)
			            this.urls.push(message.url)

		        }.bind(this));
			}
		});
	</script>

@stop