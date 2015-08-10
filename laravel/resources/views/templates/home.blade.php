@extends('masterlogin')

@section('content')
<!--navbar starts-->
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
		<div class="navbar-header"> 
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>                        
			</button>
			<img src="images/catalog/sas/sas.jpg" width="39" height="39"> 
			<a class="navbar-brand myColor" href="#">SAS</a>
		</div>
    <div class="collapse navbar-collapse" id="myNavbar">
		<ul class="nav navbar-nav">
			<li class=""><a href="#">Master</a></li>
			<li class=""><a href="#">Voucher Entry</a></li>
			<li><a class="" href="#">Display</a></li>
			<li><a class="" href="#">Admin</a></li>
		</ul>
		
		<ul class="nav navbar-nav navbar-right">
			<div class="dropdown">
				<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
				<img src="https://marutitech.atlassian.net/secure/useravatar?size=small&amp;ownerId=ankur&amp;avatarId=10500" alt="User profile for Ankur Vyas">
				Profile<span class="caret"></span></button>
		
				<ul class="dropdown-menu">
					<li><a href="#">User Stats <span class="glyphicon glyphicon-stats"></span></a></li>
					<li><a href="#">Account Setting <span class="glyphicon glyphicon-cog"></span></a></li>
					<li><a href="{{ URL::route('home.show') }}">Logout <span class="glyphicon glyphicon-lock"></li></a></li>
				</ul>
			</div>
		</ul>
	</div>
    </div>
</nav>

<script>
$(document).ready(
  
  /* This is the function that will get executed after the DOM is fully loaded */
  function () {
  $("#datepicker").datepicker().datepicker('setDate',new Date());
    $( "#datepicker" ).datepicker({
      changeMonth: true,//this option for allowing user to select month
      changeYear: true //this option for allowing user to select from year range
    });
  }

);
</script>

@stop
