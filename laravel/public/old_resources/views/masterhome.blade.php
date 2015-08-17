<!DOCTYPE html>
 <html lang="en">
 <head>
   <title>Bootstrap Example</title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
   <!-- <link rel="stylesheet" type="text/css" href="loginpage.css">-->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
   <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
   <!-- Load jQuery from Google's CDN -->
   <!-- Load jQuery UI CSS  -->
   <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
   <!-- Load jQuery JS -->
   <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
   <!-- Load jQuery UI Main JS  -->
   <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <link rel="stylesheet" href="animate.css">
		<link rel="stylesheet" href="/resources/views/static/css/home.css">
	<script src="/resources/views/static/js/script.js"></script>
    <script>
       $(document).ready(
			/* This is the function that will get executed after the DOM is fully loaded */
		function () {
			$( ".datepicker").datepicker({
			changeMonth: true,//this option for allowing user to select month
			changeYear: true //this option for allowing user to select from year range
				});
			}
		);
	</script>
	</head>
	<body>
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
								<li class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" href="#">Admin<span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="{{ URL::to('manageusers') }}">Manage Users <span class="glyphicon glyphicon-tasks"></span></a></li>
											<li><a href="{{ URL::to('loginhistory') }}">Login History <span class="glyphicon glyphicon-list-alt"></a></li>
										</ul>
								</li>
						</ul>
							<ul class="nav navbar-nav navbar-right">
								<div class="dropdown">
									<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span>
										Profile<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a href="#">User Stats <span class="glyphicon glyphicon-stats"></span></a></li>
											<li><a href="#">Account Setting <span class="glyphicon glyphicon-cog"></span></a></li>
											<li><a href="{{ URL::route('home.show') }}">Logout   <span class="glyphicon glyphicon-lock"></li></a></li>
										</ul>
								</div>
							</ul>
					</div>
		</div>
	</nav>
    @yield('content')
</body>
</html>