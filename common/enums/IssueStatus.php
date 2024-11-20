<?php

namespace common\enums;

enum IssueStatus: int implements DictionaryInterface
{
    use DictionaryTrait;

    case No = 0;
    case Yes = 1;

    /**
     * {@inheritdoc}
     */
    public function description(): string
    {
        return match ($this) {
            self::No => 'Не выдан',
            self::Yes => 'Выдан'
        };
    }

    /**
     * {@inheritdoc}
     */
    public function color(): string
    {
        return match ($this) {
            self::No => 'var(--bs-danger)',
            self::Yes => 'var(--bs-success)'
        };
    }
}
