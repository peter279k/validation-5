<?php

namespace Rakit\Validation\Rules;

use Rakit\Validation\Rule;
use Rakit\Validation\MimeTypeGuesser;

class UploadedFileType extends Rule
{
    use FileTrait;

    /** @var string */
    protected $message = "The :attribute is not valid";

    /** @var array */
    protected $allowedTypes = [];

    /**
     * Given $params and assign $this->params
     *
     * @param array $params
     * @return self
     */
    public function fillParameters(array $params): Rule
    {
        $this->fileTypes($params);

        return $this;
    }

    /**
     * Given $types and assign $this->params
     *
     * @param mixed $types
     * @return self
     */
    public function fileTypes($types): Rule
    {
        if (is_string($types)) {
            $types = explode('|', $types);
        }

        $this->params['allowed_types'] = $types;

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
        $allowedTypes = $this->parameter('allowed_types');

        // just make sure there is no error
        if ($value['error']) {
            return false;
        }

        if (!empty($allowedTypes)) {
            $guesser = new MimeTypeGuesser;
            $ext = $guesser->getExtension($value['type']);
            unset($guesser);

            if (!in_array($ext, $allowedTypes)) {
                return false;
            }
        }

        return true;
    }
}
