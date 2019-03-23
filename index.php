<html>
 <head>
 <Title>Curhat Online</Title>
 <style type="text/css">
 	body { background-color: #fff; border-top: solid 10px #000;
 	    color: #333; font-size: .85em; margin: 20; padding: 20;
 	    font-family: "Segoe UI", Verdana, Helvetica, Sans-Serif;
 	}
 	h1, h2, h3,{ color: #000; margin-bottom: 0; padding-bottom: 0; }
 	h1 { font-size: 2em; }
 	h2 { font-size: 1.75em; }
 	h3 { font-size: 1.2em; }
 	table { margin-top: 0.75em; }
 	th { font-size: 1.2em; text-align: left; border: none; padding-left: 0; }
 	td { padding: 0.25em 2em 0.25em 0em; border: 0 none; }
 </style>
 </head>
 <body>
 <h1>Silahkan Curhat Apapun!</h1>
 <p>Isi nama dan ketik apapun pada kolom curhat, lalu klik <strong>Simpan</strong> untuk mengirim curhatmu.</p>
 <form method="post" action="index.php" enctype="multipart/form-data" >
       Nama  <input type="text" name="nama" id="nama"/></br></br>
       Curhat <textarea type="text" name="curhat" id="curhat" rows="10" cols="30"></textarea></br></br>
       <input type="submit" name="submit" value="Simpan" />
       <input type="submit" name="load_curhat" value="Lihat Curhatan" />
 </form>
 <?php
    $host = "ibborwebappserver.database.windows.net";
    $user = "ibbor";
    $pass = "@Gaktaugwlupa0";
    $db = "ibborwebapp";
    try {
        $conn = new PDO("sqlsrv:server = $host; Database = $db", $user, $pass);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    } catch(Exception $e) {
        echo "Failed: " . $e;
    }
    if (isset($_POST['submit'])) {
        try {
            $nama = $_POST['nama'];
            $curhat = $_POST['curhat'];
            // Insert data
            $sql_insert = "INSERT INTO curhat (nama, textCurhat)
                        VALUES (?,?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bindValue(1, $nama);
            $stmt->bindValue(2, $curhat);
            $stmt->execute();
        } catch(Exception $e) {
            echo "Failed: " . $e;
        }
        echo "<h3>Curhatanmu telah disimpan!</h3>";
    } else if (isset($_POST['load_curhat'])) {
        try {
            $sql_select = "SELECT * FROM curhat";
            $stmt = $conn->query($sql_select);
            $curhats = $stmt->fetchAll();
            if(count($curhats) > 0) {
                echo "<h2>Orang-orang yang telah curhat:</h2>";
                echo "<table>";
                echo "<tr><th>Nama</th>";
                echo "<th>Isi Curhat</th></tr>";
                foreach($curhats as $curhats) {
                    echo "<tr><td>".$curhats['nama']."</td>";
                    echo "<td>".$curhats['textCurhat']."</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<h3>Belum ada yang pernah curhat.</h3>";
            }
        } catch(Exception $e) {
            echo "Failed: " . $e;
        }
    }
 ?>
 </body>
 </html>
