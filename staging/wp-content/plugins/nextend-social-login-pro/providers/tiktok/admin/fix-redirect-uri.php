<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://developers.tiktok.com/" target="_blank">https://developers.tiktok.com/</a>'); ?></li>
    <li><?php printf(__('Log in to your %s developer account, if you are not logged in yet.', 'nextend-facebook-connect'), 'TikTok'); ?></li>
    <li><?php printf(__('On the top right corner click on %1$s then click on the name of that App that you used for the configuration.', 'nextend-facebook-connect'), '<strong>Manage apps</strong>'); ?></li>
    <li><?php printf(__('Scroll down to the %s section.', 'nextend-facebook-connect'), '<strong>Configuration > Platform > Configure for Web</strong>'); ?></li>
    <li><?php printf(__('If the URLs don\'t match, enter the URL of your website into the %1$s field, probably: %2$s', 'nextend-facebook-connect'), '<strong>Website URL</strong>', '<strong>' . site_url() . '</strong>'); ?></li>
    <li><?php printf(__('Scroll down to the %s section.', 'nextend-facebook-connect'), '<strong>Products > Login Kit</strong>'); ?></li>
    <li><?php printf(__('Enter your domain name into the %1$s field, if it is not added already: %2$s', 'nextend-facebook-connect'), '<strong>Redirect domain</strong>', '<strong>' . $_SERVER['HTTP_HOST'] . '</strong>'); ?></li>
    <li><?php printf(__('Press the %s button.', 'nextend-facebook-connect'), '<strong>Save changes</strong>'); ?></li>
    <li><?php printf(__('Submit your App for a review, by pressing the %1$s button on the top right corner and wait until the %2$s field says %3$s. This can take a couple of days.', 'nextend-facebook-connect'), '<strong>Submit for review</strong>', '<strong>Status</strong>', '<strong>Live in production</strong>'); ?></li>
</ol>