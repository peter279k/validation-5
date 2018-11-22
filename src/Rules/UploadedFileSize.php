<?php

namespace Rakit\Validation\Rules;

use Rakit\Validation\Rule;
use Rakit\Validation\MimeTypeGuesser;

class UploadedFileSize extends Rule
{
    use FileTrait;

    /** @var string */
    protected $message = "The :attribute is not valid";

    /** @var string|int */
    protected $maxSize = null;

    /** @var string|int */
    protected $minSize = null;

    /**
     * Given $params and assign $this->params
     *
     * @param array $params
     * @return self
     */
    public function fillParameters(array $params): Rule
    {
        $this->minSize(array_shift($params));
        $this->maxSize(array_shift($params));

        return $this;
    }

    /**
     * Given $size and set the max size
     *
     * @param string|int $size
     * @return self
     */
    public function maxSize($size): Rule
    {
        $this->params['max_size'] = $size;
        return $this;
    }

    /**
     * Given $size and set the min size
     *
     * @param string|int $size
     * @return self
     */
    public function minSize($size): Rule
    {
        $this->params['min_size'] = $size;
        return $this;
    }

    /**
     * Given $min and $max then set the range size
     *
     * @param string|int $min
     * @param string|int $max
     * @return self
     */
    public function sizeBetween($min, $max): Rule
    {
        $this->minSize($min);
        $this->maxSize($max);

        return $this;
    }

    /**
     * Check the $value is valid
     *
     * @param mixed $value
     * @return bool
     */
    public function check($value): bool
    {
        $minSize = $this->parameter('min_size');
        $maxSize = $this->parameter('max_size');

        // below is Required rule job
        if (!$this->isValueFromUploadedFiles($value) or $value['error'] == UPLOAD_ERR_NO_FILE) {
            return true;
        }

        if (!$this->isUploadedFile($value)) {
            return false;
        }

        // just make sure there is no error
        if ($value['error']) {
            return false;
        }

        if ($minSize) {
            $bytesMinSize = $this->getBytes($minSize);
            if ($value['size'] < $bytesMinSize) {
                return false;
            }
        }

        if ($maxSize) {
            $bytesMaxSize = $this->getBytes($maxSize);
            if ($value['size'] > $bytesMaxSize) {
                return false;
            }
        }

        return true;
    }
}
