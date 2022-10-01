<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Skyrim Alchemy</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

</head>
<body >

<main>

    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <div class="container">
            <div class="row">

                <a href="/" class="d-flex col-3 align-items-center col nav-link link-dark">
                    <img src="/SkillAlchemy.webp" height="50" width="50" alt="logo">
                    <span>Home</span>
                </a>

                <ul class="nav col-6 justify-content-center">
                    <li><a href="/effects" class="nav-link px-2 link-dark">Effects</a></li>
                    <li><a href="/ingredients" class="nav-link px-2 link-dark">Ingredients</a></li>
                    <li><a href="/export" class="nav-link px-2 link-dark">Export</a></li>
                </ul>

                <div class="col-3 justify-content-center ">
                    <form class="" role="search" METHOD="GET" action="/search">
                        <input type="search" name="search" class="form-control" placeholder="Search..." aria-label="Search">
                    </form>
                </div>
            </div>

        </div>
    </header>


    <div class="container justify-center">

        {{ $slot }}

    </div>
</main>
</body>
</html>
