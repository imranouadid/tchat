<?php

    class Chat{

        private $db;

        public function __construct(){
            $this->db = new Database();
        }

        public function getChats(){

            $this->db->query("select *, chats.id as chatId, 
                            users.id as userId,
                            users.is_online as isOnline,
                            chats.created_at as chatCreated,
                            users.created_at as userCreated
                            from chats 
                            inner join users
                            on chats.user_id = users.id
                            order by chats.created_at asc"
                            );

            $results =  $this->db->resultSet();

            return $results;
        }

        public function addMessage($data){
            $this->db->query("INSERT INTO `chats`(`user_id`, `message`) VALUES (:user_id,:message)");

            $this->db->bind(':user_id',$data['user_id']);
            $this->db->bind(':message',$data['message']);

            if($this->db->execute()){
                return true;
            }else{
                return false;
            }

        }

        public function getMessageById($id){
            $this->db->query('select chats.*, chats.id as chatId, 
                            users.id as userId,
                            users.is_online as isOnline,
                            chats.created_at as chatCreated,
                            users.created_at as userCreated
                            from chats 
                            inner join users
                            on chats.user_id = users.id
                            where chats.id =:id');
            $this->db->bind(':id',$id);
            $row = $this->db->single();

            return $row;
        }

        public function lastInsertId(){
            return $this->db->lastInsertId();
        }

    }


?>