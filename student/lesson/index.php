<?php if($_settings->chk_flashdata('success')): ?>
<script>
alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
</script>
<?php endif; ?>
<div class="card card-outline cardprimary w-fluid">
    <div class="card-header">
        <h3 class="card-title">Lessons</h3>

    </div>
    <div class="card-body">
        <table class="table table-hover table-compact table-striped">
            <colgroup>
                <col width="5%">
                <col width="20%">
                <col width="20%">
                <col width="40%">
                <col width="15%">
            </colgroup>
            <thead>

                <tr class='bg-light'>
                    <th>#</th>
                    <th>Title</th>
                    <th>Course</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    <?php 
    $i = 1;
    $academic_year_id = $_settings->userdata('academic_id');
    $student_id = $_settings->userdata('student_id');
    $userlevel = $_settings->userdata('level');
    $userdept = $_settings->userdata('department');
    
    // echo $userdept;
    // echo $userlevel;

    // First query to get the classes based on user level and department
    $oldqry = $conn->query("SELECT * FROM class WHERE level = '$userlevel' AND department_id = '$userdept'");
    
    while ($ft = mysqli_fetch_assoc($oldqry)):
        $classid = $ft['id'];

        // Second query to get lessons for the current class
        $qry = $conn->query("SELECT * FROM lessons WHERE class_id = '$classid'");

        while ($row = mysqli_fetch_assoc($qry)):
            // Check if $row is valid and continue processing the lesson details
            if ($row) {
                // Process the lesson description safely
                $desc = html_entity_decode($row['description']);
                $desc = stripslashes($desc);
                $desc = strip_tags($desc);

                // Fetch subject name based on the subject_id in the lesson
                $id = $row['subject_id']; 
                $subject = $conn->query("SELECT * from course WHERE id = '$id'");
                $fet = mysqli_fetch_array($subject);
                $subject_name = $fet['course'];
            } else {
                // Handle the case where no lessons are found for the class
                $desc = "No lessons available.";
                $subject_name = "N/A";
            }
            ?>

            <tr>
                <td><?php echo $i++ ?></td>
                <td><?php echo $row['title'] ?></td>
                <td>
                    <?php echo $subject_name; ?>
                </td>
                <td>
                    <span class="truncate" title="<?php echo htmlspecialchars($desc, ENT_QUOTES); ?>">
                        <?php echo strlen($desc) > 100 ? substr($desc, 0, 50) . '...' : $desc; ?>
                    </span>
                </td>
                <td class="text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-block btn-flat dropdown-toggle dropdown-hover dropdown-icon btn-sm" data-toggle="dropdown" aria-expanded="false">
                            Action
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item action_load" href="./?page=lesson/view_lesson&id=<?php echo $row['id']; ?>">View Lesson</a>
                        </div>
                    </div>
                </td>
            </tr>

        <?php endwhile; ?>
    <?php endwhile; ?>
</tbody>

        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.new_lesson').click(function() {
        location.href = "./?page=lesson/manage_lesson";
    })
    $('.action_edit').click(function() {
        uni_modal("Edit lesson", "./lesson/manage_lesson.php?id=" + $(this).attr('data-id'));
    })

    $('.action_delete').click(function() {
        _conf("Are you sure to delete lesson?", "delete_lesson", [$(this).attr('data-id')])
    })
    $('table th,table td').addClass('px-1 py-0 align-middle')
    $('table').dataTable();
})

function delete_lesson($id) {
    start_loader()
    $.ajax({
        url: _base_url_ + 'lessones/Master.php?f=delete_lesson',
        method: 'POST',
        data: {
            id: $id
        },
        success: function(resp) {
            if (resp == 1) {
                location.reload()
            }
        }
    })
}
</script>