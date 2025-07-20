<?php
/* ---------- bootstrap / header ---------- */
include '../../templates/Uheader.php';
include '../../templates/HeaderNav.php';

$pdo = db_connect();

/* ---------- POST HANDLERS ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* assign faculty */
    if (isset($_POST['assign'])) {
        $professor_id = $_POST['professor_id'];
        $sysem_id     = $_POST['school_year_semester_id'];

        $query = "UPDATE professor SET evalStatus = 'assigned' WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $professor_id);
        $stmt->execute();

        $exists = $pdo->prepare(
                    "SELECT COUNT(*) FROM professor_school_year_semester
                     WHERE professor_id = ? AND school_year_semester_id = ?"
                  );
        $exists->execute([$professor_id, $sysem_id]);
            
        if (!$exists->fetchColumn()) {
            $ok = $pdo->prepare(
                     "INSERT INTO professor_school_year_semester
                      (professor_id, school_year_semester_id, assigned_at)
                      VALUES (?,?,NOW())"
                  )->execute([$professor_id, $sysem_id]);
            $status = $ok ? 'success' : 'error';
        } else {
            $status = 'warning';   // already assigned
        }
        header("Location: assignedProf.php?assign={$status}");
        exit;
    }

    /* delete assignment */
    if (isset($_POST['delete'])) {
        $professor_id = $_POST['professor_id'];
        $sysem_id     = $_POST['school_year_semester_id'];

        $query = "UPDATE professor SET evalStatus = 'unassigned' WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $professor_id);
        $stmt->execute();

        $ok = $pdo->prepare(
                 "DELETE FROM professor_school_year_semester
                  WHERE professor_id = ? AND school_year_semester_id = ?"
              )->execute([$professor_id, $sysem_id]);
        $status = $ok ? 'success' : 'error';
        header("Location: assignedProf.php?delete={$status}");
        exit;
    }
}

/* ---------- filter drop‑down ---------- */
$selected_term = $_GET['filter_term'] ?? '';

?>
<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');

.top-bar{display:flex;justify-content:flex-end;margin-bottom:1rem;gap:1rem;flex-wrap:wrap}
.filter-term-select{width:60%;min-width:200px;max-width:400px}
.professors-table-wrapper{width:90%;margin:0 auto}

.FacultyNav{background:#77070A!important;font-weight:500}
.dashboardNav,.CurriculumeNav,.EvalNav{
  background:linear-gradient(40deg,#77070b62,#77070b62,#77070A,#77070b62,#77070b62)!important
}
</style>

<div class="main w-100 h-100 d-flex flex-column">
    <?= getAdminHeader() ?>

    <div class="row w-100 p-0 m-0">
        <!-- side nav -->
        <div class="col-auto sideNav bg-linear h-100">
            <div class="sideContents">
                <div class="profileBox d-flex flex-column justify-content-center align-items-center mt-2 mb-3">
                    <img src="../../assets/image/admin.png" alt="pfp" id="pfpOnTop">
                    <h5 class="text-white">ADMIN</h5>
                </div>
                <?= getAdminNav() ?>
            </div>
        </div>

        <!-- page content -->
        <div class="col content p-0 d-flex flex-column align-items-center">
            <div class="title col-md-11 d-flex justify-content-start mb-4 fadeInAnimation">
                <label class="fs-2 fw-bold text-muted">ASSIGN FACULTY</label>
            </div>

            <div class="container p-0 d-flex flex-column align-items-center fadeInAnimation" style="height: 78vh;">
                <!-- top bar -->
                <div class="top-bar col-md-11 col-11 align-items-center">
                    <!-- filter -->
                    <form method="GET" class="d-flex gap-2 align-items-center col-md-3 p-0">
                        <select name="filter_term"
                                class="form-select filter-term-select"
                                onchange="this.form.submit()">
                            <option value="">-- Filter by Semester --</option>
                            <?php
                            $stmt = $pdo->query(
                                "SELECT id, school_year, semester
                                 FROM school_year_semester ORDER BY id DESC"
                            );
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $sel = $selected_term == $row['id'] ? 'selected' : '';
                                echo "<option value='{$row['id']}' {$sel}>{$row['school_year']} - {$row['semester']}</option>";
                            } ?>
                        </select>
                        <noscript><button class="btn btn-secondary">Filter</button></noscript>
                    </form>

                    <!-- search -->
                    <li class="list-unstyled col-md-6 p-0">
                        <form class="input-group">
                            <input id="searchInput" class="form-control" placeholder="Search...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </form>
                    </li>

                    <!-- add -->
                    <div class="col-md-2 d-flex justify-content-end mb-2">
                        <button class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#assignProfessorModal">
                            Assign Faculty
                        </button>
                    </div>
                </div>

                <!-- assign modal -->
                <div class="modal fade" id="assignProfessorModal" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    <form method="POST" class="modal-content">
                      <div class="modal-header bg-linear text-white">
                        <h5 class="modal-title">Assign Faculty Member for Evaluation</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                      </div>

                      <div class="modal-body">
                        <!-- DEPARTMENT -->
                        <div class="mb-3">
                          <label class="form-label">Select Department</label>
                          <select name="department_id" class="form-select"
                                  onchange="filterProfessors(this.value)" required>
                              <option value="">-- Choose Department --</option>
                              <?php
                              $deps = $pdo->query("SELECT id, department_name FROM department ORDER BY department_name");
                              while ($d = $deps->fetch(PDO::FETCH_ASSOC)) {
                                  echo "<option value='{$d['id']}'>{$d['department_name']}</option>";
                              } ?>
                          </select>
                        </div>

                        <!-- PROFESSOR -->
                        <div class="mb-3">
                          <label class="form-label">Select Professor</label>
                          <select id="professorDropdown" name="professor_id" class="form-select" required>
                              <option value="">-- Select Department First --</option>
                          </select>
                        </div>

                        <!-- TERM -->
                        <div class="mb-3">
                          <label class="form-label">Select School Year & Semester</label>
                          <select name="school_year_semester_id" class="form-select" required>
                              <option value="">-- Choose Term --</option>
                              <?php
                              $terms = $pdo->query("SELECT id, school_year, semester FROM school_year_semester ORDER BY id DESC");
                              while ($t = $terms->fetch(PDO::FETCH_ASSOC)) {
                                  echo "<option value='{$t['id']}'>{$t['school_year']} - {$t['semester']}</option>";
                              } ?>
                          </select>
                        </div>
                      </div>

                      <div class="modal-footer">
                        <button type="submit" name="assign" class="btn btn-primary">Assign</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- table -->
                <div class="professors-table-wrapper col-md-12">
                    <div class="card shadow rounded-2" style="height:66vh">
                        <div class="card-header bg-linear text-white">
                            <h5 class="mb-0">Assigned Professors</h5>
                        </div>

                        <div class="card-body table-responsive" style="height: 70vh;">
                            <table class="table table-bordered table-hover table-striped" id="professorTable">
                                <thead class="table-secondary">
                                  <tr>
                                      <th>Faculty Name</th>
                                      <th>Term</th>
                                      <th>Date Assigned</th>
                                      <th style="width:120px">Actions</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  /* query rows */
                                  if ($selected_term) {
                                      $q = $pdo->prepare(
                                          "SELECT p.id prof_id, p.fname, p.lname,
                                                  sys.id sysem_id, sys.school_year, sys.semester,
                                                  psys.assigned_at
                                           FROM professor_school_year_semester psys
                                           JOIN professor p ON psys.professor_id = p.id
                                           JOIN school_year_semester sys ON psys.school_year_semester_id = sys.id
                                           WHERE sys.id = ?
                                           ORDER BY psys.assigned_at DESC"
                                      );
                                      $q->execute([$selected_term]);
                                  } else {
                                      $q = $pdo->query(
                                          "SELECT p.id prof_id, p.fname, p.lname,
                                                  sys.id sysem_id, sys.school_year, sys.semester,
                                                  psys.assigned_at
                                           FROM professor_school_year_semester psys
                                           JOIN professor p ON psys.professor_id = p.id
                                           JOIN school_year_semester sys ON psys.school_year_semester_id = sys.id
                                           ORDER BY psys.assigned_at DESC"
                                      );
                                  }
                                  $rows = $q->fetchAll(PDO::FETCH_ASSOC);

                                  if ($rows) {
                                      foreach ($rows as $r):
                                  ?>
                                  <tr>
                                      <td><?= htmlspecialchars($r['lname'] . ', ' . $r['fname']) ?></td>
                                      <td><?= htmlspecialchars($r['school_year'].' - '.$r['semester']) ?></td>
                                      <td><?= date('M d, Y', strtotime($r['assigned_at'])) ?></td>
                                      <td>
                                          <button class="btn btn-sm btn-danger"
                                                  data-bs-toggle="modal"
                                                  data-bs-target="#deleteAssignModal"
                                                  data-professor="<?= $r['prof_id'] ?>"
                                                  data-sysem="<?= $r['sysem_id'] ?>">
                                              <i class="fa-solid fa-trash"></i>
                                          </button>
                                      </td>
                                  </tr>
                                  <?php
                                      endforeach;
                                  } else {
                                      echo '<tr><td colspan="4" class="text-center">No assigned professors found.</td></tr>';
                                  } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!-- /container -->
        </div><!-- /page content -->
    </div><!-- /row -->
</div><!-- /main -->

<!-- delete‑assignment modal (single instance) -->
<div class="modal fade" id="deleteAssignModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <input type="hidden" name="delete" value="true">
      <input type="hidden" name="professor_id" id="del-prof">
      <input type="hidden" name="school_year_semester_id" id="del-sysem">

      <div class="modal-header">
        <h5 class="modal-title">Delete Assignment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this assignment?
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" type="submit">Delete</button>
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/* toast on ?assign= / ?delete= */
document.addEventListener('DOMContentLoaded',()=>{
  const url=new URL(window.location),p=url.searchParams;
  let icon='',title='';
  if(p.get('assign')==='success'){icon='success';title='Professor successfully assigned!'}
  else if(p.get('assign')==='warning'){icon='warning';title='Professor already assigned to this term.'}
  else if(p.get('assign')==='error'){icon='error';title='Error assigning professor.'}
  else if(p.get('delete')==='success'){icon='success';title='Assignment deleted successfully.'}
  else if(p.get('delete')==='error'){icon='error';title='Failed to delete assignment.'}

  if(icon){
    Swal.fire({toast:true,position:'top-end',icon,title,showConfirmButton:false,timer:3000,timerProgressBar:true,customClass:{popup:'swal2-row-toast'}});
    ['assign','delete'].forEach(k=>p.delete(k));
    history.replaceState({},document.title,url.pathname+url.search);
  }
});

/* transfer ids into delete modal */
document.getElementById('deleteAssignModal').addEventListener('show.bs.modal',ev=>{
  const btn=ev.relatedTarget;
  document.getElementById('del-prof').value=btn.dataset.professor;
  document.getElementById('del-sysem').value=btn.dataset.sysem;
});

/* filter professors Ajax */
function filterProfessors(depId){
  const dd=document.getElementById('professorDropdown');
  dd.innerHTML='<option>Loading...</option>';
  fetch(`../api/ajax.php?department_id=${depId}`)
    .then(r=>r.json())
    .then(d=>{
      dd.innerHTML='<option value="">-- Choose Professor --</option>';
      if(d.length){
        d.forEach(p=>{
          const o=document.createElement('option');
          o.value=p.id;o.textContent=`${p.lname}, ${p.fname}`;
          dd.appendChild(o);
        })
      }else dd.innerHTML='<option>No professors found</option>';
    });
}

/* live column search */
document.getElementById('searchInput').addEventListener('input',function(){
  const q=this.value.toLowerCase();
  document.querySelectorAll('#professorTable tbody tr').forEach(tr=>{
    const match=[...tr.cells].some(td=>td.textContent.toLowerCase().includes(q));
    tr.style.display=match?'':'none';
  });
});
</script>

<?php include '../../templates/Ufooter.php'; ?>
