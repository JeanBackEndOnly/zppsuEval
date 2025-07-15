<?php
include '../../templates/Uheader.php';
include '../../templates/HeaderNav.php';

$pdo = db_connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['AddCriteria']) && $_POST['AddCriteria'] === 'true') {
        $title = trim($_POST['title'] ?? '');
        $desc  = trim($_POST['description'] ?? '');

        if ($title !== '') {
            $stmt = $pdo->prepare("INSERT INTO criteria (name, description) VALUES (?, ?)");
            $stmt->execute([$title, $desc]);
        }
         header("Location: " . $_SERVER['PHP_SELF'] . '?AddCriteria=success');
        exit;
    }

    if (isset($_POST['DeleteCriteria']) && isset($_POST['id'])) {
        $stmt = $pdo->prepare("DELETE FROM criteria WHERE id = ?");
        $stmt->execute([intval($_POST['id'])]);
         header("Location: " . $_SERVER['PHP_SELF'] . '?DeleteCriteria=success');
        exit;
    }

   
}

$search  = $_GET['search'] ?? '';
$params  = [];
$where   = '';

if ($search !== '') {
    $where  = 'WHERE criteria.name LIKE :s OR criteria.description LIKE :s';
    $params = [':s' => "%{$search}%"];
}

$sql  = "
    SELECT id, name, description, created_at
    FROM criteria
    $where
    ORDER BY id DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<style>
    .EvalNav      { background:#77070A!important;font-weight:500 }
    .FacultyNav,
    .CurriculumeNav,
    .dashboardNav { background:linear-gradient(40deg,#77070b62,#77070b62,#77070A,#77070b62,#77070b62)!important }
</style>

<div class="main w-100 h-100 d-flex flex-column">
    <?= getAdminHeader() ?>
    <div class="row w-100 p-0 m-0">
        <div class="col-auto sideNav bg-linear h-100">
            <div class="sideContents" id="sideContents">
                <div class="profileBox w-100 d-flex flex-column justify-content-center align-items-center mt-2 mb-3">
                    <img src="../../assets/image/admin.png" alt="pfp" id="pfpOnTop">
                    <h5 class="text-white">ADMIN</h5>
                </div>
                <?= getAdminNav() ?>
            </div>
        </div>

        <div class="col content p-0 d-flex flex-column justify-content-start align-items-center fadeInAnimation">
            <div class="title m-0 col-md-11 col-11 d-flex justify-content-start mb-4">
                <label class="text-black fw-bold fs-2 text-muted">CRITERIA MANAGEMENT</label>
            </div>

            <div class="col-md-11 col-11 d-flex justify-content-between align-items-center mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCriteriaModal">
                    Add Criterion
                </button>

                <form class="d-flex" method="GET">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                           class="form-control me-2" placeholder="Search criteria...">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </form>
            </div>

            <div class="col-md-11 col-11 table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
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
                        <tr><td colspan="5" class="text-center text-muted">No records found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <form method="POST" class="modal-content">
              <input type="hidden" name="DeleteCriteria" value="true">
              <div class="modal-header">
                <h5 class="modal-title">Delete Criterion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <p>Are you sure you want to delete this criterion?</p>
                <input type="hidden" name="id" id="delete-id">
              </div>
              <div class="modal-footer">
                <button class="btn btn-danger" type="submit">Delete</button>
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>

        <div class="modal fade" id="addCriteriaModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <form method="POST" class="modal-content">
              <input type="hidden" name="AddCriteria" value="true">
              <div class="modal-header">
                <h5 class="modal-title">Add Criterion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label">Title</label>
                  <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Description</label>
                  <textarea name="description" rows="3" class="form-control" required></textarea>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (AddCriteria) {
            console.log("Showing updateReq toast");
            Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Criteria added successfully!.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: { popup: 'swal2-row-toast' }
            });
            removeUrlParams(['AddCriteria']);
        }else if (DeleteCriteria) {
            console.log("Showing updateReq toast");
            Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Criteria Deleted successfully!.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: { popup: 'swal2-row-toast' }
            });
            removeUrlParams(['DeleteCriteria']);
        }
        function removeUrlParams(params) {
            const url = new URL(window.location);
            params.forEach(param => url.searchParams.delete(param));
            window.history.replaceState({}, document.title, url.toString());
        }
    });
</script>
<script>
document.getElementById('deleteModal')
        .addEventListener('show.bs.modal', function (ev) {
    const id = ev.relatedTarget.getAttribute('data-id');
    document.getElementById('delete-id').value = id;
});
</script>

<?php include '../../templates/Ufooter.php'; ?>
