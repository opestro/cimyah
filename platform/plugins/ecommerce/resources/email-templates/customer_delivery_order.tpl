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
                                <img src="{{ 'truck-delivery' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon">
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <h1 class="bb-text-center bb-m-0 bb-mt-md">Your order is delivering!</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <p>Dear {{ customer_name }},</p>
                    <p>Your products are on the way.</p>
                    {% if order_delivery_notes %}
                        <p><i>" {{ order_delivery_notes }} "</i></p>
                    {% endif %}
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
