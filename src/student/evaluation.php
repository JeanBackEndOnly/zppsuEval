<?php include '../../templates/Uheader.php'; include '../../templates/HeaderNav.php'; ?>
<style>
    .evaluationNav{
        background-color: var(--new);
        font-weight: 500;
    }
</style>
<div class="main w-100 h-100 d-flex flex-column">
    <?= getHeader() ?>
    <div class="row w-100 p-0 m-0">
        <div class="col-auto sideNav bg-linear h-100">
            <div class="sideContents" id="sideContents">
                <div class="profileBox w-100 d-flex flex-column justify-content-center align-items-center mt-2 mb-3">
                    <img src="../../assets/image/zppsu-logo.png" alt="pfp" id="pfpOnTop">
                    <label class="fw-bold text-white"><?= $student_info["SchoolID"] ?></label>
                    <h5 class="text-white"><?= $student_info["lname"] . ", " . $student_info["fname"]  ?></h5>
                </div>
                <?= getNav() ?>
            </div>
        </div>

        <div class="col content p-0">
            <div class="containerDashboard w-100">
                <div class="card p-3 m-3 shadow" style="width: 97%; height: 95%;">
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

            