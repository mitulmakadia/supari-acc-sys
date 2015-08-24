@extends('masterhome')
@section('content')
   <div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">Login History</div>
				<div class="panel-body">
				<label for="history">View Login History here!</label>	
				</div>
      </div>
	<!-- Modal -->
			
		
    <hr>
	<div class="table-responsive">
    <table id="myTable" class="table table-bordered table-striped keyword">
    <thead>
      <tr>
        <th>Name</th>
        <th>IP Address</th>
        <th>Date</th>
		<th>Result</th>
      </tr>
	  </thead>
	   <tbody>
	  @foreach ($users as $user)
		<tr>
	
			<td>{{ $user->Name }}</td>
			<td>{{ $user->IPAddress }}</td>
			<td>{{ $user->LastLogin }}</td>
			@if (($user->Result) === 1)
				<td>Success</td>
			@else
				<td>Fail</td>
			@endif
			
		</tr>			
	  @endforeach
    
   
     </tbody>
  </table>
  </div>
  
  <div id="pageNavPosition"></div>
  </div>
 
 <script>
$(document).ready(function(){
    $('#myTable').dataTable();
});
</script>

  
 <style>
 body{
background-color: aliceblue;
 }
hr {
border-top: 3px solid #2C3E50;}
 </style>
@stop
