<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

class Email
{
    private ?AddressInterface $from = null;

    /** @var array<AddressInterface> */
    private array $to = [];

    /** @var array<AddressInterface> */
    private array $replayTo = [];

    /** @var array<AddressInterface> */
    private array $cc = [];

    /** @var array<AddressInterface> */
    private array $bcc = [];
    private ?string $templateId = null;

    /** @var array<string, mixed> */
    private array $templateVars = [];
    private Format $emailFormat = Format::Both;

    public function getFrom(): ?AddressInterface
    {
        return $this->from;
    }

    /**
     * @return array<AddressInterface>
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @return array<AddressInterface>
     */
    public function getReplayTo(): array
    {
        return $this->replayTo;
    }

    /**
     * @return array<AddressInterface>
     */
    public function getCc(): array
    {
        return $this->cc;
    }

    /**
     * @return array<AddressInterface>
     */
    public function getBcc(): array
    {
        return $this->bcc;
    }

    public function getTemplateId(): ?string
    {
        return $this->templateId;
    }

    /**
     * @return array<string, mixed>
     */
    public function getTemplateVars(): array
    {
        return $this->templateVars;
    }

    public function getEmailFormat(): Format
    {
        return $this->emailFormat;
    }

    public function setFrom(?AddressInterface $from): self
    {
        $clone = clone $this;
        $clone->from = $from;

        return $clone;
    }

    /**
     * @param array<AddressInterface> $to
     */
    public function setTo(array $to): self
    {
        $clone = clone $this;
        $clone->to = $to;

        return $clone;
    }

    /**
     * @param array<AddressInterface> $replayTo
     */
    public function setReplayTo(array $replayTo): self
    {
        $clone = clone $this;
        $clone->replayTo = $replayTo;

        return $clone;
    }

    /**
     * @param array<AddressInterface> $cc
     */
    public function setCc(array $cc): self
    {
        $clone = clone $this;
        $clone->cc = $cc;

        return $clone;
    }

    /**
     * @param array<AddressInterface> $bcc
     */
    public function setBcc(array $bcc): self
    {
        $clone = clone $this;
        $clone->bcc = $bcc;

        return $clone;
    }

    public function setTemplate(?string $template): self
    {
        $clone = clone $this;
        $clone->templateId = $template;

        return $clone;
    }

    /**
     * @param array<string, mixed> $templateVars
     */
    public function setTemplateVars(array $templateVars): self
    {
        $clone = clone $this;
        $clone->templateVars = $templateVars;

        return $clone;
    }

    public function setEmailFormat(Format $emailFormat): self
    {
        $clone = clone $this;
        $clone->emailFormat = $emailFormat;

        return $clone;
    }
}
