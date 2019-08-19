<?php

namespace App\Enums;

final class DatabaseFieldType extends Enum
{
    const PlainText = 'plain-text';
    const RichText = 'rich-text';
    const Image = 'image';
    const Video = 'video';
    const URL = 'url';
    const Email = 'email';
    const Phone = 'phone';
    const Number = 'number';
    const DateTime = 'date-time';
    const Switch = 'switch';
    const Option = 'option';
    const Color = 'color';
    const File = 'file';
    const Reference = 'reference';
}
