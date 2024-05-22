@php
    Theme::set('breadcrumbStyle', 'none');
@endphp

<div>
    <h4 class="text-danger">You need to setup your homepage first!</h4>

    <ul class="list-unstyled">
        <li class="mb-2"><strong>1. Go to Admin -> Plugins then activate all plugins.</strong></li>

        <li class="mb-2">
            <strong>2. Go to Admin -> Pages and create a page:</strong>

            <div class="mt-2">
                <label>Copy and paste the following code into the page content:</label>
                <div class="border p-2 mb-1">
                    <code>
                        <div>[simple-slider style="1" key="home-slider" shape_1="main/sliders/shape-1.png" shape_2="main/sliders/shape-2.png" shape_3="main/sliders/shape-3.png" shape_4="main/sliders/shape-4.png" is_autoplay="yes" autoplay_speed="5000"][/simple-slider]</div>
                            <div>[ecommerce-categories style="slider" category_ids="6,10,13,16,30"][/ecommerce-categories]</div>
                            <div>[site-features style="1" quantity="4" title_1="Free Delivery" description_1="Orders from all item" icon_1="ti ti-truck-delivery" title_2="Return & Refund" description_2="Money-back guarantee" icon_2="ti ti-currency-dollar" title_3="Member Discount" description_3="Every order over $140.00" icon_3="ti ti-discount-2" title_4="Support 24/7" description_4="Contact us 24 hours a day" icon_4="ti ti-headset"][/site-features]</div>
                            <div>[ecommerce-product-groups title="Trending Products" limit="8" tabs="all,featured,on-sale,trending,top-rated"][/ecommerce-product-groups]</div>
                            <div>[ads style="1" key_1="UROL9F9ZZVAA" key_2="B30VDBKO7SBF"][/ads]</div>
                            <div>[ecommerce-flash-sale style="1" title="Deal of The Day" flash_sale_id="1" limit="4" button_label="View All Deals" button_url="/products"][/ecommerce-flash-sale]</div>
                            <div>[ecommerce-products style="grid" category_ids="20" limit="12" with_sidebar="on" image="main/gadgets/gadget-girl.png" action_label="More Products" ads_ids="3,4"][/ecommerce-products]</div>
                            <div>[ads style="4" key_1="B5ZA76ZWMWAE" key_2="F1LTQS976YPY" key_3="IHPZ2WBSYJUK"][/ads]</div>
                            <div>[ecommerce-products style="slider" title="New Arrivals" by="collection" collection_ids="1" limit="12"][/ecommerce-products]</div>
                            <div>[ecommerce-product-groups style="columns" limit="3" tabs="on-sale,trending,top-rated"][/ecommerce-product-groups]</div>
                            <div>[blog-posts title="Latest news & articles" type="latest" limit="3" button_label="View All" button_url="/blog"][/blog-posts]</div>
                            <div>[gallery style="1" limit="5"][/gallery]</div>
                    </code>
                </div>

                <p>And choose <strong>Full width</strong> template.</p>
            </div>
        </li>

        <li><strong>3. Then go to Admin -> Appearance -> Theme options -> Page to set your homepage.</strong></li>
    </ul>
</div>
