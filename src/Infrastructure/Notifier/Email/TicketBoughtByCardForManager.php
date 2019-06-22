<?php
declare(strict_types=1);

namespace App\Infrastructure\Notifier\Email;

use Swift_Message;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

final class TicketBoughtByCardForManager implements Factory
{
    private $translator;

    private $twig;

    private $from;

    private $nameFrom;

    public function __construct(TranslatorInterface $translator, Environment $twig, string $from, string $nameFrom)
    {
        $this->translator = $translator;
        $this->twig = $twig;
        $this->from = $from;
        $this->nameFrom = $nameFrom;
    }

    public function create(array $event): Swift_Message
    {
        $letter = $this->twig->render('', $event);
        $subject = $this->translator->trans('');
        // TODO move swift message to constructor to replace params. Init swift message in dic
        $message = (new Swift_Message())
            ->setSubject($subject)
            ->setFrom($this->from, $this->nameFrom)
            ->setTo($event['email'])
            ->setBody(
                $letter,
                'text/html'
            )
        ;

        return $message;
    }
}
