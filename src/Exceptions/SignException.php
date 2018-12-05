<?php

namespace Sentiger\PowerSign\Exceptions;

class SignException extends Exception
{
    // 签名过期
    const SIGN_EXPIRE_CODE = 5120;
    // 签名错误
    const SING_ERR_CODE = 5121;
}