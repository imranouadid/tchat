<?php require APP_ROOT.'/views/inc/header.php'; ?>
<?php $chatLastMsgId = null; ?>

<h1>Dashboard</h1>
<hr>

<div class="row">
    <div class="col">
        <div class="card text-white bg-secondary mb-3" >
            <div class="card-header">
                <h3>Chat box (public)</h3>
            </div>
            <div class="card-body inbox_chat">
                <!-- List messages -->
                <div class="chat-public">
                    <?php foreach($data['chats'] as $chat):?>
                        <?php $chatLastMsgId = $chat->chatId ?>
                        <p class="card-text" chat_msg_id="<?php echo $chat->chatId ?>">
                    <span class="badge rounded-pill bg-primary">
                        <?php echo $chat->userId == $_SESSION['user_id'] ? "Me" : $chat->name ?>
                    </span>
                            <?php if($chat->isOnline && $chat->userId != $_SESSION['user_id']) :?>
                                <span class="badge rounded-pill bg-success">Online</span>
                            <?php endif; ?>
                            <br>
                            <?php echo $chat->message ?> <br>
                            <?php echo $chat->created_at ?>
                        </p>
                        <hr>
                    <?php endforeach; ?>
                </div>
                <!--Add message to chat-->
                <form method="POST" action="#" id="send_msg_form">
                    <div class="form-group">
                        <textarea class="form-control form-control-lg
                        <?php echo (!empty($data['message_err'])? 'is-invalid':'')?>"
                          name="message" id="message"></textarea>
                        <input id="chat_last_msg_id" name="chat_last_msg_id" type="hidden" value="<?php echo $chatLastMsgId ?>">
                        <span class="invalid-feedback"><?php echo $data['message_err']?></span>
                    </div>
                    <input type="submit" value="Send" class="btn btn-success">
                </form>
            </div>
        </div>
    </div>

    <!--  Online members  -->
    <div class="col col-lg-3">
        <div class="card text-white bg-secondary mb-3" >
            <div class="card-header">
                <h3>Online members</h3>
            </div>
            <div class="card-body inbox_chat">
                <?php foreach($data['onlineMembers'] as $member):?>
                    <p class="card-text">
                        <?php echo $member->name ?> <span class="badge rounded-pill bg-success">Online</span>
                    </p>
                    <hr>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!--  All members  -->
    <div class="col col-lg-3">
        <div class="card text-white bg-secondary mb-3" >
            <div class="card-header">
                <h3>Private chat</h3>
            </div>
            <div class="card-body inbox_chat">
                <?php foreach($data['allMembers'] as $member):?>
                    <p class="card-text">
                        <a href="#" data-toggle="modal" data-target="#addMessageModal" class="text-white mdl-btn"
                           user_id="<?php echo $member->id ?>" conversation_id="" id="user_<?php echo $member->id ?>">
                            <?php echo $member->name ?>
                        </a>
                    </p>
                    <hr>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>

<!-- MODALS -->

<!-- ADD MESSAGE MODAL -->
<div class="modal fade" id="addMessageModal">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-envelope"></i> Conversation</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body inbox_chat conversation-messages" id="conversation-msgs">
                    <!-- List messages -->
                </div>

                <div class="modal-footer">
                    <div class="card-body">
                        <form method="POST" action="#" id="send_msg_conversation_form">
                            <div class="form-group">
                                <input id="conversation_id" name="conversation_id" type="hidden" value="">
                                <input id="user_id" name="user_id" type="hidden" value="">
                                <input id="last_msg_id" name="last_msg_id" type="hidden" value="">
                                <textarea class="form-control form-control-lg
                                    <?php echo (!empty($data['message_err'])? 'is-invalid':'')?>"
                                          name="content" id="content"></textarea>
                                <span class="invalid-feedback"><?php echo $data['message_err']?></span>
                            </div>
                            <input type="submit" class="btn btn-secondary btn-sm" value="Send" name="add_msg_conversation">
                        </form>
                    </div>
                </div>

        </div>
    </div>
</div>



<?php require APP_ROOT.'/views/inc/footer.php'; ?>


<script>

    let baseURL = "<?php echo URL_ROOT ?>";
    let currentUserID = "<?php echo $_SESSION['user_id'] ?>";

    $(document).ready(function (){

        async function loadNewMessageConversation(){
            let conversationId = $('#conversation_id').val();
            let lastMsgId = $('#last_msg_id').val();
            if(conversationId != "" && lastMsgId != ""){
                $.ajax({
                    url:baseURL+'/dashboards/loadNewMessagesConversation',
                    method:"POST",
                    data:{
                        'conversation_id':conversationId,
                        'last_msg_id':lastMsgId,
                    },
                    dataType:"json",
                    success:function(data){
                        if(data){
                            data.map(msg => {
                                $('#conversation_id').val(msg.conversation_id);
                                $('#last_msg_id').val(msg.id);
                                message = `<p class="card-text">
                                        <span class="badge rounded-pill bg-primary">
                                            ${msg.author_id == currentUserID ? "MOI" : msg.userName}
                                        </span>

                                        <br>
                                        ${msg.content} <br>
                                        ${msg.created_at}
                                        </p>
                                        <hr>`;
                                $('.conversation-messages').append(message);
                            })
                        }
                    }
                });
            }

        };

        $('.mdl-btn').click(function() {

            let userId = $(this).attr('user_id');

            $.ajax({
                url:baseURL+'/dashboards/loadConversation',
                method:"POST",
                data:{
                    'user_id':userId,
                },
                dataType:"json",
                success:function(data){
                    console.log(data['conversation_id']);
                    if(data){
                        $('.conversation-messages').empty();
                        $('#user_' + userId).attr('conversation_id', data['conversation_id'])
                        $('#user_id').val(userId);
                        $('#conversation_id').val(data['conversation_id']);
                        data['messages'].map(msg => {
                            // $('#conversation_id').val(data['conversation_id']);
                            $('#last_msg_id').val(msg.id);
                            message = `<p class="card-text">
                                        <span class="badge rounded-pill bg-primary">
                                            ${msg.author_id == currentUserID ? "MOI" : msg.userName}
                                        </span>

                                        <br>
                                        ${msg.content} <br>
                                        ${msg.created_at}
                                        </p>
                                        <hr>`;
                            $('.conversation-messages').append(message);
                        })
                    }
                }
            });

        });

        $('#send_msg_conversation_form').on("submit", function(event){

            event.preventDefault();

            let content = $('#content').val();
            let contentErr = '';

            let conversationId = $('#conversation_id').val();
            let conversationIdErr = '';



            let url = baseURL+'/dashboards/sendMessageConversation';

            if(content == ""){
                contentErr = "Cannot send empty message!";
                alert(contentErr);
            }

            if(conversationId == ""){
                conversationIdErr = "Something went wrong !";
                alert(conversationIdErr);
            }

            if(contentErr == "" && conversationIdErr == ""){

                $.ajax({
                    url:url,
                    method:"POST",
                    data:{
                        'content': content,
                        'conversation_id': conversationId,
                    },
                    dataType: 'json',
                    beforeSend:function(){
                        $('#insert').val("Message is sending...");
                    },
                    success:function(data){
                        $('#last_msg_id').val(data.id);
                        message = `<p class="card-text">
                                        <span class="badge rounded-pill bg-primary">
                                            MOI
                                        </span>

                                        <br>
                                        ${data.content} <br>
                                        ${data.created_at}
                                        </p>
                                        <hr>`;
                        $('.conversation-messages').append(message);
                        $('#send_msg_conversation_form')[0].reset();
                    }
                });
            }
        });

        $('#send_msg_form').on("submit", function(event){

            event.preventDefault();

            let message = $('#message').val();
            let messageErr = '';

            let chatLastMsgId = $('#chat_last_msg_id').val();
            let chatLastMsgIdErr = '';

            var url = baseURL+'/dashboards/sendMessage';

            if(message == ""){
                messageErr = "Cannot send empty message!";
                alert(messageErr);
            }

            if(messageErr == ""){

                $.ajax({
                    url:url,
                    method:"POST",
                    data:{
                        'message': message,
                        'chat_last_msg_id': chatLastMsgId,
                    },
                    dataType: 'json',
                    beforeSend:function(){
                        $('#insert').val("Message is sending...");
                    },
                    success:function(data){
                        $('#send_msg_form')[0].reset();
                        $('#chat_last_msg_id').val(data.id);
                        message = ` <p class="card-text" chat_msg_id="${data.id}">
                                    <span class="badge rounded-pill bg-primary">
                                        ME
                                    </span>
                                    <br>
                                    ${data.message} <br>
                                    ${data.created_at}
                                    </p>
                                    <hr>`;
                        $('.chat-public').append(message);
                    }
                });
            }
        });

        setInterval(function(){
            loadNewMessageConversation();
        }, 1000);

        $(".inbox_chat").scrollTop($(".inbox_chat")[0].scrollHeight);



    });


</script>

