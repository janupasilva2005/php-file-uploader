<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>File uploader</title>
</head>
<body>
	<h1>Upload your file</h1>

	<div>
		<form action="./data.php" method="post" enctype="multipart/form-data">
			<input type="file" name="image">
			<input type="submit" name="submit" value="submit">
		</form>
	</div>
</body>
</html>