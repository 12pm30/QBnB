<?php
if (isset($_POST['json_field'])) {
	$arr =  json_decode($_POST['json_field']);
	for ($i = 0; $i < count($arr); $i++){
		//echo $i;
		echo $arr[$i]->caption;
		echo $arr[$i]->URL;
	}
}
?>
<!DOCTYPE HTML>
<html>
<body>
 
 <form name='json' id='json' action='jsontest.php' method='post'>
    <table border='0'>
        <tr>
            <td>JSON</td>
            <td><input type='text' name='json_field' id='json_field'
			/></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='jsonBtn' name='jsonBtn' value='Display Result' /> 
            </td>
        </tr>
    </table>
</form>
 
</body>
</html>