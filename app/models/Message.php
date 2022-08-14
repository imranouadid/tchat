<?php

    class Message{

        private $db;

        public function __construct(){
            $this->db = new Database();
        }

        public function getMessageById($id){
            $this->db->query("SELECT messages.*, users.name as userName, users.is_online as userIsOnline
                                    FROM `messages` 
                                    INNER JOIN users on users.id = messages.author_id
                                    WHERE messages.id = :id");
            $this->db->bind(':id', $id);
            $row = $this->db->single();
            return $row;
        }

        public function getLatestMessages($conversationId, $lastId){
            $this->db->query("SELECT messages.*, users.name as userName, users.is_online as userIsOnline
                                    FROM `messages` 
                                    INNER JOIN users on users.id = messages.author_id
                                    WHERE conversation_id = :conversation_id and messages.id > :id");
            $this->db->bind(':conversation_id', $conversationId);
            $this->db->bind(':id', $lastId);
            $results =  $this->db->resultSet();
            return $results;
        }

        public function getMessagesByConversation($conversationId){
            $this->db->query("SELECT messages.*, users.name as userName, users.is_online as userIsOnline
                                    FROM `messages` 
                                    INNER JOIN users on users.id = messages.author_id
                                    WHERE conversation_id = :conversation_id");
            $this->db->bind(':conversation_id', $conversationId);
            $results =  $this->db->resultSet();
            return $results;
        }

        public function addMessage($data){

            $this->db->query("INSERT INTO `messages`(`content`, `author_id`, `conversation_id`) 
                                  VALUES (:content,:author_id,:conversation_id)");

            $this->db->bind(':content',$data['content']);
            $this->db->bind(':author_id',$data['author_id']);
            $this->db->bind(':conversation_id',$data['conversation_id']);

            if($this->db->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function lastInsertId(){
            return $this->db->lastInsertId();
        }

    }


?>