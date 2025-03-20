<?php

namespace Kali\MessageBroker\Messages\Data;
use DateTime;
use Illuminate\Support\Carbon;

class CourseEnrollment extends Base
{
    protected string $template = "course-enrollment";

    public function __construct(
        public string $id,
        public string $username,
        public string $courseName,
        public DateTime|null $startDate,
        public DateTime|null $endDate
    ) {}

    public function toResource(): array
    {
        return [
            "id" => $this->id,
            "username" => $this->username,
            "courseName" => $this->courseName,
            "startDate" => $this->startDate?->format('d.m.Y'),
            "endDate" => $this->endDate?->format('d.m.Y')
        ];
    }

    public static function from(string|array $data)
    {
        $params = self::prepareParamsFrom($data);

        return new self(
            id: $params->id,
            username: $params->username,
            courseName: $params->courseName,
            startDate: $params->startDate ? Carbon::parse($params->startDate) : null,
            endDate: $params->endDate ? Carbon::parse($params->endDate) : null
        );
    }
}