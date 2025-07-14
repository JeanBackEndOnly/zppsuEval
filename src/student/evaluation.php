<?php include '../../templates/Uheader.php'; include '../../templates/HeaderNav.php'; ?>
<style>
    .evaluationNav{
        background-color: var(--new);
        font-weight: 500;
    }
    .dashboardNav{
        background: linear-gradient(40deg , #77070b62,#77070b62, #77070A, #77070b62, #77070b62) !important;
    }
</style>
<div class="main w-100 h-100 d-flex flex-column">
    <?= getHeader() ?>
    <div class="row w-100 p-0 m-0">
        <div class="col-auto sideNav bg-linear h-100">
            <div class="sideContents" id="sideContents">
                <div class="profileBox w-100 d-flex flex-column justify-content-center align-items-center mt-2 mb-3">
                    <?php if($student_info["users_profile"] != '') { ?><img src="../../assets/image/<?= $student_info["users_profile"] ?>" alt="pfp" id="pfpOnTop"><?php }else{ ?>
                    <img src="../../assets/image/Avatar.png" alt="pfp" id="pfpOnTop"><?php } ?>
                    <label class="fw-bold text-white"><?= $student_info["SchoolID"] ?></label>
                    <label class="text-white text-center fw-bold"><?= $student_info["lname"] . ", " . $student_info["fname"]  ?></label>
                </div>
                <?= getNav() ?>
            </div>
        </div>

        <div class="col content p-0">
            <div class="col-md-12 col-md-11 d-flex align-items-center justify-content-center mt-1 mb-4">
                <div class="col-md-11 col-md-11 d-flex align-items-center justify-content-start">
                    <h3 class="fw-bold text-muted">EVALUATEES</h3>
                </div>
            </div>
            <div class="containerDashboard w-100 col-md-12 col-md-11 d-flex align-items-center justify-content-center">
                <div class="card p-0 m-0 shadow col-md-11 p-2">
                    <h4 class="mb-3">Professors in Your Department</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Professor ID</th>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Action</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($professor)): ?>
                                    <?php $count = 1; ?>
                                    <?php foreach ($professor as $prof): ?>
                                        <tr>
                                            <th scope="row"><?= $count++ ?></th>
                                            <td><?= htmlspecialchars($prof['teacherID']) ?></td>
                                            <td><?= htmlspecialchars($prof['fname'] . ' ' . $prof['lname']) ?></td>
                                            <td><?= htmlspecialchars($prof['email']) ?></td>
                                            <td><?= htmlspecialchars($prof['profession']) ?></td>
                                            <td>
                                                <a href="evaluateProfessor.php?id=<?= $prof['id'] ?>" class="btn btn-success btn-sm w-100">Evaluate</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-muted fst-italic">No assigned professors to evaluate.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
    <!-- <script src="../../assets/js/hr/hrLL.js"></script> -->
<?php include '../../templates/Ufooter.php'; ?>

            