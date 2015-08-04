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

        <form action="{{ $request_uri }}" class="form-horizontal" method="post" enctype="multipart/form-data">
            <div class="well">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="exampleInputFile">Fichier excel</label>
                    <div class="col-sm-6">
                        <input type="file" name="file">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="exampleInputFile">Spécialisations</label>
                    <div class="col-sm-6">
                        <select name="specialisation" class="form-control">
                            <option value="">Choisir</option>
                            @if(!empty($specialisations))
                                @foreach($specialisations as $id_specialisation => $specialisation)
                                    <option value="{{ $id_specialisation }}">{{ $specialisation }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="exampleInputFile">Membres</label>
                    <div class="col-sm-6">
                        <select name="membre" class="form-control">
                            <option value="">Choisir</option>
                            @if(!empty($membres))
                                @foreach($membres as $id_membre => $membre)
                                    <option value="{{ $id_membre }}">{{ $membre }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-info">Submit</button>
        </form>

        <?php

/*      echo '<pre>';
        print_r($inserted);
        echo '</pre>';
;*/
        echo '<pre>';
        print_r($results);
        echo '</pre>';

        echo 'adresse';
        echo '<pre>';
        print_r($adresses);
        echo '</pre>';
        echo 'inserted';
        echo '<pre>';
        print_r($inserted);
        echo '</pre>';

/*      foreach($adresses['has'] as $has){
            echo $has['uid'].'<br/>';
        }*/


        ?>

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