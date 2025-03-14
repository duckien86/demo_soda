<?php

    /**
     * Extended captcha client validator
     * Implements support for UTF-8 extended latin2, cyrillic etc characters
     */
    class CaptchaExtendedValidator extends CCaptchaValidator
    {

        /**
         * Client (ajax) validator for extended captcha.
         *
         * @param CModel $object    the data object being validated
         * @param string $attribute the name of the attribute to be validated.
         *
         * @return string the client-side validation script.
         * @see CActiveForm::enableClientValidation
         */
        public function clientValidateAttribute($object, $attribute)
        {
            $captcha = $this->getCaptchaAction();

            if ($captcha->mode == CaptchaExtendedAction::MODE_DEFAULT) {
                // default framework implementation
                return parent::clientValidateAttribute($object, $attribute);
            }
            exit();
            $message = $this->message !== null ? $this->message : Yii::t('main', 'The verification code is incorrect.');
            echo $message;
            exit();
            $message = strtr($message, array(
                '{attribute}' => $object->getAttributeLabel($attribute),
            ));

            $result = $captcha->getVerifyResult();
            if (!$this->caseSensitive) {
                $result = mb_convert_case($result, MB_CASE_LOWER, 'utf-8');
            }

            $hash = $captcha->generateValidationHash($result);

            $js = "
var hash = $('body').data('{$this->captchaAction}.hash');
if(hash == null){
	hash = $hash;
}else{
	hash = hash[" . ($this->caseSensitive ? 0 : 1) . "];
}
value = value.replace(/\\s/g,'');
" . ($this->caseSensitive ? '' : 'value = value.toLowerCase();') . "
value = encodeURIComponent(value);
for(var i=value.length-1, h=0; i >= 0; --i){
	h+=value.charCodeAt(i);
}
if(h != hash) {
	messages.push(" . CJSON::encode($message) . ");
}
";
            if ($this->allowEmpty) {
                $js = "
if(value!=''){
	$js
}
";
            }

            return $js;
        }

    }