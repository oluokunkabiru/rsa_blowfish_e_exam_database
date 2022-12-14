<?php
  require_once('../../../includes/constant.php');
  require_once("../../i../ncludes/connection.php");
session_start();
if ($_SESSION['teacherauth']) {
    if (isset($_GET['examinationid'])) {
        $id = $_GET['examinationid'];


?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>EXAMINATION QUESTIONS SETUP</title>
            <?php include('../header.php') ?>

        </head>

        <body class="hold-transition sidebar-mini layout-fixed">
            <div class="wrapper">
                <?php include('../sidebar.php') ?>
                <div class="content-wrapper">
                    <section class="content">
                        <?php
                        $exam =  new Examination;
                        $q = $exam->query("SELECT examinations.*, class.name AS classname, subjects.subjectname AS 
subjectname FROM examinations JOIN class ON class.classid = examinations.classid JOIN subjects 
ON subjects.subjectid = examinations.subjectid WHERE examinationid = '$id'");
                        $exams = $exam->data($q);
                        if (isset($_GET['examinationid']) && isset($_GET['view'])) {


                        ?>
                            <div class="container-fluid">
                                <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2"><?php echo $exams['subjectname'] ?> for <?php echo $exams['classname'] ?> Questions </h2>
                                <a href="examinationquestions.php?examinationid=<?php echo $id ?>&&addquestions=addquestion" class="btn btn-success btn-lg text-uppercase mt-2 mb-2"><span class="fa fa-question mr-2"></span>Add new question</a>
                               <a href="manageexamination.php" class="btn btn-primary float-right text-uppercase btn-lg p-2">Manage question</a>
                                <table id="tables" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Qn No</th>
                                            <th>Questions</th>
                                            <th>Correct Answers</th>
                                            <th>Mark(s)</th>
                                            <!-- <th>Duration</th> -->
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $exam = new Examination;
                                        $q = $exam->query("SELECT questions.*, examinations.duration as duration FROM questions JOIN examinations ON questions.examinationid = examinations.examinationid WHERE examinations.examinationid = '$id' ");
                                        $i = 1;
                                        while ($data = $exam->data($q)) {


                                        ?>
                                            <tr>
                                            <td scope="row"><?php echo $i++ ?></td>
                                            <td><?php echo html_entity_decode($data['question']) ?></td>
                                            <td><span class="text-uppercase"><?php echo html_entity_decode($data['correct'])?></span></td>
                                            <td><?php echo html_entity_decode($data['mark']) ?></td>
                                            <!-- <td><?php //echo html_entity_decode($data['duration']) ?></td> -->
                                            <td>
                                            <a href="examinationquestions.php?examinationid=<?php echo $id ?>&&editquestions=<?php echo $data['questionid'] ?>" class ="btn btn-success"><span class="fa fa-edit p-2"></span></a>
                                            <a href="#deletequestion" class="btn btn-sm btn-danger"  data-toggle="modal" deletequestion="<?php echo $data['questionid'] ?>"><span class="fa fa-trash p-2"></span></a>
                                         </td>
                                            
                                            </tr>
                                        <?php
                                        }
                                        ?>

                                    </tbody>

                                </table>

                            </div>
                        <?php } ?>
                        <div id="deletequestion" class="modal">
            <div class="modal-dialog">
              <div class="deletequestion">
              
              </div>
            </div>
          </div>
                        <?php

                        if (isset($_GET['examinationid']) && isset($_GET['addquestions'])) {
                        ?>
                            <div class="container">
                                <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">Add <?php echo $exams['subjectname'] ?> for <?php echo $exams['classname'] ?> Questions</h2>
                                <a href="examinationquestions.php?examinationid=<?php echo $id ?>&&view=question" class="btn btn-success btn-lg text-uppercase mt-2 mb-2"><span class="fa fa-angle-left mr-2"></span>Back to questions</a>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 py-4">
                                        <!-- Horizontal Form -->
                                        <div class="card card-dark">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    <h3 class="text-center text-uppercase font-weight-bold">new question</h3>
                                                </h3>
                                            </div>
                                            <!-- /.card-header -->
                                            <!-- form start -->
                                            <p class="error text-danger pl-4"></p>
                                            <div class="container p-3">
                                             <div class="form-group row">
                                                        <label for="inputEmail3" class="col-sm-4 col-form-label">Question Type</label>
                                                        <div class="col-sm-8">
                                                            <select class="custom-select" onchange="selectclass(this.value)" name="qtype">
                                                            <option value="normal">Normal</option>
                                                            <option value="file">Upload Excel file</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div id="accordion">
                                                     <div class="collapse show" id="normalquestion"  data-parent="#accordion">
                                            <form class="form-horizontal" id="newexaminationquestionform" enctype="multipart/form-data" >
                                                <div class="card-body">
                                                    
                                                   
                                                  
                                                    <div class="form-group row">
                                                        <label for="options" class="col-sm-4 col-form-label">Question</label>
                                                        <div class="col-sm-8">
                                                        <textarea class="textarea" name="question" placeholder="Place some text here" ></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group row">
                                                        <label for="options" class="col-sm-4 col-form-label">Option A</label>
                                                        <div class="col-sm-8">
                                                        <textarea class="textarea"  name="optiona" placeholder="Option A" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                                        </div>
                                                    </div>
                                                        </div>
                                                        <div class="col">
                                                             <div class="form-group row">
                                                        <label for="options" class="col-sm-4 col-form-label">Option B</label>
                                                        <div class="col-sm-8">
                                                        <textarea class="textarea"  name="optionb" placeholder="Option B" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                                        </div>
                                                    </div>
                                                        </div>
                                                    </div>
                                                    
                                                   <div class="row">
                                                       <div class="col">
                                                            <div class="form-group row">
                                                        <label for="options" class="col-sm-4 col-form-label">Option C</label>
                                                        <div class="col-sm-8">
                                                        <textarea class="textarea" placeholder="Option C" name="optionc" style="width: 100%; height: 200px; font-size: 12px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                                        </div>
                                                    </div>
                                                       </div>

                                                       <div class="col">
                                                           <div class="form-group row">
                                                        <label for="options" class="col-sm-4 col-form-label">Option D</label>
                                                        <div class="col-sm-8">
                                                        <textarea class="textarea" placeholder="Option D" name="optiond" style="width: 100%; height: 200px; font-size: 12px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                                        </div>
                                                    </div>
                                                       </div>
                                                   </div>
                                                   
                                                    <div class="row">
                                                        <div class="col">
                                                             <div class="form-group row">
                                                        <label for="options" class="col-sm-4 col-form-label">Option E</label>
                                                        <div class="col-sm-8">
                                                        <textarea class="textarea" placeholder="Option E" name="optione" style="width: 100%; height: 200px; font-size: 12px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                                        </div>
                                                    </div>
                                                        </div>
                                                        <div class="col">
                                                             <div class="form-group row">
                                                        <label for="" class="col-sm-4 col-form-label">Mark</label>
                                                        <div class="col-sm-8">
                                                            <input type="number" class="form-control" name="mark" placeholder="Assign mark">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputEmail3" class="col-sm-4 col-form-label">Correct Answer </label>
                                                        <div class="col-sm-8">
                                                            <select class="custom-select" name="correct">
                                                            <option value="">Select correct</option>
                                                            <option value="A">A</option>
                                                                <option value="B">B</option>
                                                                <option value="C">C</option>
                                                                <option value="D">D</option>
                                                                <option value="E">E</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <input type="hidden" name="examid" value="<?php echo $id ?>">
                                                        </div>
                                                        <div class="col-sm-8">
                                                    <button id="newquestionbtn" type="submit" class="btn btn-primary btn-block btn-lg text-uppercase">Create new question</button>
                                                </div></div>
                                                        </div>
                                                    </div>
                                                   
                                                    
                                                   
                                                   
                                                    
                                                </div>
                                                <!-- /.card-body -->
                                                
                                                <!-- /.card-footer -->
                                            </form>
                                            </div>
                                            <div class="collapse" id="filequestion" data-parent="#accordion">
                                                <p class="excelfileerror text-center"></p>
                                                <div class="container p-4">
                                                    <form id="fileformquestion" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label for="usr">Upload Questions in excel:  <a href="../../../includes/cbt_questions-format.csv" class="btn btn-sm btn-danger" download>Download exam format</a></label>
                                                        <input type="file" class="form-control" name="excelfile">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="hidden" name="examid" value="<?php echo $id ?>">
                                                    </div>

                                                    <button id="filequestionbtn" type="submit" class="btn btn-primary btn-block btn-lg text-uppercase">add question</button>
                        
                                                    </form>
                                                    </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- /.card -->

                                </div>
                            </div>

                        <?php } ?>


                        <?php

if (isset($_GET['examinationid']) && isset($_GET['editquestions'])) {
    $ids = $_GET['editquestions'];
    $que = $exam->query("SELECT* FROM questions WHERE questionid = '$ids' ");
    $question = $exam->data($que);
    // print_r($question);
?>
    <div class="container">
        <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">edit <?php echo $exams['subjectname'] ?> for <?php echo $exams['classname'] ?> Questions</h2>
        <a href="examinationquestions.php?examinationid=<?php echo $id ?>&&view=question" class="btn btn-success btn-lg text-uppercase mt-2 mb-2"><span class="fa fa-angle-left mr-2"></span>Back to questions</a>
        <div class="row">
            <div class="col-md-12 col-lg-12 py-4">
                <!-- Horizontal Form -->
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">
                            <h3 class="text-center text-uppercase font-weight-bold">edit question</h3>
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <p class="questionediterror text-danger ml-5 pl-4"></p>
                     <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Question Type</label>
                                <div class="col-sm-8">
                                    <select class="custom-select" disabled onchange="selectclass(this.value)" name="qtype">
                                   <option value="<?php $question['questiontype'] ?>" selected><?php echo ucwords($question['questiontype']) ?></option>
                                    <option value="normal">Normal</option>
                                    <option value="file">Upload Excel File</option>
                                    </select>
                                </div>
                            </div>

                    <form class="form-horizontal" id="editexaminationquestionform" enctype="multipart/form-data" >
                        <div class="card-body">
                            
                           
                           <input type="hidden" value="<?php $question['questiontype'] ?>" name="qtype">
                            <div class="form-group row">
                                <label for="options" class="col-sm-4 col-form-label">Question</label>
                                <div class="col-sm-8">
                                <textarea class="textarea" name="question" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                            <?php echo $question['question']?html_entity_decode($question['question']):"" ?>
                            </textarea>
                                </div>
                            </div>
                                <input type="hidden" name="editquestionsid" value="<?php echo $_GET['editquestions'] ?>">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group row">
                                <label for="options" class="col-sm-4 col-form-label">Option A</label>
                                <div class="col-sm-8">
                                <textarea class="textarea"  name="optiona" placeholder="Option A" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                <?php if(!empty($question['option_a'])){ echo html_entity_decode($question['option_a']); }?>

                            </textarea>
                                </div>
                            </div>
                                </div>
                                <div class="col">
                                     <div class="form-group row">
                                <label for="options" class="col-sm-4 col-form-label">Option B</label>
                                <div class="col-sm-8">
                                <textarea class="textarea"  name="optionb" placeholder="Option B" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                <?php if(!empty($question['option_b'])){ echo html_entity_decode($question['option_b']); }?>

                            </textarea>
                                </div>
                            </div>
                                </div>
                            </div>
                            
                           <div class="row">
                               <div class="col">
                                    <div class="form-group row">
                                <label for="options" class="col-sm-4 col-form-label">Option C</label>
                                <div class="col-sm-8">
                                <textarea class="textarea" placeholder="Option C" name="optionc" style="width: 100%; height: 200px; font-size: 12px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                <?php if(!empty($question['option_c'])){ echo html_entity_decode($question['option_c']); }?>

                            </textarea>
                                </div>
                            </div>
                               </div>

                               <div class="col">
                                   <div class="form-group row">
                                <label for="options" class="col-sm-4 col-form-label">Option D</label>
                                <div class="col-sm-8">
                                <textarea class="textarea" placeholder="Option D" name="optiond" style="width: 100%; height: 200px; font-size: 12px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                <?php if(!empty($question['option_d'])){ echo html_entity_decode($question['option_d']); }?>

                            </textarea>
                                </div>
                            </div>
                               </div>
                           </div>
                           
                            <div class="row">
                                <div class="col">
                                     <div class="form-group row">
                                <label for="options" class="col-sm-4 col-form-label">Option E</label>
                                <div class="col-sm-8">
                                <textarea class="textarea" placeholder="Option E" name="optione" style="width: 100%; height: 200px; font-size: 12px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                <?php if( !empty($question['option_e'])){ echo html_entity_decode($question['option_e']); }?>

                            </textarea>
                                </div>
                            </div>
                                </div>
                                <div class="col">
                                     <div class="form-group row">
                                <label for="" class="col-sm-4 col-form-label">Mark</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" value="<?php echo $question['mark'] ?>" name="mark" placeholder="Assign mark">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Correct Answer </label>
                                <div class="col-sm-8">
                                    <select class="custom-select" name="correct">
                                    <option value="<?php echo $question['correct'] ?>" selected class="text-uppercase"><?php echo html_entity_decode($question['correct']) ?></option>

                                    <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                        <option value="E">E</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <input type="hidden" name="examid" value="<?php echo $id ?>">
                                </div>
                                <div class="col-sm-8">
                            <button id="newquestionbtn" type="submit" class="btn btn-primary btn-block btn-lg text-uppercase">update question</button>
                        </div></div>
                                </div>
                            </div>
                           
                            
                           
                           
                            
                        </div>
                        <!-- /.card-body -->
                        
                        <!-- /.card-footer -->
                    </form>


                </div>
            </div>
            <!-- /.card -->

        </div>
    </div>

<?php } ?>

                    </section>

                </div>
                <?php include('../footer.php'); ?>
            </div>
        </body>

        </html>
  
<?php
    } else {
        header('location:manageexamination.php');
    }
    include('../script.php');
} else {
    header('location:../../../');
}
?>
      <script>
            // $(document).ready(function() {

                function selectclass(data){
                    if(data=="file"){
                        $("#filequestion").collapse('show');
                    }
                    if(data=="normal"){
                        $("#normalquestion").collapse('show');
                    }

                }
               
               
            // })
        </script>