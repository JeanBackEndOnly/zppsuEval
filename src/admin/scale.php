<?php
include '../../templates/Uheader.php';
include '../../templates/HeaderNav.php';

$pdo = db_connect();

/* ------------- POST HANDLERS ------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* add scale */
    if (isset($_POST['AddScale']) && $_POST['AddScale'] === 'true') {
        $score = intval($_POST['score_value'] ?? 0);
        $desc  = trim($_POST['description']   ?? '');

        if ($score !== 0 && $desc !== '') {
            $stmt = $pdo->prepare(
                "INSERT INTO grading_scale (score_value, description) VALUES (?, ?)"
            );
            $stmt->execute([$score, $desc]);
        }
        header("Location: {$_SERVER['PHP_SELF']}?AddScale=success");
        exit;
    }
    /* delete scale */
    if (isset($_POST['DeleteScale']) && isset($_POST['id'])) {
        $stmt = $pdo->prepare("DELETE FROM grading_scale WHERE id = ?");
        $stmt->execute([intval($_POST['id'])]);

        header("Location: {$_SERVER['PHP_SELF']}?DeleteScale=success");
        exit;
    }
}

/* ------------- FETCH / SEARCH ------------- */
$search = $_GET['search'] ?? '';
$where  = '';
$params = [];

if ($search !== '') {
    $where  = 'WHERE grading_scale.score_value LIKE :s OR grading_scale.description LIKE :s';
    $params = [':s' => "%{$search}%"];
}

$sql = "
    SELECT id, score_value, description
    FROM grading_scale
    $where
    ORDER BY score_value DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<style>
  .EvalNav      {background:#77070A!important;font-weight:500}
  .FacultyNav,
  .CurriculumeNav,
  .dashboardNav {background:linear-gradient(40deg,#77070b62,#77070b62,#77070A,#77070b62,#77070b62)!important}
</style>

<div class="main w-100 h-100 d-flex flex-column">
    <?= getAdminHeader() ?>
    <div class="row w-100 p-0 m-0">
        <!-- Side Nav -->
        <div class="col-auto sideNav bg-linear h-100">
            <div class="sideContents">
                <div class="profileBox d-flex flex-column justify-content-center align-items-center mt-2 mb-3">
                    <img src="../../assets/image/admin.png" alt="pfp" id="pfpOnTop">
                    <h5 class="text-white">ADMIN</h5>
                </div>
                <?= getAdminNav() ?>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col content p-0 d-flex flex-column align-items-center fadeInAnimation">
            <!-- title -->
            <div class="title col-md-11 col-11 d-flex justify-content-start mb-4">
                <label class="text-black fw-bold fs-2 text-muted">GRADING SCALE MANAGEMENT</label>
            </div>

            <!-- add + search -->
            <div class="col-md-11 col-11 d-flex justify-content-between align-items-center mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScaleModal">
                    Add Scale
                </button>

                <form class="d-flex" method="GET">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                           class="form-control me-2" placeholder="Search scale…">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </form>
            </div>

            <!-- table -->
            <div class="col-md-11 col-11 table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Score Value</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rows as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($row['score_value']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-id="<?= $row['id'] ?>">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$rows): ?>
                        <tr><td colspan="4" class="text-center text-muted">No records found</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <form method="POST" class="modal-content">
              <input type="hidden" name="DeleteScale" value="true">
              <div class="modal-header">
                <h5 class="modal-title">Delete Scale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <p>Are you sure you want to delete this scale?</p>
                <input type="hidden" name="id" id="delete-id">
              </div>
              <div class="modal-footer">
                <button class="btn btn-danger" type="submit">Delete</button>
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Add Modal -->
        <div class="modal fade" id="addScaleModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <form method="POST" class="modal-content">
              <input type="hidden" name="AddScale" value="true">
              <div class="modal-header">
                <h5 class="modal-title">Add Scale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label">Score Value</label>
                  <input type="number" name="score_value" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Description</label>
                  <input type="text" name="description" maxlength="100" class="form-control" required>
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Add</button>
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
    </div>
</div>

<!-- scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* transfer id into delete modal */
document.getElementById('deleteModal')
        .addEventListener('show.bs.modal', ev=>{
  document.getElementById('delete-id')
          .value = ev.relatedTarget.getAttribute('data-id');
});

/* SweetAlert toasts on ?AddScale / ?DeleteScale */
document.addEventListener('DOMContentLoaded', ()=>{
  const url = new URL(window.location);
  const added   = url.searchParams.has('AddScale');
  const deleted = url.searchParams.has('DeleteScale');

  if (added || deleted) {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: added ? 'success' : 'error',
      title: added ? 'Scale added successfully!' : 'Scale deleted successfully!',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      customClass:{popup:'swal2-row-toast'}
    });
    /* strip flags so toast doesn’t reappear on refresh */
    ['AddScale','DeleteScale'].forEach(p=>url.searchParams.delete(p));
    history.replaceState({}, document.title, url);
  }
});
</script>

<?php include '../../templates/Ufooter.php'; ?>
