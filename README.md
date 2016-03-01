# THIS MODULE IS NO MORE SUPPORTED

# MSP CashOnDelivery Magento extension
==================

MSP CashOnDelivery is a Magento 1.x extension that adds a new payment method to your store. 
 You can configure fees based on order amount, different fees for local and foreign destinations.
 Cash on Delivery supports both fixed and percentage fee and is fully integrated with magento shipping methods backend, 
 taxes calculation and price rules.
 You can configure a standard fee and/or fee for different total order amounts.

Extension's development is available on github here:
https://github.com/magespecialist/MSP_CashOnDelivery
while Magento stable packages are available for free at MageSpecialist website here:  
http://www.magespecialist.it/en/msp-cashondelivery.html

# Dependency notice
Be sure to install MSP_Common magento extension **before** MSP CashOnDelivery or your store could not work properly.
You can install it via Magento Connect here: http://www.magentocommerce.com/magento-connect/msp-common.html
(Ignore this dependency if you use composer to install the extension)

# Stable version

1.2.5

# MAGENTO Installation

### via [modman](https://github.com/colinmollenhour/modman):
<pre>
modman clone https://github.com/magespecialist/MSP_CashOnDelivery
</pre>

### via [composer](https://getcomposer.org/download/)
Add to your composer.json file this:
<pre>
{
    ...
    "require": {
        "magento-hackathon/magento-composer-installer": "*",
        "magespecialist/msp_cashondelivery": "1.2.5"
    },
    ....
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:magespecialist/MSP_CashOnDelivery.git"
        }
    ],
    .....
}</pre>
