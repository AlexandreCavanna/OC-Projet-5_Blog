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


    /**
     * @return string
     * @throws Exception
     */
    public function sendMail(): string
    {
        $mail = new PHPMailer();
        $mail->isSMTP();

        $this->configureMail($mail);

        $mail->setFrom('blog.acavanna@gmail.com', 'Contact form Blog');
        $mail->addAddress('blog.acavanna@gmail.com', 'Alexandre CAVANNA');
        $mail->addReplyTo($this->email, $this->name);

        $mail->Subject = 'Contact Form from my Blog';

        $mail->Body = <<<EOT
            Email: {$this->email}
            Name: {$this->name}
            Message: {$this->message}
            EOT;

        if (!$mail->send()) {
            return $_SESSION["email_error"] = $mail->ErrorInfo;
        } else {
            return $_SESSION["email_success"] = 'Votre email vient d\'être envoyé !';
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
        $mail->Username = 'blog.acavanna@gmail.com';
        $mail->Password = '6pWp6BkT4';
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $this->setEmail(filter_input(INPUT_POST, 'email'));
        $this->setName(filter_input(INPUT_POST, 'name'));
        $this->setMessage(filter_input(INPUT_POST, 'message'));
    }
}
