<style>
    .easyCommentsCard,
    .easyCommentsResponse {
        position: relative;
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        border: solid 1px #ddd;
        border-radius: 0.2rem;
        box-sizing: border-box;
    }


    .easyCommentsResponse {
        width: 100%;
        position: relative;
        border-left: solid 5px #000;

    }



    .easyCommentsCard hr {
        opacity: 0.2;
    }

    .easyCommentsName {
        font-style: italic;
        font-weight: bold;
        margin: 0 !important;
        padding: 0 !important;
    }

    .easyCommentsCard p {
        margin: 0 !important;
        padding: 0 !important;
        margin: 10px !important 0;
    }

    .easyCommentsCardDelete {
        position: absolute;
        top: 10px;
        right: 10px;
        background: red;
        border-radius: 0.2rem;
        padding: 0.1rem 0.2rem;
        color: #fff !important;
        text-decoration: none !important;
        font-size: 13px;
        border: none;
        padding: 5px;
    }

    .easyCommentsCardAprove {
        position: absolute;
        top: 10px;
        right: 120px;
        background: green;
        border-radius: 0.2rem;
        padding: 0.1rem 0.2rem;
        color: #fff !important;
        text-decoration: none !important;
        font-size: 13px;
        border: none;
        padding: 5px;
    }

    .easyCommentsCard-reply {
        all: unset;
        position: relative;
        background: #fafafa;
        border: solid 1px;
        padding: 3px 15px;
        margin-top: 10px;
        border-radius: 10px;
        background: #ddd;
        color: #111;
        font-size: 13px;
        border: none;
        cursor: pointer;
        text-decoration: none !important;
        display: inline-block;
    }

    .alert-success {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        padding: 10px;
        border-radius: 5px;
        z-index: 99;
    }

    .alert-success span {
        color: #fff;
        font-size: 2rem;
    }
</style>

<h3><?php echo $L->get('comments');?></h3>
<hr>
<?php




if (file_exists($fileDir)) {
    $comments = simplexml_load_file($fileDir);

    foreach ($comments->comment as $comment) {

        if ((bool)$comment->approved || isset($adminCheck) && $adminCheck == true) {


            echo '<div class="easyCommentsCard">';



            if (isset($adminCheck) && $adminCheck == true) {


                if ((bool) $comment->approved !== true) {
                    echo ' 
                        <form  method="POST">
                        <input type="hidden" name="publishComment" value="' . $comment['id'] . '">
                        <input type="submit"    class="easyCommentsCardAprove" value="' . $L->get('publishcomment') . '">
                        </form>';
                };

                echo '
                    <form  method="POST">
                    <input type="hidden" name="deleteComment" value="' . $comment['id'] . '">
                    <input type="submit"    class="easyCommentsCardDelete" value="' .  $L->get('deletecomment') . ' ">
                    </form>
                     ';
            };


            echo '<p class="easyCommentsName">' . htmlspecialchars($comment->name, ENT_QUOTES, 'UTF-8') . '</p>';
            echo '<p style="border-bottom:solid 1px #ddd;padding:10px 0 !important;">' . htmlspecialchars($comment->message, ENT_QUOTES, 'UTF-8') . '</p>';

            // Dodanie przycisku Odpowiedź
            echo '<a href="#comments" class="easyCommentsCard-reply"  data-name="' . $comment->name . '" data-reply="' . $comment['id'] . '">' . $L->get('reply') . '</a>';



            echo '</div>';

            // Sprawdzenie, czy komentarz ma odpowiedzi
            if (isset($comment->response)) {
                foreach ($comment->response as $response) {

                    if ((bool) $response->approved || isset($adminCheck) && $adminCheck == true) {
                        echo '<div class="easyCommentsResponse">';

                        if (isset($adminCheck) && $adminCheck == true) {
 
                                if ((bool) $response->approved !== true) {
                                    echo ' 
                                    <form   method="POST">
                                    <input type="hidden" name="publishComment" value="' . $response['id'] . '">
                                    <input type="submit" class="easyCommentsCardAprove" value="publish comment">
                                    </form>';
                                };

                                echo  ' <form   method="POST">
                                <input type="hidden" name="deleteComment" value="' . $response['id'] . '">
                                <input type="submit"    class="easyCommentsCardDelete" value="delete comment">
                                </form>
                                 ';
                                };


                       
                      

                        echo '<p class="easyCommentsName">' . htmlspecialchars($response->name, ENT_QUOTES, 'UTF-8') . '</p>';
                        echo '<p style="border-bottom:solid 1px #ddd;padding:10px 0 !important;margin:0;">' . htmlspecialchars($response->message, ENT_QUOTES, 'UTF-8') . '</p>';
                        echo '<a href="#comments" class="easyCommentsCard-reply" data-name="' . $response->name . '" data-reply="' . $comment['id'] . '">' . $L->get('reply') . '</a>';

                        echo '</div>';
                    }
                }
            }
        }
    }
}
?>