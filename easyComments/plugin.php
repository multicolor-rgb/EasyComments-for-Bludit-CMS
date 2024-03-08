<?php
class easyComments extends Plugin
{



    public function adminController()
    {

        if (isset($_POST['deletelog'])) {

            global $fileLog;
            unlink(PATH_CONTENT.'easyCommentsLog.txt');
            echo "<meta http-equiv='refresh' content='0'>";
        };




        if (isset($_POST['saveadminemail'])) {

            file_put_contents(PATH_CONTENT . 'easyCommentsMail.txt', $_POST['adminemail']);
            echo "<meta http-equiv='refresh' content='1'>";
        };
    }

    public function adminView()
    {
        global $L;
        include($this->phpPath() . 'PHP/backform.inc.php');
    }


    public function adminBodyBegin(){

        if (!isset($_SESSION)) {
            session_start();
        };

    }

    public function adminSidebar()
    {
        $pluginName = Text::lowercase(__CLASS__);
        $url = HTML_PATH_ADMIN_ROOT . 'plugin/' . $pluginName;
        $html = '<a id="current-version" class="nav-link" href="' . $url . '">EasyComments 🤭</a>';
        return $html;
    }
}





function easyComments()
{

    global $page;
    global $L;


    $id = $page->slug();

  


    $fileLog = PATH_CONTENT . 'easyCommentsLog.txt';


    $fileDir = PATH_CONTENT . 'easyComments/' . $id . '.xml';

    $adminCheck;

    $Login = new Login();
    if (in_array($Login->role(), array("author", "admin", true))) {

        $adminCheck = true;
    };





    if (isset($_POST['sendcomment'])) {
        

    $id = $page->slug();
        // Sprawdź, czy kod CAPTCHA został wprowadzony poprawnie
        if (isset($_POST['captcha_answer']) && $_POST['captcha_answer'] == $_SESSION['captcha_question']) {
            if (!empty($_POST['honeypot'])) {
                echo 'Błędne żądanie!';
                exit;
            }

            // Pobranie danych z formularza
            $name = htmlentities($_POST['name']);
            $email = htmlentities($_POST['email']);
            $message = htmlentities($_POST['message']);
            $parent_id = $_POST['parent_id'] !== '' ? htmlentities($_POST['parent_id']) : null;

            // Tworzenie wiadomości e-mail
            $to = @file_get_contents(PATH_CONTENT . 'easyCommentsMail.txt'); // Zmień na właściwy adres e-mail administratora
            $subject = "New comment: $name";
            $body = "New Comments: $name\n";
            $body .= "Email: $email\n";

             $body .= "Slug page with comment: $id\n";
            $body .= "message:\n$message";

            // Wysyłanie e-maila
            $headers = "From: " . @file_get_contents(PATH_CONTENT . 'easyCommentsMail.txt') . "\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";


            // E-mail został wysłany pomyślnie

            // Otwarcie pliku XML
            if (file_exists($fileDir)) {
                $xml = simplexml_load_file($fileDir);
            } else {
                // Tworzenie pliku XML, jeśli nie istnieje
                if (!file_exists(PATH_CONTENT . 'easyComments/')) {
                    mkdir(PATH_CONTENT . 'easyComments/', 0755);
                }

                file_put_contents(PATH_CONTENT . 'easyComments/.htaccess', 'Deny from all');
                $con = '<?xml version="1.0"?><comments></comments>';
                file_put_contents($fileDir, $con);
                $xml = simplexml_load_file($fileDir);
            }

            // Dodawanie komentarza lub odpowiedzi do pliku XML
            if ($parent_id !== null) {
                $parentComment = $xml->xpath("//comment[@id='$parent_id']")[0];
                $response = $parentComment->addChild('response');
                $response->addChild('name', $name);
                $response->addAttribute('id', md5(uniqid('', true)));
                $response->addChild('email', $email);
                $response->addChild('message', strip_tags(html_entity_decode($message)));
            } else {
                $comment = $xml->addChild('comment');
                $comment->addAttribute('id', uniqid());
                $comment->addChild('name', $name);
                $comment->addChild('email', $email);
                $comment->addChild('message',  strip_tags(html_entity_decode($message)));
            }

            // Zapisanie zmian w pliku XML
            $xml->asXML($fileDir);

            $fileLog = PATH_CONTENT . 'easyCommentsLog.txt';
            $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            if (file_exists($fileLog)) {
                mail($to, $subject, $body, $headers);
                file_put_contents($fileLog,  ' <b>' . date('l jS \of F Y h:i:s A')  . ' ' . $L->get('commentwait') .'<a href="'. $actual_link.'" target="_blank">'. $id . '</a></b><br>' . file_get_contents($fileLog));
            } else {
                mail($to, $subject, $body, $headers);
                file_put_contents($fileLog, ' <b>' . date('l jS \of F Y h:i:s A') . ' ' .  $L->get('commentwait') .'<a href="'. $actual_link.'"  target="_blank">'. $id . '</a></b><br>');
            }

            echo '<div class="alert alert-success" id="comment-alert"><span>' . $L->get('commentadded')  . '</span></div>';
            echo "<meta http-equiv='refresh' content='1'>";
        } else {
            // Kod CAPTCHA jest niepoprawny
            echo '<div class="alert alert-danger" id="comment-alert"><span>' . $L->get('wrongcaptcha') . '</span></div>';
            echo "<meta http-equiv='refresh' content='1'>";
        }

        // Usuń pytanie CAPTCHA z sesji
        unset($_SESSION['captcha_question']);
        unset($_SESSION['captcha_answer']);

        global $fileLog;
        global $id;
    }



    if (isset($_POST['deleteComment'])) {
        $xmlFile = $fileDir;

        // Ładuj plik XML
        $xml = simplexml_load_file($xmlFile);

        // Identyfikator komentarza do usunięcia
        $commentIdToDelete =  $_POST['deleteComment'];

        // Znajdź elementy z odpowiednim id i usuń je
        $elementsToDelete = $xml->xpath("//comment[@id='$commentIdToDelete']");
        foreach ($elementsToDelete as $element) {
            $dom = dom_import_simplexml($element);
            $dom->parentNode->removeChild($dom);
        }

        // Znajdź elementy response z odpowiednim id i usuń je
        $responsesToDelete = $xml->xpath("//response[@id='$commentIdToDelete']");
        foreach ($responsesToDelete as $response) {
            $domResponse = dom_import_simplexml($response);
            $domResponse->parentNode->removeChild($domResponse);
        }


        // Zapisz zmiany do pliku XML
        $xml->asXML($xmlFile);

        echo '<div class="alert alert-success" id="comment-alert"><span>'   . $L->get('deleted') . '</span></div>';
        echo "<meta http-equiv='refresh' content='1'>";
    }

    if (isset($_POST['publishComment'])) {
        $xmlFile = $fileDir;

        // Ładuj plik XML
        $xml = simplexml_load_file($xmlFile);

        // Identyfikator komentarza do opublikowania
        $commentIdToPublish = $_POST['publishComment'];

        // Znajdź elementy komentarza z odpowiednim id i dodaj element <approved>
        $elementsToPublish = $xml->xpath("//comment[@id='$commentIdToPublish']");
        foreach ($elementsToPublish as $element) {
            $element->addChild('approved');
        }

        // Znajdź elementy response z odpowiednim id i dodaj element <approved>
        $responsesToPublish = $xml->xpath("//response[@id='$commentIdToPublish']");
        foreach ($responsesToPublish as $response) {
            $response->addChild('approved');
        }

        // Zapisz zmiany do pliku XML
        $xml->asXML($xmlFile);

        echo '<div class="alert alert-success" id="comment-alert"><span>'  . $L->get('published') .  '</span></div>';

        echo "<meta http-equiv='refresh' content='1'>";
    }




    include(PATH_PLUGINS . 'easyComments/PHP/loop.inc.php');
    include(PATH_PLUGINS . 'easyComments/PHP/captcha.inc.php');
    include(PATH_PLUGINS . 'easyComments/PHP/form.inc.php');
};
