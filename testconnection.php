<?php
// phpinfo();
$servername = "localhost:3306";
$username = "root";
$password = "Pa\$\$w0rd";
$dbname = "Lotto";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

$sql = "SELECT * FROM Lotto.lotto_result;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<br>" . "Meeting: " . $row["meeting_date"]. " - Number: " . $row["number_1"]. " " . $row["number_2"]. " " . $row["number_3"] .  " " . $row["number_4"]
         .  " " . $row["number_5"] .  " " . $row["number_special"] . "<br>";
    }
} else {
    echo "0 results";
}


$conn->close();
?>
