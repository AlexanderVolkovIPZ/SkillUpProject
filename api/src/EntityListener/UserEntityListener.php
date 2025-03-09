<?php

namespace App\EntityListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserEntityListener
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher,
                                EntityManagerInterface      $entityManager,
                                UrlGeneratorInterface       $urlGenerator)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param User $user
     * @param LifecycleEventArgs $lifecycleEventArgs
     * @return void
     */
    public function prePersist(User $user, LifecycleEventArgs $lifecycleEventArgs):void
    {
        if ($user->getPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
        }
    }

    /**
     * @param User $user
     * @param LifecycleEventArgs $eventArgs
     * @return void
     * @throws \Random\RandomException
     */
    public function postPersist(User $user, LifecycleEventArgs $eventArgs): void
    {
        $email = $user->getEmail();
        $token = $this->generateUniqueToken();

        $user->setRegistrationToken($token);

        $confirmationUrl = $this->urlGenerator->generate('confirm_registration',
            ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL);

        $this->entityManager->flush();

        $this->sendConfirmationEmail($email, $confirmationUrl);
    }

    /**
     * @param $recipient
     * @param $confirmationUrl
     * @return void
     */
    private function sendConfirmationEmail($recipient, $confirmationUrl): void
    {
        $subject = 'Confirmation of registration';
        $body = '<p>Thank you for registering!</p>' . '<p>Confirm registration by following the link: <a href="'
            . $confirmationUrl . '">Confirm registration</a></p>';

        $mailer = $this->createMailer(host: 'smtp.gmail.com',
            smtpSecure: 'tls',
            port: 587,
            username: 'volkov.aliksandr0203@gmail.com',
            password: 'qozp aqyw nzdl sjoy',
            recipient: $recipient,
            subject: $subject,
            body: $body);

        try {
            if (!$mailer->send()) {
                echo 'Error sending the letter: ' . $mailer->ErrorInfo;
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * @param string $host
     * @param string $smtpSecure
     * @param int $port
     * @param string $username
     * @param string $password
     * @param string $recipient
     * @param string $subject
     * @param string $body
     * @param bool $smtpAuth
     * @param bool $isHtml
     * @return PHPMailer
     */
    private function createMailer(string $host,
                                  string $smtpSecure,
                                  int    $port,
                                  string $username,
                                  string $password,
                                  string $recipient,
                                  string $subject,
                                  string $body,
                                  bool   $smtpAuth = true,
                                  bool   $isHtml = true): PHPMailer
    {
        $mailer = new PHPMailer(true);

        try {
            $mailer->isSMTP();
            $mailer->Host = $host;
            $mailer->SMTPSecure = $smtpSecure;
            $mailer->Port = $port;
            $mailer->Username = $username;
            $mailer->Password = $password;
            $mailer->addAddress($recipient);
            $mailer->Subject = $subject;
            $mailer->Body = $body;
            $mailer->SMTPAuth = $smtpAuth;
            $mailer->isHTML($isHtml);

            return $mailer;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return $mailer;
        }
    }

    /**
     * @return string
     * @throws \Random\RandomException
     */
    private function generateUniqueToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}