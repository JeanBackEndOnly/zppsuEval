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

        <div class="col content p-0 w-100 d-flex justify-content-start align-items-center flex-column fadeInAnimation">
             <div class="title col-md-11 col-11 d-flex justify-content-start mb-4 fadeInAnimation">
                <label class="text-black fw-bold fs-2 text-muted">ACADEMIC YEAR MANAGEMENT</label>
            </div>
           <div class="container-fluid m-0 p-0 col-md-12 d-flex justify-content-center align-items-center flex-column">

                <div class="d-flex justify-content-between align-items-center mb-2 col-md-11">
                    <li class="list-unstyled m-0 col-md-10">
                        <form class="input-group col-md-9">
                            <input type="text" name="search" id="search" class="form-control col-md-3" placeholder="Search...">
                            <button class="btn btn-outline-secondary" type="button" id="buttonSearchHehe">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </form>
                    </li>
                   <button type="button" class="btn btn-primary" style="width: 10%;" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                        Add
                    </button>
                </div>

                <div class="table-responsive col-md-11" style="height: 60vh;">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">School Year</th>
                                <th scope="col">Semester</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php foreach($semester as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row["id"]) ?></td>
                                    <td><?= htmlspecialchars($row["school_year"]) ?></td>
                                    <td><?= htmlspecialchars($row["semester"]) ?></td>
                                    <td>
                                        <?php if($row["status"] == "open"){ ?>
                                            <p class="p-0 m-0 rounded-5 text-white shadow fw-bold" style="background-color: #4bb543c9;"><?= htmlspecialchars($row["status"]) ?></p>
                                        <?php }else{ ?>
                                            <p class="p-0 m-0 rounded-5 text-white shadow fw-bold" style="background-color: #b54343c9;"><?= htmlspecialchars($row["status"]) ?></p>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm fw-bold text-white" onclick="openSemesterModal(<?= $row['id'] ?>)">
                                            Open
                                        </button>
                                         <button style="margin: 0 10px;" class="btn btn-danger btn-sm fw-bold" onclick="CloseSemsterModaL(<?= $row['id'] ?>)">
                                            Close
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteSub(<?= $row['id'] ?>)">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- IPEN SEMESTER -->
                <div class="modal fade" id="openSemester" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <form action="" id="openForm" method="post">
                            <input type="hidden" id="semesterID" name="id" value="">
                            <input type="hidden" name="semesternd" value="open">
                            <div class="modal-header bg-linear">
                            <h5 class="modal-title text-white" style="color: #000;">Openning Semester</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to Open This Semester?</p>
                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                <!-- cLOSE SEMESTER -->
                <div class="modal fade" id="closeSemester" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <form action="" id="closeForm" method="post">
                            <input type="hidden" id="semesterIDClose" name="id" value="">
                            <input type="hidden" name="semesterrd" value="close">
                            <div class="modal-header bg-linear">
                            <h5 class="modal-title text-white" style="color: #000;">Closing Semester</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to Close This Semester?</p>
                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                <!-- Delete confirmation modal -->
                <div id="deletesubjects" class="modal fade" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-top">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-linear bg-danger text-white">
                                <h5 class="modal-title text-white">Confirm Deletion</h5>
                                <button type="button" class="btn-close" onclick="CancelJob()" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this Semester?</p>
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
                    <div class="modal-dialog modal-dialog-top">
                        <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-linear bg-primary text-white">
                            <h5 class="modal-title text-white" id="addSubjectLabel">Add Year and Semester</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="../../auth/authentications.php" method="post">
                            <div class="modal-body">
                            <input type="hidden" name="semesterHEhe" value="admin">
                            <div class="mb-3">
                                <label for="subjectInput" class="form-label">Year and Semester</label>
                                <input type="text" class="form-control mb-2" id="schoolYearInput" name="school_year" placeholder="e.g. 2021" required>
                                <input type="text" class="form-control" id="semesterInput" name="semester" placeholder="e.g. 1st" required>
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
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function openSemesterModal(id) {
            console.log("Open modal with id:", id);
            const modal = new bootstrap.Modal(document.getElementById('openSemester'));
            modal.show();
            document.getElementById("semesterID").value = id;
            document.getElementById("openForm").action = "../../auth/authentications.php";
        }
        function CloseSemsterModaL(id) {
            console.log("Open modal with id:", id);
            const modal = new bootstrap.Modal(document.getElementById('closeSemester'));
            modal.show();
            document.getElementById("semesterIDClose").value = id;
            document.getElementById("closeForm").action = "../../auth/authentications.php";
        }

       function deleteSub(id) {
            const deleteModal = new bootstrap.Modal(document.getElementById('deletesubjects'));
            deleteModal.show();
            document.getElementById("jobID").value = id;
            document.getElementById("deleteForm").action = "../../auth/authentications.php?id=" + id;
        }

     

        function CancelJob() {
            const deleteModalEl = document.getElementById('deletesubjects');
            const openModalEl = document.getElementById('openSemester');

            const deleteModal = bootstrap.Modal.getInstance(deleteModalEl);
            const openModal = bootstrap.Modal.getInstance(openModalEl);

            if (deleteModal) deleteModal.hide();
            if (openModal) openModal.hide();
        }

        document.getElementById("search").addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();
                filterSemesterTable();
            }
        });

        document.getElementById("buttonSearchHehe").addEventListener("click", filterSemesterTable);

        function filterSemesterTable() {
            const input = document.getElementById("search").value.toLowerCase();
            const rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const schoolYear = row.cells[1].textContent.toLowerCase();
                const semester = row.cells[2].textContent.toLowerCase();
                row.style.display = (schoolYear.includes(input) || semester.includes(input)) ? "" : "none";
            });
        }

    </script>
    <!-- <script src="../../assets/js/hr/hrLL.js"></script> -->
<?php include '../../templates/Ufooter.php'; ?>