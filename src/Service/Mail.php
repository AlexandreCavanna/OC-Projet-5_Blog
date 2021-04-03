<?php


namespace App\Service;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    private string $name;

    private string $email;

    private string $message;


    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = htmlspecialchars($name);
    }


    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        if (PHPMailer::validateAddress($email)) {
            $this->email = htmlspecialchars($email);
        }
    }


    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = htmlspecialchars($message);
    }



    public function sendMail(): void
    {
        $mail = new PHPMailer();
        $mail->isSMTP();

        $this->configureMail($mail);

        try {
            $mail->setFrom('blog@alexandrecavanna.fr', 'Contact form Blog');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        try {
            $mail->addAddress('alexandre.cavanna.pro@gmail.com', 'Alexandre CAVANNA');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        try {
            $mail->addReplyTo($this->email, $this->name);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        $mail->Subject = 'Contact Form from my Blog';

        $mail->Body = <<<EOT
            Email: {$this->email}
            Name: {$this->name}
            Message: {$this->message}
            EOT;

        try {
            if (!$mail->send()) {
                $_SESSION["email_error"] = $mail->ErrorInfo;
            } else {
                $_SESSION["email_success"] = 'Votre email vient d\'être envoyé !';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param PHPMailer $mail
     */
    private function configureMail(PHPMailer $mail): void
    {
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAuth = true;
        $mail->Username = 'alexandre.cavanna.pro@gmail.com';
        $mail->Password = 'Alex9891';
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $this->setEmail($_POST['email']);
        $this->setName($_POST['name']);
        $this->setMessage($_POST['message']);
    }
}
