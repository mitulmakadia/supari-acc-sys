@extends('masterhome')
@section('content')
  
    <div class="container">
		@if(Session::has('flash_message'))
							<div class="alert alert-success">
								{{ Session::get('flash_message')}}
							</div>
		@endif
		<div class="panel panel-default">
			<div class="panel-heading">Manage Users</div>
				<div class="panel-body">
					<div class="first" id="right">
							Search<input type="text" name="search" class="search" value="">
							<button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#myModal">Create User</button>
					</div>
						<div class="modal fade" id="myModal" role="dialog">
							<div class="modal-dialog modal-md">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Add Users</h4>
									</div>
										<div class="modal-body">
											<!--{!!Form::open(['route' => 'manageusers.store', 'method' => 'POST', 'files' => true])!!}-->
											<form>
											 <input type="hidden" name="_token" value="{{ csrf_token() }}">
												<input type="First" name="FirstName" id="FirstName" class="form-control" placeholder="First Name" required="" autofocus="" onkeypress="return tabE(this,Event)"><br>
												<input type="Last" name="LastName" id="LastName" class="form-control" placeholder="Last Name" required="" autofocus="" onkeypress="return tabE(this,Event)"><br>
												<input type="Email" name="Email" id="Email" class="form-control" placeholder="Email" required="" autofocus="" onkeypress="return tabE(this,Event)"><br>
												<p>Upload Profile Picture</p><input type="file" name="datafile" onkeypress="return tabE(this,Event)">
								        </div>
												<div class="modal-footer">
													<button type="button" id="submit" class="btn btn-danger btn-block">Create</button>
												</div>
											</form>
											<!--{!!Form::close()!!}	-->
											
								</div>
							</div>
						</div>
				</div>
		</div>
    <hr>
    <table class="table table-bordered table-striped table-hover keyword" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th>Firstname</th>
				<th>Lastname</th>
				<th>Email</th>
				<th>Last Login</th>
			</tr>
		</thead>
		<tbody>
				@foreach ($users as $user)
					<tr>
						<td>{{ $user->FirstName }}</td>
						<td>{{ $user->LastName }}</td>
						<td>{{ $user->Email }}</td>
						<td>{{ $user->LastLogin }}</td>
					</tr>			
				@endforeach
			
		
		
		</tbody>
	</table>
</div>
<script>
/*
 $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          } 
     });
$(document).ready(function(){
$("#submit").on("click",function(){
var FirstName = $("#FirstName").val();
var LastName = $("#LastName").val();
var Email = $("#Email").val();

// Returns successful data submission message when the entered information is stored in database.
       var dataString = 'FirstName='+ FirstName + '&LastName='+ LastName + '&Email='+ Email;
       if(FirstName==''||LastName==''||Email=='')
        {
            alert("Please Fill All Fields");
        }
        else
        {
			// AJAX Code To Submit Form.
		$.ajax({
		type: "GET",
		url: "adduser",
		data: datastring,
		success: function(){
		
		}
	});		
		}	});
	});
*/
$(document).ready(function(){
  $('#submit').click(function(){    
   
          if($('input[name=Email]').val()==''||$('input[name=FirstName]').val()==''||$('input[name=LastName]').val()=='')
		  {
			    alert("Please Enter all fields");
		  }			  
		  else
		  {
               $.ajax({
               url: 'adduser',
               type: "post",
               data: {'Email':$('input[name=Email]').val(),'FirstName':$('input[name=FirstName]').val(),'LastName':$('input[name=LastName]').val(), '_token': $('input[name=_token]').val()},
		 });
 
      
    }     

  }); 
});
</script>
@stop