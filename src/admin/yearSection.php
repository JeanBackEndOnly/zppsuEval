<?php include '../../templates/Uheader.php'; include '../../templates/HeaderNav.php'; ?>
<style>
    .CurriculumeNav{
        background-color: #77070A !important;
        font-weight: 500;
    }
    .FacultyNav, .dashboardNav, .EvalNav{
       background: linear-gradient(40deg , #77070b62,#77070b62, #77070A, #77070b62, #77070b62) !important;
    }
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
            <div class="col content p-0 w-100 d-flex flex-column justify-content-start align-items-center fadeInAnimation">
                <div class="title mb-4 col-md-11 d-flex justify-content-start mb-4 fadeInAnimation">
                    <label class="text-black fw-bold fs-2 text-muted">YEAR AND SECTION MANAGEMENT</label>
                </div>
                <div class="container-fluid col-md-12 d-flex flex-column justify-content-start align-items-center m-0 p-0">

                <div class="d-flex justify-content-between align-items-center mb-2 col-md-11">
                   <li class="list-unstyled m-0 col-md-10">
                        <form class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </form>
                    </li>
                   <button type="button" class="btn btn-primary" style="width:10%;" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                        Add
                    </button>
                </div>

                <div class="table-responsive col-md-11" style="height: 75vh;">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Year Level</th>
                                <th scope="col">Section</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php foreach($yearSection as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row["id"]) ?></td>
                                    <td><?= htmlspecialchars($row["year_level"]) ?></td>
                                    <td><?= htmlspecialchars($row["section"]) ?></td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" onclick="deleteSub(<?= $row['id'] ?>)">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Delete confirmation modal -->
                <div id="deletesubjects" class="modal fade" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Confirm Deletion</h5>
                                <button type="button" class="btn-close" onclick="CancelJob()" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this Year and section?</p>
                            </div>
                            <div class="modal-footer">
                                <form action="" id="deleteForm" method="post" class="d-inline">
                                    <input type="hidden" name="semesterst" value="delete">
                                    <input type="hidden" id="jobID" name="id" value="">
                                    <button type="submit" class="btn btn-danger">Yes</button>
                                </form>
                                <button type="button" class="btn btn-secondary" onclick="CancelJob()">No</button>
                            </div>
                        </div>
                    </div>
                </div>

                    
                </div>
                <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="addSubjectLabel">Add Year and Section</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="../../auth/authentications.php" method="post">
                            <div class="modal-body">
                            <input type="hidden" name="sectionHEhe" value="admin">
                            <div class="mb-3">
                                <label for="subjectInput" class="form-label">Year and Section</label>
                                <input type="text" class="form-control" id="schoolYearInput" name="year_level" placeholder="e.g. 1st" required>
                                <input type="text" class="form-control" id="semesterInput" name="section" placeholder="e.g. A" required>
                            </div>
                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Add</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/hr/hrJ.js"></script>

    <script>
        function deleteSub(id) {
            document.getElementById("jobID").value = id;
            var deleteModal = new bootstrap.Modal(document.getElementById('deletesubjects'));
            deleteModal.show();
            document.getElementById("jobID").value = id;
            document.getElementById("deleteForm").action = "../../auth/authentications.php?id=" + id;
        }

        function CancelJob() {
            var deleteModal = bootstrap.Modal.getInstance(document.getElementById('deletesubjects'));
            deleteModal.hide();
        }

        // function deleteSub(id){
        //     console.log("button clicked!");
        //     document.getElementById("deletesubjects").style.display = 'flex';
            
        // }

        // function CancelJob(){
        //     document.getElementById("deletesubjects").style.display = "none";
        // }
    </script>

<?php include '../../templates/Ufooter.php'; ?>