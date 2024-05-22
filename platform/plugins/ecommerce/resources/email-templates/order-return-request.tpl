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
                            <img src="{{ 'arrow-back-up' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                        </td>
                    </tr>
                    </tbody>
                </table>
                <h1 class="bb-text-center bb-m-0 bb-mt-md">New order return request!</h1>
            </td>
        </tr>
        <tr>
            <td class="bb-content">
                <div>{{ customer_name }} has just requested return product(s) on your site.</div>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-pt-0">
                <table class="bb-row bb-mb-md" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="bb-bb-col">
                                <h4 class="bb-m-0">Return reason</h4>
                                <div><strong>{{ return_reason }}</strong></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-pt-0">
                <table class="bb-row bb-mb-md" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="bb-bb-col">
                                <h4 class="bb-m-0">Customer Information</h4>
                                <div>Name: <strong>{{ customer_name }}</strong></div>
                                <div>Phone: <strong>{{ customer_phone }}</strong></div>
                                <div>Address: <strong>{{ customer_address }}</strong></div>
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
