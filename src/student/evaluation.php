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
                    <?php if($student_info["user_profile"] != '') { ?><img src="../../assets/image/<?= $student_info["user_profile"] ?>" alt="pfp" id="pfpOnTop"><?php }else{ ?>
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
            <div class="containerDashboard p-0 m-0 px-2 col-md-12 col-md-11 d-flex align-items-center justify-content-center">
                <div class="card p-0 m-0 shadow col-md-12 p-2" style="height: 78vh;">
                    <div class="row m-0 d-flex align-items-center justify-content-center noScroll col-md-12 col-12" style="height: 75vh; overflow-y: scroll; ">
                        <?php if (!empty($professor)): ?>
                            <?php foreach ($professor as $prof): ?>
                                <div class="col d-flex flex-row flex-wrap align-items-center justify-content-center m-0 p-0 col-md-4 col-11">
                                    <div class="card col-md-12 col-12">
                                        <div class="card-body text-center col-md-12 col-12">
                                            <?php if($prof["professor_profile"] !== null) {?>
                                                <img src="../../assets/image/uploads/<?= $prof["professor_profile"] ?>" alt="" style="width: 150px; height: 150px; border-radius: 50%;">
                                            <?php }else{ ?>
                                                <img src="../../assets/image/user.png" alt="icon">
                                            <?php } ?>
                                            <h6 class="card-subtitle text-muted mb-2">
                                                Professor ID: <?= htmlspecialchars($prof['teacherID']) ?>
                                            </h6>

                                            <h5 class="card-title mb-1">
                                                <?= htmlspecialchars($prof['fname'].' '.$prof['lname']) ?>
                                            </h5>

                                            <p class="card-text mb-1">
                                                <strong>Email:</strong> <?= htmlspecialchars($prof['email']) ?><br>
                                                <strong>Position:</strong> <?= htmlspecialchars($prof['profession']) ?>
                                            </p>

                                            <a href="evaluateProfessor.php?teacherID=<?= $prof['teacherID'] ?>"
                                            class="btn btn-success btn-sm w-100">
                                            Evaluate
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col">
                                <div class="alert alert-secondary text-center w-100">
                                    No assigned professors to evaluate.
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
    
    <!-- <script src="../../assets/js/hr/hrLL.js"></script> -->
<?php include '../../templates/Ufooter.php'; ?>

            