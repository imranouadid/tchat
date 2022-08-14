<?php

    class ConversationUser{

        private $db;

        public function __construct(){
            $this->db = new Database();
        }

        public function getConversationsUsers(){

            $this->db->query("SELECT * FROM `conversations_users` WHERE 1");
            $results =  $this->db->resultSet();

            return $results;
        }

        public function addConversationUser($data){

            $this->db->query("INSERT INTO `conversations_users`(`user_id`, `conversation_id`) VALUES (:user_id, :conversation_id)");

            $this->db->bind(':user_id',$data['user_id']);
            $this->db->bind(':conversation_id',$data['conversation_id']);

            if($this->db->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function getConversationUserByUser($userId){
            $this->db->query('SELECT conversations_users.* from conversations_users 
                                 where conversations_users.user_id = :currentUser 
                                 and conversation_id = 
                                 (SELECT conversations_users.conversation_id 
                                 from conversations_users as c where c.user_id = :user_id 
                                 and c.conversation_id = conversations_users.conversation_id LIMIT 1);');

            $this->db->bind(':currentUser', $_SESSION['user_id']);
            $this->db->bind(':user_id', $userId);

            $row = $this->db->single();

            return $this->db->rowCount() > 0 ? $row : false;
        }

        public function getConversationUser($data){
            $this->db->query('select * from conversations_users where user_id = :user_id and conversation_id = :conversation_id ');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':conversation_id', $data['conversation_id']);
            $row = $this->db->single();
            return $row;
        }

        public function lastInsertId(){
            return $this->db->lastInsertId();
        }

    }


?>