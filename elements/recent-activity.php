<?php
// Fungsi untuk menghitung waktu mundur
function time_ago($timestamp) {
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    $minutes      = round($seconds / 60);           // value 60 is seconds
    $hours        = round($seconds / 3600);         // value 3600 is 60 minutes * 60 sec
    $days         = round($seconds / 86400);        // value 86400 is 24 hours * 60 minutes * 60 sec
    $weeks        = round($seconds / 604800);       // value 604800 is 7 days * 24 hours * 60 minutes * 60 sec
    $months       = round($seconds / 2629440);      // value 2629440 is ((365+365+365+365+365)/5/12/30) days * 24 hours * 60 minutes * 60 sec
    $years        = round($seconds / 31553280);     // value 31553280 is (365+365+365+365+365)/5 days * 24 hours * 60 minutes * 60 sec

    if ($seconds <= 60) {
        return "Just Now";
    } else if ($minutes <= 60) {
        if ($minutes == 1) {
            return "one minute ago";
        } else {
            return "$minutes minutes ago";
        }
    } else if ($hours <= 24) {
        if ($hours == 1) {
            return "an hour ago";
        } else {
            return "$hours hours ago";
        }
    } else if ($days <= 7) {
        if ($days == 1) {
            return "yesterday";
        } else {
            return "$days days ago";
        }
    } else if ($weeks <= 4.3) { // 4.3 == 30/7
        if ($weeks == 1) {
            return "one week ago";
        } else {
            return "$weeks weeks ago";
        }
    } else if ($months <= 12) {
        if ($months == 1) {
            return "one month ago";
        } else {
            return "$months months ago";
        }
    } else {
        if ($years == 1) {
            return "one year ago";
        } else {
            return "$years years ago";
        }
    }
}

// Query untuk mengambil 10 data terbaru dari activity_log menggunakan PDO
$sql = "SELECT * FROM activity_log ORDER BY timestamp DESC LIMIT 10";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Cek apakah ada data
if ($stmt->rowCount() > 0) {
    // Output setiap aktivitas
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $activity = $row['activity'];
        $timestamp = $row['timestamp'];
        
        // Hitung waktu mundur
        $time_ago = time_ago($timestamp);
        
        // Menampilkan data dalam format timeline
        echo '<div class="timeline-item"><div class="timeline-item-marker"><div class="timeline-item-marker-text">'.$time_ago.$activity;
    }
}
?>