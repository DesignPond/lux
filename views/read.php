<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Import</title>
    <meta name="description" content="Import">
    <meta name="author" content="@DesignPond">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

</head>
<body>

<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Admin import</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">

    <div class="col-md-12">
        <h1>Import</h1>

        <form action="{{ $upload_uri }}" class="form-inline" method="post" enctype="multipart/form-data">
            <div class="well">
                <div class="form-group">
                    <label>Fichier excel</label>
                    <input type="file" name="file">
                </div>
            </div>
            <button type="submit" class="btn btn-info">Submit</button>
        </form>

    </div>

</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</body>
</html>