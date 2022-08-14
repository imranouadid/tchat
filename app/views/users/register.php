<?php require APP_ROOT.'/views/inc/header.php'; ?>


    <div class="row">

        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2 class="text-center">Create an account</h2>
                <p class="text-center">Please fill out this form to register with us</p>
                <form method="POST" action="<?php URL_ROOT.'/users/register'?>">

                    <div class="form-group">
                        <label for="name" >Name: <sub>*</sub></label>
                        <input type="text" class="form-control form-control-lg <?php echo (!empty($data['name_err'])? 'is-invalid':'')?>" value="<?php echo $data['name']?>" name="name">
                        <span class="invalid-feedback"><?php echo $data['name_err']?></span>
                    </div>

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

                    <div class="form-group">
                        <label for="confirm_password" >Confirm password: <sub>*</sub></label>
                        <input type="password" class="form-control form-control-lg <?php echo (!empty($data['confirm_password_err'])? 'is-invalid':'')?>" value="<?php echo $data['confirm_password']?>" name="confirm_password">
                        <span class="invalid-feedback"><?php echo $data['confirm_password_err']?></span>
                    </div>

                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Register" class="btn btn-success btn-block">
                        </div>

                        <div class="col">

                            <a href="<?php echo URL_ROOT.'/users/login'?>" class="btn btn-light btn-block">Have an account? Login</a>

                        </div>


                    </div>



                </form>
            </div>
        </div>

    

    </div>
  



<?php require APP_ROOT.'/views/inc/footer.php'; ?>