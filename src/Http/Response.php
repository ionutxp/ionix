<?php namespace Ionix\Http;

use ArrayObject;
use Ionix\Support\Interfaces\Renderable;

class Response
{

    /**
     * Headers that will be sent via the response
     *
     * @var ParamBag
     */
    public $headers;

    /**
     * The content that will be echoed
     *
     * @var string
     */
    protected $content;

    /**
     * Page response code
     *
     * @var int
     */
    protected $status;

    /**
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function __construct($content = '', $status = 200, $headers = [])
    {
        $this->content = $content;
        $this->setStatusCode($status);
        $this->headers = new ParamBag($headers);
    }

    /**
     * Creates a new request
     *
     * @param $content
     * @param int $status
     * @param array $headers
     * @return static
     */
    public static function make($content, $status = 200, $headers = [])
    {
        return new static($content, $status, $headers);
    }

    /**
     * Prepare content to be printed out
     *
     * @return string
     */
    public function prepareContent()
    {
        $content = $this->content;

        if ($content instanceof Renderable) {
            $content = $content->render();
        } else if ($this->shouldBeJson($content)) {
            $content = $this->toJson($content);
        }

        return $content;
    }

    /**
     * Check if content should be converted to json
     *
     * @param $content
     * @return bool
     */
    public function shouldBeJson($content)
    {
        return is_array($content) || $content instanceof ArrayObject;
    }

    /**
     * Transform array or Jsonable objects to json
     *
     * @param $content
     * @return string
     */
    public function toJson($content)
    {
        return json_encode($content);
    }

    /**
     * Set status code
     *
     * @param $code
     */
    public function setStatusCode($code)
    {
        $this->status = $code;
    }

    /**
     * Send the header to browser
     */
    public function sendHeaders()
    {
        foreach ($this->headers->all() as $header => $value) {
            header(sprintf('%s:%s', $header, $value));
        }
    }

    /**
     * Send the response (headers, status code and content)
     */
    public function send()
    {
        if ( ! headers_sent()) {
            $this->sendHeaders();
        }
        http_response_code($this->status);
        echo $this->prepareContent($this->content);
    }
}