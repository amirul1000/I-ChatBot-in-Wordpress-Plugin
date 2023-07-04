<?php
	   //define('WP_USE_THEMES', false);
	   if (file_exists("../../../../wp-load.php"))
		{
			require_once("../../../../wp-load.php");
		}
		
		
	   global $wpdb;
		
	   ob_clean();
  
	   $msg = strtolower(trim($_POST['msg']));

        $arrInput = explode(" ", $msg);
        // debug($arrInput);
        $arr = array(); 
        $res  = $wpdb->get_results("select * from ".$wpdb->prefix ."chatbot");
		foreach($res as $key=>$value){
		  $arr[$key]['answer'] = $res[$key]->answer;	
		  $arr[$key]['question'] = $res[$key]->question;	
		}

        $arrCount = array();

        for ($i = 0; $i < count((array)$arr); $i ++) {
            $question = strtolower($arr[$i]['question']);
            $arrQuestion = explode(" ", $question);
            $arrCount[$i] = 0;
            // debug($arrQuestion);
            for ($j = 0; $j < count((array)$arrInput); $j ++) {
                for ($k = 0; $k < count((array)$arrQuestion); $k ++) {
                    if ($arrInput[$j] == $arrQuestion[$k]) {
                        $arrCount[$i] = $arrCount[$i] + 1;
                    }
                }
            }
            // $answer = strtolower($arr[$i]['answer']);
        }
        // debug($arrCount);

        if (array_sum($arrCount) == 0) {
            echo "Sorry I can't recognize you.Please provide a bit more details";
            exit();
        } else {
            $max = $arrCount[0];
            $indicate = 0;
            for ($i = 1; $i < count((array)$arrCount); $i ++) {
                if ($arrCount[$i] > $max) {
                    $max = $arrCount[$i];
                    $indicate = $i;
                }
            }
            echo $arr[$indicate]['answer'];
            exit();
        }
  ?>  