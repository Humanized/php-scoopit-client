<?php

/**
 * PHP API client bundle for the Scoop.it API.
 * 
 * The third-party tool provides API operations allowing access to the raw data,
 * using programming code.
 * Operations supported are listed on http://www.scoop.it/dev/api/1/
 *
 * @author Jeffrey Geyssens
 * @package php-scoopit-client
 * @version 1.0
 * @link https://github.com/humanized/scoopit-php-client
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * 
 */

namespace PhpScoopitClient;

class ResponseFormatter
{

    /**
     * 
     * @param type $response
     * @return type
     */
    public static function decodeBodyContents($response)
    {
        return \GuzzleHttp\json_decode($response->getBody()->getContents());
    }

}
