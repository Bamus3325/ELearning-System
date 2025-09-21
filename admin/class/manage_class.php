<?php
include "../../initialize.php";
include "../../config.php";
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM class where id =".$_GET['id']);
	$data = $qry->fetch_array();
	foreach ($data as $key => $value) {
		$$key = $value;
	}
}
?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div id="msg" class="form-group"></div>
		<form action="" id="manage_class">
			<input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
			<div class="form-group">
				<label for="department_id" class="control-label">Department</label>
				<select name="department_id" id="department_id" class="custom-select custom-select-sm select2" required="">
					<?php 
					$department = $conn->query("SELECT * from department order by department desc");
					while($row = $department->fetch_assoc()):
					?>
						<option value="<?php echo $row['id'] ?>" <?php echo isset($department_id) && $department_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['department'] ?></option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="course_id" class="control-label">Course</label>
				<select name="course_id" id="course_id" class="custom-select custom-select-sm select2" required="">
					<?php 
					$course = $conn->query("SELECT * from course order by course desc");
					while($row = $course->fetch_assoc()):
					?>
						<option value="<?php echo $row['id'] ?>" <?php echo isset($course_id) && $course_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['course'] ?></option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="level" class="control-label">Level</label>
				<select class="form-control" name="level">
				<option value="">--Select Level--</option>
				<option>HND II</option>
				<option>HND I</option>
				<option>ND II</option>
				<option>ND I</option>
				</select>
			</div>
			<div class="form-group">
				<label for="level" class="control-label">Lecturer in Charge</label>
				<select name="lecturer" id="lecturer" class="custom-select custom-select-sm select2" required="">
					<?php 
					$course = $conn->query("SELECT * from faculty");
					while($row = $course->fetch_assoc()):
					?>
						<option value="<?php echo $row['id']; ?>"><?php echo ucwords($row['lastname'].", ".$row['firstname'].' '.$row['middlename']) ?></option>
					<?php endwhile; ?>
					
				</select>

			</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('.select2').select2();
		$('#manage_class').submit(function(e){
			e.preventDefault();
			start_loader();
			$('#msg').html('')
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_class",
				method:"POST",
				data:$(this).serialize(),
				error:err=>{
					console.log(err)
					alert_toast("An error occured.","error")
					end_loader();
				},
				success:function(resp){
					if(resp == 1){
						location.reload()
					}else if(resp ==2){
						$('#msg').html('<span class="alert alert-danger w-fluid">Class already Exist.</span>')
						end_loader();
					}else{
						console.log(resp)
						end_loader();
					}
				}
			})

		})
	})
</script>