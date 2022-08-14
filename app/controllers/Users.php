<?php

    class Users extends Controller{

        private $userModel = null;

        public function __construct() {
            $this->userModel = new User();
        }

        public function register(){

            // check if it's a POST method
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

                $data = [
                    'name'=>trim($_POST['name']),
                    'email'=>trim($_POST['email']),
                    'password'=>trim($_POST['password']),
                    'confirm_password'=>trim($_POST['confirm_password']),
                    'name_err'=>'',
                    'email_err'=>'',
                    'password_err'=>'',
                    'confirm_password_error'=>''
                ];

                if(empty($data['name'])){
                    $data['name_err'] = 'Please enter name';
                }

                if(empty($data['email'])){
                    $data['email_err'] = 'Please enter email';
                }elseif($this->userModel->findUserByemail($data['email'])){
                    $data['email_err'] = 'Email is already taken';
                }

                if(empty($data['password'])){
                    $data['password_err'] = 'Please enter password';
                }elseif(strlen($data['password']) < 6){
                    $data['password_err'] = 'Password must be at least 6 characters';
                }


                if(empty($data['confirm_password'])){
                    $data['confirm_password_err'] = 'Please enter confirm password';
                }elseif($data['confirm_password'] != $data['password']){
                    $data['confirm_password_err'] = 'Passord do not match';
                }

                if(empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['email_err']) && empty($data['name_err'])){

                    // Hash password
                    $data['password'] = password_hash($data['password'],PASSWORD_DEFAULT);

                    if($this->userModel->register($data)){

                        flash('register_success','You are registred and can log in !');
                        redirect('users/login');

                    }else{
                        die ('Somthing went wrong...try again !');
                    }

                   


                }else{

                    // load view
                    $this->view('users/register',$data);

                }





            }else{

                // load form

                // init data

                $data = [

                    'name'=>'',
                    'email'=>'',
                    'password'=>'',
                    'confirm_password'=>'',
                    'name_err'=>'',
                    'email_err'=>'',
                    'password_err'=>'',
                    'confirm_password_error'=>''

                ];

                // load view

                $this->view('users/register',$data);



            }

             
           
            
        }

        public function login(){

            // check if it's a POST method
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
                $data = [
                    'email'=>trim($_POST['email']),
                    'password'=>trim($_POST['password']),
                    'email_err'=>'',
                    'password_err'=>'',
                ];

                if(empty($data['email'])){
                    $data['email_err'] = 'Please enter email';
                }

                if(empty($data['password'])){
                    $data['password_err'] = 'Please enter password';
                }elseif(strlen($data['password']) < 6){
                    $data['password_err'] = 'Password must be at least 6 characters';
                }

                // Check for user email
                if($this->userModel->findUserByEmail($data['email'])){
                    // here user found
                }else{
                    // here user not found
                    $data['email_err'] = 'No user found!';
                }

                if(empty($data['password_err']) && empty($data['email_err'])){
                    $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                    if($loggedInUser){
                        // create session
                        $this->createUserSession($loggedInUser);
                    }else{
                        $data['password_err'] = 'Password incorrect!';
                        $this->view('users/login',$data);
                    }

                }else{
                    // load view
                    $this->view('users/login', $data);
                }

            }else{
                // load form
                // init data
                $data = [
                    'email'=>'',
                    'password'=>'',
                    'email_err'=>'',
                    'password_err'=>'',
                ];

                 // load view
                $this->view('users/login',$data);

            }

           
            
        }

        public function createUserSession($user){
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_email'] = $user->email;
            $this->userModel->setIsOnline(true);

            redirect('dashboards/index');
        }

        public function logout(){
            $this->userModel->setIsOnline(false);
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_name']);
            session_destroy();
            redirect('users/login');
        }

    }


 
?>