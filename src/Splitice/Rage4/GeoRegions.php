<?php
namespace SplitIce\Rage4;

class GeoRegions {
	private static $cache = null;
	static function RegionList(Rage4Api $api, $cache = true) {
		if ($cache && self::$cache) {
			return self::$cache;
		}
		
		$ret = array ();
		
		foreach ( $api->getGeoRegions () as $g ) {
			$ret [$g ['name']] = ( int ) $g ['value'];
		}
		
		self::$cache = $ret;
		
		return $ret;
	}
	static function GetCountryZones() {
		return array (
				0 => array (
						'ISO' => 'AF',
						'Name' => 'Afghanistan',
						'Region' => 'Asia',
						'Subregion' => 'Southern Asia' 
				),
				1 => array (
						'ISO' => 'AX',
						'Name' => 'Ã…land Islands',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				2 => array (
						'ISO' => 'AL',
						'Name' => 'Albania',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				3 => array (
						'ISO' => 'DZ',
						'Name' => 'Algeria',
						'Region' => 'Africa',
						'Subregion' => 'Northern Africa' 
				),
				4 => array (
						'ISO' => 'AS',
						'Name' => 'American Samoa',
						'Region' => 'Oceania',
						'Subregion' => 'Polynesia' 
				),
				5 => array (
						'ISO' => 'AD',
						'Name' => 'Andorra',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				6 => array (
						'ISO' => 'AO',
						'Name' => 'Angola',
						'Region' => 'Africa',
						'Subregion' => 'Middle Africa' 
				),
				7 => array (
						'ISO' => 'AI',
						'Name' => 'Anguilla',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				8 => array (
						'ISO' => 'AG',
						'Name' => 'Antigua and Barbuda',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				9 => array (
						'ISO' => 'AR',
						'Name' => 'Argentina',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				10 => array (
						'ISO' => 'AM',
						'Name' => 'Armenia',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				11 => array (
						'ISO' => 'AW',
						'Name' => 'Aruba',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				12 => array (
						'ISO' => 'AU',
						'Name' => 'Australia',
						'Region' => 'Oceania',
						'Subregion' => 'Australia and New Zealand' 
				),
				13 => array (
						'ISO' => 'AT',
						'Name' => 'Austria',
						'Region' => 'Europe',
						'Subregion' => 'Western Europe' 
				),
				14 => array (
						'ISO' => 'AZ',
						'Name' => 'Azerbaijan',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				15 => array (
						'ISO' => 'BS',
						'Name' => 'Bahamas',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				16 => array (
						'ISO' => 'BH',
						'Name' => 'Bahrain',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				17 => array (
						'ISO' => 'BD',
						'Name' => 'Bangladesh',
						'Region' => 'Asia',
						'Subregion' => 'Southern Asia' 
				),
				18 => array (
						'ISO' => 'BB',
						'Name' => 'Barbados',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				19 => array (
						'ISO' => 'BY',
						'Name' => 'Belarus',
						'Region' => 'Europe',
						'Subregion' => 'Eastern Europe' 
				),
				20 => array (
						'ISO' => 'BE',
						'Name' => 'Belgium',
						'Region' => 'Europe',
						'Subregion' => 'Western Europe' 
				),
				21 => array (
						'ISO' => 'BZ',
						'Name' => 'Belize',
						'Region' => 'Americas',
						'Subregion' => 'Central America' 
				),
				22 => array (
						'ISO' => 'BJ',
						'Name' => 'Benin',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				23 => array (
						'ISO' => 'BM',
						'Name' => 'Bermuda',
						'Region' => 'Americas',
						'Subregion' => 'Northern America' 
				),
				24 => array (
						'ISO' => 'BT',
						'Name' => 'Bhutan',
						'Region' => 'Asia',
						'Subregion' => 'Southern Asia' 
				),
				25 => array (
						'ISO' => 'BO',
						'Name' => 'Bolivia, Plurinational State Of',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				26 => array (
						'ISO' => 'BQ',
						'Name' => 'Bonaire, Sint Eustatius and Saba',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				27 => array (
						'ISO' => 'BA',
						'Name' => 'Bosnia and Herzegovina',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				28 => array (
						'ISO' => 'BW',
						'Name' => 'Botswana',
						'Region' => 'Africa',
						'Subregion' => 'Southern Africa' 
				),
				29 => array (
						'ISO' => 'BV',
						'Name' => 'Bouvet Island',
						'Region' => 'Africa',
						'Subregion' => 'Southern Africa' 
				),
				30 => array (
						'ISO' => 'BR',
						'Name' => 'Brazil',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				31 => array (
						'ISO' => 'IO',
						'Name' => 'British Indian Ocean Territory',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				32 => array (
						'ISO' => 'BN',
						'Name' => 'Brunei Darussalam',
						'Region' => 'Asia',
						'Subregion' => 'South-Eastern Asia' 
				),
				33 => array (
						'ISO' => 'BG',
						'Name' => 'Bulgaria',
						'Region' => 'Europe',
						'Subregion' => 'Eastern Europe' 
				),
				34 => array (
						'ISO' => 'BF',
						'Name' => 'Burkina Faso',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				35 => array (
						'ISO' => 'BI',
						'Name' => 'Burundi',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				36 => array (
						'ISO' => 'KH',
						'Name' => 'Cambodia',
						'Region' => 'Asia',
						'Subregion' => 'South-Eastern Asia' 
				),
				37 => array (
						'ISO' => 'CM',
						'Name' => 'Cameroon',
						'Region' => 'Africa',
						'Subregion' => 'Middle Africa' 
				),
				38 => array (
						'ISO' => 'CA',
						'Name' => 'Canada',
						'Region' => 'Americas',
						'Subregion' => 'Northern America' 
				),
				39 => array (
						'ISO' => 'CV',
						'Name' => 'Cape Verde',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				40 => array (
						'ISO' => 'KY',
						'Name' => 'Cayman Islands',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				41 => array (
						'ISO' => 'CF',
						'Name' => 'Central African Republic',
						'Region' => 'Africa',
						'Subregion' => 'Middle Africa' 
				),
				42 => array (
						'ISO' => 'TD',
						'Name' => 'Chad',
						'Region' => 'Africa',
						'Subregion' => 'Middle Africa' 
				),
				43 => array (
						'ISO' => 'CL',
						'Name' => 'Chile',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				44 => array (
						'ISO' => 'CN',
						'Name' => 'China',
						'Region' => 'Asia',
						'Subregion' => 'Eastern Asia' 
				),
				45 => array (
						'ISO' => 'CX',
						'Name' => 'Christmas Island',
						'Region' => 'Oceania',
						'Subregion' => 'Australia and New Zealand' 
				),
				46 => array (
						'ISO' => 'CC',
						'Name' => 'Cocos (Keeling) Islands',
						'Region' => 'Oceania',
						'Subregion' => 'Australia and New Zealand' 
				),
				47 => array (
						'ISO' => 'CO',
						'Name' => 'Colombia',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				48 => array (
						'ISO' => 'KM',
						'Name' => 'Comoros',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				49 => array (
						'ISO' => 'CG',
						'Name' => 'Congo',
						'Region' => 'Africa',
						'Subregion' => 'Middle Africa' 
				),
				50 => array (
						'ISO' => 'CD',
						'Name' => 'Congo, The Democratic Republic Of The',
						'Region' => 'Africa',
						'Subregion' => 'Middle Africa' 
				),
				51 => array (
						'ISO' => 'CK',
						'Name' => 'Cook Islands',
						'Region' => 'Oceania',
						'Subregion' => 'Polynesia' 
				),
				52 => array (
						'ISO' => 'CR',
						'Name' => 'Costa Rica',
						'Region' => 'Americas',
						'Subregion' => 'Central America' 
				),
				53 => array (
						'ISO' => 'CI',
						'Name' => 'CÃ´te D\'Ivoire',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				54 => array (
						'ISO' => 'HR',
						'Name' => 'Croatia',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				55 => array (
						'ISO' => 'CU',
						'Name' => 'Cuba',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				56 => array (
						'ISO' => 'CW',
						'Name' => 'CuraÃ§ao',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				57 => array (
						'ISO' => 'CY',
						'Name' => 'Cyprus',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				58 => array (
						'ISO' => 'CZ',
						'Name' => 'Czech Republic',
						'Region' => 'Europe',
						'Subregion' => 'Eastern Europe' 
				),
				59 => array (
						'ISO' => 'DK',
						'Name' => 'Denmark',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				60 => array (
						'ISO' => 'DJ',
						'Name' => 'Djibouti',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				61 => array (
						'ISO' => 'DM',
						'Name' => 'Dominica',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				62 => array (
						'ISO' => 'DO',
						'Name' => 'Dominican Republic',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				63 => array (
						'ISO' => 'EC',
						'Name' => 'Ecuador',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				64 => array (
						'ISO' => 'EG',
						'Name' => 'Egypt',
						'Region' => 'Africa',
						'Subregion' => 'Northern Africa' 
				),
				65 => array (
						'ISO' => 'SV',
						'Name' => 'El Salvador',
						'Region' => 'Americas',
						'Subregion' => 'Central America' 
				),
				66 => array (
						'ISO' => 'GQ',
						'Name' => 'Equatorial Guinea',
						'Region' => 'Africa',
						'Subregion' => 'Middle Africa' 
				),
				67 => array (
						'ISO' => 'ER',
						'Name' => 'Eritrea',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				68 => array (
						'ISO' => 'EE',
						'Name' => 'Estonia',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				69 => array (
						'ISO' => 'ET',
						'Name' => 'Ethiopia',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				70 => array (
						'ISO' => 'FK',
						'Name' => 'Falkland Islands (Malvinas)',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				71 => array (
						'ISO' => 'FO',
						'Name' => 'Faroe Islands',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				72 => array (
						'ISO' => 'FJ',
						'Name' => 'Fiji',
						'Region' => 'Oceania',
						'Subregion' => 'Melanesia' 
				),
				73 => array (
						'ISO' => 'FI',
						'Name' => 'Finland',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				74 => array (
						'ISO' => 'FR',
						'Name' => 'France',
						'Region' => 'Europe',
						'Subregion' => 'Western Europe' 
				),
				75 => array (
						'ISO' => 'GF',
						'Name' => 'French Guiana',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				76 => array (
						'ISO' => 'PF',
						'Name' => 'French Polynesia',
						'Region' => 'Oceania',
						'Subregion' => 'Polynesia' 
				),
				77 => array (
						'ISO' => 'TF',
						'Name' => 'French Southern Territories',
						'Region' => 'Oceania',
						'Subregion' => 'Australia and New Zealand' 
				),
				78 => array (
						'ISO' => 'GA',
						'Name' => 'Gabon',
						'Region' => 'Africa',
						'Subregion' => 'Middle Africa' 
				),
				79 => array (
						'ISO' => 'GM',
						'Name' => 'Gambia',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				80 => array (
						'ISO' => 'GE',
						'Name' => 'Georgia',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				81 => array (
						'ISO' => 'DE',
						'Name' => 'Germany',
						'Region' => 'Europe',
						'Subregion' => 'Western Europe' 
				),
				82 => array (
						'ISO' => 'GH',
						'Name' => 'Ghana',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				83 => array (
						'ISO' => 'GI',
						'Name' => 'Gibraltar',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				84 => array (
						'ISO' => 'GR',
						'Name' => 'Greece',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				85 => array (
						'ISO' => 'GL',
						'Name' => 'Greenland',
						'Region' => 'Americas',
						'Subregion' => 'Northern America' 
				),
				86 => array (
						'ISO' => 'GD',
						'Name' => 'Grenada',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				87 => array (
						'ISO' => 'GP',
						'Name' => 'Guadeloupe',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				88 => array (
						'ISO' => 'GU',
						'Name' => 'Guam',
						'Region' => 'Oceania',
						'Subregion' => 'Micronesia' 
				),
				89 => array (
						'ISO' => 'GT',
						'Name' => 'Guatemala',
						'Region' => 'Americas',
						'Subregion' => 'Central America' 
				),
				90 => array (
						'ISO' => 'GG',
						'Name' => 'Guernsey',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				91 => array (
						'ISO' => 'GN',
						'Name' => 'Guinea',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				92 => array (
						'ISO' => 'GW',
						'Name' => 'Guinea-Bissau',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				93 => array (
						'ISO' => 'GY',
						'Name' => 'Guyana',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				94 => array (
						'ISO' => 'HT',
						'Name' => 'Haiti',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				95 => array (
						'ISO' => 'HM',
						'Name' => 'Heard Island and McDonald Islands',
						'Region' => 'Oceania',
						'Subregion' => 'Australia and New Zealand' 
				),
				96 => array (
						'ISO' => 'VA',
						'Name' => 'Holy See (Vatican City State)',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				97 => array (
						'ISO' => 'HN',
						'Name' => 'Honduras',
						'Region' => 'Americas',
						'Subregion' => 'Central America' 
				),
				98 => array (
						'ISO' => 'HK',
						'Name' => 'Hong Kong',
						'Region' => 'Asia',
						'Subregion' => 'Eastern Asia' 
				),
				99 => array (
						'ISO' => 'HU',
						'Name' => 'Hungary',
						'Region' => 'Europe',
						'Subregion' => 'Eastern Europe' 
				),
				100 => array (
						'ISO' => 'IS',
						'Name' => 'Iceland',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				101 => array (
						'ISO' => 'IN',
						'Name' => 'India',
						'Region' => 'Asia',
						'Subregion' => 'Southern Asia' 
				),
				102 => array (
						'ISO' => 'ID',
						'Name' => 'Indonesia',
						'Region' => 'Asia',
						'Subregion' => 'South-Eastern Asia' 
				),
				103 => array (
						'ISO' => 'IR',
						'Name' => 'Iran, Islamic Republic Of',
						'Region' => 'Asia',
						'Subregion' => 'Southern Asia' 
				),
				104 => array (
						'ISO' => 'IQ',
						'Name' => 'Iraq',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				105 => array (
						'ISO' => 'IE',
						'Name' => 'Ireland',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				106 => array (
						'ISO' => 'IM',
						'Name' => 'Isle of Man',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				107 => array (
						'ISO' => 'IL',
						'Name' => 'Israel',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				108 => array (
						'ISO' => 'IT',
						'Name' => 'Italy',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				109 => array (
						'ISO' => 'JM',
						'Name' => 'Jamaica',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				110 => array (
						'ISO' => 'JP',
						'Name' => 'Japan',
						'Region' => 'Asia',
						'Subregion' => 'Eastern Asia' 
				),
				111 => array (
						'ISO' => 'JE',
						'Name' => 'Jersey',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				112 => array (
						'ISO' => 'JO',
						'Name' => 'Jordan',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				113 => array (
						'ISO' => 'KZ',
						'Name' => 'Kazakhstan',
						'Region' => 'Asia',
						'Subregion' => 'Central Asia' 
				),
				114 => array (
						'ISO' => 'KE',
						'Name' => 'Kenya',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				115 => array (
						'ISO' => 'KI',
						'Name' => 'Kiribati',
						'Region' => 'Oceania',
						'Subregion' => 'Micronesia' 
				),
				116 => array (
						'ISO' => 'KP',
						'Name' => 'Korea, Democratic People\'s Republic Of',
						'Region' => 'Asia',
						'Subregion' => 'Eastern Asia' 
				),
				117 => array (
						'ISO' => 'KR',
						'Name' => 'Korea, Republic of',
						'Region' => 'Asia',
						'Subregion' => 'Eastern Asia' 
				),
				118 => array (
						'ISO' => 'KW',
						'Name' => 'Kuwait',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				119 => array (
						'ISO' => 'KG',
						'Name' => 'Kyrgyzstan',
						'Region' => 'Asia',
						'Subregion' => 'Central Asia' 
				),
				120 => array (
						'ISO' => 'LA',
						'Name' => 'Lao People\'s Democratic Republic',
						'Region' => 'Asia',
						'Subregion' => 'South-Eastern Asia' 
				),
				121 => array (
						'ISO' => 'LV',
						'Name' => 'Latvia',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				122 => array (
						'ISO' => 'LB',
						'Name' => 'Lebanon',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				123 => array (
						'ISO' => 'LS',
						'Name' => 'Lesotho',
						'Region' => 'Africa',
						'Subregion' => 'Southern Africa' 
				),
				124 => array (
						'ISO' => 'LR',
						'Name' => 'Liberia',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				125 => array (
						'ISO' => 'LY',
						'Name' => 'Libya',
						'Region' => 'Africa',
						'Subregion' => 'Northern Africa' 
				),
				126 => array (
						'ISO' => 'LI',
						'Name' => 'Liechtenstein',
						'Region' => 'Europe',
						'Subregion' => 'Western Europe' 
				),
				127 => array (
						'ISO' => 'LT',
						'Name' => 'Lithuania',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				128 => array (
						'ISO' => 'LU',
						'Name' => 'Luxembourg',
						'Region' => 'Europe',
						'Subregion' => 'Western Europe' 
				),
				129 => array (
						'ISO' => 'MO',
						'Name' => 'Macao',
						'Region' => 'Asia',
						'Subregion' => 'Eastern Asia' 
				),
				130 => array (
						'ISO' => 'MK',
						'Name' => 'Macedonia, the Former Yugoslav Republic Of',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				131 => array (
						'ISO' => 'MG',
						'Name' => 'Madagascar',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				132 => array (
						'ISO' => 'MW',
						'Name' => 'Malawi',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				133 => array (
						'ISO' => 'MY',
						'Name' => 'Malaysia',
						'Region' => 'Asia',
						'Subregion' => 'South-Eastern Asia' 
				),
				134 => array (
						'ISO' => 'MV',
						'Name' => 'Maldives',
						'Region' => 'Asia',
						'Subregion' => 'Southern Asia' 
				),
				135 => array (
						'ISO' => 'ML',
						'Name' => 'Mali',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				136 => array (
						'ISO' => 'MT',
						'Name' => 'Malta',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				137 => array (
						'ISO' => 'MH',
						'Name' => 'Marshall Islands',
						'Region' => 'Oceania',
						'Subregion' => 'Micronesia' 
				),
				138 => array (
						'ISO' => 'MQ',
						'Name' => 'Martinique',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				139 => array (
						'ISO' => 'MR',
						'Name' => 'Mauritania',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				140 => array (
						'ISO' => 'MU',
						'Name' => 'Mauritius',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				141 => array (
						'ISO' => 'YT',
						'Name' => 'Mayotte',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				142 => array (
						'ISO' => 'MX',
						'Name' => 'Mexico',
						'Region' => 'Americas',
						'Subregion' => 'Central America' 
				),
				143 => array (
						'ISO' => 'FM',
						'Name' => 'Micronesia, Federated States Of',
						'Region' => 'Oceania',
						'Subregion' => 'Micronesia' 
				),
				144 => array (
						'ISO' => 'MD',
						'Name' => 'Moldova, Republic of',
						'Region' => 'Europe',
						'Subregion' => 'Eastern Europe' 
				),
				145 => array (
						'ISO' => 'MC',
						'Name' => 'Monaco',
						'Region' => 'Europe',
						'Subregion' => 'Western Europe' 
				),
				146 => array (
						'ISO' => 'MN',
						'Name' => 'Mongolia',
						'Region' => 'Asia',
						'Subregion' => 'Eastern Asia' 
				),
				147 => array (
						'ISO' => 'ME',
						'Name' => 'Montenegro',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				148 => array (
						'ISO' => 'MS',
						'Name' => 'Montserrat',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				149 => array (
						'ISO' => 'MA',
						'Name' => 'Morocco',
						'Region' => 'Africa',
						'Subregion' => 'Northern Africa' 
				),
				150 => array (
						'ISO' => 'MZ',
						'Name' => 'Mozambique',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				151 => array (
						'ISO' => 'MM',
						'Name' => 'Myanmar',
						'Region' => 'Asia',
						'Subregion' => 'South-Eastern Asia' 
				),
				152 => array (
						'ISO' => 'NA',
						'Name' => 'Namibia',
						'Region' => 'Africa',
						'Subregion' => 'Southern Africa' 
				),
				153 => array (
						'ISO' => 'NR',
						'Name' => 'Nauru',
						'Region' => 'Oceania',
						'Subregion' => 'Micronesia' 
				),
				154 => array (
						'ISO' => 'NP',
						'Name' => 'Nepal',
						'Region' => 'Asia',
						'Subregion' => 'Southern Asia' 
				),
				155 => array (
						'ISO' => 'NL',
						'Name' => 'Netherlands',
						'Region' => 'Europe',
						'Subregion' => 'Western Europe' 
				),
				156 => array (
						'ISO' => 'NC',
						'Name' => 'New Caledonia',
						'Region' => 'Oceania',
						'Subregion' => 'Melanesia' 
				),
				157 => array (
						'ISO' => 'NZ',
						'Name' => 'New Zealand',
						'Region' => 'Oceania',
						'Subregion' => 'Australia and New Zealand' 
				),
				158 => array (
						'ISO' => 'NI',
						'Name' => 'Nicaragua',
						'Region' => 'Americas',
						'Subregion' => 'Central America' 
				),
				159 => array (
						'ISO' => 'NE',
						'Name' => 'Niger',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				160 => array (
						'ISO' => 'NG',
						'Name' => 'Nigeria',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				161 => array (
						'ISO' => 'NU',
						'Name' => 'Niue',
						'Region' => 'Oceania',
						'Subregion' => 'Polynesia' 
				),
				162 => array (
						'ISO' => 'NF',
						'Name' => 'Norfolk Island',
						'Region' => 'Oceania',
						'Subregion' => 'Australia and New Zealand' 
				),
				163 => array (
						'ISO' => 'MP',
						'Name' => 'Northern Mariana Islands',
						'Region' => 'Oceania',
						'Subregion' => 'Micronesia' 
				),
				164 => array (
						'ISO' => 'NO',
						'Name' => 'Norway',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				165 => array (
						'ISO' => 'OM',
						'Name' => 'Oman',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				166 => array (
						'ISO' => 'PK',
						'Name' => 'Pakistan',
						'Region' => 'Asia',
						'Subregion' => 'Southern Asia' 
				),
				167 => array (
						'ISO' => 'PW',
						'Name' => 'Palau',
						'Region' => 'Oceania',
						'Subregion' => 'Micronesia' 
				),
				168 => array (
						'ISO' => 'PS',
						'Name' => 'Palestinian Territory, Occupied',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				169 => array (
						'ISO' => 'PA',
						'Name' => 'Panama',
						'Region' => 'Americas',
						'Subregion' => 'Central America' 
				),
				170 => array (
						'ISO' => 'PG',
						'Name' => 'Papua New Guinea',
						'Region' => 'Oceania',
						'Subregion' => 'Melanesia' 
				),
				171 => array (
						'ISO' => 'PY',
						'Name' => 'Paraguay',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				172 => array (
						'ISO' => 'PE',
						'Name' => 'Peru',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				173 => array (
						'ISO' => 'PH',
						'Name' => 'Philippines',
						'Region' => 'Asia',
						'Subregion' => 'South-Eastern Asia' 
				),
				174 => array (
						'ISO' => 'PN',
						'Name' => 'Pitcairn',
						'Region' => 'Oceania',
						'Subregion' => 'Polynesia' 
				),
				175 => array (
						'ISO' => 'PL',
						'Name' => 'Poland',
						'Region' => 'Europe',
						'Subregion' => 'Eastern Europe' 
				),
				176 => array (
						'ISO' => 'PT',
						'Name' => 'Portugal',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				177 => array (
						'ISO' => 'PR',
						'Name' => 'Puerto Rico',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				178 => array (
						'ISO' => 'QA',
						'Name' => 'Qatar',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				179 => array (
						'ISO' => 'RE',
						'Name' => 'RÃ©union',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				180 => array (
						'ISO' => 'RO',
						'Name' => 'Romania',
						'Region' => 'Europe',
						'Subregion' => 'Eastern Europe' 
				),
				181 => array (
						'ISO' => 'RU',
						'Name' => 'Russian Federation',
						'Region' => 'Europe',
						'Subregion' => 'Eastern Europe' 
				),
				182 => array (
						'ISO' => 'RW',
						'Name' => 'Rwanda',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				183 => array (
						'ISO' => 'BL',
						'Name' => 'Saint BarthÃ©lemy',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				184 => array (
						'ISO' => 'SH',
						'Name' => 'Saint Helena, Ascension and Tristan Da Cunha',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				185 => array (
						'ISO' => 'KN',
						'Name' => 'Saint Kitts And Nevis',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				186 => array (
						'ISO' => 'LC',
						'Name' => 'Saint Lucia',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				187 => array (
						'ISO' => 'MF',
						'Name' => 'Saint Martin (French Part)',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				188 => array (
						'ISO' => 'PM',
						'Name' => 'Saint Pierre And Miquelon',
						'Region' => 'Americas',
						'Subregion' => 'Northern America' 
				),
				189 => array (
						'ISO' => 'VC',
						'Name' => 'Saint Vincent And The Grenadines',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				190 => array (
						'ISO' => 'WS',
						'Name' => 'Samoa',
						'Region' => 'Oceania',
						'Subregion' => 'Polynesia' 
				),
				191 => array (
						'ISO' => 'SM',
						'Name' => 'San Marino',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				192 => array (
						'ISO' => 'ST',
						'Name' => 'Sao Tome and Principe',
						'Region' => 'Africa',
						'Subregion' => 'Middle Africa' 
				),
				193 => array (
						'ISO' => 'SA',
						'Name' => 'Saudi Arabia',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				194 => array (
						'ISO' => 'SN',
						'Name' => 'Senegal',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				195 => array (
						'ISO' => 'RS',
						'Name' => 'Serbia',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				196 => array (
						'ISO' => 'SC',
						'Name' => 'Seychelles',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				197 => array (
						'ISO' => 'SL',
						'Name' => 'Sierra Leone',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				198 => array (
						'ISO' => 'SG',
						'Name' => 'Singapore',
						'Region' => 'Asia',
						'Subregion' => 'South-Eastern Asia' 
				),
				199 => array (
						'ISO' => 'SX',
						'Name' => 'Sint Maarten (Dutch part)',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				200 => array (
						'ISO' => 'SK',
						'Name' => 'Slovakia',
						'Region' => 'Europe',
						'Subregion' => 'Eastern Europe' 
				),
				201 => array (
						'ISO' => 'SI',
						'Name' => 'Slovenia',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				202 => array (
						'ISO' => 'SB',
						'Name' => 'Solomon Islands',
						'Region' => 'Oceania',
						'Subregion' => 'Melanesia' 
				),
				203 => array (
						'ISO' => 'SO',
						'Name' => 'Somalia',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				204 => array (
						'ISO' => 'ZA',
						'Name' => 'South Africa',
						'Region' => 'Africa',
						'Subregion' => 'Southern Africa' 
				),
				205 => array (
						'ISO' => 'GS',
						'Name' => 'South Georgia and the South Sandwich Islands',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				206 => array (
						'ISO' => 'SS',
						'Name' => 'South Sudan',
						'Region' => 'Africa',
						'Subregion' => 'Northern Africa' 
				),
				207 => array (
						'ISO' => 'ES',
						'Name' => 'Spain',
						'Region' => 'Europe',
						'Subregion' => 'Southern Europe' 
				),
				208 => array (
						'ISO' => 'LK',
						'Name' => 'Sri Lanka',
						'Region' => 'Asia',
						'Subregion' => 'Southern Asia' 
				),
				209 => array (
						'ISO' => 'SD',
						'Name' => 'Sudan',
						'Region' => 'Africa',
						'Subregion' => 'Northern Africa' 
				),
				210 => array (
						'ISO' => 'SR',
						'Name' => 'Suriname',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				211 => array (
						'ISO' => 'SJ',
						'Name' => 'Svalbard And Jan Mayen',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				212 => array (
						'ISO' => 'SZ',
						'Name' => 'Swaziland',
						'Region' => 'Africa',
						'Subregion' => 'Southern Africa' 
				),
				213 => array (
						'ISO' => 'SE',
						'Name' => 'Sweden',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				214 => array (
						'ISO' => 'CH',
						'Name' => 'Switzerland',
						'Region' => 'Europe',
						'Subregion' => 'Western Europe' 
				),
				215 => array (
						'ISO' => 'SY',
						'Name' => 'Syrian Arab Republic',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				216 => array (
						'ISO' => 'TW',
						'Name' => 'Taiwan, Province Of China',
						'Region' => 'Asia',
						'Subregion' => 'Eastern Asia' 
				),
				217 => array (
						'ISO' => 'TJ',
						'Name' => 'Tajikistan',
						'Region' => 'Asia',
						'Subregion' => 'Central Asia' 
				),
				218 => array (
						'ISO' => 'TZ',
						'Name' => 'Tanzania, United Republic of',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				219 => array (
						'ISO' => 'TH',
						'Name' => 'Thailand',
						'Region' => 'Asia',
						'Subregion' => 'South-Eastern Asia' 
				),
				220 => array (
						'ISO' => 'TL',
						'Name' => 'Timor-Leste',
						'Region' => 'Asia',
						'Subregion' => 'South-Eastern Asia' 
				),
				221 => array (
						'ISO' => 'TG',
						'Name' => 'Togo',
						'Region' => 'Africa',
						'Subregion' => 'Western Africa' 
				),
				222 => array (
						'ISO' => 'TK',
						'Name' => 'Tokelau',
						'Region' => 'Oceania',
						'Subregion' => 'Polynesia' 
				),
				223 => array (
						'ISO' => 'TO',
						'Name' => 'Tonga',
						'Region' => 'Oceania',
						'Subregion' => 'Polynesia' 
				),
				224 => array (
						'ISO' => 'TT',
						'Name' => 'Trinidad and Tobago',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				225 => array (
						'ISO' => 'TN',
						'Name' => 'Tunisia',
						'Region' => 'Africa',
						'Subregion' => 'Northern Africa' 
				),
				226 => array (
						'ISO' => 'TR',
						'Name' => 'Turkey',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				227 => array (
						'ISO' => 'TM',
						'Name' => 'Turkmenistan',
						'Region' => 'Asia',
						'Subregion' => 'Central Asia' 
				),
				228 => array (
						'ISO' => 'TC',
						'Name' => 'Turks and Caicos Islands',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				229 => array (
						'ISO' => 'TV',
						'Name' => 'Tuvalu',
						'Region' => 'Oceania',
						'Subregion' => 'Polynesia' 
				),
				230 => array (
						'ISO' => 'UG',
						'Name' => 'Uganda',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				231 => array (
						'ISO' => 'UA',
						'Name' => 'Ukraine',
						'Region' => 'Europe',
						'Subregion' => 'Eastern Europe' 
				),
				232 => array (
						'ISO' => 'AE',
						'Name' => 'United Arab Emirates',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				233 => array (
						'ISO' => 'GB',
						'Name' => 'United Kingdom',
						'Region' => 'Europe',
						'Subregion' => 'Northern Europe' 
				),
				234 => array (
						'ISO' => 'US',
						'Name' => 'United States',
						'Region' => 'Americas',
						'Subregion' => 'Northern America' 
				),
				235 => array (
						'ISO' => 'UM',
						'Name' => 'United States Minor Outlying Islands',
						'Region' => 'Americas',
						'Subregion' => 'Northern America' 
				),
				236 => array (
						'ISO' => 'UY',
						'Name' => 'Uruguay',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				237 => array (
						'ISO' => 'UZ',
						'Name' => 'Uzbekistan',
						'Region' => 'Asia',
						'Subregion' => 'Central Asia' 
				),
				238 => array (
						'ISO' => 'VU',
						'Name' => 'Vanuatu',
						'Region' => 'Oceania',
						'Subregion' => 'Melanesia' 
				),
				239 => array (
						'ISO' => 'VE',
						'Name' => 'Venezuela, Bolivarian Republic of',
						'Region' => 'Americas',
						'Subregion' => 'South America' 
				),
				240 => array (
						'ISO' => 'VN',
						'Name' => 'Viet Nam',
						'Region' => 'Asia',
						'Subregion' => 'South-Eastern Asia' 
				),
				241 => array (
						'ISO' => 'VG',
						'Name' => 'Virgin Islands, British',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				242 => array (
						'ISO' => 'VI',
						'Name' => 'Virgin Islands, U.S.',
						'Region' => 'Americas',
						'Subregion' => 'Caribbean' 
				),
				243 => array (
						'ISO' => 'WF',
						'Name' => 'Wallis and Futuna',
						'Region' => 'Oceania',
						'Subregion' => 'Polynesia' 
				),
				244 => array (
						'ISO' => 'EH',
						'Name' => 'Western Sahara',
						'Region' => 'Africa',
						'Subregion' => 'Northern Africa' 
				),
				245 => array (
						'ISO' => 'YE',
						'Name' => 'Yemen',
						'Region' => 'Asia',
						'Subregion' => 'Western Asia' 
				),
				246 => array (
						'ISO' => 'ZM',
						'Name' => 'Zambia',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				),
				247 => array (
						'ISO' => 'ZW',
						'Name' => 'Zimbabwe',
						'Region' => 'Africa',
						'Subregion' => 'Eastern Africa' 
				) 
		);
	}
	static function GetUSFederalRegionStates() {
		$ret ['USRegion1'] = array (
				'Connecticut',
				'Maine',
				'Massachusetts',
				'New Hampshire',
				'Rhode Island',
				'Vermont' 
		);
		$ret ['USRegion2'] = array (
				'New Jersey',
				'New York',
				'Puerto Rico',
				'Virgin Islands' 
		);
		$ret ['USRegion3'] = array (
				'Delaware',
				'District of Columbia',
				'Maryland',
				'Pennsylvania',
				'Virginia',
				'West Virginia' 
		);
		$ret ['USRegion4'] = array (
				'Alabama',
				'Florida',
				'Georgia',
				'Kentucky',
				'Mississippi',
				'North Carolina',
				'South Carolina',
				'Tennessee' 
		);
		$ret ['USRegion5'] = array (
				'Illinois',
				'Indiana',
				'Michigan',
				'Minnesota',
				'Ohio',
				'Wisconsin' 
		);
		$ret ['USRegion6'] = array (
				'Arkansas',
				'Louisiana',
				'New Mexico',
				'Oklahoma',
				'Texas' 
		);
		$ret ['USRegion7'] = array (
				'Iowa',
				'Kansas',
				'Missouri',
				'Nebraska' 
		);
		$ret ['USRegion8'] = array (
				'Colorado',
				'Montana',
				'North Dakota',
				'South Dakota',
				'Utah',
				'Wyoming' 
		);
		$ret ['USRegion9'] = array (
				'Arizona',
				'California',
				'Hawaii',
				'Nevada' 
		);
		$ret ['USRegion10'] = array (
				'Alaska',
				'Idaho',
				'Oregon',
				'Washington' 
		);
		
		return $ret;
	}
}