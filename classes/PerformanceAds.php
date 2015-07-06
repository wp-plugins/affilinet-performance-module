<?php


class Affilinet_PerformanceAds
{

    /**
     * Get the ProgramID for a Platform Id
     *
     * Returns false if the platform id is invalid
     *
     * @param   int $platformId
     *
     * @return  bool|int $programId
     */
    public static function getProgramIdByPlatform($platformId)
    {
        switch ($platformId) {
            case 1: // DE
                return 9192;
            case 2: // UK
                return 12752;
            case 3: // FR
                return 12751;
            case 4: // NL
                return 13397;
            case 6: // CH
                return 12252;
            case 7: // AT
                return 12376;

            default :
                return false;
        }
    }

    /**
     * Return the AdCode for the given size
     * $size must be one of '728x90','300x250','250x250', '468x60', '160x600', '120x600'
     * @param $size
     * @return string|void
     */
    public static function getAdCode($size)
    {
        $publisherId = get_option('affilinet_publisher_id');
        $platformId = get_option('affilinet_platform');


        if ($publisherId === false || $publisherId === '') {
            return __('No publisher ID given', 'affilinet');
        }
        if ($platformId === false || $platformId === '') {
            return __('No platform  chosen', 'affilinet');
        }

        $programId = Affilinet_PerformanceAds::getProgramIdByPlatform($platformId);
        $viewUrl = Affilinet_Helper::getViewHostnameForPlatform($platformId);
        $clickUrl = Affilinet_Helper::getClickHostnameForPlatform($platformId);

        $hnb = self::getHnbForPlatform($platformId, $size);
        if ($hnb === false) {
            return __('Invalid ad size given. Choose one of "728x90","300x250","250x250","468x60","160x600","120x600"', 'affilinet');
        }

        $html = '<script language="javascript" type="text/javascript" src="' .
            'http://' . $viewUrl . '/view.asp?ref=' . $publisherId . '&site=' . $programId . '&type=html&hnb=' . $hnb . '&js=1'.
            '"></script><noscript><a href="' .
            'http://' . $clickUrl . '/click.asp?ref=' . $publisherId . '&site=' . $programId . '&type=b1&bnb=1&'.
            '" target="_blank"><img src="' .
            'http://' . $viewUrl . '/view.asp?ref=' . $publisherId . '&site=' . $programId . '&b=1'.
            '" border="0"/></a><br /></noscript>';

        return $html;
    }


    /**
     * Get the HNB paramter for performance Ads
     * @param $platformId
     * @param $size
     * @return bool
     */
    public static function getHnbForPlatform($platformId, $size)
    {
        $hnb = array(
            // DE
            1 => array(
                '728x90' => 1,
                '300x250' => 4,
                '250x250' => 6,
                '468x60' => 5,
                '160x600' => 3,
                '120x600' => 2
            ),
            // AT
            7 => array(
                '728x90' => 1,
                '300x250' => 2,
                '250x250' => 6,
                '468x60' => 3,
                '160x600' => 4,
                '120x600' => 5,
            ),
            // CH
            6 => array(
                '728x90' => 1,
                '300x250' => 2,
                '250x250' => 6,
                '468x60' => 4,
                '160x600' => 3,
                '120x600' => 5,
            ),
            // UK
            2 => array(
                '728x90' => 2,
                '300x250' => 3,
                '250x250' => 6,
                '468x60' => 1,
                '160x600' => 4,
                '120x600' => 5
            ),
            // FR
            3 => array(
                '728x90' => 2,
                '300x250' => 3,
                '250x250' => 6,
                '468x60' => 1,
                '160x600' => 4,
                '120x600' => 5
            ),
            4 => array(
                '728x90' => 2,
                '300x250' => 3,
                '250x250' => 6,
                '468x60' => 1,
                '160x600' => 4,
                '120x600' => 5
            )
        );
        if (isset($hnb[$platformId]) && isset($hnb[$platformId][$size])) {
            return $hnb[$platformId][$size];
        } else {
            return false;
        }

    }
}