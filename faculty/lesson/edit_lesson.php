<?php

	$qry = $conn->query("SELECT * FROM lessons where id = {$_GET['id']}");
    $row = mysqli_fetch_array($qry);
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and collect the form data
    $title = isset($_POST['title']) ? mysqli_real_escape_string($conn, $_POST['title']) : '';
    $subject_id = isset($_POST['subject_id']) ? mysqli_real_escape_string($conn, $_POST['subject_id']) : '';
    $class_id = isset($_POST['class_ids']) ? mysqli_real_escape_string($conn, $_POST['class_ids']) : ''; // Single class_id
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';
    $faculty_id = isset($_POST['faculty_id']) ? mysqli_real_escape_string($conn, $_POST['faculty_id']) : '';
    $academic_year_id = isset($_POST['academic_year_id']) ? mysqli_real_escape_string($conn, $_POST['academic_year_id']) : '';

    // Debug: Display class_id for troubleshooting
    //echo "<script>alert('Class ID: " . $class_id . "');</script>";

    // Check if the required fields are not empty
    if (!empty($title) && !empty($subject_id) && !empty($class_id) && !empty($description)) {
        // Insert data into the `lessons` table (including class_id)
        $sql = "UPDATE lessons SET title = '$title', subject_id = '$subject_id', class_id = '$class_id', 
                                    description = '$description', faculty_id = '$faculty_id', 
                                    academic_year_id = '$academic_year_id' WHERE id = '{$_GET['id']}'"; 
         

        if (mysqli_query($conn, $sql)) {
            // Success: Set flash message and redirect
            $_settings->set_flashdata('success', 'Lesson Updated successfully!');
            echo "<script>window.location = './?page=lesson';</script>";
        } else {
            // Error handling for the lesson insertion
            echo "Error inserting lesson: " . mysqli_error($conn);
        }
    } else {
        // If fields are missing, show an alert
        echo "<script>alert('Please fill all required fields.');</script>";
    }
}
?>


<?php if ($_settings->chk_flashdata('success')): ?>
<script>
    alert_toast("<?php echo $_settings->flashdata('success'); ?>", 'success');
</script>
<?php endif; ?>


<style>
    .form-group.note-form-group.note-group-select-from-files {
        display: none;
    }
</style>
<?php

$academic_year_id= $_settings->userdata('academic_id');
$faculty_id= $_settings->userdata('id');

// if(isset($_GET['id'])){
// 	$qry = $conn->query("SELECT * FROM lessons where id = {$_GET['id']}");
//     $row = mysqli_fetch_array($qry);

	// foreach($qry->fetch_array() as $k =>$v){
	// 	if(!is_numeric($k))
	// 	$$k = $v;
	// }
	// if(isset($description))
	// $description = html_entity_decode(stripslashes($description));
	// $class_arr = array();
	// $qry2 = $conn->query("SELECT * FROM lesson_class where lesson_id = {$_GET['id']}");
	// while($row = $qry2->fetch_assoc()){
	// 	$class_arr[] = $row['class_id'];
	// }
// }
?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title"></h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="faculty_id" value="<?php echo $faculty_id ?>">
                <input type="hidden" name="academic_year_id" value="<?php echo $academic_year_id ?>">

                <div class="form-group">
                    <label for="" class="control-label">Title</label>
                    <input type="text" class="form-control" value="<?php echo $row['title'] ?>" name="title" required="">
                </div>

                <div class="form-group">
                    <label for="subject_id" class="control-label">Course</label>
                    <select name="subject_id" id="subject_id" class="custom-select custom-select-sm select2" required="">
                    <?php
                    $oldid = $row['subject_id'];
                    $oldsubject = $conn->query("SELECT * from course WHERE id = '$oldid'");
                    $ff = mysqli_fetch_array($oldsubject);
                    ?>    
                    <option value="<?php echo $ff['id']; ?>"><?php echo $ff['course']; ?></option>
                        <?php
                        // Fetch subjects
                        $subject = $conn->query("SELECT * from course");
                        while ($row1 = $subject->fetch_assoc()):
                        ?>
                            <option value="<?php echo $row1['id']; ?>"><?php echo $row1['course']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="class_id" class="control-label">Class</label>
                    <select name="class_ids" id="class_id" class="custom-select custom-select-sm select2" required="">
                    <?php
                    $oldidd = $row['class_id'];
                    $oldclass = $conn->query("SELECT * from class WHERE id = '$oldidd'");
                    $fff = mysqli_fetch_array($oldclass);
                    ?> 
					<option value="<?php echo $fff['id']; ?>"><?php echo $fff['level']; ?></option>
						<?php
                        // Fetch classes for the logged-in faculty
                        $class = $conn->query("SELECT * FROM class WHERE lecturer = '$faculty_id'");
                        while ($row2 = $class->fetch_assoc()):
                        ?>
                            <option value="<?php echo $row2['id']; ?>"><?php echo $row2['level']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="" class="control-label">Description</label>
                    <textarea name="description" id="" cols="30" rows="10" class="form-control summernote"><?php echo $row['description']; ?></textarea>
                </div>
                
            
        </div>
    </div>
    <div class="card-footer">
        <div class="col-md-12">
            <button class="btn btn-flat btn-primary" type="submit">Update</button>
            <a class="btn btn-flat btn-default" href="./?page=lesson">Cancel</a>
        </div>
    </div>
	</form>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();

        var customMediaButton = function(context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-photo-video"></i> Media',
                tooltip: 'List All uploaded media to copy file link or short codes',
                click: function() {
                    context.invoke('editor.foreColor', 'red');
                    uni_modal("Media List", "file_uploads/list_uploads.php", "mid-large");
                }
            });
            return button.render();
        };

        $('.summernote').summernote({
            height: '50vh',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ol', 'ul', 'paragraph', 'height']],
                ['table', ['table', 'picture', 'video', 'media']],
                ['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
            ],
            buttons: {
                media: customMediaButton
            }
        });
    });
</script>
