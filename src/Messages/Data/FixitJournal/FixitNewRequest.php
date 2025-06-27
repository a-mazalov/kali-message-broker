<?php

namespace Kali\MessageBroker\Messages\Data\FixitJournal;
use Kali\MessageBroker\Messages\Data\Base;

/**
 * Сообщение об новой заявке в журнале Fixit (ремонт в зданиях и управления)
 * 
 * @property string $username - табельный 
 * @property string $requestId - номер заявки
 */
class FixitNewRequest extends Base
{
  protected string $template = "fixit-new-request";

  public function __construct(
    public string $username,
    public string $requestId,
    public string $created_at
  ) {
  }

  public function toResource(): array
  {
    return [
      "username" => $this->username,
      "requestId" => $this->requestId,
      "created_at" => $this->created_at,
    ];
  }

  public static function from(string|array $data)
  {
    $params = self::prepareParamsFrom($data);

    return new self(
      username: $params->username,
      requestId: $params->requestId,
      created_at: $params->created_at,
    );
  }
}