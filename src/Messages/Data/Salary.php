<?php

namespace Kali\MessageBroker\Messages\Data;

use Carbon\CarbonImmutable;

class Salary extends Base
{

    protected string $template = "salary";

    // Дополнительные атрибуты
    public CarbonImmutable $salaryDateCarbon;
    public string $salaryMonthYear;

    /**
     * @param string $salaryDate - 2022-01-01
     */
    public function __construct(public string $salaryDate)
    {
        $this->salaryDateCarbon = CarbonImmutable::createFromFormat("Y-m-d", $this->salaryDate);

        // TODO: Пофиксить русский язык и изменить на ->format("M Y");
        $this->salaryMonthYear = $this->salaryDateCarbon->format("m-Y");
    }

    /**
     * Возращаемые атрибуты, которые используются в уведомлениях и прочее
     * 
     * P.S Атрибуты template и created_at добавляются в абстрактном классе при вызове toMessageData()
     * @return array
     */
    public function toResource(): array
    {
        return [
            "salaryDate" => $this->salaryDate,
            "salaryMonthYear" => $this->salaryMonthYear,
        ];
    }
    
    public static function from(string|array $data) {
        $params = self::prepareParamsFrom($data);

        return new self(
            salaryDate: $params->salaryDate,
        );
    }
}