<?php

    class Conversation{

        private $db;

        public function __construct(){
            $this->db = new Database();
        }

        public function getConversations(){

            $this->db->query("SELECT * FROM `conversations` WHERE 1 order by conversations.created_at desc");
            $results =  $this->db->resultSet();

            return $results;
        }

        public function addConversation(){
            $this->db->query("INSERT INTO `conversations`() VALUES ()");
            if($this->db->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function getConversationById($id){
            $this->db->query('select * from conversations where id = :id');
            $this->db->bind(':id',$id);
            $row = $this->db->single();

            return $row;
        }

        public function lastInsertId(){
            return $this->db->lastInsertId();
        }

    }


?>