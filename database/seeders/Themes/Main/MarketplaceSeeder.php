<?php

namespace Database\Seeders\Themes\Main;

use Botble\Marketplace\Database\Seeders\Traits\HasMarketplaceSeeder;
use Botble\Theme\Database\Seeders\ThemeSeeder;

class MarketplaceSeeder extends ThemeSeeder
{
    use HasMarketplaceSeeder;

    public function run(): void
    {
        $this->uploadFiles('stores');

        $storeNames = [
            'GoPro',
            'Global Office',
            'Young Shop',
            'Global Store',
            'Robert’s Store',
            'Stouffer',
            'StarKist',
            'Old El Paso',
        ];

        $data = [];

        for ($i = 0; $i < count($storeNames); $i++) {
            $content = '<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>

                <p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>

                <p style="text-align:center"><img alt="f4" src="/storage/main/blog/post-1.jpg" /></p>

                <p><br />
                &nbsp;</p>

                <p><strong><em>For all of the reason above, here are 7 expert tips to help you pick up the right men&rsquo;s wallet for you:</em></strong></p>

                <h4><strong>Number 1: Choose A Neat Wallet</strong></h4>

                <p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even&nbsp;<strong>minimalist style</strong>&nbsp;is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>

                <p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don&rsquo;t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>

                <p style="text-align:center"><img alt="f5" src="/storage/main/blog/post-2.jpg" /></p>

                <p><br />
                &nbsp;</p>
                <hr />
                <h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>

                <p>You should avoid having an over-sized wallet. Don&rsquo;t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers&rsquo; pocket. In addition, it will create a bulge and ruin your look.</p>

                <p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don&rsquo;t need anymore.</p>

                <p style="text-align:center"><img alt="f1" src="/storage/main/blog/post-3.jpg" /></p>

                <p><br />
                &nbsp;</p>

                <hr />
                <h4><strong>Number 3: Don&rsquo;t Limit Your Options Of Materials</strong></h4>

                <p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>

                <p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>

                <p style="text-align:center"><img alt="f6" src="/storage/main/blog/post-4.jpg" /></p>

                <p><br />
                &nbsp;</p>

                <hr />
                <h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>

                <p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>

                <p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>

                <p style="text-align:center"><img alt="f2" src="/storage/main/blog/post-5.jpg" /></p>

                <p>&nbsp;</p>
                ';

            $data[] = [
                'name' => $storeNames[$i],
                'logo' => $this->filePath(sprintf('stores/%d.png', $i + 1)),
                'content' => $content,
                'cover_image' => $this->filePath(sprintf('stores/cover-%s.png', rand(1, 5))),
            ];
        }

        $this->createStores($data);
    }
}
