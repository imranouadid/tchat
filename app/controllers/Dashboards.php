<?php

class Dashboards extends Controller{

    private $chatModel = null;
    private $userModel = null;
    private $conversationUserModel = null;
    private $conversationModel = null;
    private $messageModel = null;

    public function __construct(){
        if(!isLoggedIn()){
            redirect('users/login');
        }else{
            $this->chatModel = new Chat();
            $this->userModel = new User();
            $this->conversationModel = new Conversation();
            $this->conversationUserModel = new ConversationUser();
            $this->messageModel = new Message();
        }
    }

    public function index(){
        $data  = [
            'chats' => $this->chatModel->getChats(),
            'onlineMembers' => $this->userModel->getOnlineUsers(),
            'allMembers' => $this->userModel->getAllUsers(),
            'message_err' => '',
        ];
        $this->view('dashboards/index', $data);
    }

    public function sendMessage(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $data = [
                'message' => html_entity_decode(dataready($_POST['message'])),
                'message_err' => '',
            ];

            if(empty($data['message']) ){
                $data['message_err'] = 'Cannot send empty message';
            }

            if(empty($data['message_err'])){

                $this->chatModel->addMessage([
                    'user_id' => $_SESSION['user_id'],
                    'message' => $data['message']
                ]);

                $message =  $this->chatModel->getMessageById($this->chatModel->lastInsertId());

                echo json_encode($message);

            }

        }
    }

    public function sendMessageConversation(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $data = [
                'author_id' => $_SESSION['user_id'],

                'content' => html_entity_decode(dataready($_POST['content'])),
                'content_err' => '',

                'conversation_id' => html_entity_decode(dataready($_POST['conversation_id'])),
                'conversation_err' => ''

            ];

            if(empty($data['content']) ){
                $data['content_err'] = 'Cannot send empty message';
            }

            if(empty($data['conversation_id']) ){
                $data['conversation_err'] = 'Cannot send message without conversation id';
            }

            if(empty($data['content_err']) && empty($data['conversation_err'])){

                $this->messageModel->addMessage($data);

                $message =  $this->messageModel->getMessageById($this->messageModel->lastInsertId());

                echo json_encode($message);

            }

        }
    }

    public function loadConversation(){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $data = [
                'user_id' => isset($_POST['user_id']) ?
                            trim(filter_input(INPUT_POST, "user_id", FILTER_SANITIZE_NUMBER_INT)) : '',
                'user_id_err' => '',
            ];

            if( empty($data['user_id']) ){
                $data['user_id_err'] = 'empty';
            }elseif(!$this->userModel->getUserById($data['user_id'])){
                $data['user_id_err'] = 'empty';
            }

            if(empty($data['user_id_err'])){
                $conversation = $this->conversationUserModel->getConversationUserByUser($data['user_id']);
                $conversationId = null;
                if(!$conversation){
                    $this->conversationModel->addConversation();
                    $conversationId = $this->conversationModel->getConversationById($this->conversationModel->lastInsertId())->id;
                    $this->conversationUserModel->addConversationUser([
                        'user_id' => $data['user_id'],
                        'conversation_id' => $conversationId
                    ]);
                    $this->conversationUserModel->addConversationUser([
                        'user_id' => $_SESSION['user_id'],
                        'conversation_id' => $conversationId
                    ]);
                }else{
                    $conversationId = $conversation->conversation_id;
                }
                $messages = $this->messageModel->getMessagesByConversation($conversationId);

                echo json_encode(['conversation_id' => $conversationId, 'messages' => $messages]);
            }

        }
    }

    public function loadNewMessagesConversation(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $data = [
                'last_msg_id' => html_entity_decode(dataready($_POST['last_msg_id'])),
                'last_msg_id_err' => '',

                'conversation_id' => html_entity_decode(dataready($_POST['conversation_id'])),
                'conversation_id_err' => ''
            ];

            if(empty($data['last_msg_id']) ){
                $data['last_msg_id_err'] = 'Something went wrong';
            }

            if(empty($data['conversation_id']) ){
                $data['conversation_id_err'] = 'Cannot send message without conversation id';
            }

            if(empty($data['conversation_id_err']) && empty($data['last_msg_id_err'])){
                $messages = $this->messageModel->getLatestMessages($data['conversation_id'], $data['last_msg_id']);
                echo json_encode($messages);
            }

        }
    }

}

?>