<?php 
include("../template/header.php");
include("../../back-end/db/db.php");
include("../../back-end/actions/search.php");
include("../../back-end/actions/sendMessage.php");
?>

<?php
$email = $_SESSION['email'];
$SQLsequence = $conexion->prepare("SELECT * FROM messages where userRecipient = '$email'");
$SQLsequence->execute();

$userList = $SQLsequence->fetchAll(PDO::FETCH_ASSOC);

$GetAllUsers = $conexion->prepare("SELECT * FROM users");
$GetAllUsers->execute();
$allUsersList = $GetAllUsers->fetchAll(PDO::FETCH_ASSOC);
?>
<script type="text/javascript" src="<?php echo $url;?>/front-end/js/dselect.js"></script>
<main class="home h-full bg-slate-200 w-full flex flex-col items-center">
    <article class="header">
        <section>
            <header class="text">
                <input type="text" id="search" placeholder="Search here" />
            </header>
        </section>
        <div>
            <div id="display"></div>
        </div>
    </article>
    <div class="top-section">
        <div>
            <button class="bg-red-500 hover:bg-red-900 transition ease-in-out text-white p-2 font-bold rounded-md m-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Send message</button>
            <h3 class="text">Your messages</h3>
        </div>
    </div>
    <article class="main">
    <?php foreach($userList as $users){ ?>
    <?php if ($users['seen'] == 0) {?>
        <section class="not-seen">
    <?php }else if ($users['seen'] == 1) {?>
        <section class="seen">
    <?php } ?>
            <div class="left">
                <div class='user_image'>
                    <img src="../../img/basic_logo_dark.svg" alt="user image">
                </div>
            </div>
            <div class="right">
                <div class='userName_<?php echo $users['id'] ?>' id="userName"><?php echo $users['userSender']; ?></div>
            <?php if ($users['seen'] == 0) {?>
                <div id="NoShow" class='user_message_<?php echo $users['id'] ?>'><?php echo $users['message']; ?></div>
                <button id="readButton_<?php echo $users['id'] ?>" class="readButton bg-red-500 hover:bg-red-900 transition ease-in-out text-white p-2 font-bold rounded-md m-3">Read message</button>
            <?php }else if ($users['seen'] == 1) {?>
                <div id="Show" class='userMessage_<?php echo $users['id'] ?>'><?php echo $users['message']; ?></div>
            <?php } ?>
            </div>
        </section>
        <?php } ?>
    </article>
    <article>
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">New message</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="../../back-end/actions/sendMessage.php" method="POST">
                        <div class="modal-body">
                            <div class="row">
                                <select name="select_box" class="form-select" id="select_box" name="email-recipient" id="email-recipient">
                                    <option value="">Select the recipient</option>
                                    <?php 
                                    foreach($allUsersList as $allUsers){
                                        echo '<option value="'.$allUsers["email"].'">'.$allUsers["email"].'</option>';
                                    }
                                    ?>  
                                </select>
                            </div>
                            <div class="row">
                                <input type="text" name="email-subject" id="email-subject" class="form-control" placeholder="Subject" aria-label="Subject">
                            </div>
                            <div class="row">
                                <textarea class="form-control" name="email-message" id="email-message" placeholder="Message" aria-label="Message"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <input type="submit" value="">
                                <button type="button" class="btn btn-primary"><i class='bx bxs-send icon'></i></button>
                            </input>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </article>
</main>
<script>
    var select_box_element = document.querySelector('#select_box');
    dselect(select_box_element, {
        search: true
    });
</script>
<script src="../js/messages.js">
    <?php
        include("../../back-end/actions/updateSeen.php");
        ?>
</script>

<?php
include("../template/footer.php");?>