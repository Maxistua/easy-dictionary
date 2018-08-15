<?php

namespace EasyDictionary;

use EasyDictionary\Exception\InvalidConfigurationException;
use EasyDictionary\Exception\RuntimeException;

class Repository
{
    protected $dictionaries = [];

    public $defaultDictionaryType = 'EasyDictionary\DictionaryType\Simple';
    public $defaultDataProvider = 'EasyDictionary\DataProvider\FromArray';

    protected $config = [
        'dictionaries' => [
            'format' => [
                'data' => [
                    'items' => [
                        0 => 'a',
                        1 => 'b',
                        2 => 'c',
                        3 => 'd',
                        4 => 'e',
                        5 => 'f',
                    ]
                ]
            ],
            'country' => [
                'data' => [
                    'items' => [
                        0 => ['code' => '0', 'name' => 'Unknown'],
                        1 => ['code' => 'A1', 'name' => 'Anonymous Proxy'],
                        2 => ['code' => 'A2', 'name' => 'Satellite Provider'],
                        3 => ['code' => 'O1', 'name' => 'Other Country'],
                        4 => ['code' => 'AD', 'name' => 'Andorra'],
                        5 => ['code' => 'AE', 'name' => 'United Arab Emirates'],
                        6 => ['code' => 'AF', 'name' => 'Afghanistan'],
                        7 => ['code' => 'AG', 'name' => 'Antigua and Barbuda'],
                        8 => ['code' => 'AI', 'name' => 'Anguilla'],
                        9 => ['code' => 'AL', 'name' => 'Albania'],
                        10 => ['code' => 'AM', 'name' => 'Armenia'],
                        11 => ['code' => 'AO', 'name' => 'Angola'],
                        12 => ['code' => 'AP', 'name' => 'Asia/Pacific Region Pacific'],
                        13 => ['code' => 'AQ', 'name' => 'Antarctica'],
                        14 => ['code' => 'AR', 'name' => 'Argentina'],
                        15 => ['code' => 'AS', 'name' => 'American Samoa'],
                        16 => ['code' => 'AT', 'name' => 'Austria'],
                        17 => ['code' => 'AU', 'name' => 'Australia'],
                        18 => ['code' => 'AW', 'name' => 'Aruba'],
                        19 => ['code' => 'AX', 'name' => 'Aland Islands'],
                        20 => ['code' => 'AZ', 'name' => 'Azerbaijan'],
                        21 => ['code' => 'BA', 'name' => 'Bosnia and Herzegovina'],
                        22 => ['code' => 'BB', 'name' => 'Barbados'],
                        23 => ['code' => 'BD', 'name' => 'Bangladesh'],
                        24 => ['code' => 'BE', 'name' => 'Belgium'],
                        25 => ['code' => 'BF', 'name' => 'Burkina Faso'],
                        26 => ['code' => 'BG', 'name' => 'Bulgaria'],
                        27 => ['code' => 'BH', 'name' => 'Bahrain'],
                        28 => ['code' => 'BI', 'name' => 'Burundi'],
                        29 => ['code' => 'BJ', 'name' => 'Benin'],
                        30 => ['code' => 'BL', 'name' => 'Saint Bartelemey'],
                        31 => ['code' => 'BM', 'name' => 'Bermuda'],
                        32 => ['code' => 'BN', 'name' => 'Brunei Darussalam'],
                        33 => ['code' => 'BO', 'name' => 'Bolivia'],
                        34 => ['code' => 'BQ', 'name' => 'Bonaire, Saint Eustatius and Saba'],
                        35 => ['code' => 'BR', 'name' => 'Brazil'],
                        36 => ['code' => 'BS', 'name' => 'Bahamas'],
                        37 => ['code' => 'BT', 'name' => 'Bhutan'],
                        38 => ['code' => 'BV', 'name' => 'Bouvet Island'],
                        39 => ['code' => 'BW', 'name' => 'Botswana'],
                        40 => ['code' => 'BY', 'name' => 'Belarus'],
                        41 => ['code' => 'BZ', 'name' => 'Belize'],
                        42 => ['code' => 'CA', 'name' => 'Canada'],
                        43 => ['code' => 'CC', 'name' => 'Cocos (Keeling) Islands'],
                        44 => ['code' => 'CD', 'name' => 'Congo, The Democratic Republic of the'],
                        45 => ['code' => 'CF', 'name' => 'Central African Republic'],
                        46 => ['code' => 'CG', 'name' => 'Congo'],
                        47 => ['code' => 'CH', 'name' => 'Switzerland'],
                        48 => ['code' => 'CI', 'name' => 'Cote d\'Ivoire'],
                        49 => ['code' => 'CK', 'name' => 'Cook Islands'],
                        50 => ['code' => 'CL', 'name' => 'Chile'],
                        51 => ['code' => 'CM', 'name' => 'Cameroon'],
                        52 => ['code' => 'CN', 'name' => 'China'],
                        53 => ['code' => 'CO', 'name' => 'Colombia'],
                        54 => ['code' => 'CR', 'name' => 'Costa Rica'],
                        55 => ['code' => 'CU', 'name' => 'Cuba'],
                        56 => ['code' => 'CV', 'name' => 'Cape Verde'],
                        57 => ['code' => 'CW', 'name' => 'Curacao'],
                        58 => ['code' => 'CX', 'name' => 'Christmas Island'],
                        59 => ['code' => 'CY', 'name' => 'Cyprus'],
                        60 => ['code' => 'CZ', 'name' => 'Czech Republic'],
                        61 => ['code' => 'DE', 'name' => 'Germany'],
                        62 => ['code' => 'DJ', 'name' => 'Djibouti'],
                        63 => ['code' => 'DK', 'name' => 'Denmark'],
                        64 => ['code' => 'DM', 'name' => 'Dominica'],
                        65 => ['code' => 'DO', 'name' => 'Dominican Republic'],
                        66 => ['code' => 'DZ', 'name' => 'Algeria'],
                        67 => ['code' => 'EC', 'name' => 'Ecuador'],
                        68 => ['code' => 'EE', 'name' => 'Estonia'],
                        69 => ['code' => 'EG', 'name' => 'Egypt'],
                        70 => ['code' => 'EH', 'name' => 'Western Sahara'],
                        71 => ['code' => 'ER', 'name' => 'Eritrea'],
                        72 => ['code' => 'ES', 'name' => 'Spain'],
                        73 => ['code' => 'ET', 'name' => 'Ethiopia'],
                        74 => ['code' => 'EU', 'name' => 'Europe'],
                        75 => ['code' => 'FI', 'name' => 'Finland'],
                        76 => ['code' => 'FJ', 'name' => 'Fiji'],
                        77 => ['code' => 'FK', 'name' => 'Falkland Islands (Malvinas)'],
                        78 => ['code' => 'FM', 'name' => 'Micronesia, Federated States of'],
                        79 => ['code' => 'FO', 'name' => 'Faroe Islands'],
                        80 => ['code' => 'FR', 'name' => 'France'],
                        81 => ['code' => 'GA', 'name' => 'Gabon'],
                        82 => ['code' => 'GB', 'name' => 'United Kingdom'],
                        83 => ['code' => 'GD', 'name' => 'Grenada'],
                        84 => ['code' => 'GE', 'name' => 'Georgia'],
                        85 => ['code' => 'GF', 'name' => 'French Guiana'],
                        86 => ['code' => 'GG', 'name' => 'Guernsey'],
                        87 => ['code' => 'GH', 'name' => 'Ghana'],
                        88 => ['code' => 'GI', 'name' => 'Gibraltar'],
                        89 => ['code' => 'GL', 'name' => 'Greenland'],
                        90 => ['code' => 'GM', 'name' => 'Gambia'],
                        91 => ['code' => 'GN', 'name' => 'Guinea'],
                        92 => ['code' => 'GP', 'name' => 'Guadeloupe'],
                        93 => ['code' => 'GQ', 'name' => 'Equatorial Guinea'],
                        94 => ['code' => 'GR', 'name' => 'Greece'],
                        95 => ['code' => 'GS', 'name' => 'South Georgia and the South Sandwich Islands'],
                        96 => ['code' => 'GT', 'name' => 'Guatemala'],
                        97 => ['code' => 'GU', 'name' => 'Guam'],
                        98 => ['code' => 'GW', 'name' => 'Guinea-Bissau'],
                        99 => ['code' => 'GY', 'name' => 'Guyana'],
                        100 => ['code' => 'HK', 'name' => 'Hong Kong'],
                        101 => ['code' => 'HM', 'name' => 'Heard Island and McDonald Islands'],
                        102 => ['code' => 'HN', 'name' => 'Honduras'],
                        103 => ['code' => 'HR', 'name' => 'Croatia'],
                        104 => ['code' => 'HT', 'name' => 'Haiti'],
                        105 => ['code' => 'HU', 'name' => 'Hungary'],
                        106 => ['code' => 'ID', 'name' => 'Indonesia'],
                        107 => ['code' => 'IE', 'name' => 'Ireland'],
                        108 => ['code' => 'IL', 'name' => 'Israel'],
                        109 => ['code' => 'IM', 'name' => 'Isle of Man'],
                        110 => ['code' => 'IN', 'name' => 'India'],
                        111 => ['code' => 'IO', 'name' => 'British Indian Ocean Territory'],
                        112 => ['code' => 'IQ', 'name' => 'Iraq'],
                        113 => ['code' => 'IR', 'name' => 'Iran, Islamic Republic of'],
                        114 => ['code' => 'IS', 'name' => 'Iceland'],
                        115 => ['code' => 'IT', 'name' => 'Italy'],
                        116 => ['code' => 'JE', 'name' => 'Jersey'],
                        117 => ['code' => 'JM', 'name' => 'Jamaica'],
                        118 => ['code' => 'JO', 'name' => 'Jordan'],
                        119 => ['code' => 'JP', 'name' => 'Japan'],
                        120 => ['code' => 'KE', 'name' => 'Kenya'],
                        121 => ['code' => 'KG', 'name' => 'Kyrgyzstan'],
                        122 => ['code' => 'KH', 'name' => 'Cambodia'],
                        123 => ['code' => 'KI', 'name' => 'Kiribati'],
                        124 => ['code' => 'KM', 'name' => 'Comoros'],
                        125 => ['code' => 'KN', 'name' => 'Saint Kitts and Nevis'],
                        126 => ['code' => 'KP', 'name' => 'Korea, Democratic People\'s Republic of'],
                        127 => ['code' => 'KR', 'name' => 'Korea, Republic of'],
                        128 => ['code' => 'KW', 'name' => 'Kuwait'],
                        129 => ['code' => 'KY', 'name' => 'Cayman Islands'],
                        130 => ['code' => 'KZ', 'name' => 'Kazakhstan'],
                        131 => ['code' => 'LA', 'name' => 'Lao People\'s Democratic Republic'],
                        132 => ['code' => 'LB', 'name' => 'Lebanon'],
                        133 => ['code' => 'LC', 'name' => 'Saint Lucia'],
                        134 => ['code' => 'LI', 'name' => 'Liechtenstein'],
                        135 => ['code' => 'LK', 'name' => 'Sri Lanka'],
                        136 => ['code' => 'LR', 'name' => 'Liberia'],
                        137 => ['code' => 'LS', 'name' => 'Lesotho'],
                        138 => ['code' => 'LT', 'name' => 'Lithuania'],
                        139 => ['code' => 'LU', 'name' => 'Luxembourg'],
                        140 => ['code' => 'LV', 'name' => 'Latvia'],
                        141 => ['code' => 'LY', 'name' => 'Libyan Arab Jamahiriya'],
                        142 => ['code' => 'MA', 'name' => 'Morocco'],
                        143 => ['code' => 'MC', 'name' => 'Monaco'],
                        144 => ['code' => 'MD', 'name' => 'Moldova, Republic of'],
                        145 => ['code' => 'ME', 'name' => 'Montenegro'],
                        146 => ['code' => 'MF', 'name' => 'Saint Martin'],
                        147 => ['code' => 'MG', 'name' => 'Madagascar'],
                        148 => ['code' => 'MH', 'name' => 'Marshall Islands'],
                        149 => ['code' => 'MK', 'name' => 'Macedonia'],
                        150 => ['code' => 'ML', 'name' => 'Mali'],
                        151 => ['code' => 'MM', 'name' => 'Myanmar'],
                        152 => ['code' => 'MN', 'name' => 'Mongolia'],
                        153 => ['code' => 'MO', 'name' => 'Macao'],
                        154 => ['code' => 'MP', 'name' => 'Northern Mariana Islands'],
                        155 => ['code' => 'MQ', 'name' => 'Martinique'],
                        156 => ['code' => 'MR', 'name' => 'Mauritania'],
                        157 => ['code' => 'MS', 'name' => 'Montserrat'],
                        158 => ['code' => 'MT', 'name' => 'Malta'],
                        159 => ['code' => 'MU', 'name' => 'Mauritius'],
                        160 => ['code' => 'MV', 'name' => 'Maldives'],
                        161 => ['code' => 'MW', 'name' => 'Malawi'],
                        162 => ['code' => 'MX', 'name' => 'Mexico'],
                        163 => ['code' => 'MY', 'name' => 'Malaysia'],
                        164 => ['code' => 'MZ', 'name' => 'Mozambique'],
                        165 => ['code' => 'NA', 'name' => 'Namibia'],
                        166 => ['code' => 'NC', 'name' => 'New Caledonia'],
                        167 => ['code' => 'NE', 'name' => 'Niger'],
                        168 => ['code' => 'NF', 'name' => 'Norfolk Island'],
                        169 => ['code' => 'NG', 'name' => 'Nigeria'],
                        170 => ['code' => 'NI', 'name' => 'Nicaragua'],
                        171 => ['code' => 'NL', 'name' => 'Netherlands'],
                        172 => ['code' => 'NO', 'name' => 'Norway'],
                        173 => ['code' => 'NP', 'name' => 'Nepal'],
                        174 => ['code' => 'NR', 'name' => 'Nauru'],
                        175 => ['code' => 'NU', 'name' => 'Niue'],
                        176 => ['code' => 'NZ', 'name' => 'New Zealand'],
                        177 => ['code' => 'OM', 'name' => 'Oman'],
                        178 => ['code' => 'PA', 'name' => 'Panama'],
                        179 => ['code' => 'PE', 'name' => 'Peru'],
                        180 => ['code' => 'PF', 'name' => 'French Polynesia'],
                        181 => ['code' => 'PG', 'name' => 'Papua New Guinea'],
                        182 => ['code' => 'PH', 'name' => 'Philippines'],
                        183 => ['code' => 'PK', 'name' => 'Pakistan'],
                        184 => ['code' => 'PL', 'name' => 'Poland'],
                        185 => ['code' => 'PM', 'name' => 'Saint Pierre and Miquelon'],
                        186 => ['code' => 'PN', 'name' => 'Pitcairn'],
                        187 => ['code' => 'PR', 'name' => 'Puerto Rico'],
                        188 => ['code' => 'PS', 'name' => 'Palestinian Territory'],
                        189 => ['code' => 'PT', 'name' => 'Portugal'],
                        190 => ['code' => 'PW', 'name' => 'Palau'],
                        191 => ['code' => 'PY', 'name' => 'Paraguay'],
                        192 => ['code' => 'QA', 'name' => 'Qatar'],
                        193 => ['code' => 'RE', 'name' => 'Reunion'],
                        194 => ['code' => 'RO', 'name' => 'Romania'],
                        195 => ['code' => 'RS', 'name' => 'Serbia'],
                        196 => ['code' => 'RU', 'name' => 'Russian Federation'],
                        197 => ['code' => 'RW', 'name' => 'Rwanda'],
                        198 => ['code' => 'SA', 'name' => 'Saudi Arabia'],
                        199 => ['code' => 'SB', 'name' => 'Solomon Islands'],
                        200 => ['code' => 'SC', 'name' => 'Seychelles'],
                        201 => ['code' => 'SD', 'name' => 'Sudan'],
                        202 => ['code' => 'SE', 'name' => 'Sweden'],
                        203 => ['code' => 'SG', 'name' => 'Singapore'],
                        204 => ['code' => 'SH', 'name' => 'Saint Helena'],
                        205 => ['code' => 'SI', 'name' => 'Slovenia'],
                        206 => ['code' => 'SJ', 'name' => 'Svalbard and Jan Mayen'],
                        207 => ['code' => 'SK', 'name' => 'Slovakia'],
                        208 => ['code' => 'SL', 'name' => 'Sierra Leone'],
                        209 => ['code' => 'SM', 'name' => 'San Marino'],
                        210 => ['code' => 'SN', 'name' => 'Senegal'],
                        211 => ['code' => 'SO', 'name' => 'Somalia'],
                        212 => ['code' => 'SR', 'name' => 'Suriname'],
                        213 => ['code' => 'ST', 'name' => 'Sao Tome and Principe'],
                        214 => ['code' => 'SV', 'name' => 'El Salvador'],
                        215 => ['code' => 'SX', 'name' => 'Sint Maarten'],
                        216 => ['code' => 'SY', 'name' => 'Syrian Arab Republic'],
                        217 => ['code' => 'SZ', 'name' => 'Swaziland'],
                        218 => ['code' => 'TC', 'name' => 'Turks and Caicos Islands'],
                        219 => ['code' => 'TD', 'name' => 'Chad'],
                        220 => ['code' => 'TF', 'name' => 'French Southern Territories'],
                        221 => ['code' => 'TG', 'name' => 'Togo'],
                        222 => ['code' => 'TH', 'name' => 'Thailand'],
                        223 => ['code' => 'TJ', 'name' => 'Tajikistan'],
                        224 => ['code' => 'TK', 'name' => 'Tokelau'],
                        225 => ['code' => 'TL', 'name' => 'Timor-Leste\'Leste'],
                        226 => ['code' => 'TM', 'name' => 'Turkmenistan'],
                        227 => ['code' => 'TN', 'name' => 'Tunisia'],
                        228 => ['code' => 'TO', 'name' => 'Tonga'],
                        229 => ['code' => 'TR', 'name' => 'Turkey'],
                        230 => ['code' => 'TT', 'name' => 'Trinidad and Tobago'],
                        231 => ['code' => 'TV', 'name' => 'Tuvalu'],
                        232 => ['code' => 'TW', 'name' => 'Taiwan'],
                        233 => ['code' => 'TZ', 'name' => 'Tanzania, United Republic of'],
                        234 => ['code' => 'UA', 'name' => 'Ukraine'],
                        235 => ['code' => 'UG', 'name' => 'Uganda'],
                        236 => ['code' => 'UM', 'name' => 'United States Minor Outlying Islands'],
                        237 => ['code' => 'US', 'name' => 'United States'],
                        238 => ['code' => 'UY', 'name' => 'Uruguay'],
                        239 => ['code' => 'UZ', 'name' => 'Uzbekistan'],
                        240 => ['code' => 'VA', 'name' => 'Holy See (Vatican City State)'],
                        241 => ['code' => 'VC', 'name' => 'Saint Vincent and the Grenadines'],
                        242 => ['code' => 'VE', 'name' => 'Venezuela'],
                        243 => ['code' => 'VG', 'name' => 'Virgin Islands, British'],
                        244 => ['code' => 'VI', 'name' => 'Virgin Islands, U.S.'],
                        245 => ['code' => 'VN', 'name' => 'Vietnam'],
                        246 => ['code' => 'VU', 'name' => 'Vanuatu'],
                        247 => ['code' => 'WF', 'name' => 'Wallis and Futuna'],
                        248 => ['code' => 'WS', 'name' => 'Samoa'],
                        249 => ['code' => 'YE', 'name' => 'Yemen'],
                        250 => ['code' => 'YT', 'name' => 'Mayotte'],
                        251 => ['code' => 'ZA', 'name' => 'South Africa'],
                        252 => ['code' => 'ZM', 'name' => 'Zambia'],
                        253 => ['code' => 'ZW', 'name' => 'Zimbabwe'],
                        254 => ['code' => 'ZZ', 'name' => 'All countries'],
                        255 => ['code' => 'SS', 'name' => 'South Sudan'],
                        256 => ['code' => 'XK', 'name' => 'Kosovo'],
                    ]
                ]
            ]
        ]
    ];

    /**
     * @param DictionaryInterface $dictionary
     * @return Repository
     * @throws \Exception
     */
    public function add(DictionaryInterface $dictionary)
    {
        $name = $dictionary->getName();

        if (isset($this->dictionaries[$name])) {
            throw new \Exception(sprintf('The key "%s" already exists', $name));
        }

        $this->dictionaries[$name] = $dictionary;

        return $this;
    }

    /**
     * @param string $name
     * @return DictionaryInterface
     * @throws \Exception
     */
    public function get(string $name):DictionaryInterface
    {
        if (!isset($this->dictionaries[$name])) {
            $config = $this->config['dictionaries'][$name] ?? null;
            if (!$config) {
                throw new RuntimeException(sprintf('Dictionary with key "%s" not found', $name));
            }

            $dictionaryClass = $config['class'] ?? $this->defaultDictionaryType;
            if (!class_exists($dictionaryClass)) {
                throw new InvalidConfigurationException(sprintf('Class "%s" not found', $dictionaryClass));
            }

            $dataProviderClass = $config['data']['class'] ?? $this->defaultDataProvider;
            if (!class_exists($dataProviderClass)) {
                throw new InvalidConfigurationException(sprintf('Class "%s" not found', $dataProviderClass));
            }

            $dataProvider = new $dataProviderClass($config['data']);

            /** @var DictionaryInterface $dictionary */
            $dictionary = new $dictionaryClass($name);
            $dictionary->setDataProvider($dataProvider);

            $this->dictionaries[$name] = $dictionary;
        }

        return $this->dictionaries[$name];
    }
}
