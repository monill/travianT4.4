<?php

$AppConfig = array(

    'plus' => array(
        'packages' => array(
            array(
                'id' => 1,
                'name' => 'Package A',
                'gold' => '100',
                'cost' => '25000',
                'currency' => 'تومان',
                'image' => 'package_a.jpg'
            ),
            array(
                'id' => 2,
                'name' => 'Package B',
                'gold' => '250',
                'cost' => '50000',
                'currency' => 'تومان',
                'image' => 'package_b.jpg'
            ),
            array(
                'id' => 3,
                'name' => 'Package C',
                'gold' => '600',
                'cost' => '80000',
                'currency' => 'تومان',
                'image' => 'package_c.jpg'
            ),
            array(
                'id' => 4,
                'name' => 'Package D',
                'gold' => '1400',
                'cost' => '120000',
                'currency' => 'تومان',
                'image' => 'package_d.jpg'
            ),
            array(
                'id' => 5,
                'name' => 'Package Bronze',
                'gold' => '3200',
                'cost' => '250000',
                'currency' => 'تومان',
                'image' => 'package_f.jpg'
            ),
            array(
                'id' => 6,
                'name' => 'Package silver',
                'gold' => '8000',
                'cost' => '500000',
                'currency' => 'تومان',
                'image' => 'package_f.jpg'
            ),

            array(
                'id' => 7,
                'name' => 'Package Golden',
                'gold' => '16000',
                'cost' => '900000',
                'currency' => 'تومان',
                'image' => 'package_f.jpg'
            ),
            array(
                'id' => 8,
                'name' => 'Package Diamond',
                'gold' => '50000',
                'cost' => '1500000',
                'currency' => 'Toman',
                'image' => 'package_f.jpg'
            ),
        ),

        'payments' => array(
            'payline' => array(
                'testMode' => FALSE,
                'name' => 'Pay by acceleration',
                'image' => 'cashu.gif',
                'serviceName' => 'tatar.dboor',
                'api' => '50c9abb0-b27c-4c96-963a-6ed35ee8aeb5',
                'key' => '5248910',
                'returnKey' => '548fhmr847470',
                'Link' => 'http://travian5.net/e/demo/tgpay.php?back=2,',
                'currency' => 'Tomans'
            ),

            'parspal' => array(
                'testMode' => FALSE,
                'name' => 'Pay by acceleration',
                'image' => 'parspal.gif',
                'serviceName' => 'tatar.dboor',
                'MerchantID' => '1331011',
                'password' => '5skdUpXdF',
                'testKey' => '',
                'returnKey' => '',
                'Link' => 'http://travian5.net/e/demo/tgpay.php?pid=1&verify=',
                'currency' => 'Tomans'
            )

        )

    )
);
