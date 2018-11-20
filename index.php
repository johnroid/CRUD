<html>
<?php
 require_once("db_config.php");
 ?>
    <head>
        <title>GRUD</title>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
<script src="js/jquery.min.js"></script>
<script src="js/materialize.min.js"></script>
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
   
    </head>
    <body class="container">
       <br>
       <br>
            <ul class="collapsible">
                    <li class="active">
                      <div class="collapsible-header">Create</div>
                      <div class="collapsible-body">
                          
                        <div class="center">
                            <div class="input-field">
                                <input id="name" type="text" required class="validate">
                                <label for="name">Name</label>
                            </div>
                            <div class="input-field">
                                    <textarea id="address" required class="materialize-textarea"></textarea>
                                    <label for="address">Address</label>
                                </div>
                                <button type="button" id="save" onclick="save()" class="waves-effect waves-light btn">Save</button>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="collapsible-header">Read</div>
                      <div class="collapsible-body">
                          <table class="table">
                              <tr><th>ID</th><th>Name</th><th>Address</th></tr>
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            // Check connection
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            } 

                            $sql = "SELECT * From student";

                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) { 
                                        echo "<tr><td>".$row['id']."</td><td>".$row['name']."</td><td>".$row['address']."</td></tr>";
                                    }              
                                } 
                                else
                                echo "No result";     
                            mysqli_close($conn);
                            ?>
                            </table>
                      </div>
                    </li>
                    <li>
                      <div class="collapsible-header">Update</div>
                      <div class="collapsible-body">
                            <div class="center">
                                <div class="input-field">
                                    <input id="upid" type="text" class="validate">
                                    <label for="upid">Enter ID to update</label>
                                </div>
                                <!-- <button type="button" onclick="fetch()" class="waves-effect waves-light btn">GET</button> -->
                                <div class="input-field">
                                    <input id="upname" type="text" class="validate">
                                    <label for="upname">Name</label>
                                </div>
                                <div class="input-field">
                                    <textarea id="upaddress" class="materialize-textarea"></textarea>
                                    <label for="upaddress">Address</label>
                                </div>
                                <button type="button" onclick="update()" class="waves-effect waves-light btn">Update</button>
                            </div>
                      </div>
                    </li>
                    <li>
                        <div class="collapsible-header">Delete</div>
                            <div class="collapsible-body">
                                    <div class="center">
                                        <div class="input-field">
                                            <input id="did" type="text" class="validate">
                                            <label for="did">Enter ID to Delete</label>
                                        </div>
                                            <button type="button" onclick="del()" class="waves-effect waves-light btn">Delete</button>
                                    </div>
                                </div>
                          </li>
                  </ul>
                  <br>
                  <br>
                  <div class="center">
                        <button type="button" onclick="backup()" class="waves-effect waves-light btn">Backup Database</button>
                        <button type="button" onclick="restore()" class="waves-effect waves-light btn">Restore Database</button>
                  </div>
    </body>
    <script>
     document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.collapsible');
    var instances = M.Collapsible.init(elems, options);
  });

  // Or with jQuery

  $(document).ready(function(){
    $('.collapsible').collapsible();
  });

    </script>
    <script>
        function save(){
            //alert(".");
            var name=document.getElementById("name").value;
            var address=document.getElementById("address").value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                // document.getElementById("r").innerHTML =
                alert(this.responseText);
                }
            };
            xhttp.open("GET", "ajax.php?name="+name+"&address="+address, true);
            xhttp.send();
        }
        </script>
        <script>
            function fetch()
            {
                var id=document.getElementById("upid").value;
            //var address=document.getElementById("address").value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                // document.getElementById("r").innerHTML =
                alert(this.responseText);
                }
            };
            xhttp.open("GET", "ajax.php?uid="+id, true);
            xhttp.send();
            }
        </script>
        <script>
            function update(){
               // alert(".");
            var id=document.getElementById("upid").value;
            var name=document.getElementById("upname").value;
            var address=document.getElementById("upaddress").value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                // document.getElementById("r").innerHTML =
                alert(this.responseText);
                }
            };
            xhttp.open("GET", "ajax.php?uid="+id+"&uname="+name+"&uaddress="+address, true);
            xhttp.send();
            }
        </script>
        <script>
            function del(){
                //alert(".");
            var id=document.getElementById("did").value;
            // var name=document.getElementById("upname").value;
            // var address=document.getElementById("upaddress").value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                // document.getElementById("r").innerHTML =
                alert(this.responseText);
                }
            };
            xhttp.open("GET", "ajax.php?did="+id, true);
            xhttp.send();
            }
        </script>
        <script>
            function backup(){
               // alert(".");
            //var id=document.getElementById("did").value;
            // var name=document.getElementById("upname").value;
            // var address=document.getElementById("upaddress").value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                // document.getElementById("r").innerHTML =
                alert(this.responseText);
                }
            };
            xhttp.open("GET", "ajax.php?backup", true);
            xhttp.send();
            }
        </script>
        <script>
            function restore(){
                //alert(".");
            //var id=document.getElementById("did").value;
            // var name=document.getElementById("upname").value;
            // var address=document.getElementById("upaddress").value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                // document.getElementById("r").innerHTML =
                alert(this.responseText);
                }
            };
            xhttp.open("GET", "ajax.php?restore", true);
            xhttp.send();
            }
        </script>
    

</html>