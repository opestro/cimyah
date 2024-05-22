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
                                <img src="{{ 'cloud-download' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h1 class="bb-text-center bb-m-0 bb-mt-md">Download digital products</h1>
            </td>
        </tr>
        <tr>
            <td class="bb-content">
                <p>Dear {{ customer_name }},</p>
                <div>Thank you for purchasing our product.</div>
                <div>You can now download digital product(s) you have purchased here:</div>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-pt-0">
                <h4>Products</h4>
                {{ digital_product_list }}
            </td>
        </tr>
        {% if payment_method %}
            <tr>
                <td class="bb-content bb-pt-0">
                    <h4>Payment Method</h4>
                    {{ payment_method }}
                </td>
            </tr>
        {% endif %}
        </tbody>
    </table>
</div>

{{ footer }}
