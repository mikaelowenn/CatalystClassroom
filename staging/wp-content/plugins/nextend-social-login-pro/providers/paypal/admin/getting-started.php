<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2022-06-01';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create an %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "PayPal", "Client ID", "Secret"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'PayPal App'); ?></h2>

        <ol>
            <li><?php printf(__('Editing Live Apps are only possible with a %s. If you own one, go to the 4. step, if not click on the link!', 'nextend-facebook-connect'), '<a href="https://www.paypal.com/" target="_blank">PayPal Business Account</a>'); ?></li>
            <li><?php _e('Click on Registration and create a Business account.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('If you are done, follow the guide from the 5. step.', 'nextend-facebook-connect') ?></li>
            <li><?php printf(__('Log in with your %s credentials.', 'nextend-facebook-connect'), 'PayPal'); ?></li>
            <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://developer.paypal.com/developer/applications/" target="_blank">https://developer.paypal.com/developer/applications/</a>'); ?></li>
            <li><?php _e('There is a Sandbox/Live switch. Make sure "<b>Live</b>" is selected!', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Click the "<b>Create App</b>" button under the REST API apps section.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Fill the "<b>App Name</b>" field and click "<b>Create App</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Scroll down to "<b>LIVE APP SETTINGS</b>", find the "<b>Live Return URL</b>" heading then click "<b>Show</b>".', 'nextend-facebook-connect') ?></li>
            <li><?php
                $loginUrls = $provider->getAllRedirectUrisForAppCreation();
                printf(__('Add the following URL to the %s field:', 'nextend-facebook-connect'), '"<b>Live Return URL</b>"');
                echo "<ul>";
                foreach ($loginUrls as $loginUrl) {
                    echo "<li><strong>" . $loginUrl . "</strong></li>";
                }
                echo "</ul>";
                ?>
            </li>
            <li><?php _e('Scroll down to "<b>App feature options</b>" section and tick "<b>Log In with PayPal</b>".', 'nextend-facebook-connect') ?></li>
            <li><?php printf(__('Click %1$s that appears after the %2$s text.', 'nextend-facebook-connect'), '"<b>Advanced options</b>"', '"<b>Approval Status</b>"'); ?></li>
            <li><?php _e('Tick "<b>Full name</b>".', 'nextend-facebook-connect') ?></li>
            <li><?php printf(__('If you want to get the email address as well, then don\'t forget to tick %1$s option. In this case you should also enable the %2$s setting in our %3$s %4$s tab.', 'nextend-facebook-connect'), '<b>Email address</b>', '<b>' . __('Email scope', 'nextend-facebook-connect') . '</b>', 'PayPal', __('Settings', 'nextend-facebook-connect')); ?></li>
            <li><?php _e('Fill "<b>Privacy policy URL</b>" and  "<b>User agreement URL</b>".', 'nextend-facebook-connect') ?></li>
            <li><?php _e('When all fields are filled, click "<b>Save</b>".', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Scroll up to "<b>LIVE API CREDENTIALS</b>" section and find the necessary "<b>Client ID</b>" and "<b>Secret</b>"! ( Make sure you are in "<b>Live</b>" mode and not "Sandbox". )', 'nextend-facebook-connect') ?></li>
            <li><?php printf(__('%1$s Before you could start using the App, it requires an App review, which might take up to 7 business days to process. Below the %2$s field you can check the %3$s. Once your App got approved, you could continue with the provider verification in our %4$s tab.', 'nextend-facebook-connect'), '<b>' . __('Important note:', 'nextend-facebook-connect') . '</b>', '<b>Log in with PayPal</b>', '<b>Approval Status</b>', __('Settings', 'nextend-facebook-connect')); ?></li>
        </ol>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'PayPal App'); ?></a>
    </div>
</div>