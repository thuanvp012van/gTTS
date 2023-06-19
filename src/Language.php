<?php

namespace Thuanvp012van\GTTS;

use ReflectionEnum;

enum Language: string
{
    case AF = 'Afrikaans';
    case AR = 'Arabic';
    case BG = 'Bulgarian';
    case BN = 'Bengali';
    case BS = 'Bosnian';
    case CA = 'Catalan';
    case CS = 'Czech';
    case DA = 'Danish';
    case DE = 'German';
    case EL = 'Greek';
    case EN = 'English';
    case ES = 'Spanish';
    case ET = 'Estonian';
    case FI = 'Finnish';
    case FR = 'French';
    case GU = 'Gujarati';
    case HI = 'Hindi';
    case HR = 'Croatian';
    case HU = 'Hungarian';
    case ID = 'Indonesian';
    case IS = 'Icelandic';
    case IT = 'Italian';
    case IW = 'Hebrew';
    case JA = 'Japanese';
    case JW = 'Javanese';
    case KM = 'Khmer';
    case KN = 'Kannada';
    case KO = 'Korean';
    case LA = 'Latin';
    case LV = 'Latvian';
    case ML = 'Malayalam';
    case MR = 'Marathi';
    case MS = 'Malay';
    case MY = 'Myanmar (Burmese)';
    case NE = 'Nepali';
    case NL = 'Dutch';
    case NO = 'Norwegian';
    case PL = 'Polish';
    case PT = 'Portuguese';
    case RO = 'Romanian';
    case RU = 'Russian';
    case SI = 'Sinhala';
    case SK = 'Slovak';
    case SQ = 'Albanian';
    case SR = 'Serbian';
    case SU = 'Sundanese';
    case SV = 'Swedish';
    case SW = 'Swahili';
    case TA = 'Tamil';
    case TE = 'Telugu';
    case TH = 'Thai';
    case TL = 'Filipino';
    case TR = 'Turkish';
    case UK = 'Ukrainian';
    case UR = 'Urdu';
    case VI = 'Vietnamese';
    case ZHCN = 'Chinese (Simplified)';
    case ZHTW = 'Chinese (Traditional)';
    case ZH = 'Chinese (Mandarin)';

    public function getName(): string
    {
        if ($this->name !== 'ZH' && str_contains($this->name, 'ZH')) {
            return str_replace('ZH', 'zh-', $this->name);
        }
        return strtolower($this->name);
    }

    public static function getCaseByKey(string $key): static
    {
        $key = strtoupper(str_replace('-', '', $key));
        return (new ReflectionEnum(Language::class))->getCase($key)->getValue();
    }
}