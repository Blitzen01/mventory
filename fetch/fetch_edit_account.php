<?php
    $user_id = $_SESSION['user_id'];
    $msg = "";
    $msg_type = "dark";

    // --- UPDATE LOGIC ---
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
        $first_name = $conn->real_escape_string($_POST['first_name']);
        $last_name  = $conn->real_escape_string($_POST['last_name']);
        $username   = $conn->real_escape_string($_POST['username']);
        $email      = $conn->real_escape_string($_POST['email']);
        $phone      = $conn->real_escape_string($_POST['phone']);
        $address    = $conn->real_escape_string($_POST['address']);
        
        $password_update_sql = "";
        if(!empty($_POST['new_password'])) {
            if($_POST['new_password'] === $_POST['confirm_password']) {
                $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $password_update_sql = ", password = '$new_pass'";
            } else {
                $msg = "PASSWORDS DO NOT MATCH";
                $msg_type = "danger";
            }
        }

        $image_update_sql = "";
        if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $target_dir = "../src/image/profiles/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $file_ext = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
            $file_name = "user_" . $user_id . "_" . time() . "." . $file_ext;
            $target_file = $target_dir . $file_name;
            if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $image_update_sql = ", profile_image = '$target_file'";
            }
        }

        if ($msg_type !== "danger") {
            $sql = "UPDATE users SET 
                    first_name = '$first_name', 
                    last_name = '$last_name', 
                    username = '$username', 
                    email = '$email', 
                    phone = '$phone', 
                    address = '$address' 
                    $password_update_sql 
                    $image_update_sql
                    WHERE user_id = $user_id";

            if($conn->query($sql)) {
                $msg = "ACCOUNT SUCCESSFULLY UPDATED";
                $msg_type = "dark";
            } else {
                $msg = "DATABASE ERROR: " . $conn->error;
                $msg_type = "danger";
            }
        }
    }

    // --- FETCH USER DATA ---
    $user_query = "SELECT * FROM users WHERE user_id = $user_id";
    $user_result = $conn->query($user_query);
    $user_data = $user_result->fetch_assoc();

    // --- FORMATTED USER ID LOGIC ---
    // This creates the ID like 202401003
    if ($user_data) {
        $date_part = date("Ym", strtotime($user_data['created_at']));
        $padded_id = str_pad($user_data['user_id'], 3, "0", STR_PAD_LEFT);
        $display_user_id = $date_part . $padded_id;
    } else {
        $display_user_id = "N/A";
    }

    $password_length_query = "SELECT * FROM system_settings WHERE setting_key = 'password_min_length'";
    $length_result = mysqli_query($conn, $password_length_query);
    $min_password_length = ($length_result && $length_result->num_rows > 0) ? (int)trim($length_result->fetch_assoc()['setting_value']) : 8;
?>