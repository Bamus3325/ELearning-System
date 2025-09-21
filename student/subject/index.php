
<div class="card card-outline cardprimary w-fluid">
	<div class="card-header">
		<h3 class="card-title">My Course List</h3>
		<!-- <div class="card-tools">
			<a class="btn btn-block btn-sm btn-default btn-flat border-primary new_class" href="javascript:void(0)"><i class="fa fa-plus"></i> Add New Lesson</a>
		</div> -->
	</div>
	<div class="card-body">
		<table class="table table table-compact table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Course Code</th>
					<th>Description</th>
					
					<!-- <th>Action</th> -->
				</tr>
			</thead>
			<tbody>
				<?php
				$userlevel = $_settings->userdata('level');
				$userdept = $_settings->userdata('department');
				$i =1;
				$qry = $conn->query("SELECT * FROM course WHERE department = '$userdept' and level = '$userlevel'");
				while($row=$qry->fetch_assoc()):
				?>
				<tr>
					<td><?php echo $i++ ?></td>
					<!-- <td>
						<?php
						$id = $row['department']; 
						$department = $conn->query("SELECT * from department where id = '$id' ");
						$row1 = mysqli_fetch_array($department);
						echo $row1['department'];						?>
					</td>
					<td><?php echo $row['level'] ?></td> -->
					<td><?php echo $row['course'] ?></td>
					<td><span class="truncate"><?php echo $row['description'] ?></span></td>
					<!-- <td class="text-center">
						<div class="btn-group">
		                    <button type="button" class="btn btn-default btn-block btn-flat dropdown-toggle dropdown-hover dropdown-icon btn-sm" data-toggle="dropdown" aria-expanded="false">
		                    	Action
		                      <span class="sr-only">Toggle Dropdown</span>
		                    </button>
		                    <div class="dropdown-menu" role="menu" style="">
		                      <a class="dropdown-item action_edit" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item action_delete" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
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
		$('table th,table td').addClass('px-1 py-0')
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