<?php require APP_ROOT.'/views/inc/header.php'; ?>


    <div class="row">

        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <?php flash('register_success')?>
                <h2 class="text-center">Login</h2>
                <p class="text-center">Please fill in your credentials to log in</p>
                <form method="POST" action="<?php URL_ROOT.'/users/login'?>">

                    <div class="form-group">
                        <label for="email" >Email: <sub>*</sub></label>
                        <input type="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])? 'is-invalid':'')?>" value="<?php echo $data['email']?>" name="email">
                        <span class="invalid-feedback"><?php echo $data['email_err']?></span>
                    </div>


                    <div class="form-group">
                        <label for="password" >Password: <sub>*</sub></label>
                        <input type="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])? 'is-invalid':'')?>" value="<?php echo $data['password']?>" name="password">
                        <span class="invalid-feedback"><?php echo $data['password_err']?></span>
                    </div>


                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Login" class="btn btn-success btn-block">
                        </div>

                        <div class="col">

                            <a href="<?php echo URL_ROOT.'/users/register'?>" class="btn btn-light btn-block">No account? Register</a>

                        </div>


                    </div>



                </form>
            </div>
        </div>

    

    </div>
  



<?php require APP_ROOT.'/views/inc/footer.php'; ?>