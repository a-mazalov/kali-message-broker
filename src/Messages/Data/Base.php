<?php

namespace Kali\MessageBroker\Messages\Data;

use Kali\MessageBroker\Interfaces\MessageDataInterface;
use Illuminate\Support\Carbon;

abstract class Base implements MessageDataInterface
{
    protected string $template;
    protected string $created_at;

    public static function prepareParamsFrom(string|array $params) {
        if(is_string($params)) {
            return (object) json_decode($params);
        }

        if(is_array($params)) {
            return (object) $params;
        }
    }

    /**
     * Summary of getTemplate
     * @return string
     */
    function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $templateName 
     * @return Base
     */
    function setTemplate(string $template): self
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at 
     * @return Base
     */
    function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function toJson(): string {
        return json_encode($this->toResource());
    }

    /**
     * Возвращает свойства объекта и включает в себя следующие атрибуты по умолчанию @var string template @var string date
     * Используется для создания уведомлений, отправки в database, fcm и другие каналы.
     * 
     * @param bool flat. Параметр преобразует в структуру вида key => value, без вложенностей.
     * @api \Kreait\Firebase\Messaging\MessageData
     * @return array
     */
    public function toNotificationData(bool $flat = false): array
    {
        $attributes = $this->toResource();

        // Дата создания по умолчанию
        $this->setCreatedAt(Carbon::now()->toDateTimeLocalString());

        $attributes['template'] = $this->template;
        $attributes['created_at'] = $this->created_at;

        if ($flat) {
            foreach ($attributes as $key => $value) {
                if (is_array($value)) {
                    $attributes[$key] = json_encode($value);
                }
            }
        }

        return $attributes;
    }
}