<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif; ?>
<div class="card card-outline cardprimary w-fluid">
	<div class="card-header">
		<h3 class="card-title">My Class List</h3>
	</div>
	<div class="card-body">
		<table class="table table-hover table-compact table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Department</th>
					<th>Class</th>
					<th>Course</th>
					<!-- <th>Action</th> -->
				</tr>
			</thead>
			<tbody>
				<?php 
				$i =1;
				$academic_year_id= $_settings->userdata('academic_id');
				$faculty_id= $_settings->userdata('id');

				$qry = $conn->query("SELECT * FROM class where lecturer = '$faculty_id'");
				while($row=$qry->fetch_assoc()):
				?>
				<tr>
					<td><?php echo $i++ ?></td>
					<td><?php
						$id = $row['department_id']; 
						$department = $conn->query("SELECT * from department where id = '$id' ");
						$row1 = mysqli_fetch_array($department);
						echo $row1['department'];						?></td>
					<td><?php echo $row['level'] ?></td>
					<td><span class="truncate"><?php
						$id = $row['course_id']; 
						$course = $conn->query("SELECT * from course where id = '$id' ");
						$row1 = mysqli_fetch_array($course);
						echo $row1['course'];						?></span></td>
					<!-- <td class="text-center">
						<div class="btn-group">
		                    <button type="button" class="btn btn-default btn-block btn-flat dropdown-toggle dropdown-hover dropdown-icon btn-sm" data-toggle="dropdown" aria-expanded="false">
		                    	Action
		                      <span class="sr-only">Toggle Dropdown</span>
		                    </button>
		                    <div class="dropdown-menu" role="menu" style="">
	                    	 <a class="dropdown-item action_load" href="javascript:void(0)" data-id="<?php echo $row['class_id'].'_'.$row['subject_id'] ?>">View Lesson</a>
		                    </div>
		                </div>
					</td>	 -->
				</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('.new_class').click(function(){
			uni_modal("New Class","./class/manage_class.php")
		})
		$('.action_edit').click(function(){
			uni_modal("Edit Class","./class/manage_class.php?id="+$(this).attr('data-id'));
		})
		$('.action_load').click(function(){
			uni_modal("Load Class Subjects","./class/load_subject.php?id="+$(this).attr('data-id'));
		})
		$('.action_delete').click(function(){
		_conf("Are you sure to delete class?","delete_class",[$(this).attr('data-id')])
		})
		$('table th,table td').addClass('px-1 py-0 align-middle')
		$('table').dataTable();
	})
	function delete_class($id){
		start_loader()
		$.ajax({
			url:_base_url_+'classes/Master.php?f=delete_class',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					location.reload()
				}
			}
		})
	}
</script>