<?php
if (isset($_POST['submit'])) {
    $url = $_POST['url'];
} else {
    header("Location: gambar.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Analisis Gambar</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>
<h1>Hasil Analisis Gambar</h1>
<script type="text/javascript">
    $(document).ready(function () {
        // **********************************************
        // *** Update or verify the following values. ***
        // **********************************************
        // Replace <Subscription Key> with your valid subscription key.
        let subscriptionKey = "2508812d996843d5bff40fd8c9bc54ca";
        // You must use the same Azure region in your REST API method as you used to
        // get your subscription keys. For example, if you got your subscription keys
        // from the West US region, replace "westcentralus" in the URL
        // below with "westus".
        //
        // Free trial subscription keys are generated in the "westus" region.
        // If you use a free trial subscription key, you shouldn't need to change
        // this region.
        let uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
        // Request parameters.
        let params = {
            "visualFeatures": "Categories,Description,Color",
            "details": "",
            "language": "en",
        };
        // Display the image.
        let sourceImageUrl = "<?php echo $url ?>";
        document.querySelector("#sourceImage").src = sourceImageUrl;
        // Make the REST API call.
        $.ajax({
            url: uriBase + "?" + $.param(params),
            // Request headers.
            beforeSend: function(xhrObj){
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key", subscriptionKey);
            },
            type: "POST",
            // Request body.
            data: '{"url": ' + '"' + sourceImageUrl + '"}',
        })
            .done(function(data) {
                // Show formatted JSON on webpage.
                $("#responseTextArea").val(JSON.stringify(data, null, 2));
                $("#description").text(data.description.captions[0].text);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                // Display error message.
                let errorString = (errorThrown === "") ? "Error. " :
                    errorThrown + " (" + jqXHR.status + "): ";
                errorString += (jqXHR.responseText === "") ? "" :
                    jQuery.parseJSON(jqXHR.responseText).message;
                alert(errorString);
            });
    });
</script>

<br>
<div id="wrapper" style="width:1020px; display:table;">
    <div id="jsonOutput" style="width:600px; display:table-cell;">
        <b>Response:</b>
        <br><br>
        <textarea id="responseTextArea" class="UIInput"
                  style="width:580px; height:400px;" readonly=""></textarea>
    </div>
    <div id="imageDiv" style="width:420px; display:table-cell;">
        <b>Source Image:</b>
        <br><br>
        <img id="sourceImage" width="400" />
        <br>
        <b id="description">Memuat deskripsi gambar. . .</b>
    </div>
</div>
</body>
</html>
