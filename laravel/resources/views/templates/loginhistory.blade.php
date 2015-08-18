@extends('masterhome')
@section('content')
   <div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">Login History</div>
				<div class="panel-body">
					<div class="first">
						<form class="form-inline" role="form">
							<div class="form-group">
								<label>Search :</label>
								<input type="Search" class="form-control search" placeholder="Enter Name" onkeypress="return tabE(this,Event)">
							</div>
									<div class="form-group">
										<label>Date :</label>
										<input type="text" class="form-control datepicker"onkeypress="return tabE(this,Event)">
									</div>
											<div class="form-group">
												<label>To Date :</label>
												<input type="text" class="form-control datepicker" onkeypress="return tabE(this,Event)">      
												<button type="submit" class="btn btn-success"> GO </button>
						</form>
											</div>
					</div>
	<!-- Modal -->
				</div>
		</div>
    <hr>
    <table id="results" class="table table-bordered keyword">
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
  
  <div id="pageNavPosition"></div>
  </div>
  
  <script type="text/javascript">
       var pager = new Pager('results', 10); 
       pager.init(); 
       pager.showPageNav('pager', 'pageNavPosition'); 
       pager.showPage(1);
   </script>
 
@stop
