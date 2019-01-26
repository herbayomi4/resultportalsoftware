<main class="row">
  <section class="col-md-10 col-xs-offset-1 col-xs-10">
    <h3><center><?php echo $header;?></center></h3>
    <div class="input-group">
  <span class="input-group-addon" id="basic-addon1">SESSION</span>
  <input type="text" class="form-control" name="session" id="session" aria-describedby="basic-addon1" placeholder="2014/2015">
  <script type="text/javascript">
    document.getElementById('session').value = "<?php echo $_POST['session'];?>";
  </script>
</div>
<!--use form-grou[p on every page-->
<div class="input-group">
  <span class="input-group-addon" id="basic-addon1">SEMESTER</span>
  <select type="text" class="form-control" id="semester" name="semester" aria-describedby="basic-addon1">
    <option>First Semester</option>
    <option>Second Semester</option>
    <option>Harmattan Semester</option>
    <option>Rain Semester</option>
  </select>
  <script type="text/javascript">
    document.getElementById('semester').value = "<?php echo $_POST['semester'];?>";
  </script>
</div>
<div class="input-group">
  <span class="input-group-addon" id="basic-addon1">COLLEGE</span>
  <select type="text" class="form-control" name="college" aria-describedby="basic-addon1">
    <option>Ramon Adedoyin College of Natural and Applied Sciences</option>
  </select>
</div>
<div class="input-group">
  <span class="input-group-addon" id="basic-addon1">DEPARTMENT</span>
  <select type="text" class="form-control" id="department" name="department" aria-describedby="basic-addon1">
    <option>Biochemistry</option>
    <option>Computer Science</option>
    <option>Industrial Chemistry</option>
    <option>Microbiology</option>
    <option>Physics</option>
  </select>
  <script type="text/javascript">
    document.getElementById('department').value = "<?php echo $_POST['department'];?>";
  </script>
</div>
<div class="input-group">
  <span class="input-group-addon" id="basic-addon1">LEVEL</span>
  <select type="text" class="form-control" id="level" name="level" aria-describedby="basic-addon1">
    <option>100</option>
    <option>200</option>
    <option>300</option>
    <option>400</option>
  </select>
  <script type="text/javascript">
    document.getElementById('level').value = "<?php echo $_POST['level'];?>";
  </script>
</div>