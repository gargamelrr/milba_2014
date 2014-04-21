<html>
    <head>
        <title>GCM Demo application</title>
    </head>
    <body>
        <?php
        if (isset($_POST['submit'])) {

            $url = 'https://android.googleapis.com/gcm/send';
            //$registatoin_ids = array();
            $registatoin_ids[] = "APA91bEtbXEtsR2HPH936bn1mqCvRp6Jr6TQLOeCE8NivcVCsqECJcEuwI_CD8H_PcN8uC6toyXiifStvC-aoRYgk_zaQIg6TZsVW5A6Ot1VOO7WXIkgZMt0r5vS7LpPP3CnmsUp51Sy5lXZTPj2tAxf1IK7rkT4gQ";
            $message = array("Notice" => $_POST['message']);
            $fields = array(
                'registration_ids' => $registatoin_ids,
                'data' => $message,
            );
            $headers = array(
                'Authorization: key=AIzaSyBiqNmTo_OO1aJJJReNo7vLV_WldDzR1T4',
                'Content-Type: application/json'
            );
            // Open connection
            $ch = curl_init();

            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }

            // Close connection
            curl_close($ch);
            echo $result;
        }
        ?>
        <form method="post" action="gcmServer.php">
            <label>Insert Message: </label><input type="text" name="message" />
            <input type="submit" name="submit" value="Send" />
        </form>
    </body>
</html>