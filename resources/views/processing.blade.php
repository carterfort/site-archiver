@extends('layouts.main')
@section('main')

<style>
	div.col {
		width: 28.5%;
		float: left;
		margin: 2.5%;
	}
	div.col:first-of-type {
		margin-left:0;
	}

	div.col:last-of-type {
		margin-right:0;
	}
</style>
<div id="terminal">

	<div class="title" v-html="titleMessage">
	</div>

	<br/>

	<p>Session {{$sessionID}}</p>

	<div class="col">
		<h3>Loaded Resources</h3>
		<p v-for="url in loadedUrls.slice(0,3)">
			@{{ url }}
		</p>
	</div>
	<div class="col">
		<h3>Archived Resources</h3>
		<p v-for="url in archivedUrls.slice(0,3)">
			@{{ url }}
		</p>
	</div>
	<div class="col">
		<h3>Latest Image</h3>
		<img v-if="imageSource" v-bind:src="imageSource" style="width:100%" />
	</div>


</div>

@stop

@section('scripts')

	<script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>
	<script>
		var socket = io('//:3000');

		new Vue({
			el : '#terminal',
			data : {
				titleMessage: "Processing...",
				loadedUrls : [],
				archivedUrls : [],
				imageSource : false,
			},
			ready (){

				socket.on("session-progress:{{$sessionID}}:App\\Events\\ResourceWasLoaded", function(message){
		            if(message.url)
			            this.loadedUrls.unshift(message.url)

		        }.bind(this));

				socket.on("session-progress:{{$sessionID}}:App\\Events\\UrlWasArchived", function(message){
		            if(message.url)
			            this.archivedUrls.unshift(message.url)

		        }.bind(this));

		        socket.on("session-progress:{{$sessionID}}:App\\Events\\ImageWasDownloaded", function(message){
		            if(message.image)
			        	this.imageSource = "data:image/jpeg;base64, "+message.image;

		        }.bind(this));

		        socket.on("session-progress:{{$sessionID}}:App\\Events\\ArchiveComplete", function(message){
		            this.archiveComplete();
		        }.bind(this));     
			},
			methods : {
				archiveComplete(){ 
					this.titleMessage = 'Archive complete!<br/><small>Downloading now...</small>';
					window.location = "{{$redirectUrl}}";
				}
			}
		});
	</script>

@stop