{{ header }}

<div class="bb-main-content">
    <table class="bb-box" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
            <td class="bb-content bb-pb-0" align="center">
                <table class="bb-icon bb-icon-lg bb-bg-blue" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td valign="middle" align="center">
                                <img src="{{ 'shopping-cart' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h1 class="bb-text-center bb-m-0 bb-mt-md">Order is waiting for you to complete!</h1>
            </td>
        </tr>
        <tr>
            <td class="bb-content">
                <p>Dear Admin,</p>
                <div>We noticed you were intending to buy some products in our store, would you like to continue?</div>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-text-center bb-pt-0 bb-pb-xl">
                <table cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td align="center">
                            <table cellpadding="0" cellspacing="0" border="0" class="bb-bg-blue bb-rounded bb-w-auto">
                                <tbody>
                                    <tr>
                                        <td align="center" valign="top" class="lh-1">
                                            <a href="{{ site_url }}/checkout/{{ order_token }}/recover" class="bb-btn bb-bg-blue bb-border-blue">
                                                <span class="btn-span">Complete order</span>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-pt-0">
                <h4>Here's what you ordered:</h4>
                {{ product_list }}

                {% if order_note %}
                <div>Note: {{ order_note }}</div>
                {% endif %}
            </td>
        </tr>
        </tbody>
    </table>
</div>

{{ footer }}
