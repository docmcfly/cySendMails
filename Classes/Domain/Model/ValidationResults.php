<?php
declare(strict_types = 1);
namespace Cylancer\CySendMails\Domain\Model;

/**
 *
 * This file is part of the "cy_send_mails" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2024 C. Gogolin <service@cylancer.net>
 *
 * @package Cylancer\CySendMails\Domain\Model
 *         
 */
class ValidationResults
{

    /**
     *
     * @var array
     */
    protected $infos = array();

    /**
     *
     * @var array
     */
    protected $errors = array();

    /**
     *
     * @return array of srings
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     *
     * @return array of srings
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @return array of srings
     */
    public function hasErrors()
    {
        return ! empty($this->errors);
    }

    /**
     *
     * @return array of srings
     */
    public function hasInfos()
    {
        return ! empty($this->infos);
    }

    /**
     *
     * @param String $errorKey
     * @param array $arguments
     */
    public function addInfo(String $infoKey, array $arguments = []): void
    {
        $keySplit = explode('.', $infoKey, 2);
        $this->infos['info.' . $infoKey]['arguments'] = $arguments;
        $this->infos['info.' . $infoKey]['id'] = count($keySplit) == 2 ? $keySplit[0] : $infoKey;
    }

    /**
     *
     * @param String $errorKey
     * @param array $arguments
     */
    public function addError(String $errorKey, array $arguments = []): void
    {
        $keySplit = explode('.', $errorKey, 2);
        $this->errors['error.' . $errorKey]['arguments'] = $arguments;
        $this->errors['error.' . $errorKey]['id'] = count($keySplit) == 2 ? $keySplit[0] : $errorKey;
    }

    /**
     *
     * @param
     *            array
     */
    public function addInfos(array $infos)
    {
        foreach ($infos as $info) {
            $this->addInfo($info);
        }
    }

    /**
     *
     * @param
     *            array
     */
    public function addErrors(array $errors)
    {
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }
}
