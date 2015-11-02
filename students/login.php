<div id="page-wrapper">

            <div class="container-fluid">
                <div align = "center">


    <img src="../img/student.gif" alt="student" />
    <?php
    if(isset($_GET['err'])) {?>
        <div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            Login error
        </div>
    <?php } ?>
    <form name = "loginstudent" method = "post" action = "login-check.php" class="form-signin">
        <h2 class="form-signin-heading">Please sign in</h2>

        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <br/>
        <button class="btn btn-primary btn-sm" type="submit">Sign in</button>
        <a href="register.php" title="Register a student">Signup Student</a>
    </form>

</div>