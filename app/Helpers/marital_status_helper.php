<?php

declare(strict_types=1);

if (! function_exists('witness_marital_status_options')) {
    /**
     * Value => label for best man / matron marital status.
     *
     * @return array<string, string>
     */
    function witness_marital_status_options(): array
    {
        return [
            'single'  => 'Single',
            'married' => 'Married',
        ];
    }
}

if (! function_exists('witness_marital_status_label')) {
    function witness_marital_status_label(?string $value): string
    {
        if ($value === null || $value === '') {
            return 'Not provided';
        }

        $opts = witness_marital_status_options();
        if (isset($opts[$value])) {
            return $opts[$value];
        }

        // Legacy relationship field values
        $legacy = [
            'bachelor'              => 'Bachelor',
            'spinster'              => 'Spinster',
            'divorced-separated'    => 'Divorced/Separated',
            'married-traditionally' => 'Married Traditionally',
            'widowed'               => 'Widowed',
            'civil-marriage'        => 'Civil Marriage',
            'cohabiting'            => 'Cohabiting',
            'family'        => 'Family Member (previous field)',
            'friend'        => 'Friend (previous field)',
            'colleague'     => 'Colleague (previous field)',
            'church-member' => 'Church member (previous field)',
            'other'         => 'Other (previous field)',
        ];

        return $legacy[$value] ?? ucfirst(str_replace('-', ' ', $value));
    }
}

if (! function_exists('parent_living_label')) {
    function parent_living_label(?string $status): string
    {
        return match ($status) {
            'alive'    => 'Alive',
            'deceased' => 'Deceased',
            default    => 'Not specified',
        };
    }
}
