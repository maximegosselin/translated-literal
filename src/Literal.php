<?php
declare(strict_types = 1);

namespace MaximeGosselin\TranslatedLiteral;

use InvalidArgumentException;
use UnexpectedValueException;

final class Literal implements LiteralInterface
{
    /**
     *
     * @var string
     */
    private $locale;

    /**
     *
     * @var array
     */
    private $translations;

    public function __construct(string $locale, string $string)
    {
        $this->setLocale($locale);
        $this->setTranslation($locale, $string);
    }

    public function getDefault():string
    {
        return $this->translate($this->locale);
    }

    public function getLocale():string
    {
        return $this->isoLocale($this->locale);
    }

    public function translate(string $locale):string
    {
        return $this->translations[$this->normalizeLocale($locale)] ?? '';
    }

    public function withLocale(string $locale):LiteralInterface
    {
        $clone = clone $this;
        $clone->setLocale($locale);
        return $clone;
    }

    public function withTranslation(string $locale, string $translation):LiteralInterface
    {
        $clone = clone $this;
        $clone->setTranslation($locale, $translation);
        return $clone;
    }

    public function jsonSerialize()
    {
        return [
            'locale' => $this->getLocale(),
            'content' => $this->translations
        ];
    }

    public function toJson(int $options = 0):string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function __toString():string
    {
        return $this->toJson();
    }

    /**
     * @throws InvalidArgumentException if the JSON is not valid.
     */
    public static function fromJson(string $json):LiteralInterface
    {
        $data = json_decode($json);
        if (!is_array($data)) {
            throw new InvalidArgumentException(json_last_error_msg());
        }

        $locale = $data['locale'] ?? '';
        $content = $data['content'] ?? [];
        $default = $content[$locale] ?? '';
        if (!is_array($content)) {
            throw new InvalidArgumentException('Invalid JSON.');
        }

        $literal = new static($locale, $default);
        foreach ($content as $key => $value) {
            $literal = $literal->withTranslation($key, $value);
        }

        return $literal;
    }

    private function setLocale(string $locale)
    {
        $this->assertValidLocale($locale);
        $this->locale = $this->normalizeLocale($locale);
    }

    private function setTranslation(string $locale, string $string)
    {
        $this->assertValidLocale($locale);
        $this->translations[$this->normalizeLocale($locale)] = $string;
    }

    private function normalizeLocale(string $locale):string
    {
        return strtolower(strtr($locale, '_', '-'));
    }

    private function isoLocale(string $locale):string
    {
        $this->assertValidLocale($locale);
        return $this->getLocales()[$this->normalizeLocale($locale)];
    }

    /**
     * @throws UnexpectedValueException if the locale is not valid.
     */
    private function assertValidLocale(string $locale)
    {
        if (!$this->localeExists($locale)) {
            throw new UnexpectedValueException(sprintf('%s is not a valid locale.', $locale));
        }
    }

    private function localeExists(string $locale):bool
    {
        return array_key_exists($this->normalizeLocale($locale), $this->getLocales());
    }

    private function getLocales():array
    {
        return [
            'aa-dj' => 'aa_DJ',
            'aa-er' => 'aa_ER',
            'aa-et' => 'aa_ET',
            'af-za' => 'af_ZA',
            'sq-al' => 'sq_AL',
            'sq-mk' => 'sq_MK',
            'am-et' => 'am_ET',
            'ar-dz' => 'ar_DZ',
            'ar-bh' => 'ar_BH',
            'ar-eg' => 'ar_EG',
            'ar-in' => 'ar_IN',
            'ar-iq' => 'ar_IQ',
            'ar-jo' => 'ar_JO',
            'ar-kw' => 'ar_KW',
            'ar-lb' => 'ar_LB',
            'ar-ly' => 'ar_LY',
            'ar-ma' => 'ar_MA',
            'ar-om' => 'ar_OM',
            'ar-qa' => 'ar_QA',
            'ar-sa' => 'ar_SA',
            'ar-sd' => 'ar_SD',
            'ar-sy' => 'ar_SY',
            'ar-tn' => 'ar_TN',
            'ar-ae' => 'ar_AE',
            'ar-ye' => 'ar_YE',
            'an-es' => 'an_ES',
            'hy-am' => 'hy_AM',
            'as-in' => 'as_IN',
            'ast-es' => 'ast_ES',
            'az-az' => 'az_AZ',
            'az-tr' => 'az_TR',
            'eu-fr' => 'eu_FR',
            'eu-es' => 'eu_ES',
            'be-by' => 'be_BY',
            'bem-zm' => 'bem_ZM',
            'bn-bd' => 'bn_BD',
            'bn-in' => 'bn_IN',
            'ber-dz' => 'ber_DZ',
            'ber-ma' => 'ber_MA',
            'byn-er' => 'byn_ER',
            'bs-ba' => 'bs_BA',
            'br-fr' => 'br_FR',
            'bg-bg' => 'bg_BG',
            'my-mm' => 'my_MM',
            'ca-ad' => 'ca_AD',
            'ca-fr' => 'ca_FR',
            'ca-it' => 'ca_IT',
            'ca-es' => 'ca_ES',
            'zh-cn' => 'zh_CN',
            'zh-hk' => 'zh_HK',
            'zh-sg' => 'zh_SG',
            'zh-tw' => 'zh_TW',
            'cv-ru' => 'cv_RU',
            'kw-gb' => 'kw_GB',
            'crh-ua' => 'crh_UA',
            'hr-hr' => 'hr_HR',
            'cs-cz' => 'cs_CZ',
            'da-dk' => 'da_DK',
            'dv-mv' => 'dv_MV',
            'nl-aw' => 'nl_AW',
            'nl-be' => 'nl_BE',
            'nl-nl' => 'nl_NL',
            'dz-bt' => 'dz_BT',
            'en-ag' => 'en_AG',
            'en-au' => 'en_AU',
            'en-bw' => 'en_BW',
            'en-ca' => 'en_CA',
            'en-dk' => 'en_DK',
            'en-hk' => 'en_HK',
            'en-in' => 'en_IN',
            'en-ie' => 'en_IE',
            'en-nz' => 'en_NZ',
            'en-ng' => 'en_NG',
            'en-ph' => 'en_PH',
            'en-sg' => 'en_SG',
            'en-za' => 'en_ZA',
            'en-gb' => 'en_GB',
            'en-us' => 'en_US',
            'en-zm' => 'en_ZM',
            'en-zw' => 'en_ZW',
            'eo' => 'eo',
            'et-ee' => 'et_EE',
            'fo-fo' => 'fo_FO',
            'fil-ph' => 'fil_PH',
            'fi-fi' => 'fi_FI',
            'fr-be' => 'fr_BE',
            'fr-ca' => 'fr_CA',
            'fr-fr' => 'fr_FR',
            'fr-lu' => 'fr_LU',
            'fr-ch' => 'fr_CH',
            'fur-it' => 'fur_IT',
            'ff-sn' => 'ff_SN',
            'gl-es' => 'gl_ES',
            'lg-ug' => 'lg_UG',
            'gez-er' => 'gez_ER',
            'gez-et' => 'gez_ET',
            'ka-ge' => 'ka_GE',
            'de-at' => 'de_AT',
            'de-be' => 'de_BE',
            'de-de' => 'de_DE',
            'de-li' => 'de_LI',
            'de-lu' => 'de_LU',
            'de-ch' => 'de_CH',
            'el-cy' => 'el_CY',
            'el-gr' => 'el_GR',
            'gu-in' => 'gu_IN',
            'ht-ht' => 'ht_HT',
            'ha-ng' => 'ha_NG',
            'iw-il' => 'iw_IL',
            'he-il' => 'he_IL',
            'hi-in' => 'hi_IN',
            'hu-hu' => 'hu_HU',
            'is-is' => 'is_IS',
            'ig-ng' => 'ig_NG',
            'id-id' => 'id_ID',
            'ia' => 'ia',
            'iu-ca' => 'iu_CA',
            'ik-ca' => 'ik_CA',
            'ga-ie' => 'ga_IE',
            'it-it' => 'it_IT',
            'it-ch' => 'it_CH',
            'ja-jp' => 'ja_JP',
            'kl-gl' => 'kl_GL',
            'kn-in' => 'kn_IN',
            'ks-in' => 'ks_IN',
            'csb-pl' => 'csb_PL',
            'kk-kz' => 'kk_KZ',
            'km-kh' => 'km_KH',
            'rw-rw' => 'rw_RW',
            'ky-kg' => 'ky_KG',
            'kok-in' => 'kok_IN',
            'ko-kr' => 'ko_KR',
            'ku-tr' => 'ku_TR',
            'lo-la' => 'lo_LA',
            'lv-lv' => 'lv_LV',
            'li-be' => 'li_BE',
            'li-nl' => 'li_NL',
            'lt-lt' => 'lt_LT',
            'nds-de' => 'nds_DE',
            'nds-nl' => 'nds_NL',
            'mk-mk' => 'mk_MK',
            'mai-in' => 'mai_IN',
            'mg-mg' => 'mg_MG',
            'ms-my' => 'ms_MY',
            'ml-in' => 'ml_IN',
            'mt-mt' => 'mt_MT',
            'gv-gb' => 'gv_GB',
            'mi-nz' => 'mi_NZ',
            'mr-in' => 'mr_IN',
            'mn-mn' => 'mn_MN',
            'ne-np' => 'ne_NP',
            'se-no' => 'se_NO',
            'nso-za' => 'nso_ZA',
            'nb-no' => 'nb_NO',
            'nn-no' => 'nn_NO',
            'oc-fr' => 'oc_FR',
            'or-in' => 'or_IN',
            'om-et' => 'om_ET',
            'om-ke' => 'om_KE',
            'os-ru' => 'os_RU',
            'pap-an' => 'pap_AN',
            'ps-af' => 'ps_AF',
            'fa-ir' => 'fa_IR',
            'pl-pl' => 'pl_PL',
            'pt-br' => 'pt_BR',
            'pt-pt' => 'pt_PT',
            'pa-in' => 'pa_IN',
            'pa-pk' => 'pa_PK',
            'ro-ro' => 'ro_RO',
            'ru-ru' => 'ru_RU',
            'ru-ua' => 'ru_UA',
            'sa-in' => 'sa_IN',
            'sc-it' => 'sc_IT',
            'gd-gb' => 'gd_GB',
            'sr-me' => 'sr_ME',
            'sr-rs' => 'sr_RS',
            'sid-et' => 'sid_ET',
            'sd-in' => 'sd_IN',
            'si-lk' => 'si_LK',
            'sk-sk' => 'sk_SK',
            'sl-si' => 'sl_SI',
            'so-dj' => 'so_DJ',
            'so-et' => 'so_ET',
            'so-ke' => 'so_KE',
            'so-so' => 'so_SO',
            'nr-za' => 'nr_ZA',
            'st-za' => 'st_ZA',
            'es-ar' => 'es_AR',
            'es-bo' => 'es_BO',
            'es-cl' => 'es_CL',
            'es-co' => 'es_CO',
            'es-cr' => 'es_CR',
            'es-do' => 'es_DO',
            'es-ec' => 'es_EC',
            'es-sv' => 'es_SV',
            'es-gt' => 'es_GT',
            'es-hn' => 'es_HN',
            'es-mx' => 'es_MX',
            'es-ni' => 'es_NI',
            'es-pa' => 'es_PA',
            'es-py' => 'es_PY',
            'es-pe' => 'es_PE',
            'es-es' => 'es_ES',
            'es-us' => 'es_US',
            'es-uy' => 'es_UY',
            'es-ve' => 'es_VE',
            'sw-ke' => 'sw_KE',
            'sw-tz' => 'sw_TZ',
            'ss-za' => 'ss_ZA',
            'sv-fi' => 'sv_FI',
            'sv-se' => 'sv_SE',
            'tl-ph' => 'tl_PH',
            'tg-tj' => 'tg_TJ',
            'ta-in' => 'ta_IN',
            'tt-ru' => 'tt_RU',
            'te-in' => 'te_IN',
            'th-th' => 'th_TH',
            'bo-cn' => 'bo_CN',
            'bo-in' => 'bo_IN',
            'tig-er' => 'tig_ER',
            'ti-er' => 'ti_ER',
            'ti-et' => 'ti_ET',
            'ts-za' => 'ts_ZA',
            'tn-za' => 'tn_ZA',
            'tr-cy' => 'tr_CY',
            'tr-tr' => 'tr_TR',
            'tk-tm' => 'tk_TM',
            'ug-cn' => 'ug_CN',
            'uk-ua' => 'uk_UA',
            'hsb-de' => 'hsb_DE',
            'ur-pk' => 'ur_PK',
            'uz-uz' => 'uz_UZ',
            've-za' => 've_ZA',
            'vi-vn' => 'vi_VN',
            'wa-be' => 'wa_BE',
            'cy-gb' => 'cy_GB',
            'fy-de' => 'fy_DE',
            'fy-nl' => 'fy_NL',
            'wo-sn' => 'wo_SN',
            'xh-za' => 'xh_ZA',
            'yi-us' => 'yi_US',
            'yo-ng' => 'yo_NG',
            'zu-za' => 'zu_ZA'
        ];
    }
}
