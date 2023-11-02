<?php
	foreach($category_data as $row){
?>
	<div class="tab-pane fade active in" id="edit">
		<?php
			echo form_open(base_url() . 'index.php/admin/pre_order/update/' . $row['id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'pre_order_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('start_date');?>
                        	</label>
					<div class="col-sm-6">
						<input type="datetime-local" name="start_date"  
                        	value="<?php echo $row['start_date'];?>" id="datetimepicker" 
                            	class="form-control required" placeholder="<?php echo translate('start_date');?>" >
					</div>
				</div>
                
			</div>


            <div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('end_date');?>
                        	</label>
					<div class="col-sm-6">
						<input type="datetime-local" name="end_date"  
                        	value="<?php echo $row['end_date'];?>" id="datetimepicker1" 
                            	class="form-control required" placeholder="<?php echo translate('end_date');?>" >
					</div>
				</div>
                
			</div>

			<div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                	<?php echo translate('description');?>
                </label>
                <div class="col-sm-6">
                <textarea class="summernotes" name="description" data-height="700" data-name="content" rows="20" style="width: 100%;"><?php echo  $row['description'];?></textarea>
                </div>
            </div>
            
        </div>
		</form>
	</div>
<?php
	}
?>
<script>
// $(function(){
//     var dtToday = new Date();
    
//     var month = dtToday.getMonth() + 1;
//     var day = dtToday.getDate();
//     var year = dtToday.getFullYear();
//     if(month < 10)
//         month = '0' + month.toString();
//     if(day < 10)
//         day = '0' + day.toString();
    
//     var maxDate = year + '-' + month + '-' + day;

//     // or instead:
//     // var maxDate = dtToday.toISOString().substr(0, 10);

//    // alert(maxDate);
//     $('#datetimepicker').attr('min', maxDate);
// });
$(function(){
    var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    var hours = dtToday.getHours();
    var minutes = dtToday.getMinutes();
    
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    if(hours < 10)
        hours = '0' + hours.toString();
    if(minutes < 10)
        minutes = '0' + minutes.toString();
    
    var minDateTime = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
    console.log("0-", minDateTime);
    //$('#datetimepicker').attr('min', minDateTime);

    var currentHourTime = hours + ':' + minutes;
    //$('#datetimepicker').attr('value', minDateTime); // Set the initial value to the minDateTime

    // Update the input value if user changes the date/time manually
    $('#datetimepicker').on('change', function () {
        var selectedDateTime = $(this).val();
        if (selectedDateTime < minDateTime) {
            $(this).val(minDateTime);
        }
    });
    $('#datetimepicker1').on('change', function () {
        var selectedDateTime = $(this).val();
        if (selectedDateTime < minDateTime) {
            $(this).val(minDateTime);
        }
    });
});
</script>
<script>
	$(document).ready(function() {
	    $("form").submit(function(e) {
	        return false;
	    });
	});
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
	
			reader.onload = function(e) {
				$('#wrap').hide('fast');
				$('#blah').attr('src', e.target.result);
				$('#wrap').show('fast');
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#imgInp").change(function() {
		readURL(this);
	});
	
</script>