<?php

class mysqlhandler
{

    var $query;

    var $response;

    var $dbc;

    function mysqlhandler()
    {
        require_once ('dbConnect.php');
        $this->dbc = $dbc;
        $this->query = "SELECT s_no,serial_no,product_name, brand, cost_price, selling_price, quantity, date_in, date_out, standing_quantity FROM product_info";
        $this->response = mysqli_query($this->dbc, $this->query);
        // var_dump($this->response);
    }

    // Close connection to the database
    function __destruct()
    {
        mysqli_close($this->dbc);
    }
    function insert(array $db_attributes)
    {
//         $insert_string = "INSERT INTO product_info (serial_no,product_name, brand, cost_price, selling_price, quantity, date_in, date_out, standing_quantity )VALUES (?,?,?,?,?,?,?,?,?)";
            $insert_string = "INSERT INTO ".$db_attributes['table_name']." (";
            foreach($db_attributes as $key=>$key_value){
                if($key==="table_name"){continue;}
                else{
                $insert_string = $insert_string.$key." , ";
                }
            }   
            $insert_string = substr_replace($insert_string,")",strripos($insert_string,","));
            $insert_string .= " VALUES (";
            foreach($db_attributes as $key=>$key_value){
                if($key==="table_name"){continue;}
                else{
                    if(is_string($key_value)){
                        $insert_string = $insert_string. "'" .$key_value. "'" ." , ";
                    }
                    else{
                        $insert_string = $insert_string.$key_value." , ";
                    }
                // var_dump($key_value);
                // echo "<br>";
                }
            }
            $insert_string = substr_replace($insert_string,")",strripos($insert_string,","));
            echo $insert_string;    
            
            
        $stmt = mysqli_prepare($this->dbc, $insert_string);       
        mysqli_stmt_execute($stmt);
        
        $this->checkResponse($stmt);
    }
      
    function checkResponse($stmt){
        //Code for error handling in sql side
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        
        if($affected_rows == 1){
            
            // echo 'Student Entered';
            
            mysqli_stmt_close($stmt);
            
            // mysqli_close($this->dbc);
            
        } else {
            
            echo 'Error Occurred<br />';
            echo mysqli_error($this->dbc);
            
            mysqli_stmt_close($stmt);
            
            // mysqli_close($this->dbc);
            
        }
        
    }
    function read(){

        if ($this->response) {
            
            echo '<table align="left"
    cellspacing="5" cellpadding="8">
            <tr><td align="left"><b>s_no.</b></td>
    <td align="left"><b>serial_no</b></td>
    <td align="left"><b>product_name</b></td>
    <td align="left"><b>brand</b></td>
    <td align="left"><b>cost_price</b></td>
    <td align="left"><b>selling_price</b></td>
    <td align="left"><b>quantity</b></td>
    <td align="left"><b>date_in</b></td>
    <td align="left"><b>date_out</b></td>
    <td align="left"><b>standing_quantity</b></td></tr>';
            
            // mysqli_fetch_array will return a row of data from the query
            // until no further data is available
            while ($row = mysqli_fetch_array($this->response)) {
                // print_r(row);
                echo '<tr><td align="left">' .$row['s_no'] .'</td><td align="left">' . $row['serial_no'] . '</td><td align="left">' . $row['product_name'] . '</td>><td align="left">' . $row['brand'] . '</td>><td align="left">' . $row['cost_price'] . '</td>><td align="left">' . $row['selling_price'] . '</td>><td align="left">' . $row['quantity'] . '</td>><td align="left">' . $row['date_in'] . '</td>><td align="left">' . $row['date_out'] . '</td>><td align="left">' . $row['standing_quantity'] . '</td>';
                
                echo '</tr>';
            }
            
            echo '</table>';
        } else {
            
            echo "Couldn't issue database query<br />";
            
            echo mysqli_error($this->dbc);
        }
    }

    function update(array $db_attributes)
    {
        $update_string="UPDATE ".$db_attributes['table_name']." "." SET " ;
//         require_once('dbConnect.php');
        foreach($db_attributes as $key=>$key_value){
            if($key==="table_name"){ continue;}
            if($key==="WHERE"){ continue;}
            if($key===$db_attributes["WHERE"]){ continue;}
            else{

                $update_string=$update_string.$key." = '".$key_value."', ";
            }

        }
        $update_string = substr_replace($update_string,"",strripos($update_string,","));
        $update_string .= " WHERE ";
        $update_string .= $db_attributes["WHERE"]." = ".$db_attributes[$db_attributes["WHERE"]];
        // $update_string = "UPDATE product_info SET standing_quantity='2',selling_price='1600', date_out='2017/11/21' WHERE s_no=5";
        var_dump($update_string);
        
        if (!$this->dbc) {
            echo ("Connection failed: " . mysqli_connect_error());
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $stmt = mysqli_prepare($this->dbc, $update_string);       
        mysqli_stmt_execute($stmt);
        
        $this->checkResponse($stmt);
    }

    function delete()
    {}

    function create()
    {}
}

$odbHandler = new mysqlhandler();
// var_dump(odbHandler);

$date = "2017/03/20";
$date = explode("/", $date);

$time = "07:16:17";
$time = explode(":", $time); 
$tz_string = "America/Los_Angeles";
$tz_object = new DateTimeZone($tz_string);

$datetime = new DateTime();
$datetime->setTimezone($tz_object);
$datetime->setDate($date[0], $date[1], $date[2]);
$datetime->setTime($time[0], $time[1], $time[2]);

$db_attributes = [
    'table_name'=>'product_info',
    'serial_no' => 'CB345J',
    'product_name' => 'J314 Cam',
    'brand' => 'SONY',
    'cost_price' => 1300,
    'selling_price' => 1500,
    'quantity' => 20,
    'date_in' => $datetime->format('Y/m/d H:i:s'),
    'date_out' => $datetime->format('Y/m/d H:i:s'),
    'standing_quantity' => 3];
$db_update_attr = [
    'table_name'=>"product_info",
    'WHERE'=>'s_no',
    's_no'=>40,
    'serial_no'=>'CVBGF',
    'cost_price'=>1400,
    'standing_quantity'=>21
];

// var_dump($db_attributes);
// $odbHandler->insert($db_attributes);
$odbHandler->update($db_update_attr);

// foreach ($db_attributes as $a=>$a_value){
//     echo "Key= ".$a." Value= ".$a_value."<br>";

    
// }
$odbHandler->read();

?>