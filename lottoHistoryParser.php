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

      // function __destruct() {
      //   print "Destroying " . __CLASS__ . "\n";
      //   $this->conn->close();
      // }

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
    // echo $ret->children(99);
    // foreach ($ret as $lottoInfo) {
    //     // $lottoNumber = $lottoInfo->next_sibling();
    //     echo $lottoInfo;
    //     echo "<p></p>";
    //     // echo $lottoInfo->plaintext;
    //     // echo $lottoInfo->title;
    //     echo "Next";
    //     echo "<p></p>";
    //     echo "<p></p>";
    //     // $linktext = str_replace("../cinemas/Cinemas?id=","",$linkid);
    //         // array_push($lottoArray,$linktext);
    // }
    //
    // for ($cinema_index = 0; $cinema_index < count($cinema_id); $cinema_index++) {
    //
    //
    // $all_cinema_content = array();
    // $html = file_get_html('http://www.uacinemas.com.hk/eng/cinemas/Cinemas?id=' . $cinema_id[$cinema_index]);
    // $form_index = 2;
    // $movie_id = $html->find('li[id]', $form_index-1)->id;
    //
    // while (isset($movie_id)){
    //     $movie_content = array();
    //     $version = array();
    //     $version1_content = array();
    //     $temp_array = array();
    //
    //     //find out cinema id in UA
    //     //$html = new simple_html_dom();
    //     $forms_content = $html->find('form', $form_index);
    //
    //     $movie_content['film_name_en'] = $forms_content->find('a',0)->title;
    //     $movie_content['film_poster'] = "http://www.uacinemas.com.hk" . $forms_content->find('img', 0)->src;
    //
    //     $film_info= $forms_content->find('p[class=len]',0);
    //     $movie_content['film_length'] = trim(str_replace("mins", "", trim(str_replace("Length : ","", $film_info->plaintext))));
    //
    //     $film_info = $film_info->prev_sibling(); //Class : IIB (HK), C (Macau)
    //     $temp_text = trim(str_replace("Class : ","", $film_info->plaintext));
    //     if (strpos($temp_text,',') !== false) {
    //         $temp_array = explode(",", $temp_text);
    //         $movie_content['film_level'] = trim(str_replace("(HK)","", $temp_array[0]));
    //     } else {
    //         $movie_content['film_level'] = trim(str_replace("(HK)","", $temp_text));
    //     }
    //
    //     $film_info = $film_info->prev_sibling();    //Opening Date: 2015-04-02 06:00:00.0
    //     $temp_text = str_replace("Opening Date: ","", $film_info->plaintext);
    //     $temp_array = explode(" ", $temp_text);
    //     $movie_content['film_start_date'] = $temp_array[0];
    //
    //     $film_info = $film_info->prev_sibling();    //Genre : Action / Adventure
    //     $temp_text = trim(str_replace("Genre : ","", $film_info->plaintext));
    //     if (strpos($temp_text,'/') !== false) {
    //         $temp_array = explode("/", $temp_text);
    //         $movie_content['category'] = trim($temp_array[0]);
    //     } else {
    //         $movie_content['category'] = trim($temp_text);
    //     }
    //
    //     //$movie_content['film_detail_link'] = str_replace("..", "http://www.uacinemas.com.hk", $forms_content->find('a',0)->href);
    //     //
    //     $id = str_replace("../movie/MovieDetail?key=", "", $forms_content->find('a',0)->href);  //num id
    //
    //     //$ver = $forms_content->find('select[id=movies_ver]', 0);
    //     //$ver_array = $ver->find('option');
    //     //if (count($ver_array) > 0) {
    //     //    for ($i = 0; $i < count($ver_array); $i ++) {
    //     //        array_push($version, $ver_array[$i]->plaintext);
    //     //    }
    //     //}
    //
    //     array_push($all_cinema_content, $movie_content);
    //
    //     $time_select;
    //     if (preg_match("/(_allVersion)/",$movie_id)) {
    //         $time_select = $forms_content->find('select[id=' . $id . '_allVerSchedule]', 0);
    //     } else {
    //         $time_select = $forms_content->find('select[id=' . $movie_id . ']', 0);
    //     }
    //         $index = 0;
    //         $time_selected = $time_select->find('option',$index);
    //         while (isset($time_selected)) {
    //             if (strpos($time_selected->plaintext,'---------------------------------------------------------') === false) {
    //             $temp_content = explode(",", $time_selected->plaintext);
    //             $info = explode(" ", $temp_content[2]);
    //             $info[2] = $info[2] . " " . $info[3];
    //             foreach ($info as $key => $temp_info) {
    //                 if (strlen($temp_info) < 2 || (strpos($temp_info,'Midnight') !== false)) {
    //                     unset($info[$key]);
    //                 }
    //             }
    //             $info = array_values($info);
    //
    //             if (strpos($info[2], "Club") === 0 || strpos($info[2], "Theater") === 0) {
    //                 unset($info[2]);
    //                 $info = array_values($info);
    //             }
    //
    //             $info[2] = trim(str_replace("HK$","", $info[2]));
    //             $info[2] = trim(str_replace("HK$","", $info[2]));
    //             $info[3] = str_replace("(","", $info[3]);
    //             $info[3] = str_replace(")","", $info[3]);
    //             if (strpos($info[3], "IMAX") === 0) {
    //                 $info[3] = str_replace("IMAX","3D", $info[3]);
    //                 unset($info[4]);
    //                 $info = array_values($info);
    //             }
    //
    //
    //             //for ($j = 0; $j + 1< count($info); $j ++) {
    //             //    $info[$j] = $info[$j+1];
    //             //}
    //             $version1_content = array();
    //             array_push($version1_content, $temp_content[0], $temp_content[1]);
    //             $version1_content = array_merge($version1_content, $info);
    //
    //             // 0 - weekday, 1 - date(Apr 02), 2 - time (09:10AM), 3 House 2, 4 HK$60.00,
    //             // 5(2D), 5 normal - (2D) OR [5] => (2D [6] => Cantonese [7] => Ver.
    //             array_push($version, $version1_content);
    //             }
    //             //print($time_selected);
    //             $index ++;
    //             $time_selected = $time_select->find('option',$index);
    //         }
    //     //for ($i = 0; $i < count($version))
    //     //array_push($movie_content);
    //         array_push($all_cinema_content, $version);
    //         $form_index++;
    //         $movie_id = $html->find('li[id]', $form_index-1)->id;
    // }
    //     $UA[$cinema_id[$cinema_index]] = $all_cinema_content;
    // }
    //
    // $servername = "localhost";
    // $username = "root";         //Change it to user later (read permission only)
    // $password = "admin";
    // $dbname = "cineba_database";
    //
    // $conn = new mysqli($servername, $username, $password, $dbname);
    //
    // // Check connection
    // if ($conn->connect_error) {
    //     die("Connection failed: " . $conn->connect_error);
    // }
    // echo "Connected successfully";
    //
    // //Create sql here
    // //INSERT INTO cineba_database.film (film_id, film_name_cn, film_name_en, film_length, film_start_date, film_end_date, search_count, film_intro, film_language, category_id) VALUES ('3','����1', 'test film', '122', '2015-03-27', '2015-04-27', '0', 'hello world2', 'Chinese', '0');
    // $insert_film_array = array("film_name_en", "film_length", "film_start_date", "film_poster"); //info can get from array[0], **category_name in array[2]
    // //set film id and temp. use category_id = 0;
    // $insert_film_cinema_array = array("weekday", "date", "time", "price", "version");  //cinema_id search by UA key ....using array index (foreach)
    // //set cinema_film_id, each using same film id
    // $film_index = 2;
    // $film_cinema_index = 1;
    // $film_sql_head = "INSERT INTO cineba_database.film (";
    // $film_cinema_sql_head = "INSERT INTO cineba_database.cinema_film_info (";
    // $film_sql = "INSERT INTO cineba_database.film (";
    // $film_cinema_sql = "INSERT INTO cineba_database.cinema_film_info (";
    // $send_already = false;
    //
    //
    // $sql_head_for_cat_id = "SELECT category_id FROM cineba_database.category WHERE category_name = '";
    // $category_count = 1; //current count(*) hard code...
    // $sql_head_for_insert_cat = "INSERT INTO cineba_database.category (category_id, category_name) VALUES ('";
    //
    // $sql_head_for_cinema_id = "SELECT cinema_id FROM cineba_database.cinema WHERE ua_key = '";
    // $sql_head_for_search_film = "SELECT film_id FROM cineba_database.film WHERE film_name_en = '";
    //
    // foreach ($UA as $key=>$value) {     //key is cinema_id
    //     $insert_cinema_id = "";
    //     $temp_cin_sql = $sql_head_for_cinema_id . $key . "';";
    //     $temp_cin_result = $conn->query($temp_cin_sql);
    //     if ($temp_cin_result->num_rows > 0) {
    //        $row = $temp_cin_result->fetch_assoc();
    //        $insert_cinema_id = $row['cinema_id'];
    //     } else {   //not find category
    //        echo "<br/>". "Cannot find cinema ID!" . "<br/>" . "For ID:" . $key . "<br/>";
    //     }
    //    for ($i = 0; $i < count($value); $i += 2) {
    //
    //         //find category_id
    //          $send_already = false;
    //          $cat_id = 0;
    //          $temp_film_send = "";
    //
    //          $temp_sql = $sql_head_for_cat_id . $value[$i]['category'] . "';";
    //          $temp_result = $conn->query($temp_sql);
    //          if ($temp_result->num_rows > 0) {
    //             $row = $temp_result->fetch_assoc();
    //             $cat_id = $row['category_id'];
    //          } else {   //not find category
    //             //insert new category
    //             $temp_sql = $sql_head_for_insert_cat . $category_count . "', '" . $value[$i]['category'] . "');";
    //             $temp_result = $conn->query($temp_sql);
    //             if ($temp_result === TRUE) {
    //                 $cat_id = $category_count;
    //                 $category_count ++;
    //             } else {
    //                 echo "<br/>". "Insert category fail!!!" . "<br/>" . $conn->error . "<br/>";
    //             }
    //          }
    //
    //         $film_sql = $film_sql_head;
    //         //film sql
    //         for ($j = 0; $j < count($insert_film_array); $j++) {
    //             $film_sql .= $insert_film_array[$j] . ",";
    //         }
    //         $film_sql .= "category_id, film_id, film_language) VALUES (";
    //         $temp_film_send = $film_sql;
    //         //end film head
    //
    //         //film content
    //         $temp_search_film = $sql_head_for_search_film . $value[$i]['film_name_en'] . "';";
    //         $temp_search_film_result = $conn->query($temp_search_film);
    //         if ($temp_search_film_result->num_rows > 0){
    //             $send_already = true;
    //         } else {
    //             $value[$i]['film_name_en'] = str_replace("��","'��",$value[$i]['film_name_en']);
    //             $value[$i]['film_name_en'] = str_replace("'","''",$value[$i]['film_name_en']);
    //             for ($j = 0; $j < count($insert_film_array); $j++) {
    //                $film_sql .= "'" . $value[$i][$insert_film_array[$j]] . "',";
    //             }
    //             $film_sql .= "'" . $cat_id . "','" . $film_index . "',";
    //         }
    //         // film continue
    //
    //         //film_cinema content
    //         for ($k = 0; $k < count($value[$i+1]); $k++) {
    //             $film_cinema_sql = $film_cinema_sql_head;
    //             for ($j = 0; $j < count($insert_film_cinema_array); $j++) {
    //                 $film_cinema_sql .= $insert_film_cinema_array[$j] . ",";
    //             }
    //                 $film_cinema_sql .= "level, cinema_film_id, cinema_id, film_id) VALUES (";
    //             for ($j = 0; $j < count($value[$i+1][$k]); $j++) {
    //                 if ($j == 1) {
    //                     $film_cinema_sql .= "'" . date("Y-m-d",strtotime($value[$i+1][$k][$j])) . "',";
    //                 } else if ($j == 2){
    //                     $film_cinema_sql .= "'" . date("H:i:s",strtotime($value[$i+1][$k][$j])) . "',";
    //                 }
    //                 else if ($j == 3) {continue;}
    //                 else if ($j<6) {
    //                     $film_cinema_sql .= "'" . $value[$i+1][$k][$j] . "',";
    //                 } else if ($j == 6) {
    //                     //input film language to film
    //                     if (strpos($value[$i+1][$k][$j],'3D') === false && $send_already == false) {
    //                         $film_sql = $temp_film_send;
    //                         for ($j = 0; $j < count($insert_film_array); $j++) {
    //                             $film_sql .= "'" . $value[$i][$insert_film_array[$j]] . "',";
    //                         }
    //                         $film_sql .= "'" . $cat_id . "','" . $film_index . "',";
    //                         $film_sql .= "'". $value[$i+1][$k][6] ."');";
    //                         echo "<br/>Film sql: " . $film_sql . "<br/>";
    //
    //                         if ($conn->query($film_sql) === TRUE) {
    //                             $send_already = true;
    //                             $film_index ++;
    //                             echo "<br/>New record created successfully for film<br/>";
    //                         } else {
    //                             echo "<br/> Error: ". $conn->error ."<br/>";
    //                             echo "<br/> Data check1: " . $value[$i+1][$k][4] . " & " . $value[$i+1][$k][5] . " & " . $value[$i+1][$k][6] . "<br/>";
    //                         }
    //                     }
    //                     //finish film content
    //                 }
    //             }
    //
    //             //finish film_cinema_sql
    //
    //             if ($send_already == false) {
    //                 $film_sql .= "'---');";
    //                 echo "<br/>Film sql: " . $film_sql . "<br/>";
    //                 if ($conn->query($film_sql) === TRUE) {
    //                     $send_already = true;
    //                     $film_index++;
    //                     echo "New record created successfully for film";
    //                 } else {
    //                     echo "<br/> Error: ". $conn->error ."<br/>";
    //                     echo "<br/> Data check2: " . $value[$i+1][$k][4] . " & " . $value[$i+1][$k][5] . " & " . $value[$i+1][$k][6] . "<br/>";
    //                 }
    //             }
    //             $t_insert = $film_index-1;
    //             $film_cinema_sql .= "'" . $value[$i]['film_level'] . "','" . $film_cinema_index . "','" . $insert_cinema_id . "','" . $t_insert . "');";
    //             echo "<br/>Film cinema sql: " . $film_cinema_sql . "<br/>";
    //
    //             if ($conn->query($film_cinema_sql) === TRUE) {
    //                 echo "<br/>New record created successfully for film cinema<br/>";
    //                 $film_cinema_index++;
    //             } else {
    //                 echo "<br/>Error: ". $conn->error ."<br/>";
    //                 echo "<br/>";
    //                 print_r($value[$i+1][$k]);
    //                 echo "<br/>";
    //             }
    //         }
    //     }
    // }
    //
    //
    // //print_r($version);
    // //echo "<br/>";
    // //print_r($version1_content);
    // //echo "<br/>";
    // //$e = $ret->next_sibling();
    // //
    // //foreach ($e->find('a') as $link) {
    // //    $linkid = $link->href;
    // //
    // //}
?>
