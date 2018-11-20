<?php
 require_once("db_config.php");

            if(isset($_GET['name'])&&isset($_GET['address']))
            {
                //echo $_GET['name'].$_GET['address'];
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                } 

                $sql = "INSERT INTO student (name,address) VALUES ('".$_GET['name']."','".$_GET['address']."')";

                if ($conn->query($sql) === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

                $conn->close();
            }
            
            if(isset($_GET['uname'])&&isset($_GET['uaddress'])&&isset($_GET['uid']))
            {
                //echo $_GET['name'].$_GET['address'];
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                } 

                $sql = "update student set id=".$_GET['uid'].",name='".$_GET['uname']."',address='".$_GET['uaddress']."' where id=".$_GET['uid'];

                if ($conn->query($sql) === TRUE) {
                    echo "Updated  successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

                $conn->close();
            }
            if(isset($_GET['did']))
            {
                //echo $_GET['name'].$_GET['address'];
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                } 

                $sql = "delete from student where id=".$_GET['did'];

                if ($conn->query($sql) === TRUE) {
                    echo "Deleted  successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

                $conn->close();
            }
            if(isset($_GET['backup']))
            {
                //echo $_GET['name'].$_GET['address'];
                // Create connection
                // $conn = new mysqli($servername, $username, $password, $dbname);
                // // Check connection
                // if ($conn->connect_error) {
                //     die("Connection failed: " . $conn->connect_error);
                // } 
                // $table_name = "student";
                // $backup_file  = "student.sql";
                // //$sql = "LOAD DATA LOCAL INFILE '$backup_file' INTO TABLE $table_name";
                
                // $sql = 'mysqldump '.$dbname.' --password= --user=root --single-transaction >'.$backup_file;
            
                // if ($conn->query($sql) === TRUE) {
                //     echo "Backuped  successfully";
                // } else {
                //     echo "Error: " . $sql . "<br>" . $conn->error;
                // }

                // $conn->close();
                backup_tables($servername, $username, $password, $dbname, 'student');
            }
            function backup_tables($host, $user, $pass, $dbname, $tables = '*') {
                $link = mysqli_connect($host,$user,$pass, $dbname);
            
                // Check connection
                if (mysqli_connect_errno())
                {
                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                    exit;
                }
            
                mysqli_query($link, "SET NAMES 'utf8'");
            
                //get all of the tables
                if($tables == '*')
                {
                    $tables = array();
                    $result = mysqli_query($link, 'SHOW TABLES');
                    while($row = mysqli_fetch_row($result))
                    {
                        $tables[] = $row[0];
                    }
                }
                else
                {
                    $tables = is_array($tables) ? $tables : explode(',',$tables);
                }
            
                $return = '';
                //cycle through
                foreach($tables as $table)
                {
                    $result = mysqli_query($link, 'SELECT * FROM '.$table);
                    $num_fields = mysqli_num_fields($result);
                    $num_rows = mysqli_num_rows($result);
            
                    $return.= 'DROP TABLE IF EXISTS '.$table.';';
                    $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
                    $return.= "\n\n".$row2[1].";\n\n";
                    $counter = 1;
            
                    //Over tables
                    for ($i = 0; $i < $num_fields; $i++) 
                    {   //Over rows
                        while($row = mysqli_fetch_row($result))
                        {   
                            if($counter == 1){
                                $return.= 'INSERT INTO '.$table.' VALUES(';
                            } else{
                                $return.= '(';
                            }
            
                            //Over fields
                            for($j=0; $j<$num_fields; $j++) 
                            {
                                $row[$j] = addslashes($row[$j]);
                                $row[$j] = str_replace("\n","\\n",$row[$j]);
                                if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                                if ($j<($num_fields-1)) { $return.= ','; }
                            }
            
                            if($num_rows == $counter){
                                $return.= ");\n";
                            } else{
                                $return.= "),\n";
                            }
                            ++$counter;
                        }
                    }
                    $return.="\n\n\n";
                }
            
                //save file
                $fileName = 'db-backup.sql';
                $handle = fopen($fileName,'w+');
                fwrite($handle,$return);
                if(fclose($handle)){
                    echo "Done, the file name is: ".$fileName;
                    exit; 
                }
            }
            if(isset($_GET['restore']))
            {
                //echo $_GET['name'].$_GET['address'];
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check connection
                // if ($conn->connect_error) {
                //     die("Connection failed: " . $conn->connect_error);
                // } 

                // $sql = "mysql -h ".$servername." -u ".$username." -p ".$password." ".$dbname." < db-backup.sql";

                // if ($conn->query($sql) === TRUE) {
                //     echo "Deleted  successfully";
                // } else {
                //     echo "Error: " . $sql . "<br>" . $conn->error;
                // }
                    echo restoreMysqlDB("db-backup.sql",$conn);
                $conn->close();
            }

            function restoreMysqlDB($filePath, $conn)
            {
                $sql = '';
                $error = '';
                
                if (file_exists($filePath)) {
                    $lines = file($filePath);
                    
                    foreach ($lines as $line) {
                        
                        // Ignoring comments from the SQL script
                        if (substr($line, 0, 2) == '--' || $line == '') {
                            continue;
                        }
                        
                        $sql .= $line;
                        
                        if (substr(trim($line), - 1, 1) == ';') {
                            $result = mysqli_query($conn, $sql);
                            if (! $result) {
                                $error .= mysqli_error($conn) . "\n";
                            }
                            $sql = '';
                        }
                    } // end foreach
                    
                    if ($error) {
                        $response ="error";
                    } else {
                        $response =  "success";
                    }
                } // end if file exists
                return $response;
            }
        ?>