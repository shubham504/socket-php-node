<?php /* Template Name: Chat-Page */ ?>

<?php 

get_header(); 
  $current_user = wp_get_current_user();
                 
                $user_id = $current_user->data->ID;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<section class="banner_sec">
     <div class="breadcrumb_sec">
      <div class="container-fluid">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Message</li>
          </ol>
        </nav>
      </div>
    </div>
    <div class="banner_sec_img"><img src="<?php echo  get_template_directory_uri(); ?>/img/bg_img.png"></div>
    <div class="banner_sec_con">
      <div class="container">
        <div class="banner_sec_in">
          <h1 class="main_heading">Message</h1>
          <p class="main_subheading">Welcome to your private messaging platform. Transform those matches into meaningful conversations.</p>
        </div>
      </div>
    </div>
  </section>
  <section class="dash_sec padding_5 bg_gray">
    <div class="container">
      <div class="dash_sec_in">
        <div class="sidebar_s">
          <div class="side_bar_mobile">My Account <img src="<?php echo  get_template_directory_uri(); ?>/img/Down_Arrow.svg"></div>
          <?php get_sidebar(); ?>
        </div>
        <div class="content_s">
          <div class="message_s">
            <?php $userBoxClass='noactivlist';   if(!empty($_GET['receiver_id'])){ $userBoxClass='activlist'; }  ?>
           		

            <div class="message_sidebar <?php echo $userBoxClass; ?>" id="userList">
            	 <?php
               global $wpdb;
               	     $table_name='events_booking';
               	     $table_name11='matches';
                  $current_user = wp_get_current_user();
                 
                $user_id = $current_user->data->ID;
                	$receivers_id=@$_GET['receiver_id'];

               	 $Frienddata = $wpdb->get_results("SELECT *,if(sender_id=".$user_id.",receiver_id,sender_id) as secondry_id FROM $table_name11 Where (receiver_id='".$user_id."' or sender_id='".$user_id."') and status=1 group by secondry_id", ARRAY_A);
    		$match_found = false;

if (!empty($Frienddata)) {
foreach ($Frienddata as $friend) {
    if ($receivers_id == $friend['receiver_id'] || $receivers_id == $friend['sender_id']) {
        $match_found = true;
        break;
    }
}
}


?>
              <div class="chat_back"><a href="<?php echo site_url(); ?>/my-dashboard"><img src="<?php echo  get_template_directory_uri(); ?>/img/cross.svg"></a></div>


              <div class="message_search">
                <input type="text" name="" id="searchInput" class="form-control" placeholder="search">
              </div>
              <div class="messenger_itms">
                  <?php
               

              
          
               // /  print_r($current_user);die;
             /*   echo "SELECT user_chat_rooms.*,user_chats.message , if(user_chat_rooms.sender_id=".$user_id.",user_chat_rooms.receiver_id,user_chat_rooms.sender_id) as secondry_id FROM user_chat_rooms join user_chats on user_chats.room_id=user_chat_rooms.room_id Where (user_chat_rooms.receiver_id='".$user_id."' or user_chat_rooms.sender_id='".$user_id."')  group by secondry_id";die;*/
                 $data = $wpdb->get_results("SELECT user_chat_rooms.*,user_chats.message , if(user_chat_rooms.sender_id=".$user_id.",user_chat_rooms.receiver_id,user_chat_rooms.sender_id) as secondry_id FROM user_chat_rooms join user_chats on user_chats.room_id=user_chat_rooms.room_id Where (user_chat_rooms.receiver_id='".$user_id."' or user_chat_rooms.sender_id='".$user_id."')  group by secondry_id", ARRAY_A);
                //  echo 'select * from matches where  (sender_id='.$user_id.' or receiver_id='.$user_id.'';die;
                // / print_r($data);die;
                //   echo "select * from matches where  (sender_id='".$user_id."' or receiver_id='".$user_id."";die;
                  if(!empty($data)){


                foreach ($data as $row) {

                 
                   $userProfile=$row['receiver_id'];
                    if($user_id!=$row['sender_id']) {
                      $userProfile=$row['sender_id'];
                    }
            

                $first_name = get_user_meta($userProfile, 'first_name', true );
                $last_name = get_user_meta($userProfile, 'last_name', true );
                $profile = get_user_meta($userProfile, 'profile', true );
                if(empty($profile)){
                  $profile=get_template_directory_uri().'/img/images.png';
                }
                $user_data = get_user_by( 'id', $userProfile );
                
               ?>
                <div class="messenger_itm">
                  <a href="?receiver_id=<?php echo $userProfile; ?>">
                    <div class="messenger_itm_img"><img src="<?php echo $profile; ?>"></div>
                    <div class="messenger_itm_con">
                      <p><?php echo $user_data->data->user_nicename; ?></p>
                      <span><?php echo $first_name; ?></span>
                    </div>
                  </a>
                </div>
                <?php } } ?>
              </div>
            </div>
            <?php 
          
              $first_name_r = get_user_meta(@$_GET['receiver_id'], 'first_name', true );
                $last_name_r = get_user_meta(@$_GET['receiver_id'], 'last_name', true );
                $profile_r = get_user_meta(@$_GET['receiver_id'], 'profile', true );
              //   $profile = get_user_meta($row['receiver_id'], 'profile', true );
                if(empty($profile_r)){
                  $profile_r=get_template_directory_uri().'/img/images.png';
                }
            ?>
            <div class="message_info">
              <div class="message_info_header">
                <div class="message_info_header_user">
                  <div class="messenger_itm_back"><img src="<?php echo  get_template_directory_uri(); ?>/img/back_arrow.svg"></div>
                  <div class="messenger_itm_img"><img src="<?php echo $profile_r; ?>"></div>
                    <div class="messenger_itm_con">
                      <p><?php echo $current_user->data->user_nicename; ?></p>
                    </div>
                </div>
               <!--  <div class="message_info_header_dots"><img src="<?php echo  get_template_directory_uri(); ?>/img/more.svg"></div> -->
              </div>
              <div class="message_body">
                <div class="message_itms " id="chat-details-box">
                 
                </div>
              </div>
              <div class="message_footer">
                <textarea id="message" placeholder="Message..."></textarea>
                <input type="file" id="fileInput" name="file">
                <button type="button" onclick="sendMessage();" class="btn_send send-msg-btn">SEND<img src="<?php echo  get_template_directory_uri(); ?>/img/send_icon.svg"></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <input type="hidden" name="room_id" id="room_id">
    <input type="hidden" value="<?php echo $user_id; ?>" name="sender_id" id="sender_id">
    <input type="hidden" value="<?php echo $_GET['receiver_id']; ?>" name="receiver_id" id="receiver_id">
  </section>
<?php get_footer(); ?>
<script type="text/javascript">
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>

<script src="https://itsadate.club/socket.io/socket.io.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js"></script>
<script>
     $('#searchInput').on('input', function() {
		var searchTerm = $(this).val().toLowerCase();
		$('#userList .messenger_itm').each(function() {
			var userName = $(this).find('.messenger_itm_con p').text().toLowerCase();
			if (userName.includes(searchTerm)) {
				$(this).show();
			} else {
				$(this).hide();
			}
		});
	});

    $(document).ready(function() {
        joinroom();
        $(".send-msg-btn").show();
          
        // setTimeout(function() {
		// 	var element_receiver_id = $("#receiver_id").val();
		// 	var element_room_id = $("#room_id").val();
		// 	getChatDetails('', element_receiver_id, element_room_id);   
        // }, 2000);
    });

	var options = {
		"force new connection": true,
		reconnectionAttempt: "Infinity",
		timeout: 10000,
		transports: ["polling","websocket"],
	};
	var socket_url = "http://localhost:6754";
	var socket = io(socket_url, options);

	function joinroom() {
		var sender_id = $("#sender_id").val();
		var receiver_id = $("#receiver_id").val();
		const dataToSend = JSON.stringify({"sender_id": sender_id, "receiver_id": receiver_id});
		socket.emit('JOIN_ROOM', sender_id,receiver_id);
		socket.on('JOIN_ROOM_RESPONSE',(callback_response) => {
			if (callback_response) {
				var r_data = JSON.parse(callback_response);
				$('#room_id').val(r_data.data.room_id);
				getChatDetails(sender_id, receiver_id, r_data.data.room_id);
			}
		});
		return false;
	}

	function getUserList(user_id) {
		if (!user_id) {
			return false;
		}
		socket.emit('USER_LIST', user_id, (callback_response) => {
			if (callback_response) {
				var r_data = JSON.parse(callback_response);
				let str = '';
				r_data.data.forEach(element => {
					var element_data = JSON.stringify(element.message);
					var element_receiver_id = element.id;
					var element_room_id = element.room_id;
					var getChatDetails_fn = 'getChatDetails(' + element_data + ',' +
					element_receiver_id + ',' + element_room_id + ')';
					var profile_image = element.profile_image ? element.profile_image : 'uploads/images/demouser.png';
					var latest_message = element.message ? (element.message.message != null ? element.message.message : '') : '';
					var latest_createdAt = element.message ? moment(element
							.message.createdAt).format('DD-MMM-YYYY HH:mm:ss') : '';
					str += "<div class='friend-drawer friend-drawer--onhover' onclick='" +
						getChatDetails_fn + "'>    <img class='profile-image'        src='" + profile_image + "'        alt=''>    <div class='text'>        <h6>" + element
						.name + "</h6>        <p class='text-muted'>" + latest_message +
						"</p>    </div>    <span class='time text-muted small'>" + latest_createdAt + "</span></div><hr>";

				});
				$('.msg-user-list').empty();
				$('.msg-user-list').html(str);
			}
			$('.friend-drawer--onhover').on('click', function() {
				$('.chat-bubble').hide('slow').show('slow');
			});
		});
	}

	function getChatDetails(element_sender_id, element_receiver_id, element_room_id) {
		console.log("errrrrrrrrrrrrrrrrrrrr");
		$(".send-msg-btn").show();
		$( "#chat-details-box" ).removeAttr('class');
		$( "#chat-details-box" ).addClass( "chat-details" );
		$( "#chat-details-box" ).addClass( "chat-details"+element_room_id );
		var room_id = element_room_id;
		var sender_id = element_sender_id || 1;
		var receiver_id = element_receiver_id;
		const dataToSend = JSON.stringify({"roomId": room_id, "senderId": sender_id, "receiver_id": receiver_id});

		socket.emit('CHAT_DETAIL_NEW', dataToSend);
		socket.on('CHAT_DETAIL_RESPONSE_NEW', (callback_response) => {
			if (callback_response) {
				var r_data = JSON.parse(callback_response);
				$('.chat-details').empty();
				let str = '';
				r_data.data.forEach(element => {
					if (element.sender_id == sender_id) {
            if((element.message).indexOf('/uploads/') > -1){
              str +=
							"<div class='message_itm message_send'><div class='message_cn'><p><img src='http://localhost/wordpress-chat/chat-code/src" +
							element.message + "'></p></div> <div class='message_time'>" +
							moment(element.created_at).format('DD-MMM-YYYY HH:mm:ss') + "</div>   </div>";
            }else{
              str +=
							"<div class='message_itm message_send'><div class='message_cn'><p>" +
							element.message + "</p></div> <div class='message_time'>" +
							moment(element.created_at).format('DD-MMM-YYYY HH:mm:ss') + "</div>   </div>";
            }
						
					} else {
            if((element.message).indexOf('/uploads/') > -1){
              str +=
							"<div class='message_itm message_received'><div class='message_cn'><p><img src='http://localhost/wordpress-chat/chat-code/src" +
							element.message + "'>     </p></div> <div class='message_time'>" +
							moment(element.created_at).format('DD-MMM-YYYY HH:mm:ss') + "</div>   </div>";
            }else{
              str +=
							"<div class='message_itm message_received'><div class='message_cn'><p>"+
							element.message + "     </p></div> <div class='message_time'>" +
							moment(element.created_at).format('DD-MMM-YYYY HH:mm:ss') + "</div>   </div>";
            }
						
					}
				});
				$(".chat-details"+element_room_id).html(str);
				scrollToBottom(element_room_id);
			}
		});
	}
function sendMessage() {
    var room_id = $('#room_id').val();
    var sender_id = $('#sender_id').val();
    var receiver_id = $('#receiver_id').val();
    var message = $('#message').val();
    var fileInput = $('#fileInput')[0];
    var file = fileInput.files[0];

    if (!message && !file) {
        return false;  // No message or file to send
    }

    let dataToSend = {
        roomId: room_id,
        senderId: sender_id,
        receiverId: receiver_id,
        message: message || '',  // Ensure message is at least an empty string
    };

    if (file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            dataToSend.file = e.target.result;
            dataToSend.fileName = file.name;
            sendMessageToSocket(dataToSend, message, room_id);
        };
        reader.readAsDataURL(file);
    } else {
        sendMessageToSocket(dataToSend, message, room_id);
    }

    return false;
}

function sendMessageToSocket(dataToSend, message, room_id) {
    socket.emit('NEW_MESSAGE', JSON.stringify(dataToSend));

    let str = "<div class='message_itm message_send'><div class='message_cn'>";
    
    // Check if there's a file and if it's an image
    if (dataToSend.file && dataToSend.file.startsWith('data:image/')) {
        str += "<p><img src='" + dataToSend.file + "' alt='" + dataToSend.fileName + "' style='max-width: 100%; height: auto;'/></p>";
    } else if (message) {
        str += "<p>" + message + "</p>";
    } else if (dataToSend.file) {
        // Handle non-image files, showing just the file name as a message
        str += "<p>File sent: " + dataToSend.fileName + "</p>";
    }
    
    str += "</div><div class='message_time'>" +
           moment().format('DD-MMM-YYYY HH:mm:ss') + "</div></div>";

    $(".chat-details" + room_id).append(str);
    scrollToBottom(room_id);
    $('#message').val('');
    $('#fileInput').val('');  // Clear the file input
}

function scrollToBottom(room_id) {
    var chatDetails = $(".chat-details" + room_id);
    chatDetails.scrollTop(chatDetails[0].scrollHeight);
}




	function addUser(sender_id, receiver_id) {
		socket.emit('USER_ADDED', sender_id,receiver_id);
	}
	socket.on('SEND_MESSAGE_RESPONSE', (response, room_id) => {
		var r_data = JSON.parse(response);
		if(r_data.status){
			var sender_id = $("#sender_id").val();
			var receiver_id = $("#receiver_id").val();
			var room_id = $('#room_id').val();
			getChatDetails(sender_id, receiver_id, room_id);
		}
	});

	function scrollToBottom(room_id) {
        var container = $(".chat-details"+room_id);
        container.animate({
            scrollTop: container.prop("scrollHeight")
        }, 1000); // You can adjust the duration of the animation (500ms in this case)
    }
    </script>
