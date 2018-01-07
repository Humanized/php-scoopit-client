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
 * @link https://github.com/humanized/php-scoopit-client
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * 
 */

namespace PhpScoopitClient;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

class Client extends \GuzzleHttp\Client
{

    const AVAILABLE_GET_REQUESTS = [
        'profile', 'topic', 'post', 'test', 'notification',  'compilation',
        'search', 'resolver', 'interest', 'interestList', 'sse', 'se', 'sugs'
    ];
    const AVAILABLE_POST_REQUESTS = [
        //Actions related to topic URL
        'editTopic', 'reorderTopic', 'followTopic', 'unfollowTopic', 'markreadTopic',
        //Actions related to post URL
        'preparePost', 'createPost', 'commentPost', 'thankPost', 'acceptPost',
        'forwardPost', 'refusePost', 'deletePost', 'editPost', 'pinPost', 'rescoopPost',
        //Actions related to suggestion engine
        'configureSse', 'configureSe', 'listSugs'
    ];

    /**
     *
     * @var boolean - by default client response is wrapped 
     */
    public $decodeBodyContents = true;
    
    /**
     *
     * @var type 
     */
    public $_stack;

    /**
     *
     * @var type 
     */
    private $_middlewareConfig = [];

    /*
     * =========================================================================
     *                          Client Constructor
     * =========================================================================
     */

    /**
     * 
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        $config['base_uri'] = isset($config['base_uri']) ? $config['base_uri'] : 'https://www.scoop.it/api/1/';
        $this->_middlewareConfig = [
            'consumer_key' => $config['consumer_key'],
            'consumer_secret' => $config['consumer_secret'],
            'token' => isset($config['token']) ? $config['token'] : NULL,
            'token_secret' => isset($config['token_secret']) ? $config['token_secret'] : NULL
        ];
        $middleware = new Oauth1($this->_middlewareConfig);

        $this->_stack = HandlerStack::create();
        $this->_stack->push($middleware);

        $config['handler'] = &$this->_stack;
        $config['auth'] = 'oauth';
     
        parent::__construct($config);
    }

    /**
     * 
     * @param string $method
     * @param string[] $args
     * @return type
     */
    public function __call($method, $args)
    {
        $response = $this->_performRequest($method, $args);
        if (isset($response)) {
            return $this->decodeBodyContents? \GuzzleHttp\json_decode($response->getBody()->getContents()):$response;
        }
        return parent::__call($method, $args);
    }

    private function _performRequest($method, $args)
    {
        $q = [];
        if (count($args) > 0) {
            $q = $args[0];
        }
        if (in_array($method, self::AVAILABLE_GET_REQUESTS)) {
            return $this->get(($method=='intrestList'?'intrest-list':$method), ['query' => $q]);
        }
        if (in_array($method, self::AVAILABLE_POST_REQUESTS)) {
            foreach (self::AVAILABLE_GET_REQUESTS as $r) {
                $pos = strpos(strtolower($method), $r);
                if ($pos !== FALSE) {
                    return $this->post($r, array_merge(['action' => substr($method, 0, $pos)], $q));
                }
            }
        }
        return null;
    }
}
