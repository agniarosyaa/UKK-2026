<!DOCTYPE html>
<html lang="id">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>

<body  class="bg-light">


<div class="container mt-5">
     <div class="row justify-content-center">
      <div class="col-md-5">

                <div class="card shadow text-center">

                    <div class="card-header">
<h2> Sistem Pelanggaran Siswa</h2>
</div>
      <div class="card-body">

<form action="proses_login.php" method="POST">

   <div class="mb-3 text-start">
    <label class="form-label">Email</label><br>
    <input 
    type="email" 
    name="email" 
    class="form-control"
    required>


      <div class="mb-3 text-start">
    <label class="form-label">Password</label><br>
    <input
     type="password" 
     name="password" 
     class="form-control"
     required>
</div>
    <br><br>

    <button type="submit" class="btn btn-outline-info">Login</button>

    </div>
</div>
</body>

</form>

</html>