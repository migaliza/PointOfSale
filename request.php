<?php
$cmd=$_REQUEST['cmd'];
switch($cmd)
{
        //add items to the database
    case 1:

        $DB_HOST="localhost";
        $DB_NAME="csashesi_beatrice-lungahu";
        $DB_USER="csashesi_bl16";
	$DB_PWORD="db!hiJ35";
		
	$link = mysqli_connect($DB_HOST , $DB_USER, $DB_PWORD,$DB_NAME);
	if($link==false){
            echo "not succesfull";
	}
		/*
		if(mysqli_select_db($DB_NAME,$link)){
			echo "echo can not select db";
		}*/
		
		
		//$new_date = date('yy-mm-dd',strtotime($_POST['Date']));
	$barcode=$_REQUEST['barcode'];
	$product = $_REQUEST['product'];
	$price=$_REQUEST['price'];
	$image=$_REQUEST['image'];
		
	$str_query="INSERT INTO MwebPoS (barcode,productName,price,image) VALUES('$barcode','$product','$price','$image')";

	if(mysqli_query($link,$str_query)){
            echo '{"result":1,"message": "SUCCESFULLY ADDED"}';
	}else
	{
            echo '{"result":0,"message": "unsuccessful"}';
	}
		
        break;
            
        //this displays the content added to the database
    case 2:
        $DB_HOST="localhost";
		$DB_NAME="csashesi_beatrice-lungahu";
		$DB_USER="csashesi_bl16";
		$DB_PWORD="db!hiJ35";
		
		$link = mysqli_connect($DB_HOST , $DB_USER, $DB_PWORD,$DB_NAME);
		if($link==false){
			echo "not succesfull";
		}
		
                
		$str_query="SELECT Product_id, productName,barcode,price FROM  MwebPoS";
		$result=mysqli_query($link,$str_query);
        $row=  mysqli_fetch_assoc($result);
        echo '{"result":1,"values":[';	//start of json object
	   while($row){
		  echo json_encode($row);			//convert the result array to json object
		  $row=$result->fetch_assoc();
		  if($row){
			 echo ",";					//if there are more rows, add comma 
		  }
	   }
	   echo "]}";
    break;
       
    //this display the price and product name of a particular product gievn the id
    case 3:
        $DB_HOST="localhost";
		$DB_NAME="csashesi_beatrice-lungahu";
		$DB_USER="csashesi_bl16";
		$DB_PWORD="db!hiJ35";
		
		$link = mysqli_connect($DB_HOST , $DB_USER, $DB_PWORD,$DB_NAME);
		if($link==false){
			echo "not succesfull";
		}
		$productID=$_REQUEST['Product_id'];
                $str_query="SELECT productName,price FROM MwebPoS WHERE Product_id='$productID'";
                
		//$str_query="SELECT * FROM  MwebPointOfSale";
		$result=mysqli_query($link,$str_query);
        $row=mysqli_fetch_assoc($result);
        echo '{"result":1,"data":[';	//start of json object
	   while($row){
		  echo json_encode($row);			//convert the result array to json object
		  $row=$result->fetch_assoc();
		  if($row){
			 echo ",";					//if there are more rows, add comma 
		  }
	   }
	   echo "]}";
           
           
    break;
    case 4:
        $DB_HOST="localhost";
	$DB_NAME="csashesi_beatrice-lungahu";
	$DB_USER="csashesi_bl16";
	$DB_PWORD="db!hiJ35";
		
	$link = mysqli_connect($DB_HOST , $DB_USER, $DB_PWORD,$DB_NAME);
	if($link==false){
		echo "not succesfull";
	}
	$phoneNo=$_REQUEST['phoneNo'];
        $totalAmountSpend=$_REQUEST['gross'];
        $str_query="INSERT INTO MwebPoSsell(phoneNo,price) VALUES($phoneNo,$totalAmountSpend)";
  
	//$result=mysqli_query($link,$str_query);

        if(mysqli_query($link,$str_query)){
            echo '{"result":1,"message": "SUCCESFULLY ADDED"}';
	}else
	{
            echo '{"result":0,"message": "unsuccessful"}';
	}
         $amountspend=(double)$totalAmountSpend;
        if($amountspend>=500){
            
            ob_start();
            echo "discounting".$amountspend;
            $url = "https://api.smsgh.com/v3/messages/send?"
            . "From=BMI%20GENERAL%20TRADERS"
            . "&To=%2B$phoneNo"
            . "&Content=You%20receive%20a%20ten%20percent%20discount%20on%20your%20next%20purchase"
            . "&ClientId=odfbifrp"
            . "&ClientSecret=rktegnml"
            . "&RegisteredDelivery=true";
            // Fire the request and wait for the response
            //$myUrl=   urlencode().($url);
              file_get_contents($url,null,null);
             //var_dump($response);
             ob_end_clean();
              
              
        }
        break;
}
?>