<?php
function base_url($uri = '') {
    // Define the base URL of your application (update this for production if necessary)
    $base_url = "http://localhost/elearning";  // Change this to the correct base URL if needed
    
    // Append the URI passed as an argument (if any)
    return rtrim($base_url, '/') . '/' . ltrim($uri, '/');
}

?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
</script>
<?php endif; ?>
<?php
$id = isset($_GET['id']) ? $_GET['id']: '';
$faculty_id= $_settings->userdata('id');

				
if(!empty($id)){
    // $qry = $conn->query("SELECT l.*,CONCAT(f.firstname,' ',f.middlename,' ',f.lastname) as fname, CONCAT(s.subject_code,' - ',s.description) as subj FROM lessons l inner join faculty f on f.faculty_id = l.faculty_id inner join subjects s on s.id = l.subject_id where l.id = $id");
    // foreach($qry->fetch_array() as $k =>$v){
    //     if(!is_numeric($k)){
    //         $$k = $v;
    //     }
    // }
    // $description = stripslashes($description);


    $qry = $conn->query("SELECT * FROM lessons WHERE faculty_id = '$faculty_id' and id = '$id'");
    $row = mysqli_fetch_array($qry);

}
?>

<div class="card card-outline cardprimary w-fluid">
    <div class="card-header">
        <h3 class="card-title"><?php echo $row['title'] ?></h3>
        <div class="card-tools">
            <a class="btn btn-block btn-sm btn-default btn-flat border-primary edit_lesson"
                href="./?page=lesson/edit_lesson&id=<?php echo $id ?>"><i class="fa fa-plus"></i> Edit Lesson</a>
        </div>
    </div>
    <div class="card-body">
        <div class="w-100">
            <div class="col-md-12">
                <span class="float-right"
                    style="max-width:calc(50%);font-size:13px;margin-right:10px; !important;font-weight:bold">Course:
                    <?php
                    $id = $row['subject_id']; 
                    $subject = $conn->query("SELECT * from course WHERE id = '$id'");
                    $fet = mysqli_fetch_array($subject);
                    echo $fet['course'];
                     
                    ?>
                </span>
            </div>
        </div>
        <br>
        <div class="container-fluid">
            <h5>Description</h5>
            <hr>
            <div>
                <?php 
        $pdf_pattern  = "<iframe src='".base_url."faculty/file_uploads/view_pdf.php?path=$2' class='pdf_viewer' title='PDF Viewer'></iframe>";
        echo (preg_replace("/(\[pdf_view\spath\s=\s+)([a-zA-Z0-9\/.]+)(\])/",$pdf_pattern,html_entity_decode($row['description'])));
         ?>
         






            </div>
            <hr>
            <section>
                <div class="w-100">
                    <h5>This is visible to:</h5>
                    <hr>
                    <span class="badge badge-primary m-1" style="font-size:12px">
                        <?php
                        $class =  $row['class_id'];
                        // echo $class; 
                        $qqq = $conn->query("SELECT * FROM class WHERE lecturer = '$faculty_id' AND id = '$class'");
                        $fetc = mysqli_fetch_array($qqq);
                        $dept = $fetc['department_id'];
                        $qqt = $conn->query("SELECT * FROM department WHERE id = '$dept'");
                        $fetch = mysqli_fetch_array($qqt);
                    echo $fetch['department'].' '.$fetc['level'];
                        ?>
                    </span>

                </div>
            </section>
            <hr>
            <div class="w-100">
                <div class="col-md-12">
                    <span class="float-right"><b>Prepared By: </b>
                        <?php
                    $id = $row['faculty_id']; 
                    $lectuer = $conn->query("SELECT * from faculty WHERE id = '$id'");
                    $fet = mysqli_fetch_array($lectuer);
                    echo $fet['lastname'].' '.$fet['firstname'].', '.$fet['middlename'];
                      
                    ?></span>
                </div>
            </div>
        </div>
    </div>
</div>