<style>
    @import url('https://fonts.googleapis.com/css2?family=Special+Elite&display=swap');

    .easyCommentsForm {
        background: #fafafa;
        padding: 10px;
        box-sizing: border-box;
        border: solid 1px #ddd;
        border-radius: 0.2rem;
        margin-top: 20px;
    }

    .easyCommentsForm input {
        width: 100%;
        padding: 8px;
        background: #fff;
        box-sizing: border-box;
        border-radius: 5px;
        border: solid 1px #ddd;
    }

    .easyCommentsForm textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 5px;
        background: #fff;
        box-sizing: border-box;
        border: solid 1px #ddd;
        border-radius: 5px;
    }

    .easyCommentsForm input#captcha_answer {
        width: 150px;
        padding: 5px;
        box-sizing: border-box;
    }

    .easyCommentsForm input[type="submit"] {
        width: auto;
        display: block;
        margin-top: 10px;
        background: #000;
        border-radius: 0.2rem;
        border: none;
        color: #fff;
        padding: 0.5rem 3rem;
    }

    .easyCommentsForm label {
        margin-top: 10px !important;
        display: block;
    }

    .easyCommentsFormTitle {
        font-weight: bold;
        margin: 0 !important;
        padding: 0 !important;

    }


    .easyCommentsForm .no-highlight {
        user-select: none; // chrome and Opera
        -moz-user-select: none; // Firefox
        -webkit-text-select: none; // IOS Safari
        -webkit-user-select: none; // Safari
        float: left;
        margin-right: 15px;
        min-width: 100px;
        padding-top: 10px;
        font-family: 'Special Elite', cursive;
        font-size: 25px;
        padding: 5px;
        margin: 0;
        display: inline-block;
        margin: 0 !important;
        margin-bottom: 5px !important;
        background-image: url(<?php echo DOMAIN_BASE.'bl-plugins/easyComments/img/bg.jpg';?>);
    }

    .easyCommentsForm .checkbox {
        all: unset;
        display: block;
    }

    .easyCommentsForm .checkbox input {
        all: revert;
    }

    .ec-none {
        display: none !important;
    }
</style>

<form class="easyCommentsForm" id="comments" method="post">
    <p class="easyCommentsFormTitle"><?php echo  $L->get('leavecomment'); ?></p>
    <hr>
    <label for="name"><?php echo   $L->get('name'); ?></label>
    <input type="text" name="name" id="name" required><br>

    <label for="email"><?php echo  $L->get('email'); ?></label>
    <input type="email" name="email" id="email" required><br>

    <label for="message"><?php echo  $L->get('message'); ?></label>
    <textarea name="message" id="message" required></textarea><br>

    <!-- Pole hidden do przechowywania identyfikatora komentarza, na który odpowiada -->
    <input type="hidden" name="parent_id" id="parent_id" value="">

    <!-- Wyświetlanie identyfikatora komentarza, na który odpowiada -->
    <div class="ec-none ecr" style="width:100%;background:red;color:#fff;display:flex;border-radius:5px;padding:5px;box-sizing:border-box;margin:10px 0;align-items:center;justify-content:between;">
        <label for="reply_to" style="margin:0 !important;padding:0 !important;"><?php echo   $L->get('replyto'); ?> </label>
        <span id="reply_to" style="margin:0;padding:0;margin-left:5px;"></span><br>
    </div>

    <span class="no-highlight"><?php echo $question; ?></span>
    <input type="text" name="captcha_answer" id="captcha_answer" required>

    <label for="checkbox" class="checkbox">
        <input type="checkbox" name="checkbox" required>
        <?php echo  $L->get('privacy'); ?> <span style="color:red">(<?php echo  $L->get('required'); ?>)</span>
    </label>

    <!-- Pole honeypot -->
    <input type="text" name="honeypot" style="display: none;">
    <hr>
    <input type="submit" name="sendcomment" value="<?php echo   $L->get('addcomment'); ?>">
</form>


<script>
    // Pobierz wszystkie przyciski "Reply"
    var replyButtons = document.querySelectorAll('.easyCommentsCard-reply');

    // Iteruj przez przyciski "Reply" i dodaj obsługę zdarzeń
    replyButtons.forEach(function(replyButton) {
        replyButton.addEventListener('click', function() {
            // Pobierz identyfikator komentarza, na który odpowiada nowy komentarz
            var parent_id = replyButton.getAttribute('data-reply');

            // Ustaw wartość pola ukrytego parent_id
            document.getElementById('parent_id').value = parent_id;

            document.querySelector('.ecr').classList.remove('ec-none');

            // Pobierz nazwę komentarza, na który odpowiada nowy komentarz
            var parent_name = replyButton.getAttribute('data-name');

            // Wyświetl nazwę komentarza w polu "Reply to"
            document.getElementById('reply_to').innerText = parent_name;
        });
    });
</script>