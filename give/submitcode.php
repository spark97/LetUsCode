<?php
  require_once('curl_class.php');
  require_once('function.php');
  $client_secret = apikey();
  $compile_url = "https://api.hackerearth.com/v3/code/compile/";
  $run_url = "https://api.hackerearth.com/v3/code/run/";
  $userid = $_POST['userid'];
  $problemid = $_POST['problemid'];
  $code = $_POST['code'];
  $lang = $_POST['lang'];
  $time = $_POST['time_limit'];
  $memory = $_POST['source_limit'];

  $time = '5';
  $memory = '262144' ;


  $marks=0;
  $ans="";
  $tempsubtaskfilename = $userid."_".$problemid.".txt";
  $source = "all_files/temp_sub/".$tempsubtaskfilename;
  $file = fopen($source,"w");
  fwrite($file,$code);
  $query="SELECT * from test_cases where `problem_id`='$problemid'";
  $con=con();
  $res1=$con->query($query);
  while($arr=$res1->fetch_array())
  {
    $subtaskfilename = $arr['input'];
	$input = "all_files/input/".$subtaskfilename;
	$outputfile = "all_files/output".$subtaskfilename;
    $output = fopen($outputfile, "r");
    $output = fread($output, filesize($outputfile));
	$curl = new curl();
    $res=$curl->get_run($client_secret,$run_url,$source,$time,$memory,$input,$lang);
    //var_dump($res);
    if($res['run_status']['status']!="AC")
    	$ans=$ans.$res['run_status']['status'];
    if($res['run_status']['status']=="AC" )
    {
    	if($output == $res['run_status']['output'])
    	  {
    	  	$marks+=$arr['marks'];
    	  	$ans=$ans."AC";
    	  }	
    	  else
    	  	$ans=$ans."WA";
    }
  }
  $ans=$ans."-->".$marks;
  echo $ans;
?>