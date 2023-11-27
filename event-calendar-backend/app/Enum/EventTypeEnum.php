<?php

namespace App\Enum;

enum EventTypeEnum:string
{
    case MeetingWithAnExpert = 'expert';
    case QuestionAnswer = 'qa';
    case Conference = 'conference';
    case Webinar = 'webinar';
}
