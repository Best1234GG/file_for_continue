<?php
// การเชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "krukarnkarn";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ดึงคิวทั้งหมดจากตาราง quece
$result = $conn->query('SELECT * FROM quece');

// หาค่าคิวที่มีอยู่แล้ว
$existingQueues = [];
while ($row = $result->fetch_assoc()) {
    $existingQueues[] = $row['key_value'];
}

// ฟังก์ชั่น generate คิวใหม่
function generateQueue($existingQueues) {
    $letter = 'A';
    $number = 1;

    $newQueue = $letter . sprintf("%03d", $number);
    
    while (in_array($newQueue, $existingQueues)) {
        $number++;

        if ($number > 999) {
            $number = 1;
            $letter++;
        }

        $newQueue = $letter . sprintf("%03d", $number);
    }

    return $newQueue;
}

// ตัวอย่างการใช้งาน
$newQueue = generateQueue($existingQueues);

echo 'Generated Queue: ' . $newQueue;

// บันทึกคิวใหม่ลงในตาราง quece
$sql = "INSERT INTO quece (key_value) VALUES ('{$newQueue}')";
if ($conn->query($sql) === TRUE) {
    echo "บันทึกคิวใหม่เรียบร้อย";
} else {
    echo "ผิดพลาดในการบันทึกคิว: " . $conn->error;
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
