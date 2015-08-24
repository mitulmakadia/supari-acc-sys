@extends('masterhome')
@section('content')
  <form>
     <h2><span class="label label-primary">Payment</span></h2>
	 <div id="Date" align="left"></div>
    <form id="salesDetailForm">
					<div class="data-container">
						<table id="st" class="table table-striped table-bordered tablew table-hover" cellspacing="0" width="100%">
							 <table class="table table-bordered">
        <thead>
            <tr>
                <th>Cr/Dr</th>
                <th>Name</th>
                <th>Credit</th>
                <th>Debit</th>
				<th>Current Balance</th>
            </tr>
        </thead>
		<tbody>
		</tbody>
        <tfoot>
            <tr>
                <td><input type="text" class="crdr" id="crdr" onkeypress="return tabE(this,Event)"></td>
                <td><input type="text" class="name" id="name" onkeypress="return tabE(this,Event)"></td>
                <td><input type="number" class="credit" id="credit" onkeypress="return tabE(this,Event)"></td>
                <td><input type="number" class="debit" id="debit" onkeypress="return tabE(this,Event)"></td>
				<td><input type="text" class="cbalance" id="cbalance" onkeypress="return tabE(this,Event)"></td>
          </tr>
		</tfoot>
	
    </table>
					</div>
             
				</form>
				
				<button  id="add" class="btn btn-success btn-block">Submit Entries</button>					<!--data-records ends-->
  </body>
 
 
 
 
 
 
 
 
   <script type="text/javascript">
var monthNames = [ "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December" ];
var dayNames= ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]

var newDate = new Date();
newDate.setDate(newDate.getDate() + 1);    
$('#Date').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());
  
  </script>
  <script>
  $(document).ready( function() {
	var data3 = [
  "cr",
  "dr"
  	];

	var data2 = [
  "shaun",
  "paul",
  "max",
  "peter",
  "maruti"
  	];
	
  $( "#crdr" ).focus();
 
 
  
	
  $( ".name" ).autocomplete({
      source:data2,
      autoFocus: true ,
    });
	$( ".crdr" ).autocomplete({
      source:data3,
      autoFocus: true ,
	  });
	
	 
    $("#add").on("click",function() {
		var crdr = $("#crdr").val();
		var name = $("#name").val();
		var credit = $("#credit").val();
		var debit = $("#debit").val();
	//	var cbalance = $("#cbalance").val();

 
		
		var tableData = "<tr><td>"+ crdr +"</td> <td>"+ name +"</td> <td>"+ credit +"</td> <td>"+ debit +"</td><td>"+ cbalance +"</td></tr>";
		$("tbody").append(tableData);
		$("#crdr").val('');
		$("#name").val('');
		$("#credit").val('');
		$("#debit").val('');
	//	$("#cbalance").val('');

	});
	
  $('#crdr').keypress(function(e) {
		if ($('#crdr').val() == '') {
			$('#crdr').focus();
		}
        else {
			 $('#name').focus();
		}
			
	
  });
   
   $('#name').keypress(function(e){
	    if($('#crdr').val() == 'cr'){
			if (e.keyCode == 13)
			$('#credit').focus();
		}
		else{ 
		    if (e.keyCode == 13)
			$('#debit').focus();


			}
		});
   });
   
   
	
	




 
       function tabE(obj, e) {
        var e = (typeof event != 'undefined') ? window.event : e; // IE : Moz 
        if (e.keyCode == 13) {
            var ele = document.forms[0].elements;
            for (var i = 0; i < ele.length; i++) {
                var q = (i == ele.length - 1) ? 0 : i + 1; // if last element : if any other 
                if (obj == ele[i]) {
                    ele[q].focus();
                    break
                }
            }
            return false;
        }}

	
  </script>
  <style>
.label
{
margin-left:12px;
}
 </style>
  @stop

 