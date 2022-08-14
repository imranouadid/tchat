<?php

    class User{

        private $db;

        public function __construct(){
            $this->db = new Database();
        }

        // login function
        public function login($email,$password){

            $this->db->query("select * from users where email = :email");
            $this->db->bind(':email',$email);

            $row = $this->db->single();

            $hashed_password = $row->password;

            if(password_verify($password,$hashed_password)){
                return $row;

            }else{
                return false;
            }
            
        }

        public function findUserByEmail($email){

            $this->db->query('select * from users where email = :email');
            $this->db->bind(':email',$email);
            $row = $this->db->single();

            if($this->db->rowCount() > 0 ){
                return true;
            }else{
                return false;
            }

        }

        public function register($data){

            $this->db->query("INSERT INTO `users`(`name`, `email`, `password`, `is_online`) VALUES (:name,:email,:password, :is_online)");
           
            $this->db->bind(':name',$data['name']);
            $this->db->bind(':email',$data['email']);
            $this->db->bind(':password',$data['password']);
            $this->db->bind(':is_online', false);

            if($this->db->execute()){
                return true;
            }else{
                return false;
            }


        }

        public function getUserById($id){
            $this->db->query('select * from users where id = :id');
            $this->db->bind(':id',$id);
            $row = $this->db->single();
            
            return $row;
        }

        public function getOnlineUsers(){
            $this->db->query("SELECT * FROM `users` WHERE users.is_online = 1 AND users.id != " . $_SESSION['user_id']);
            $results =  $this->db->resultSet();
            return $results;
        }

        public function getAllUsers(){
            $this->db->query("SELECT * FROM `users` WHERE users.id != " . $_SESSION['user_id']);
            $results =  $this->db->resultSet();
            return $results;
        }

        public function setIsOnline(bool $isOnline){

            $this->db->query("update users set `is_online`=:is_online where id = :id");

            $this->db->bind(':is_online', $isOnline);
            $this->db->bind(':id', $_SESSION['user_id']);

            if($this->db->execute()){
                return true;
            }else{
                return false;
            }

        }





    }


?>