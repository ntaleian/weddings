<?php

declare(strict_types=1);

if (! function_exists('parse_residential_address')) {
    /**
     * Parse stored bride_address / groom_address: JSON object or legacy plain text.
     *
     * @return array{country:string,region:string,district:string,sub_county:string,parish:string,village:string,raw:string,legacy_plain:bool}
     */
    function parse_residential_address(?string $stored): array
    {
        $base = [
            'country'      => '',
            'region'       => '',
            'district'     => '',
            'sub_county'   => '',
            'parish'       => '',
            'village'      => '',
            'raw'          => '',
            'legacy_plain' => false,
        ];

        if ($stored === null || $stored === '') {
            return $base;
        }

        $trimmed = trim($stored);
        $base['raw'] = $trimmed;

        if ($trimmed !== '' && ($trimmed[0] === '{' || $trimmed[0] === '[')) {
            $decoded = json_decode($stored, true);
            if (is_array($decoded)) {
                return [
                    'country'      => (string) ($decoded['country'] ?? ''),
                    'region'       => (string) ($decoded['region'] ?? ''),
                    'district'     => (string) ($decoded['district'] ?? ''),
                    'sub_county'   => (string) ($decoded['sub_county'] ?? ''),
                    'parish'       => (string) ($decoded['parish'] ?? ''),
                    'village'      => (string) ($decoded['village'] ?? ''),
                    'raw'          => $trimmed,
                    'legacy_plain' => false,
                ];
            }
        }

        return array_merge($base, ['legacy_plain' => true]);
    }
}

if (! function_exists('encode_residential_address_from_post')) {
    /**
     * Build JSON string for DB from POST fields bride_res_* or groom_res_*.
     *
     * @param object $request Request object with getPost(string $key): mixed
     * @param 'bride'|'groom' $person
     */
    function encode_residential_address_from_post(object $request, string $person): string
    {
        $p = $person === 'groom' ? 'groom' : 'bride';
        $data = [
            'country'    => trim((string) $request->getPost("{$p}_res_country")),
            'region'     => trim((string) $request->getPost("{$p}_res_region")),
            'district'   => trim((string) $request->getPost("{$p}_res_district")),
            'sub_county' => trim((string) $request->getPost("{$p}_res_sub_county")),
            'parish'     => trim((string) $request->getPost("{$p}_res_parish")),
            'village'    => trim((string) $request->getPost("{$p}_res_village")),
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (! function_exists('format_residential_address_html')) {
    /** Formatted multi-line HTML for admin/user read-only views. */
    function format_residential_address_html(?string $stored): string
    {
        $p = parse_residential_address($stored);

        if ($p['legacy_plain'] && $p['raw'] !== '') {
            return esc($p['raw']);
        }

        $lines = [];
        $map   = [
            'country'    => 'Country',
            'region'     => 'Region',
            'district'   => 'District',
            'sub_county' => 'Sub county',
            'parish'     => 'Parish',
            'village'    => 'Village',
        ];

        foreach ($map as $key => $label) {
            if ($p[$key] !== '') {
                $lines[] = '<strong>' . esc($label) . ':</strong> ' . esc($p[$key]);
            }
        }

        if ($lines === []) {
            return esc($p['raw'] !== '' ? $p['raw'] : 'Not provided');
        }

        return implode('<br>', $lines);
    }
}

if (! function_exists('format_residential_address_plain')) {
    /** Single-line for PDF/JS (escaped for HTML attribute context by caller). */
    function format_residential_address_plain(?string $stored): string
    {
        $p = parse_residential_address($stored);

        if ($p['legacy_plain'] && $p['raw'] !== '') {
            return $p['raw'];
        }

        $parts = [];
        $map   = [
            'country'    => 'Country',
            'region'     => 'Region',
            'district'   => 'District',
            'sub_county' => 'Sub county',
            'parish'     => 'Parish',
            'village'    => 'Village',
        ];

        foreach ($map as $key => $label) {
            if ($p[$key] !== '') {
                $parts[] = $label . ': ' . $p[$key];
            }
        }

        if ($parts === []) {
            return $p['raw'] !== '' ? $p['raw'] : 'Not provided';
        }

        return implode('; ', $parts);
    }
}
