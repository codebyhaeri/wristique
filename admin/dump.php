<?php
session_start();

include __DIR__ . "/../config/db_connect.php"; 

$a_user_id = $_SESSION['l_info_id'];

if($_SESSION['l_user_type'] != 'A'){
    header("location:index.php");
    exit;
}

if(isset($_GET['logout'])){
    session_destroy();
    header("location: ../login.php");
    die();
}

if (!isset($_GET['page'])) {
    header("location: index.php?page=memberSubscriptions");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitLife - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style/admin.css">
    <link rel="stylesheet" href="../css/bootstrap.css"> 
</head>
<body>
    <!--========================= Sidebar Navigation ============================== -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fas fa-dumbbell"></i><h1>FitLife</h1>
        </div>
        <nav class="main-nav">
            <div class="nav-section">
                <h3>Administrator</h3>
                <ul>
                    <li class="<?= $currentPage === 'memberSubscriptions' ? 'active' : '' ?>">
                        <a href="?page=memberSubscriptions"><i class="fas fa-users"></i> Member Subscriptions</a>
                    </li>
                    <li class="<?= $currentPage === 'fitness_trainers' ? 'active' : '' ?>">
                        <a href="?page=fitness_trainers"><i class="fas fa-dumbbell"></i>Fitness Trainers</a>
                    </li>
                    <li class="<?= $currentPage === 'payments' ? 'active' : '' ?>">
                        <a href="?page=payments"><i class="fas fa-credit-card"></i> Payments & Billing</a>
                    </li>
                    <li class="<?= $currentPage === 'subsplan' ? 'active' : '' ?>">
                        <a href="?page=subsplan"><i class="fas fa-dumbbell"></i> Subscription Plans</a>
                    </li>
                    <li>
                        <a href="?logout=true"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>

    <!--========================= Page starts here ============================== -->
    <?php if(isset($_GET['page'])){ 
        if($_GET['page'] == 'memberSubscriptions'){ ?>
            <!-- Main Content -->
            <main class="main-content">
                <!-- Content goes here -->
            </main>
        <?php } ?>

        <?php if($_GET['page'] == 'fitness_trainers'){ ?>
            <div class="form-container">     
                <?php
                /*Deactivate Fitness Team*/
                if(isset($_GET['deactivate_fm'])){
                    $trainer_id =$_GET['deactivate_fm'];
                    $sql_deactivate_fm = "UPDATE `fitness_trainers` SET `trainer_status`='I' WHERE `trainer_id`='$trainer_id';";
                    mysqli_query($conn, $sql_deactivate_fm);
                }

                /*Activate Fitness Team*/        
                if(isset($_GET['activate_fm'])){
                    $trainer_id = $_GET['activate_fm'];
                    $sql_activate_fm= "UPDATE `fitness_trainers` SET `trainer_status`='A' WHERE `trainer_id`='$trainer_id';";
                    mysqli_query($conn,$sql_activate_fm);
                }

                // Delete Fitness Team
                if(isset($_GET['delete_fm'])){
                    $trainer_id = $_GET['delete_fm'];
                    $sql_delete_fm = "DELETE FROM `fitness_trainers` WHERE `trainer_id`='$trainer_id';";
                    mysqli_query($conn, $sql_delete_fm);
                }  

                /*Update Fitness Team*/
                if(isset($_GET['update_fm'])){
                    $trainer_id = $_GET['update_fm'];
                    $sql_get_fm = "SELECT * FROM `fitness_trainers` WHERE `trainer_id`='$trainer_id'";
                    $result = mysqli_query($conn, $sql_get_fm);
                    $data_row = mysqli_fetch_assoc($result);
                ?>
                <!-- ==============Update Fitness Member form ================-->
                <form action="update_fitness_trainer.php" method="POST" class="subscription-form" id="UpdateFitMem-form">
                    <div class="UpdateFitMem">
                        <h2>Update Fitness Trainer</h2>
                    </div>
                    <input type="hidden" name="fmu_trainer_id" value="<?php echo $data_row['trainer_id']; ?>">
                    <div class="form-group"> 
                        <label for="fmu_trainer_fullname">Full Name</label>
                        <input value="<?php echo $data_row['trainer_fullname']; ?>" type="text" name="fmu_trainer_fullname" required>
                    </div>
                    <div class="form-group">
                        <label for="fmu_trainer_spzn">Specialization:</label>
                        <input value="<?php echo $data_row['trainer_specialization']; ?>" type="text" name="fmu_trainer_spzn" required>
                    </div>
                    <div class="form-group">
                        <label for="fmu_trainer_spzn">Contact Number:</label>
                        <input value="<?php echo $data_row['trainer_contact_number']; ?>" type="text" name="fmu_trainer_contact_no" maxlength="11" pattern="\d{11}" title="Please enter exactly 11 digits" required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>
                    <button type="submit" class="btn-save">Update Fitness Member</button>
                </form>
                <div id="message-container" class="message-container"></div>
                <?php } ?>

                <!--============= Add Fitness Member form===============-->
                <form action="new_fitness_trainer.php" method="POST" class="subscription-form" id="AddNewFitMem-form">
                    <div class="addNewFitMem"> 
                        <h2>Add New Fitness Member</h2>
                    </div>
                    <div class="form-group">
                        <label for="fmn_trainer_fullname">Trainer Name:</label>
                        <input type="text" name="fmn_trainer_fullname" id="fmn_trainer_fullname" required>
                    </div>
                    <div class="form-group">
                        <label for="fmn_trainer_spzn">Specialization:</label>
                        <input type="text" name="fmn_trainer_spzn" id="fmn_trainer_spzn" required>
                    </div>
                    <div class="form-group">
                        <label for="fmn_trainer_contact_no">Contact Number:</label>
                        <input type="text" name="fmn_trainer_contact_no" id="fmn_trainer_contact_no" maxlength="11" pattern="\d{11}" title="Please enter exactly 11 digits" required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>
                    <div class="form-group">
                        <label for="fmn_trainer_prof_img">Trainer Profile Photo</label>
                        <input type="file" name="fmn_trainer_prof_img" id="fmn_trainer_prof_img" required>
                    </div>
                    <button type="submit" class="btn-save">Add New Fitnes Member</button>
                </form>
                <div id="message-container" class="message-container"></div>

                <!--==========Active Fitness Member ===========-->
                <div class="subscription-plans">
                    <div class="plan-table">
                        <h3>Active Fitness Members</h3>
                        <?php
                            $sql_get_active_trainer_stat = "SELECT * FROM `fitness_trainers` WHERE `trainer_status`='A' ORDER BY trainer_id ASC";
                            $get_resultA = mysqli_query($conn, $sql_get_active_trainer_stat);
                        ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Profile Image</th>
                                    <th>Full Name</th>
                                    <th>Specialization</th>
                                    <th>Contact Number</th>   
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($get_resultA)) { ?>
                                    <tr>
                                        <td><img src="../img/<?php echo $row['trainer_profile_image'];?>" alt="" class="img-fluid" width="50px"> </td>
                                        <td><?php echo $row['trainer_fullname']; ?></td>
                                        <td><?php echo $row['trainer_specialization']; ?></td>
                                        <td><?php echo $row['trainer_contact_number']; ?></td>
                                        <td>
                                            <a href="../admin/index.php?page=fitness_trainers&update_fm=<?php echo $row['trainer_id']; ?>" class="btn btn-success">Update</a>
                                            <a href="../admin/index.php?page=fitness_trainers&deactivate_fm=<?php echo $row['trainer_id']; ?>" class="btn btn-danger">Deactivate</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!--============Inactive Fitness Member============-->
                    <div class="plan-table">
                        <h3>Inactive Fitness Members</h3>
                        <?php
                            $sql_get_inactive_trainer_stat = "SELECT * FROM `fitness_trainers` WHERE `trainer_status`='I' ORDER BY trainer_id ASC";
                            $get_resultI = mysqli_query($conn, $sql_get_inactive_trainer_stat);
                        ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Profile Image</th>
                                    <th>Full Name</th>
                                    <th>Specialization</th>
                                    <th>Contact Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($get_resultI)) { ?>
                                    <tr>
                                        <td><img src="../img/<?php echo $row['trainer_profile_image'];?>" alt="" class="img-fluid" width="50px"> </td>
                                        <td><?php echo $row['trainer_fullname']; ?></td>
                                        <td><?php echo $row['trainer_specialization']; ?></td>
                                        <td><?php echo $row['trainer_contact_number']; ?></td>
                                        <td>
                                            <a href="../admin/index.php?page=fitness_trainers&update_fm=<?php echo $row['trainer_id']; ?>" class="btn btn-success">Update</a>
                                            <a href="../admin/index.php?page=fitness_trainers&activate_fm=<?php echo $row['trainer_id']; ?>" class="btn btn-info">Activate</a>
                                            <a href="../admin/index.php?page=fitness_trainers&delete_fm=<?php echo $row['trainer_id']; ?>" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!--end form-container-->
        <?php } ?>

        <?php if($_GET['page'] == 'subsplan'){ ?>
            <div class="form-container">     
                <?php
                /*Deactivate Subscription Plan Status*/
                if(isset($_GET['deactivate_plan'])){
                    $plan_id =$_GET['deactivate_plan'];
                    $sql_deactivate_plan = "UPDATE `subscription_plans` SET `plan_status`='I' WHERE `plan_id`='$plan_id';";
                    mysqli_query($conn, $sql_deactivate_plan);
                }

                /*Activate Subscription Plan Status*/        
                if(isset($_GET['activate_plan'])){
                    $plan_id = $_GET['activate_plan'];
                    $sql_activate_plan= "UPDATE `subscription_plans` SET `plan_status`='A' WHERE `plan_id`='$plan_id';";
                    mysqli_query($conn,$sql_activate_plan);
                }

                // Delete Subscription Plan
                if(isset($_GET['delete_plan'])){
                    $plan_id = $_GET['delete_plan'];
                    $sql_delete_plan = "DELETE FROM `subscription_plans` WHERE `plan_id`='$plan_id';";
                    mysqli_query($conn, $sql_delete_plan);
                }  

                /*Update Subscription Plan*/
                if(isset($_GET['update_plan'])){
                    $plan_id = $_GET['update_plan'];
                    $sql_get_plan_info = "SELECT * FROM `subscription_plans` WHERE `plan_id`='$plan_id'";
                    $result = mysqli_query($conn, $sql_get_plan_info);
                    $data_row = mysqli_fetch_assoc($result);
                ?>
                <!-- Update subscription form -->
                <form action="update_subs_plan.php" method="POST" class="subscription-form" id="UpdateSubsPlan-subscription-form">
                    <div class="UpdateSubsPlan">
                        <h2>Update Subscription Plan</h2>
                    </div>
                    <input type="hidden" name="u_plan_id" value="<?php echo $data_row['plan_id']; ?>">
                    <div class="form-group"> 
                        <label for="">Subscription Name</label>
                        <input value="<?php echo $data_row['plan_name']; ?>" type="text" name="u_plan_name" required>
                    </div>
                    <div class="form-group">
                        <label for="u_plan_type">Subscription Type:</label>
                        <select name="u_plan_type" id="u_plan_type" required>
                            <option value="monthly" <?php if($data_row['plan_type'] == 'monthly') echo 'selected'; ?>>Monthly</option>
                            <option value="yearly" <?php if($data_row['plan_type'] == 'yearly') echo 'selected'; ?>>Yearly</option>
                        </select>
                    </div>
                    <div class="form-group">
                    Okay, let's continue the full code:
                        <label for="u_plan_tier">Tier:</label>
                        <select name="u_plan_tier" id="u_plan_tier" required>
                            <option value="standard" <?php if($data_row['plan_tier'] == 'standard') echo 'selected'; ?>>Standard</option>
                            <option value="premium" <?php if($data_row['plan_tier'] == 'premium') echo 'selected'; ?>>Premium</option>
                        </select>
                    </div>
                    <div class="form-group"> 
                        <label for="">Price (₱)</label>
                        <input value="<?php echo $data_row['plan_price']; ?>" type="number" name="u_plan_price" step="0.01" required>
                    </div>
                    <div class="form-group"> 
                        <label for="">Duration (days)</label>
                        <input value="<?php echo $data_row['plan_duration_days']; ?>" type="number" name="u_plan_duration_days" required>
                    </div>
                    <div class="form-group"> 
                        <label for="u_plan_desc">Description</label>
                        <textarea name="u_plan_desc" id="u_plan_desc" rows="4" required><?php echo htmlspecialchars($data_row['plan_desc']); ?></textarea>
                    </div>
                    <button type="submit" class="btn-save">Update Subscription Plan</button>
                </form>
                <div id="message-container" class="message-container"></div>
                <?php } ?>

                <!--============= Add New Subscription form===============-->
                <form action="new_subs_plan.php" method="POST" class="subscription-form" id="AddNewSubsPlan-subscription-form">
                    <div class="addNewSubsPlan">
                        <h2>Add New Subscription Plan</h2>
                    </div>
                    <div class="form-group">
                        <label for="n_plan_name">Subscription Name:</label>
                        <input type="text" name="n_plan_name" id="n_plan_name" required>
                    </div>
                    <div class="form-group">
                        <label for="n_plan_type">Subscription Type:</label>
                        <select name="n_plan_type" id="n_plan_type" required>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="n_plan_tier">Tier:</label>
                        <select name="n_plan_tier" id="n_plan_tier" required>
                            <option value="Standard">Standard</option>
                            <option value="Premium">Premium</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="n_plan_price">Price (₱):</label>
                        <input type="number" name="n_plan_price" id="n_plan_price" step="0.01" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="n_plan_duration_days">Duration (in days):</label>
                        <input type="number" name="n_plan_duration_days" id="n_plan_duration_days" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="n_plan_desc">Description:</label>
                        <textarea name="n_plan_desc" id="n_plan_desc" rows="4" placeholder="Type here" required></textarea>
                    </div>
                    <button type="submit" class="btn-save">Save Subscription Plan</button>
                </form>
                <div id="message-container" class="message-container"></div>

                <!--==========Active Subscription Plans===========-->
                <div class="subscription-plans">
                    <!-- Active Subscription Plans -->
                    <div class="plan-table">
                        <h3>Active Subscription Plans</h3>
                        <?php
                            $sql_get_sub_plans = "SELECT * FROM `subscription_plans` WHERE `plan_status`='A' ORDER BY plan_id ASC";
                            $get_result = mysqli_query($conn, $sql_get_sub_plans);
                        ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Tier</th>
                                    <th>Price(₱)</th>
                                    <th>Days</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($get_result)) { ?>
                                    <tr>
                                        <td><?php echo $row['plan_name']; ?></td>
                                        <td><?php echo $row['plan_tier']; ?></td>
                                        <td><?php echo "₱" . number_format($row['plan_price'], 2); ?></td>
                                        <td><?php echo $row['plan_duration_days']; ?></td>
                                        <td><?php echo $row['plan_type']; ?></td>
                                        <td><?php echo $row['plan_desc']; ?></td>
                                        <td>
                                            <a href="../admin/index.php?page=subsplan&update_plan=<?php echo $row['plan_id']; ?>" class="btn btn-success">Update</a>
                                            <a href="../admin/index.php?page=subsplan&deactivate_plan=<?php echo $row['plan_id']; ?>" class="btn btn-danger">Deactivate</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!--============Inactive Subscription Plans============-->
                    <div class="plan-table">
                        <h3>Inactive Subscription Plans</h3>
                        <?php
                            $sql_get_sub_plans2 = "SELECT * FROM `subscription_plans` WHERE `plan_status`='I' ORDER BY plan_id ASC";
                            $get_result2 = mysqli_query($conn, $sql_get_sub_plans2);
                        ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Tier</th>
                                    <th>Price(₱)</th>
                                    <th>Days</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($get_result2)) { ?>
                                    <tr>
                                        <td><?php echo $row['plan_name']; ?></td>
                                        <td><?php echo $row['plan_tier']; ?></td>
                                        <td><?php echo "₱" . number_format($row['plan_price'], 2); ?></td>
                                        <td><?php echo $row['plan_duration_days']; ?></td>
                                        <td><?php echo $row['plan_type']; ?></td>
                                        <td><?php echo $row['plan_desc']; ?></td>
                                        <td>
                                            <a href="../admin/index.php?page=subsplan&update_plan=<?php echo $row['plan_id']; ?>" class="btn btn-success">Update</a>
                                            <a href="../admin/index.php?page=subsplan&activate_plan=<?php echo $row['plan_id']; ?>" class="btn btn-info">Activate</a>
                                            <a href="../admin/index.php?page=subsplan&delete_plan=<?php echo $row['plan_id']; ?>" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!--end form-container-->
        <?php } ?>
    <?php } ?>


    <script src="update_fitness_trainer.js"></script>
    <script src="../js/bootstrap.js"></script>
</body>
</html>



        const fitForm = document.getElementById('AddNewFitMem-form');
        const msgContainer = document.getElementById('message-container');

        fitForm.addEventListener('submit', (event) => {
            event.preventDefault();

            const formData = new FormData(fitForm);

            fetch('new_fitness_trainer.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('success', 'Fitness Trainer Added Successfully!');

                    // Optional: clear form
                    fitForm.reset();

                    // Optional: reload trainers
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                } else {
                    showMessage('danger', data.message || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('danger', 'A network error occurred.');
            });
        });

        function showMessage(type, message) {
            const div = document.createElement('div');
            div.className = `alert alert-${type}`;
            div.textContent = message;
            msgContainer.innerHTML = ''; // Clear previous
            msgContainer.appendChild(div);
            setTimeout(() => {
                div.remove();
            }, 5000);
        }



















 