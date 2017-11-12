<?php

namespace Phosphorum\Utils;

/**
 * Token Trait
 *
 * @package Phosphorum\Mvc\Controllers
 *
 * @property \Phosphorum\Utils\Security $security
 * @property \Phalcon\FlashInterface $flashSession
 * @property \Phalcon\Session\AdapterInterface $session
 * @property \Phalcon\Http\RequestInterface $request
 */
trait TokenTrait
{
    protected $csrfSessionKey = '$PHALCON/CSRF/KEY$';
    protected $csrfErrorMessage = '页面已失效，请重新提交';

    protected function checkTokenPost($prefix = null)
    {
        if ($prefix) {
            $result = $this->security->checkPrefixedToken($prefix);
        } else {
            $result = $this->security->checkToken();
        }

        if (!$result) {
            $this->flashSession->error($this->csrfErrorMessage);
            return false;
        }

        return true;
    }

    protected function checkTokenGetJson($prefix = null)
    {
        if ($prefix) {
            $csrfKey = $this->session->get($prefix . ':' . $this->csrfSessionKey);
            $csrfVal = $this->request->getQuery($csrfKey, null, '');

            return $this->security->checkPrefixedToken($prefix, $csrfKey, $csrfVal);
        }

        $csrfKey = $this->session->get($this->csrfSessionKey);
        $csrfVal = $this->request->getQuery($csrfKey, null, '');

        return $this->security->checkToken($csrfKey, $csrfVal);
    }

    protected function checkTokenGet($prefix = null)
    {
        if ($prefix) {
            $csrfKey = $this->session->get($prefix . ':' . $this->csrfSessionKey);
            $csrfVal = $this->request->getQuery($csrfKey, null, '');

            if (!$this->security->checkPrefixedToken($prefix, $csrfKey, $csrfVal)) {
                $this->flashSession->error($this->csrfErrorMessage);
                return false;
            }
        } else {
            $csrfKey = $this->session->get($this->csrfSessionKey);
            $csrfVal = $this->request->getQuery($csrfKey, null, '');

            if (!$this->security->checkToken($csrfKey, $csrfVal)) {
                $this->flashSession->error($this->csrfErrorMessage);
                return false;
            }
        }

        return true;
    }
}
