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
                                <img src="{{ 'shopping-cart' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h1 class="bb-text-center bb-m-0 bb-mt-md">Order return status update</h1>
            </td>
        </tr>
        <tr>
            <td class="bb-content">
                <p>Dear <strong>{{ customer_name }}</strong>,</p>
                <div>We wanted to inform you that the status of your return request for order <strong>{{ order_id }}</strong> has been updated.</div>
                <div>The new status of your return request is: <strong>{{ status }}</strong>.</div>
                {% if description %}
                    <p>Moderator's note: <strong><i>" {{ description }} "</i></strong>.</p>
                {% endif %}
                <div>If you have any questions or concerns regarding this update, please don't hesitate to contact our customer support team.</div>
            </td>
        </tr>
        </tbody>
    </table>
</div>

{{ footer }}
