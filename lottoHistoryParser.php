<?php
    include('simple_html_dom.php');
    class databaseConnection {
      private $servername = "localhost:3306";
      private $username = "root";
      private $password = "Pa\$\$w0rd";
      private $dbname = "Lotto";
      private $conn;

      function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        echo "Connected successfully";
      }

      function getInstance() {
        return $this->conn;
      }
    }

    for ($i = 2002; $i < 2022; $i ++)
    {
      $lottoArray = array();
      $html = file_get_html("https://www.national-lottery.com/thunderball/results/$i-archive");
      // $html = file_get_html('Thunderball Draw Results Archive_ 1999.html');
      $ret = $html->find('table[class=table thunderball mobFormat mobResult] tbody', 0);
      $lottoId = 0;
      $lottoInfo = $ret->children($lottoId);
      while(!is_null($lottoInfo))
      {
        echo '<p></p>';
        $query = "INSERT INTO Lotto.lotto_result (meeting_date, number_1, number_2, number_3, number_4, number_5, number_special) VALUES (";
        $format = 'l jS F Y';
        $lottoDate = substr($lottoInfo->children(0)->children(0)->title, 34);
        $formatDate = DateTime::createFromFormat($format, $lottoDate);
        if ($formatDate != false)
        {
          $query .= '\'' . $formatDate->format('Ymd') . '\'';

          $ballArray = $lottoInfo->find('li');
          foreach ($ballArray as $key => $ball) {
            $query .= ', ';
            echo $ball->plaintext;
            $query .= '\'' . $ball->plaintext . '\'';
          }
           $query .= ');';
           echo $query;
        }
        array_push($lottoArray, $query);
        $lottoId += 1;
        $lottoInfo = $ret->children($lottoId);
      }

      $database = new databaseConnection();
      foreach ($lottoArray as $key => $query) {
        $result = $database->getInstance()->query($query);
        if ($result === TRUE) {
          echo "<br/>". "Insert successfully" . "<br/>";
        } else {
          echo "<br/>". "Insert category fail!!!" . "<br/>" . $database->getInstance()->error . "<br/>";
        }
      }
    }
?>
