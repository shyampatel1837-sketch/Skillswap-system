<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE email='$email'"));
$user_id = $user['id'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat | SkillSwap</title>

<style>
.chat-container{
    display:flex;
    height:90vh;
}

/* LEFT */
.chat-list{
    width:30%;
    background:#1f1f1f;
    color:white;
    padding:10px;
    overflow-y:auto;
}

.chat-user{
    padding:10px;
    border-bottom:1px solid #333;
    cursor:pointer;
}
.chat-user:hover{ background:#333; }

/* RIGHT */
.chat-box{
    width:70%;
    display:flex;
    flex-direction:column;
}

.messages{
    flex:1;
    padding:15px;
    overflow-y:auto;
    background:#f4f4f4;
}

.msg{
    margin:5px 0;
    padding:10px;
    border-radius:8px;
    max-width:60%;
}

.sent{
    background:#4CAF50;
    color:white;
    margin-left:auto;
}

.received{
    background:#ddd;
}

.send-box{
    display:flex;
    align-items:center;
    padding:10px;
    background:white;
}

.send-box input{
    flex:1;
    padding:10px;
}

.send-box button{
    padding:10px;
}
</style>

</head>

<body>

<div class="chat-container">

<!-- LEFT -->
<div class="chat-list">
<h3>Chats</h3>

<?php
$users = mysqli_query($conn, "SELECT * FROM users WHERE id != '$user_id'");
while($u = mysqli_fetch_assoc($users)){
    echo "<div class='chat-user' onclick='openChat(".$u['id'].")'>".$u['name']."</div>";
}
?>
</div>

<!-- RIGHT -->
<div class="chat-box">

<div class="messages" id="messages">
Select a user to start chat
</div>

<div class="send-box">

<!-- ➕ FILE -->
<label for="fileInput" style="cursor:pointer;font-size:22px;padding:10px;">➕</label>
<input type="file" id="fileInput" style="display:none;">

<input type="text" id="msg" placeholder="Type message...">

<button onclick="sendMsg()">Send</button>

</div>

</div>

</div>

<script>

let receiver_id = 0;

// OPEN CHAT
function openChat(id){
    receiver_id = id;
    loadMessages();
}

// LOAD MSG
function loadMessages(){
    if(receiver_id == 0) return;

    fetch("php/load_messages.php?receiver_id=" + receiver_id)
    .then(res => res.text())
    .then(data => {
        document.getElementById("messages").innerHTML = data;
    });
}

// SEND MSG + FILE ✅ (STEP 3 FIX)
function sendMsg(){

    if(receiver_id == 0){
        alert("Select user first");
        return;
    }

    let msg = document.getElementById("msg").value;
    let file = document.getElementById("fileInput").files[0];

    let formData = new FormData();
    formData.append("receiver_id", receiver_id);
    formData.append("message", msg);

    if(file){
        formData.append("file", file);
    }

    fetch("php/send_message.php", {
        method:"POST",
        body: formData
    }).then(() => {
        document.getElementById("msg").value="";
        document.getElementById("fileInput").value="";
        loadMessages();
    });
}

// ENTER KEY ✅
document.getElementById("msg").addEventListener("keypress", function(e){
    if(e.key === "Enter"){
        e.preventDefault();
        sendMsg();
    }
});

// AUTO REFRESH
setInterval(loadMessages, 2000);

</script>

</body>
</html>