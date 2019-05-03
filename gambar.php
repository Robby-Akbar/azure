<?php
require_once 'vendor/autoload.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=ibborwebapp;AccountKey=8RSQJ95iazhpyjzYH2arOKzIVKXclB51Y9NrPQ88MctZBmDU6y0IMPtzT4bCeR038ci7XKqh1xO/ebPKPSouwQ==;";
$containerName = "blockblobsdicoding";

$blobClient = BlobRestProxy::createBlobService($connectionString);
if (isset($_POST['upload'])) {
	$file_gambar = strtolower($_FILES["file_gambar"]["name"]);
	$content = fopen($_FILES["file_gambar"]["tmp_name"], "r");

	$blobClient->createBlockBlob($containerName, $file_gambar, $content);
	header("Location: gambar.php");
} else if (isset($_POST['remove'])){
    $name = $_POST['name'];
    $blobClient->deleteBlob($containerName, $name);
    header("Location: gambar.php");
}
$listBlobsOptions = new ListBlobsOptions();
$listBlobsOptions->setPrefix("");
$result = $blobClient->listBlobs($containerName, $listBlobsOptions);
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<title>Upload Gambar ke Azure Blob Storage</title>
</head>
<body>
	<h1>Aplikasi Analisis Gambar dengan Azure Cognitive Services</h1>
	<form action="gambar.php" method="post" enctype="multipart/form-data">
		<input type="file" name="file_gambar" accept="image/*" required="">
		<input type="submit" name="upload" value="Upload">
	</form>
    <br>
    Gambar yang telah diupload :  <?php echo sizeof($result->getBlobs())?>
    <table border="1">
        <thead>
        <tr>
            <th>NO</th>
            <th>Nama File</th>
            <th>URL File</th>
            <th colspan="2">Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        do {
            foreach ($result->getBlobs() as $blob)
            {
                ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $blob->getName() ?></td>
                    <td><?php echo $blob->getUrl() ?></td>
                    <td>
                        <form action="computervision.php" method="post">
                            <input type="hidden" name="url" value="<?php echo $blob->getUrl()?>">
                            <input type="submit" name="submit" value="Analisis">
                        </form>
                    </td>
                    <td>
                        <form action="gambar.php" method="post">
                            <input type="hidden" name="name" value="<?php echo $blob->getName()?>">
                            <input type="submit" name="remove" value="Hapus"">
                        </form>
                    </td>
                </tr>
                <?php
                $i++;
            }
            $listBlobsOptions->setContinuationToken($result->getContinuationToken());
        } while($result->getContinuationToken());
        ?>
        </tbody>
    </table>
</body>
<script type="text/javascript">
    function remove() {
        alert("dihapus");
    }
</script>
</html>
